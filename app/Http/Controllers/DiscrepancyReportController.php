<?php

namespace App\Http\Controllers;

use App\Models\DiscrepancyReport;
use App\Models\AuditLog;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscrepancyReportController extends Controller
{
    /**
     * Store a new discrepancy report.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payroll_id' => 'nullable|exists:payrolls,id',
            'description' => 'required|string|max:1000',
        ]);

        if (!empty($validated['payroll_id'])) {
            $payroll = Payroll::findOrFail($validated['payroll_id']);
            // Check if user owns this payroll
            if ($payroll->user_id !== auth()->id()) {
                abort(403);
            }
        }

        $report = DiscrepancyReport::create([
            'user_id' => auth()->id(),
            'payroll_id' => $validated['payroll_id'] ?? null,
            'description' => $validated['description'],
            'status' => 'Pending',
        ]);

        // Audit: Employee filed a discrepancy
        AuditLog::create([
            'user_id' => Auth::id(),
            'auditable_type' => DiscrepancyReport::class,
            'auditable_id' => $report->id,
            'event' => 'created (Employee Filed Discrepancy)',
            'old_values' => [],
            'new_values' => $report->toArray(),
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Discrepancy reported successfully. An administrator will review it.');
    }

    /**
     * Display reports for the admin.
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $reports = DiscrepancyReport::with(['user', 'payroll'])
            ->latest()
            ->paginate(20);

        return view('admin.discrepancies.index', compact('reports'));
    }

    /**
     * Show employee's own discrepancy tickets.
     */
    public function myDisputes()
    {
        $reports = DiscrepancyReport::with('payroll')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('discrepancies.my_disputes', compact('reports'));
    }

    /**
     * Update report status (Admin).
     */
    public function update(Request $request, DiscrepancyReport $report)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $oldValues = $report->toArray();

        $validated = $request->validate([
            'status' => 'required|in:Pending,Reviewing,Resolved,Dismissed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->update($validated);

        // Immutable Audit: Admin resolved/changed a discrepancy
        AuditLog::create([
            'user_id' => Auth::id(),
            'auditable_type' => DiscrepancyReport::class,
            'auditable_id' => $report->id,
            'event' => 'updated (Admin ' . $validated['status'] . ' Discrepancy #' . $report->id . ')',
            'old_values' => $oldValues,
            'new_values' => $report->fresh()->toArray(),
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Discrepancy report updated and secured in audit trail.');
    }
}
