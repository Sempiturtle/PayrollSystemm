<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Punctuality Score (Last 30 Days)
        $last30Days = Carbon::now()->subDays(30);
        $logs = AttendanceLog::where('user_id', $user->id)
            ->where('date', '>=', $last30Days)
            ->get();
            
        $totalDays = $logs->count();
        $lateDays = 0;
        
        foreach ($logs as $log) {
            $dayName = $log->date->format('l');
            $schedule = Schedule::where('user_id', $user->id)
                ->where('day_of_week', $dayName)
                ->first();
                
            if ($schedule && $log->time_in) {
                $scheduledIn = Carbon::parse($log->date->toDateString() . ' ' . $schedule->start_time);
                $actualIn = Carbon::parse($log->date->toDateString() . ' ' . $log->time_in);
                
                if ($actualIn->greaterThan($scheduledIn)) {
                    $lateDays++;
                }
            }
        }
        
        $punctualityScore = $totalDays > 0 ? round((($totalDays - $lateDays) / $totalDays) * 100, 1) : 100;

        // 2. Attendance Trends (Last 6 Months)
        $trends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthlyHours = AttendanceLog::where('user_id', $user->id)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->get()
                ->sum(function($log) {
                    if ($log->time_in && $log->time_out) {
                        return Carbon::parse($log->time_out)->diffInMinutes(Carbon::parse($log->time_in)) / 60;
                    }
                    return 0;
                });
                
            $trends[] = [
                'month' => $month->format('M'),
                'hours' => round($monthlyHours, 1)
            ];
        }

        return view('performance.index', compact('user', 'punctualityScore', 'trends', 'totalDays', 'lateDays'));
    }
}
