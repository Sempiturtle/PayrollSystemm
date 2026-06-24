<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payroll;
use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function __construct(
        protected DeductionService $deductionService
    ) {}

    /**
     * Determine the current fixed payroll period.
     * Cycle 1: 1st - 15th
     * Cycle 2: 16th - End of month
     */
    public function getCurrentPeriod(Carbon $date = null): array
    {
        $date = $date ?? Carbon::now('Asia/Manila');
        
        if ($date->day <= 15) {
            return [
                'start' => $date->copy()->startOfMonth()->toDateString(),
                'end'   => $date->copy()->day(15)->toDateString(),
            ];
        } else {
            return [
                'start' => $date->copy()->day(16)->toDateString(),
                'end'   => $date->copy()->endOfMonth()->toDateString(),
            ];
        }
    }

    /**
     * Calculate and sync payroll for a specific user and period.
     * Branches logic based on employment_type: professor (hourly), staff (fixed + OT).
     */
    public function syncForUser(User $user, $startDate, $endDate): Payroll
    {
        return DB::transaction(function () use ($user, $startDate, $endDate) {
            // 0. Check for Locking (Bulletproofing)
            $existing = Payroll::where('user_id', $user->id)
                ->where('period_start', $startDate)
                ->where('period_end', $endDate)
                ->first();
                
            if ($existing && $existing->status === 'Finalized') {
                return $existing; // Do not modify finalized records
            }

            // 1. Get all base data
            $logs = $user->attendanceLogs()
                ->whereBetween('date', [$startDate, $endDate])
                ->get()
                ->groupBy(fn($log) => $log->date->toDateString());

            $leaves = $user->leaves()
                ->where('status', 'Approved')
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(fn($sq) => $sq->where('start_date', '<', $startDate)->where('end_date', '>', $endDate));
                })->get();

            $holidays = \App\Models\Holiday::whereBetween('date', [$startDate, $endDate])->get()
                ->keyBy(fn($h) => $h->date->toDateString());

            // Tracking variables
            $totalRegularHours = 0;
            $totalOvertimeHours = 0;
            $totalExpectedHours = 0; // For staff/part-time absence calc
            $lateCount = 0;
            $totalLateMinutes = 0;
            $holidayDetails = []; // Track holiday pay breakdown for UI display

            $employmentType = $user->employment_type ?? 'professor';

            // For professors: track hours from last working day (used for paid holiday pay)
            $professorLastWorkedHours = 0;
            if ($employmentType === 'professor') {
                // Pre-seed with the last working day BEFORE this period (in case
                // the very first day of the period is a holiday)
                $professorLastWorkedHours = $this->findLastWorkedHoursBeforePeriod($user, $startDate);
            }

            // 2. Iterate through every day in the period
            $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
            
            foreach ($period as $date) {
                $dateStr = $date->toDateString();
                $dayName = $date->format('l');

                // Get user's schedule for this day
                $daySchedules = $user->schedules
                    ->where('day_of_week', $dayName)
                    ->filter(fn($s) => is_null($s->effective_from) || $s->effective_from <= $dateStr);

                // Track expected hours for this day (staff/part-time need this)
                $expectedHoursToday = 0;
                foreach ($daySchedules as $sched) {
                    $expectedHoursToday += $sched->scheduled_hours;
                }
                $totalExpectedHours += $expectedHoursToday;

                // ─── HOLIDAY HANDLING ────────────────────────────
                if (isset($holidays[$dateStr])) {
                    $holiday = $holidays[$dateStr];
                    $dayLogs = $logs->get($dateStr, collect());
                    $hasWorked = $dayLogs->isNotEmpty();

                    // A. Case: Employee did NOT work on the holiday
                    if (!$hasWorked) {
                        if ($holiday->is_paid) {
                            if ($employmentType === 'professor') {
                                // Professor: paid holiday = hours from their last working day
                                $totalRegularHours += $professorLastWorkedHours;
                                $holidayDetails[] = [
                                    'date' => $dateStr,
                                    'name' => $holiday->name,
                                    'type' => $holiday->type,
                                    'pay_type' => $holiday->is_double_pay ? 'Double Pay' : 'Paid',
                                    'worked' => false,
                                    'hours_credited' => round($professorLastWorkedHours, 2),
                                    'method' => 'Last working day hours',
                                ];
                            } else {
                                // Staff / Part-time: paid holiday = normal scheduled hours
                                $totalRegularHours += $expectedHoursToday;
                                $holidayDetails[] = [
                                    'date' => $dateStr,
                                    'name' => $holiday->name,
                                    'type' => $holiday->type,
                                    'pay_type' => $holiday->is_double_pay ? 'Double Pay' : 'Paid',
                                    'worked' => false,
                                    'hours_credited' => round($expectedHoursToday, 2),
                                    'method' => 'Scheduled hours',
                                ];
                            }
                        } else {
                            // Unpaid holiday: no hours, no absence deduction
                            $holidayDetails[] = [
                                'date' => $dateStr,
                                'name' => $holiday->name,
                                'type' => $holiday->type,
                                'pay_type' => 'Unpaid',
                                'worked' => false,
                                'hours_credited' => 0,
                                'method' => 'No pay (unpaid holiday)',
                            ];
                        }
                    } 
                    // B. Case: Employee DID work on the holiday (Multi-session support)
                    else {
                        $holidayWorkedHours = 0;
                        foreach ($dayLogs as $log) {
                            if ($log->time_in && $log->time_out) {
                                $in = Carbon::parse($dateStr . ' ' . $log->time_in);
                                $out = Carbon::parse($dateStr . ' ' . $log->time_out);
                                
                                $result = $this->calculateSessionHours($in, $out, $daySchedules, $dateStr, $user);
                                
                                $multiplier = $holiday->is_double_pay ? 2 : 1;
                                $totalRegularHours += ($result['regular'] * $multiplier);
                                $totalOvertimeHours += ($result['overtime'] * $multiplier);

                                $holidayWorkedHours += $result['regular'];
                            }

                            if ($log->status === 'Late') {
                                $lateCount++;
                            }
                        }

                        $multiplier = $holiday->is_double_pay ? 2 : 1;
                        $holidayDetails[] = [
                            'date' => $dateStr,
                            'name' => $holiday->name,
                            'type' => $holiday->type,
                            'pay_type' => $holiday->is_double_pay ? 'Double Pay' : ($holiday->is_paid ? 'Paid' : 'Unpaid'),
                            'worked' => true,
                            'hours_credited' => round($holidayWorkedHours * $multiplier, 2),
                            'actual_hours' => round($holidayWorkedHours, 2),
                            'multiplier' => $multiplier . 'x',
                            'method' => 'Actual worked hours' . ($multiplier > 1 ? ' × ' . $multiplier : ''),
                        ];

                        // Update professor's last-worked tracker (they worked on the holiday)
                        if ($employmentType === 'professor' && $holidayWorkedHours > 0) {
                            $professorLastWorkedHours = $holidayWorkedHours;
                        }
                    }
                    continue; 
                }

                // ─── REGULAR DAY: CHECK ATTENDANCE ──────────────
                if ($logs->has($dateStr)) {
                    $dayLogs = $logs->get($dateStr);
                    $dayWorkedHours = 0;
                    
                    foreach ($dayLogs as $log) {
                        if ($log->time_in && $log->time_out) {
                            $in = Carbon::parse($dateStr . ' ' . $log->time_in);
                            $out = Carbon::parse($dateStr . ' ' . $log->time_out);
                            
                            $result = $this->calculateSessionHours($in, $out, $daySchedules, $dateStr, $user);
                            $totalRegularHours += $result['regular'];
                            $totalOvertimeHours += $result['overtime'];

                            $dayWorkedHours += $result['regular'];
                        }

                        if ($log->status === 'Late') {
                            $lateCount++;
                            $matchingSchedule = $daySchedules
                                ->sortBy('start_time')
                                ->first(function($s) use ($log) {
                                    $logIn = Carbon::parse($log->time_in)->format('H:i:s');
                                    return $logIn >= $s->start_time && $logIn <= $s->end_time;
                                });

                            if ($matchingSchedule) {
                                $schedIn = Carbon::parse($dateStr . ' ' . $matchingSchedule->start_time);
                                $actualIn = Carbon::parse($dateStr . ' ' . $log->time_in);
                                $totalLateMinutes += max(0, $actualIn->diffInMinutes($schedIn));
                            }
                        }
                    }

                    // Update professor's last-worked tracker for future holiday lookups
                    if ($employmentType === 'professor' && $dayWorkedHours > 0) {
                        $professorLastWorkedHours = $dayWorkedHours;
                    }

                    continue;
                }

                // ─── CHECK FOR APPROVED LEAVES ──────────────────
                $isOnLeave = $leaves->contains(fn($l) => $dateStr >= $l->start_date && $dateStr <= $l->end_date);
                if ($isOnLeave) {
                    // On leave: count as regular hours (paid leave)
                    $totalRegularHours += $expectedHoursToday;
                }

                // If none of the above: employee was absent (no log, no leave, no holiday)
                // For professors: they simply don't get paid for those hours
                // For staff/part-time: the absence is the gap between expected and regular
            }

            // 3. Final Calculations based on Employment Type

            if ($employmentType === 'professor') {
                return $this->calculateProfessorPayroll($user, $startDate, $endDate, $totalRegularHours, $totalLateMinutes, $existing, $holidayDetails);
            } else {
                return $this->calculateFixedSalaryPayroll(
                    $user, $startDate, $endDate,
                    $totalRegularHours, $totalOvertimeHours, $totalExpectedHours,
                    $totalLateMinutes, $existing, $holidayDetails
                );
            }
        });
    }

    /**
     * Calculate hours for a single attendance session.
     * Returns ['regular' => float, 'overtime' => float]
     */
    private function calculateSessionHours(Carbon $in, Carbon $out, $daySchedules, string $dateStr, User $user): array
    {
        $regularHours = 0;
        $overtimeHours = 0;

        if ($daySchedules->isNotEmpty()) {
            foreach ($daySchedules as $sched) {
                $schedStart = Carbon::parse($dateStr . ' ' . $sched->start_time);
                $schedEnd = Carbon::parse($dateStr . ' ' . $sched->end_time);
                
                // Regular hours: overlap between attendance and schedule
                $overlapStart = $in->greaterThan($schedStart) ? $in : $schedStart;
                $overlapEnd = $out->lessThan($schedEnd) ? $out : $schedEnd;
                
                if ($overlapStart->lessThan($overlapEnd)) {
                    $regularHours += $overlapStart->diffInSeconds($overlapEnd) / 3600;
                }

                // Overtime: only for Staff, time worked AFTER schedule end
                if ($user->isStaff() && $out->greaterThan($schedEnd)) {
                    $otStart = $schedEnd;
                    $otEnd = $out;
                    $overtimeHours += $otStart->diffInSeconds($otEnd) / 3600;
                }
            }
        } else {
            // No schedule found — count raw hours
            $regularHours = max(0, $in->diffInSeconds($out) / 3600);
        }

        return [
            'regular' => $regularHours,
            'overtime' => $overtimeHours,
        ];
    }

    /**
     * Find the hours a professor worked on their last working day BEFORE a given date.
     * Used to seed the holiday-pay tracker when the pay period starts with a holiday.
     * Searches up to 30 days back to find the most recent attendance.
     */
    private function findLastWorkedHoursBeforePeriod(User $user, string $periodStart): float
    {
        $lookbackDate = Carbon::parse($periodStart)->subDays(30)->toDateString();

        $recentLogs = $user->attendanceLogs()
            ->where('date', '<', $periodStart)
            ->where('date', '>=', $lookbackDate)
            ->whereNotNull('time_in')
            ->whereNotNull('time_out')
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy(fn($log) => $log->date->toDateString());

        if ($recentLogs->isEmpty()) {
            return 0;
        }

        // Take the most recent day's logs
        $lastDay = $recentLogs->first();
        $lastDateStr = $lastDay->first()->date->toDateString();
        $dayName = Carbon::parse($lastDateStr)->format('l');

        $daySchedules = $user->schedules
            ->where('day_of_week', $dayName)
            ->filter(fn($s) => is_null($s->effective_from) || $s->effective_from <= $lastDateStr);

        $totalHours = 0;
        foreach ($lastDay as $log) {
            $in = Carbon::parse($lastDateStr . ' ' . $log->time_in);
            $out = Carbon::parse($lastDateStr . ' ' . $log->time_out);
            $result = $this->calculateSessionHours($in, $out, $daySchedules, $lastDateStr, $user);
            $totalHours += $result['regular'];
        }

        return $totalHours;
    }

    /**
     * Professor payroll: purely hourly.
     * gross_pay = total_hours × hourly_rate
     */
    private function calculateProfessorPayroll(User $user, $startDate, $endDate, float $totalHours, float $totalLateMinutes, ?Payroll $existing, array $holidayDetails = []): Payroll
    {
        $hourlyRate = $user->hourly_rate ?? 0;
        $grossPay = $totalHours * $hourlyRate;
        
        // Late deduction (per minute)
        $lateDeduction = ($totalLateMinutes * ($hourlyRate / 60));
        
        // Statutory deductions
        $statutory = $this->deductionService->computeAll($grossPay);
        
        // Fiscal snapshot + holiday details
        $snapshot = \App\Models\SystemSetting::all()->pluck('value', 'key')->toArray();
        $snapshot['holiday_details'] = $holidayDetails;
        $snapshot['holiday_pay_method'] = 'professor';
        
        $totalDeductionsEE = $lateDeduction + $statutory['total_ee'];
        $netPay = max(0, $grossPay - $totalDeductionsEE);

        return Payroll::updateOrCreate(
            ['user_id' => $user->id, 'period_start' => $startDate, 'period_end' => $endDate],
            [
                'total_hours' => $totalHours,
                'overtime_hours' => 0,
                'overtime_pay' => 0,
                'late_minutes' => $totalLateMinutes,
                'late_deduction' => $lateDeduction,
                'undertime_deduction' => 0,
                'absence_deduction' => 0,
                'sss_deduction' => $statutory['sss'],
                'philhealth_deduction' => $statutory['philhealth'],
                'pagibig_deduction' => $statutory['pagibig'],
                'tax_deduction' => $statutory['tax'],
                'sss_employer' => $statutory['sss_er'],
                'philhealth_employer' => $statutory['philhealth_er'],
                'pagibig_employer' => $statutory['pagibig_er'],
                'sss_ec' => $statutory['sss_ec'],
                'total_deductions' => $totalDeductionsEE,
                'gross_pay' => $grossPay,
                'net_pay' => $netPay,
                'status' => 'Draft',
                'calculation_snapshot' => $snapshot,
            ]
        );
    }

    /**
     * Staff payroll: fixed salary base.
     * base_pay = monthly_salary ÷ 2
     * absence_deduction = missed_hours × effective_hourly_rate
     * overtime_pay = overtime_hours × effective_hourly_rate × 1.25 (staff only)
     * gross_pay = base_pay - absence_deduction + overtime_pay
     */
    private function calculateFixedSalaryPayroll(
        User $user, $startDate, $endDate,
        float $totalRegularHours, float $totalOvertimeHours, float $totalExpectedHours,
        float $totalLateMinutes, ?Payroll $existing, array $holidayDetails = []
    ): Payroll {
        $monthlySalary = (float) ($user->monthly_salary ?? 0);
        $basePay = $monthlySalary / 2; // Semi-monthly

        $effectiveRate = $user->getEffectiveHourlyRate();

        // Absence deduction: hours they should have worked but didn't
        $missedHours = max(0, $totalExpectedHours - $totalRegularHours);
        $absenceDeduction = $missedHours * $effectiveRate;

        // Overtime: staff only, using custom overtime rate
        $overtimePay = 0;
        if ($user->isStaff() && $totalOvertimeHours > 0) {
            $otRate = (float) ($user->overtime_rate ?? 0);
            $overtimePay = $totalOvertimeHours * $otRate;
        }

        // Late deduction (per minute, based on effective rate)
        $lateDeduction = ($totalLateMinutes * ($effectiveRate / 60));

        // Gross pay
        $grossPay = max(0, $basePay - $absenceDeduction + $overtimePay);

        // Statutory deductions
        $statutory = $this->deductionService->computeAll($grossPay);

        // Fiscal snapshot + holiday details
        $snapshot = \App\Models\SystemSetting::all()->pluck('value', 'key')->toArray();
        $snapshot['holiday_details'] = $holidayDetails;
        $snapshot['holiday_pay_method'] = $user->employment_type ?? 'staff';

        $totalDeductionsEE = $lateDeduction + $statutory['total_ee'];
        $netPay = max(0, $grossPay - $totalDeductionsEE);

        return Payroll::updateOrCreate(
            ['user_id' => $user->id, 'period_start' => $startDate, 'period_end' => $endDate],
            [
                'total_hours' => $totalRegularHours,
                'overtime_hours' => $totalOvertimeHours,
                'overtime_pay' => $overtimePay,
                'late_minutes' => $totalLateMinutes,
                'late_deduction' => $lateDeduction,
                'undertime_deduction' => 0,
                'absence_deduction' => $absenceDeduction,
                'sss_deduction' => $statutory['sss'],
                'philhealth_deduction' => $statutory['philhealth'],
                'pagibig_deduction' => $statutory['pagibig'],
                'tax_deduction' => $statutory['tax'],
                'sss_employer' => $statutory['sss_er'],
                'philhealth_employer' => $statutory['philhealth_er'],
                'pagibig_employer' => $statutory['pagibig_er'],
                'sss_ec' => $statutory['sss_ec'],
                'total_deductions' => $totalDeductionsEE,
                'gross_pay' => $grossPay,
                'net_pay' => $netPay,
                'status' => 'Draft',
                'calculation_snapshot' => $snapshot,
            ]
        );
    }
}
