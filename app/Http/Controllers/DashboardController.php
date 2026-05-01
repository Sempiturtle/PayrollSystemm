<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AttendanceLog;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Leave;
use App\Models\Holiday;
use App\Services\PayrollService;

class DashboardController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }
    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();
        $dayName = $now->englishDayOfWeek;

        $todayHoliday = \App\Models\Holiday::where('date', $today)->first();

        if ($user->isAdmin()) {
            // Admin Stats
            $totalEmployees = User::where('role', '!=', 'admin')->count();
            $presentToday = AttendanceLog::where('date', $today)->where('status', 'On-time')->count();
            $lateToday = AttendanceLog::where('date', $today)->where('status', 'Late')->count();
            
            $expectedToday = Schedule::where('day_of_week', $dayName)->count();
            $absentToday = max(0, $expectedToday - ($presentToday + $lateToday));

            // Chart 1: Attendance Distribution (Donut)
            $attendanceStats = [
                'On-time' => $presentToday,
                'Late' => $lateToday,
                'Absent' => $absentToday,
            ];

            // Chart 2: Department Distribution (Donut - logic preserved for Radial)
            $deptStats = [
                'Professors' => User::where('role', 'professor')->count(),
                'Staff' => User::where('role', 'employee')->count(),
            ];

            // Chart 3: Payroll Trends (Last 6 Months)
            $payrollTrend = \App\Models\Payroll::selectRaw('SUM(net_pay) as total, MONTHNAME(period_end) as month, MAX(period_end) as sort_date')
                ->groupBy('month')
                ->orderBy('sort_date', 'asc')
                ->limit(6)
                ->get();

            // Institutional Pulse: Hourly Flow
            $hourlyFlow = AttendanceLog::where('date', $today)
                ->selectRaw('HOUR(time_in) as hour, COUNT(*) as count')
                ->groupBy('hour')
                ->orderBy('hour')
                ->pluck('count', 'hour')
                ->toArray();
            
            $hourlyActivities = [];
            for($i=0; $i<24; $i++) { $hourlyActivities[] = $hourlyFlow[$i] ?? 0; }

            // 7-Day Velocity (Sparklines)
            $sparklineData = AttendanceLog::where('date', '>', $now->copy()->subDays(7)->toDateString())
                ->selectRaw('COUNT(*) as count, date')
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('count')
                ->toArray();

            $recentLogs = AttendanceLog::with('user')
                ->where('date', $today)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $performerStats = AttendanceLog::select('user_id', \DB::raw('count(*) as count'))
                ->where('status', 'On-time')
                ->where('date', '>=', $now->copy()->subDays(30)->toDateString())
                ->groupBy('user_id')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->with('user')
                ->get();

            // Intelligence Additions
            $pendingLeaves = Leave::where('status', 'Pending')->count();
            $upcomingHolidays = Holiday::where('date', '>', $today)
                ->orderBy('date', 'asc')
                ->limit(2)
                ->get();

            // Total Statutory Managed (Current Month)
            $statStats = \App\Models\Payroll::whereMonth('period_end', $now->month)
                ->whereYear('period_end', $now->year)
                ->selectRaw('SUM(sss_deduction) as sss, SUM(philhealth_deduction) as philhealth, SUM(pagibig_deduction) as pagibig, SUM(tax_deduction) as tax, COUNT(CASE WHEN status = "Finalized" THEN 1 END) as finalized_count, COUNT(CASE WHEN status = "Draft" THEN 1 END) as draft_count')
                ->first();

            // Profile Completion Logic
            $incompleteProfilesCount = User::where('role', '!=', 'admin')->get()->filter(function($u) {
                return $u->profile_completion < 100;
            })->count();

            // Executive Command Center Data
            $pendingDiscrepancies = \App\Models\DiscrepancyReport::where('status', 'Pending')->count();
            $unfinalizedPayrolls = \App\Models\Payroll::where('status', 'Draft')->count();

            return view('dashboard', compact(
                'totalEmployees', 
                'presentToday', 
                'lateToday', 
                'absentToday', 
                'recentLogs',
                'attendanceStats',
                'payrollTrend',
                'todayHoliday',
                'pendingLeaves',
                'upcomingHolidays',
                'performerStats',
                'statStats',
                'hourlyActivities',
                'sparklineData',
                'deptStats',
                'incompleteProfilesCount',
                'pendingDiscrepancies',
                'unfinalizedPayrolls'
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

            // Professor's full weekly schedule
            $mySchedule = Schedule::forUser($user->id)->orderByDay()->get();

            // Today's specific schedule entry
            $todaySchedule = Schedule::forUser($user->id)->forDay($dayName)->first();

            // Intelligence Additions for Employee
            $upcomingHolidays = Holiday::where('date', '>', $today)
                ->orderBy('date', 'asc')
                ->limit(2)
                ->get();

            // REAL-TIME Hardening: Current Cycle Live Awareness
            $period = $this->payrollService->getCurrentPeriod($now);
            $cycleLogs = AttendanceLog::where('user_id', $user->id)
                ->whereBetween('date', [$period['start'], $period['end']])
                ->get();
            
            $cycleStats = [
                'current_hours' => 0,
                'current_lates' => $cycleLogs->where('status', 'Late')->count(),
                'start' => Carbon::parse($period['start'])->format('M d'),
                'end' => Carbon::parse($period['end'])->format('M d'),
            ];

            foreach($cycleLogs as $log) {
                if($log->time_in && $log->time_out) {
                    $in = Carbon::parse($log->date->toDateString().' '.$log->time_in);
                    $out = Carbon::parse($log->date->toDateString().' '.$log->time_out);
                    $cycleStats['current_hours'] += max(0, $in->diffInSeconds($out) / 3600);
                }
            }

            return view('dashboard_employee', compact(
                'user', 'myLogs', 'myPayrolls', 'todayLog', 'mySchedule', 
                'todaySchedule', 'todayHoliday', 'upcomingHolidays', 'cycleStats'
            ));
        }
    }
}
