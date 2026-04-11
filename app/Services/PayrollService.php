<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payroll;
use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
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
            // 1. Get all attendance logs for the period
            $logs = $user->attendanceLogs()
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $totalHours = 0;
            $lateCount = 0;
            $totalLateMinutes = 0;
            $processedDays = [];

            foreach ($logs as $log) {
                $dateStr = $log->date->toDateString();
                
                // Calculate hours worked
                if ($log->time_in && $log->time_out) {
                    $in = Carbon::parse($dateStr . ' ' . $log->time_in);
                    $out = Carbon::parse($dateStr . ' ' . $log->time_out);
                    $totalHours += max(0, $in->diffInSeconds($out) / 3600);
                }

                if ($log->status === 'Late') {
                    $lateCount++;
                    
                    // Still tracking late minutes for metadata/display
                    $dayName = $log->date->format('l');
                    $matchingSchedule = $user->schedules
                        ->where('day_of_week', $dayName)
                        ->filter(fn($s) => is_null($s->effective_from) || $s->effective_from <= $log->date)
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
                $processedDays[] = $dateStr;
            }

            // 2. Add Pay for Approved Leaves (using schedule hours)
            $leaves = $user->leaves()
                ->where('status', 'Approved')
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(fn($sq) => $sq->where('start_date', '<', $startDate)->where('end_date', '>', $endDate));
                })->get();

            foreach ($leaves as $leave) {
                $leaveStart = max(Carbon::parse($leave->start_date), Carbon::parse($startDate));
                $leaveEnd = min(Carbon::parse($leave->end_date), Carbon::parse($endDate));

                for ($date = $leaveStart->copy(); $date->lte($leaveEnd); $date->addDay()) {
                    $currentDateStr = $date->toDateString();
                    if (in_array($currentDateStr, $processedDays)) continue;

                    $daySchedules = $user->schedules
                        ->where('day_of_week', $date->format('l'))
                        ->filter(fn($s) => is_null($s->effective_from) || $s->effective_from <= $currentDateStr);
                        
                    foreach ($daySchedules as $sched) {
                        $totalHours += $sched->scheduled_hours;
                    }
                    $processedDays[] = $currentDateStr;
                }
            }

            // 3. Final Calculations
            $hourlyRate = $user->hourly_rate ?? 0;
            $grossPay = $totalHours * $hourlyRate;
            
            // NEW RULE: 1 hour deduction per late instance
            $lateDeduction = $lateCount * $hourlyRate;
            
            $netPay = max(0, $grossPay - $lateDeduction);

            return Payroll::updateOrCreate(
                ['user_id' => $user->id, 'period_start' => $startDate, 'period_end' => $endDate],
                [
                    'total_hours' => $totalHours,
                    'late_minutes' => $totalLateMinutes, // Keep minutes for display info
                    'total_deductions' => $lateDeduction,
                    'gross_pay' => $grossPay,
                    'net_pay' => $netPay,
                    'status' => 'Draft',
                ]
            );
        });
    }
}
