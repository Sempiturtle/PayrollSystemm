<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\AttendanceLog;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
        private readonly \App\Services\PayrollService $payrollService
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
}
