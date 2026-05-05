<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\AttendanceLog;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\AttendanceService;
use App\Services\PayrollService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
        private readonly PayrollService $payrollService
    ) {}

    /**
     * Display a listing of attendance logs with filtering.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'user_id', 'status']);
        $logs = $this->attendanceService->getFilteredLogs($filters);

        $employees = User::where('role', '!=', 'admin')->get();

        return view('attendance.admin_index', compact('logs', 'employees', 'filters'));
    }

    /**
     * Store a manually created attendance log.
     */
    public function store(StoreAttendanceRequest $request)
    {
        $log = AttendanceLog::create($request->validated());

        // Secure Audit Log Integration
        AuditLog::create([
            'user_id' => Auth::id(),
            'auditable_type' => AttendanceLog::class,
            'auditable_id' => $log->id,
            'event' => 'created (Admin Manual)',
            'old_values' => [],
            'new_values' => $log->toArray(),
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Sync Payroll
        $period = $this->payrollService->getCurrentPeriod($log->date);
        $this->payrollService->syncForUser($log->user, $period['start'], $period['end']);

        return redirect()->route('attendance.index')->with('success', 'Attendance record added manually and secured in audit trail.');
    }

    /**
     * Update an attendance log (manual adjustment).
     */
    public function update(UpdateAttendanceRequest $request, AttendanceLog $attendanceLog)
    {
        $oldValues = $attendanceLog->toArray();
        $attendanceLog->update($request->validated());

        // Secure Audit Log Integration
        AuditLog::create([
            'user_id' => Auth::id(),
            'auditable_type' => AttendanceLog::class,
            'auditable_id' => $attendanceLog->id,
            'event' => 'updated (Admin Discrepancy Fix)',
            'old_values' => $oldValues,
            'new_values' => $attendanceLog->fresh()->toArray(),
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Sync Payroll
        $period = $this->payrollService->getCurrentPeriod($attendanceLog->date);
        $this->payrollService->syncForUser($attendanceLog->user, $period['start'], $period['end']);

        return redirect()->route('attendance.index')->with('success', 'Attendance record adjusted successfully and secured in audit trail.');
    }

    /**
     * Remove an incorrect attendance log.
     */
    public function destroy(Request $request, AttendanceLog $attendanceLog)
    {
        $oldValues = $attendanceLog->toArray();
        $attendanceLog->delete();

        // Secure Audit Log Integration
        AuditLog::create([
            'user_id' => Auth::id(),
            'auditable_type' => AttendanceLog::class,
            'auditable_id' => $oldValues['id'],
            'event' => 'deleted (Admin Voided)',
            'old_values' => $oldValues,
            'new_values' => [],
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance record permanently voided. Incident logged.');
    }

    /**
     * Export filtered logs to CSV.
     */
    public function export(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'user_id', 'status']);
        $logs = $this->attendanceService->getFilteredLogs($filters);

        $csv = $this->attendanceService->generateExport($logs);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendance_report.csv"',
        ]);
    }

    public function recordByFinger(Request $request)
    {
        $request->validate([
            'slot_id' => 'required|integer',
            'confidence' => 'required|integer',
            'device_uid' => 'required|string',
        ]);

        $user = User::where('fingerprint_slot', $request->slot_id)
            ->where('fingerprint_enrolled', true)
            ->where('is_active', true)
            ->first();

        if (! $user) {
            return response()->json(['status' => 'not_found'], 404);
        }

        $result = $this->attendanceService->recordBiometricLog(
            $user,
            'fingerprint',
            $request->device_uid
        );

        // Don't audit duplicate taps — just return early
        if ($result['action'] === 'Duplicate') {
            return response()->json([
                'status' => 'duplicate',
                'name' => $user->name,
                'action' => 'Already logged',
                'time' => now('Asia/Manila')->format('h:i A'),
            ]);
        }

        AuditLog::create([
            'user_id' => $user->id,
            'auditable_type' => AttendanceLog::class,
            'auditable_id' => $result['log']->id,
            'event' => 'created (Fingerprint - '.$result['action'].')',
            'old_values' => [],
            'new_values' => $result['log']->toArray(),
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Sync payroll for this period
        $period = $this->payrollService->getCurrentPeriod($result['log']->date);
        $this->payrollService->syncForUser($user, $period['start'], $period['end']);

        return response()->json([
            'status' => 'ok',
            'name' => $user->name,
            'action' => $result['action'],
            'time' => now('Asia/Manila')->format('h:i A'),
        ]);
    }
}
