<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\AttendanceLog;
use App\Models\DiscrepancyReport;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Generate a high-level Executive Fiscal Summary for institutional stakeholders.
     */
    public function executiveSummary()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        // Aggregate Data for the current/most recent period
        $latestPayroll = Payroll::orderBy('period_end', 'desc')->first();
        
        if (!$latestPayroll) {
            return back()->with('error', 'No institutional fiscal records localized for export.');
        }

        $periodStart = $latestPayroll->period_start;
        $periodEnd = $latestPayroll->period_end;

        $payrolls = Payroll::where('period_end', $periodEnd)->get();
        
        $fiscalMetrics = [
            'total_gross' => $payrolls->sum('gross_pay'),
            'total_net' => $payrolls->sum('net_pay'),
            'total_deductions' => $payrolls->sum('total_deductions'),
            'total_sss' => $payrolls->sum('sss_deduction'),
            'total_philhealth' => $payrolls->sum('philhealth_deduction'),
            'total_pagibig' => $payrolls->sum('pagibig_deduction'),
            'total_tax' => $payrolls->sum('tax_deduction'),
            'staff_count' => User::where('role', '!=', 'admin')->count(),
            'finalized_count' => $payrolls->where('status', 'Finalized')->count(),
        ];

        $attendanceMetrics = [
            'standard' => AttendanceLog::whereBetween('date', [$periodStart, $periodEnd])->where('status', 'On-time')->count(),
            'latency' => AttendanceLog::whereBetween('date', [$periodStart, $periodEnd])->where('status', 'Late')->count(),
        ];

        $discrepancyMetrics = [
            'total' => DiscrepancyReport::whereBetween('created_at', [$periodStart, $periodEnd])->count(),
            'resolved' => DiscrepancyReport::whereBetween('created_at', [$periodStart, $periodEnd])->where('status', 'Resolved')->count(),
        ];

        $pdf = Pdf::loadView('pdf.executive_summary', compact('fiscalMetrics', 'attendanceMetrics', 'discrepancyMetrics', 'periodStart', 'periodEnd'));
        
        return $pdf->download("AISAT_Executive_Summary_{$periodEnd->format('Y-m-d')}.pdf");
    }
}
