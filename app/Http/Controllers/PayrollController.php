<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\AttendanceLog;
use App\Models\User;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\PayrollService;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    public function __construct(
        private readonly PayrollService $payrollService
    ) {}

    public function index()
    {
        $query = Payroll::with('user')->orderBy('period_end', 'desc');
        
        if (!auth()->user()->isStaffOrAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $payrolls = $query->paginate(10);
        return view('payrolls.index', compact('payrolls'));
    }

    /**
     * Display the detailed payroll breakdown including holiday pay.
     */
    public function show(Payroll $payroll)
    {
        // Security: Non-admins/non-moderators can only view their own payroll
        if (!auth()->user()->isStaffOrAdmin() && $payroll->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $payroll->load('user');

        // Extract holiday details from the calculation snapshot
        $snapshot = $payroll->calculation_snapshot ?? [];
        $holidayDetails = $snapshot['holiday_details'] ?? [];
        $holidayPayMethod = $snapshot['holiday_pay_method'] ?? 'N/A';

        // Get holidays that fall within this payroll period for context
        $periodHolidays = \App\Models\Holiday::whereBetween('date', [
            $payroll->period_start,
            $payroll->period_end,
        ])->orderBy('date')->get();

        return view('payrolls.show', compact('payroll', 'holidayDetails', 'holidayPayMethod', 'periodHolidays'));
    }

    public function generate(Request $request)
    {
        // If dates are provided, use them; otherwise, use the current fixed period
        if ($request->filled(['start_date', 'end_date'])) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
        } else {
            $period = $this->payrollService->getCurrentPeriod();
            $startDate = $period['start'];
            $endDate = $period['end'];
        }

        $users = User::where('role', '!=', 'admin')->get();

        foreach ($users as $user) {
            $this->payrollService->syncForUser($user, $startDate, $endDate);
        }

        return redirect()->route('payrolls.index')->with('success', 'Payroll for period ' . $startDate . ' to ' . $endDate . ' has been processed.');
    }

    public function downloadPayslip(Payroll $payroll)
    {
        // Security: Non-admins/non-moderators can only download their own payslips
        if (!auth()->user()->isStaffOrAdmin() && $payroll->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $payroll->load('user');
        
        $pdf = Pdf::loadView('payrolls.pdf', compact('payroll'));
        
        $filename = 'payslip_' . $payroll->user->name . '_' . $payroll->period_end . '.pdf';
        return $pdf->download(str_replace(' ', '_', strtolower($filename)));
    }

    /**
     * Finalize (Lock) the payroll record.
     */
    public function finalize(Payroll $payroll)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $payroll->update(['status' => 'Finalized']);

        return redirect()->back()->with('success', 'Payroll record for ' . $payroll->user->name . ' has been finalized and locked.');
    }
}
