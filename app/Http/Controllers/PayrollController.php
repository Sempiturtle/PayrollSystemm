<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\AttendanceLog;
use App\Models\User;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with('user')->orderBy('period_end', 'desc')->get();
        return view('payrolls.index', compact('payrolls'));
    }

    public function generate(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $users = User::where('role', '!=', 'admin')->get();

        foreach ($users as $user) {
            $logs = AttendanceLog::where('user_id', $user->id)
                                ->whereBetween('date', [$startDate, $endDate])
                                ->get();

            $totalHours = 0;
            $lateMinutes = 0;

            foreach ($logs as $log) {
                if ($log->time_in && $log->time_out) {
                    $in = Carbon::parse($log->date . ' ' . $log->time_in);
                    $out = Carbon::parse($log->date . ' ' . $log->time_out);
                    $totalHours += $out->diffInMinutes($in) / 60;
                }

                if ($log->status === 'Late') {
                    // Find schedule for that day to calculate exactly how late
                    $dayName = Carbon::parse($log->date)->format('l');
                    $schedule = Schedule::where('user_id', $user->id)
                                        ->where('day_of_week', $dayName)
                                        ->where(function($query) use ($log) {
                                            $query->where('effective_from', '<=', $log->date)
                                                  ->orWhereNull('effective_from');
                                        })
                                        ->orderBy('effective_from', 'desc')
                                        ->first();
                    
                    if ($schedule && $log->time_in > $schedule->start_time) {
                        $schedIn = Carbon::parse($log->date . ' ' . $schedule->start_time);
                        $actualIn = Carbon::parse($log->date . ' ' . $log->time_in);
                        $lateMinutes += max(0, $actualIn->diffInMinutes($schedIn));
                    }
                }
            }

            $grossPay = $totalHours * ($user->hourly_rate ?? 0);
            $lateDeduction = ($lateMinutes / 60) * ($user->hourly_rate ?? 0);
            $netPay = max(0, $grossPay - $lateDeduction);

            Payroll::updateOrCreate(
                ['user_id' => $user->id, 'period_start' => $startDate, 'period_end' => $endDate],
                [
                    'total_hours' => $totalHours,
                    'late_minutes' => $lateMinutes,
                    'total_deductions' => $lateDeduction,
                    'gross_pay' => $grossPay,
                    'net_pay' => $netPay,
                ]
            );
        }

        return redirect()->route('payrolls.index')->with('success', 'Payroll generated successfully for the period!');
    }
}
