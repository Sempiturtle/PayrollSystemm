<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\AttendanceLog;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

        // Sync Payroll
        $period = $this->payrollService->getCurrentPeriod($log->date);
        $this->payrollService->syncForUser($log->user, $period['start'], $period['end']);

        return redirect()->route('attendance.index')->with('success', 'Attendance record added manually.');
    }

    /**
     * Update an attendance log (manual adjustment).
     */
    public function update(UpdateAttendanceRequest $request, AttendanceLog $attendanceLog)
    {
        $attendanceLog->update($request->validated());

        // Sync Payroll
        $period = $this->payrollService->getCurrentPeriod($attendanceLog->date);
        $this->payrollService->syncForUser($attendanceLog->user, $period['start'], $period['end']);

        return redirect()->route('attendance.index')->with('success', 'Attendance record adjusted successfully.');
    }

    /**
     * Remove an incorrect attendance log.
     */
    public function destroy(AttendanceLog $attendanceLog)
    {
        $attendanceLog->delete();

        return redirect()->route('attendance.index')->with('success', 'Attendance record removed.');
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
