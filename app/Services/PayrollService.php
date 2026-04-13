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
     */
    public function syncForUser(User $user, $startDate, $endDate): Payroll
    {
        return DB::transaction(function () use ($user, $startDate, $endDate) {
            // 1. Get all base data
            $logs = $user->attendanceLogs()
                ->whereBetween('date', [$startDate, $endDate])
                ->get()
                ->keyBy(fn($log) => $log->date->toDateString());

            $leaves = $user->leaves()
                ->where('status', 'Approved')
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(fn($sq) => $sq->where('start_date', '<', $startDate)->where('end_date', '>', $endDate));
                })->get();

            $holidays = \App\Models\Holiday::whereBetween('date', [$startDate, $endDate])->get()
                ->keyBy(fn($h) => $h->date->toDateString());

            $totalHours = 0;
            $lateCount = 0;
            $totalLateMinutes = 0;
            $processedDates = [];

            // 2. Iterate through every day in the period
            $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
            
            foreach ($period as $date) {
                $dateStr = $date->toDateString();
                $dayName = $date->format('l');

                // Get user's schedule for this day
                $daySchedules = $user->schedules
                    ->where('day_of_week', $dayName)
                    ->filter(fn($s) => is_null($s->effective_from) || $s->effective_from <= $dateStr);

                // Check for Holiday/Suspension first
                if (isset($holidays[$dateStr])) {
                    $holiday = $holidays[$dateStr];
                    $hasWorked = isset($logs[$dateStr]) && $logs[$dateStr]->time_in && $logs[$dateStr]->time_out;

                    // A. Case: Employee did NOT work
                    if (!$hasWorked) {
                        if ($holiday->is_paid) {
                            foreach ($daySchedules as $sched) {
                                $totalHours += $sched->scheduled_hours; // 100% pay for unworked holiday
                            }
                        }
                    } 
                    // B. Case: Employee DID work
                    else {
                        /** @var AttendanceLog $log */
                        $log = $logs[$dateStr];
                        $in = \Carbon\Carbon::parse($dateStr . ' ' . $log->time_in);
                        $out = \Carbon\Carbon::parse($dateStr . ' ' . $log->time_out);
                        $workHours = max(0, $in->diffInSeconds($out) / 3600);

                        $multiplier = $holiday->is_double_pay ? 2 : 1;
                        $totalHours += ($workHours * $multiplier);

                        // Optional: Still check for lateness if they worked on a holiday? 
                        // For now we follow the user's specific pay request.
                        if ($log->status === 'Late') {
                            $lateCount++;
                        }
                    }

                    continue; // Skip standard processing for this day
                }

                // Check for Attendance Logs
                if (isset($logs[$dateStr])) {
                    $log = $logs[$dateStr];
                    
                    if ($log->time_in && $log->time_out) {
                        $in = \Carbon\Carbon::parse($dateStr . ' ' . $log->time_in);
                        $out = \Carbon\Carbon::parse($dateStr . ' ' . $log->time_out);
                        $totalHours += max(0, $in->diffInSeconds($out) / 3600);
                    }

                    if ($log->status === 'Late') {
                        $lateCount++;
                        $matchingSchedule = $daySchedules
                            ->sortBy('start_time')
                            ->first(function($s) use ($log) {
                                $logIn = \Carbon\Carbon::parse($log->time_in)->format('H:i:s');
                                return $logIn >= $s->start_time && $logIn <= $s->end_time;
                            });

                        if ($matchingSchedule) {
                            $schedIn = \Carbon\Carbon::parse($dateStr . ' ' . $matchingSchedule->start_time);
                            $actualIn = \Carbon\Carbon::parse($dateStr . ' ' . $log->time_in);
                            $totalLateMinutes += max(0, $actualIn->diffInMinutes($schedIn));
                        }
                    }
                    continue;
                }

                // Check for Approved Leaves
                $isOnLeave = $leaves->contains(fn($l) => $dateStr >= $l->start_date && $dateStr <= $l->end_date);
                if ($isOnLeave) {
                    foreach ($daySchedules as $sched) {
                        $totalHours += $sched->scheduled_hours;
                    }
                }
            }

            // 3. Final Calculations
            $hourlyRate = $user->hourly_rate ?? 0;
            $grossPay = $totalHours * $hourlyRate;
            
            // NEW RULE: 1 hour deduction per late instance
            $lateDeduction = $lateCount * $hourlyRate;
            
            // Calculate Statutory Deductions
            $statutory = $this->deductionService->computeAll($grossPay);
            
            $totalDeductions = $lateDeduction + $statutory['total'];
            $netPay = max(0, $grossPay - $totalDeductions);

            return Payroll::updateOrCreate(
                ['user_id' => $user->id, 'period_start' => $startDate, 'period_end' => $endDate],
                [
                    'total_hours' => $totalHours,
                    'late_minutes' => $totalLateMinutes,
                    'sss_deduction' => $statutory['sss'],
                    'philhealth_deduction' => $statutory['philhealth'],
                    'pagibig_deduction' => $statutory['pagibig'],
                    'tax_deduction' => $statutory['tax'],
                    'total_deductions' => $totalDeductions,
                    'gross_pay' => $grossPay,
                    'net_pay' => $netPay,
                    'status' => 'Draft',
                ]
            );
        });
    }
}
