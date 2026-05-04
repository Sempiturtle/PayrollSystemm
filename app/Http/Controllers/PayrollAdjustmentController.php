<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollAdjustment;
use Illuminate\Http\Request;

class PayrollAdjustmentController extends Controller
{
    /**
     * Store a new adjustment for a payroll record.
     */
    public function store(Request $request, Payroll $payroll)
    {
        $validated = $request->validate([
            'type' => 'required|in:bonus,deduction',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'remarks' => 'nullable|string|max:500',
        ]);

        if ($payroll->status === 'Finalized') {
            return back()->with('error', 'Cannot add adjustments to a finalized payroll.');
        }

        $payroll->adjustments()->create([
            ...$validated,
            'user_id' => $payroll->user_id,
            'created_by' => auth()->id(),
        ]);

        // Recalculate net pay with adjustments
        $this->recalculateNetPay($payroll);

        return back()->with('success', 'Adjustment added successfully.');
    }

    /**
     * Remove an adjustment.
     */
    public function destroy(PayrollAdjustment $adjustment)
    {
        $payroll = $adjustment->payroll;

        if ($payroll->status === 'Finalized') {
            return back()->with('error', 'Cannot modify adjustments on a finalized payroll.');
        }

        $adjustment->delete();

        // Recalculate net pay after removal
        $this->recalculateNetPay($payroll);

        return back()->with('success', 'Adjustment removed.');
    }

    /**
     * Recalculate net pay incorporating all adjustments.
     */
    private function recalculateNetPay(Payroll $payroll): void
    {
        $payroll->refresh();

        $baseNet = $payroll->gross_pay - $payroll->total_deductions;
        $bonuses = $payroll->adjustments()->where('type', 'bonus')->sum('amount');
        $deductions = $payroll->adjustments()->where('type', 'deduction')->sum('amount');

        $payroll->update([
            'net_pay' => max(0, $baseNet + $bonuses - $deductions),
        ]);
    }
}
