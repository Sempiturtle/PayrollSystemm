<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AttendanceLog;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();
        $dayName = $now->englishDayOfWeek;

        if ($user->isAdmin()) {
            // Admin Stats
            $totalEmployees = User::where('role', '!=', 'admin')->count();
            $presentToday = AttendanceLog::where('date', $today)->where('status', 'On-time')->count();
            $lateToday = AttendanceLog::where('date', $today)->where('status', 'Late')->count();
            
            $expectedToday = Schedule::where('day_of_week', $dayName)->count();
            $absentToday = max(0, $expectedToday - ($presentToday + $lateToday));

            // Chart 1: Department Distribution
            $deptStats = [
                'professors' => User::where('role', 'professor')->count(),
                'staff' => User::where('role', 'employee')->count(),
            ];

            // Chart 2: Payroll Trends (Last 6 Months)
            $payrollTrend = \App\Models\Payroll::selectRaw('SUM(net_pay) as total, MONTHNAME(period_end) as month')
                ->groupBy('month')
                ->orderBy('period_end', 'asc')
                ->limit(6)
                ->get();

            $recentLogs = AttendanceLog::with('user')
                ->where('date', $today)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('dashboard', compact(
                'totalEmployees', 
                'presentToday', 
                'lateToday', 
                'absentToday', 
                'recentLogs',
                'deptStats',
                'payrollTrend'
            ));
        } else {
            // Employee Stats
            $myLogs = AttendanceLog::where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();
            
            $myPayrolls = \App\Models\Payroll::where('user_id', $user->id)
                ->orderBy('period_end', 'desc')
                ->limit(3)
                ->get();

            $todayLog = AttendanceLog::where('user_id', $user->id)
                ->where('date', $today)
                ->first();

            return view('dashboard_employee', compact('user', 'myLogs', 'myPayrolls', 'todayLog'));
        }
    }
}
