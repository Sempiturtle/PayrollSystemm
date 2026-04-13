<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payroll;
use App\Models\AttendanceLog;
use App\Services\PayrollService;
use App\Exports\AttendanceExport;
use App\Exports\PeriodSummaryExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    /**
     * Option 1: Today's Attendance Summary (Excel)
     */
    public function attendanceToday()
    {
        $date = now()->toDateString();
        return Excel::download(new AttendanceExport($date), "attendance_summary_{$date}.xlsx");
    }

    /**
     * Option 2: Payroll Insight (Professional PDF)
     */
    public function payrollInsight()
    {
        $period = $this->payrollService->getCurrentPeriod();
        
        $payrolls = Payroll::with('user')
            ->where('period_start', $period['start'])
            ->where('period_end', $period['end'])
            ->get();

        $totalPayout = $payrolls->sum('net_pay');
        $totalDeductions = $payrolls->sum('total_deductions');
        $activeEmployees = $payrolls->count();

        // Summarize by role/department
        $deptSummary = [];
        $roles = $payrolls->pluck('user.role')->unique();
        
        foreach ($roles as $role) {
            $rolePayrolls = $payrolls->filter(fn($p) => $p->user->role === $role);
            $deptSummary[] = [
                'name' => $role,
                'count' => $rolePayrolls->count(),
                'total' => $rolePayrolls->sum('net_pay')
            ];
        }

        $pdf = Pdf::loadView('pdf.payroll_insight', compact(
            'totalPayout', 
            'totalDeductions', 
            'activeEmployees', 
            'deptSummary',
            'period'
        ));

        return $pdf->download("payroll_insight_{$period['start']}.pdf");
    }

    /**
     * Option 3: Complete Period Summary (Excel)
     */
    public function periodSummary()
    {
        $period = $this->payrollService->getCurrentPeriod();
        return Excel::download(
            new PeriodSummaryExport($period['start'], $period['end']), 
            "period_summary_{$period['start']}_to_{$period['end']}.xlsx"
        );
    }
}
