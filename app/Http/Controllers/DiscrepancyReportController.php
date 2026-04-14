<?php

namespace App\Http\Controllers;

use App\Models\DiscrepancyReport;
use App\Models\Payroll;
use Illuminate\Http\Request;

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

        if ($validated['payroll_id']) {
            $payroll = Payroll::findOrFail($validated['payroll_id']);
            // Check if user owns this payroll
            if ($payroll->user_id !== auth()->id()) {
                abort(403);
            }
        }

        DiscrepancyReport::create([
            'user_id' => auth()->id(),
            'payroll_id' => $validated['payroll_id'],
            'description' => $validated['description'],
            'status' => 'Pending',
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
     * Update report status (Admin).
     */
    public function update(Request $request, DiscrepancyReport $report)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:Pending,Reviewing,Resolved,Dismissed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->update($validated);

        return back()->with('success', 'Discrepancy report updated.');
    }
}
