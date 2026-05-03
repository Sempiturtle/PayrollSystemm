<?php

namespace App\Http\Controllers;

use App\Models\Substitution;
use App\Models\User;
use App\Models\PayrollAdjustment;
use App\Models\Payroll;
use App\Services\AttendanceService;
use App\Services\PayrollService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubstitutionController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
        private readonly PayrollService $payrollService
    ) {}

    /**
     * Get relief suggestions for an absent faculty member.
     */
    public function suggestions(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        // Find the active schedule block for the absent user
        $now = Carbon::now('Asia/Manila');
        $dayName = $now->format('l');
        $currentTime = $now->toTimeString();

        $activeBlock = $user->schedules()
            ->where('day_of_week', $dayName)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>', $currentTime)
            ->first();

        if (!$activeBlock) {
            return response()->json(['message' => 'No active schedule block detected for this node.'], 404);
        }

        $availableStaff = $this->attendanceService->getAvailableReliefStaff($now);

        return response()->json([
            'absent_user' => $user,
            'block' => $activeBlock,
            'suggestions' => $availableStaff
        ]);
    }

    /**
     * Confirm a substitution and trigger fiscal adjustment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'absent_user_id' => 'required|exists:users,id',
            'relief_user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'bonus_amount' => 'nullable|numeric|min:0',
        ]);

        $reliefUser = User::findOrFail($validated['relief_user_id']);
        $absentUser = User::findOrFail($validated['absent_user_id']);
        
        // 1. Create the Payroll Adjustment for Relief Staff
        $period = $this->payrollService->getCurrentPeriod(Carbon::parse($validated['date']));
        $payroll = Payroll::firstOrCreate(
            ['user_id' => $reliefUser->id, 'period_start' => $period['start'], 'period_end' => $period['end']],
            ['status' => 'Draft', 'gross_pay' => 0, 'net_pay' => 0]
        );

        $bonus = $validated['bonus_amount'] ?? 500; // Default Relief Premium

        $adjustment = PayrollAdjustment::create([
            'payroll_id' => $payroll->id,
            'type' => 'Bonus',
            'amount' => $bonus,
            'description' => "Institutional Relief Premium: Coverage for {$absentUser->name} on {$validated['date']}",
        ]);

        // 2. Log the Substitution
        $substitution = Substitution::create([
            'absent_user_id' => $absentUser->id,
            'relief_user_id' => $reliefUser->id,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'bonus_amount' => $bonus,
            'adjustment_id' => $adjustment->id,
        ]);

        // 3. Sync Payroll
        $this->payrollService->syncForUser($reliefUser, $period['start'], $period['end']);

        return back()->with('success', "Institutional Continuity Secured. {$reliefUser->name} assigned as relief for {$absentUser->name}. Fiscal premium of ₱{$bonus} applied.");
    }
}
