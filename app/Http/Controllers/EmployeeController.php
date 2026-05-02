<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AIScheduleParser;
use App\Models\AttendanceLog;
use App\Models\Schedule;
use App\Models\BiometricAction;
use App\Imports\ProfessorScheduleImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly PayrollService $payrollService,
        private readonly \App\Services\AttendanceService $attendanceService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $employees = User::where('role', '!=', 'admin')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('employee_id', 'like', "%{$search}%");
                });
            })
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('employees.index', compact('employees', 'search'));
    }

    /**
     * Display the personal attendance history of the logged-in user.
     */
    public function history()
    {
        $logs = AttendanceLog::where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->orderBy('time_in', 'desc')
            ->paginate(15);

        return view('employees.history', compact('logs'));
    }

    public function scanner()
    {
        $recentLogs = AttendanceLog::with('user')
            ->where('date', Carbon::now('Asia/Manila')->toDateString())
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('employees.scanner', compact('recentLogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users|regex:/^[a-zA-Z0-9._-]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'employee_id' => 'required|string|unique:users',
            'rfid_card_num' => 'nullable|string|unique:users',
            'fingerprint_id' => 'nullable|integer|unique:users',
            'biometric_template' => 'nullable|string|unique:users',
            'hourly_rate' => 'required|numeric',
            'role' => 'required|in:professor,employee',
            'tin_id' => 'nullable|string|max:255',
            'sss_id' => 'nullable|string|max:255',
            'philhealth_id' => 'nullable|string|max:255',
            'pagibig_id' => 'nullable|string|max:255',
            'vacation_leave_credits' => 'nullable|numeric|min:0',
            'schedule_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,jfif|max:2048',
            'schedule_file' => 'nullable|mimes:xlsx,xls,csv|max:10240',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        unset($validated['schedule_image'], $validated['schedule_file']);
        
        $user = User::create($validated);

        // Handle schedule image upload
        if ($request->hasFile('schedule_image')) {
            $this->handleScheduleImageUpload($user, $request->file('schedule_image'));
        }

        // If schedules were scanned pre-creation
        if ($request->has('scanned_schedules') && !empty($request->scanned_schedules)) {
            $schedules = json_decode($request->scanned_schedules, true);
            if (is_array($schedules)) {
                // Clear any existing (just in case)
                \App\Models\Schedule::where('user_id', $user->id)->delete();
                
                foreach ($schedules as $s) {
                    \App\Models\Schedule::create([
                        'user_id' => $user->id,
                        'day_of_week' => $s['day_of_week'],
                        'start_time' => \Carbon\Carbon::parse($s['start_time'])->format('H:i:s'),
                        'end_time' => \Carbon\Carbon::parse($s['end_time'])->format('H:i:s'),
                    ]);
                }
                // Generate the Excel for them too
                $this->generateExcelFromSchedules($user, $schedules);
            }
        }

        return redirect()->route('employees.index')->with('success', "Employee '{$user->name}' added successfully!");
    }

    /**
     * Preview AI scan results before the employee is saved.
     */
    public function preScan(Request $request)
    {
        $request->validate(['image' => 'required|image|max:4096']);
        set_time_limit(120);

        $parser = new \App\Services\AIScheduleParser();
        try {
            $schedules = $parser->parseFromImage($request->file('image')->getRealPath());
            return response()->json(['success' => true, 'schedules' => $schedules]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Attendance Scanning Logic (RFID/Biometric)
     */
    public function scan(Request $request)
    {
        $request->validate([
            'id_value' => 'nullable|string', // Backward compatibility for single scan if needed
            'rfid' => 'nullable|string',
            'fingerprint_id' => 'nullable|integer',
            'source' => 'nullable|in:RFID,Biometric,MFA',
        ]);

        $source = $request->input('source', 'RFID'); 
        
        try {
            if ($source === 'MFA' || ($request->has('rfid') && $request->has('fingerprint_id'))) {
                $user = $this->attendanceService->resolveUserByMFA(
                    $request->input('rfid'), 
                    $request->input('fingerprint_id')
                );
                $source = 'MFA'; // Force source to MFA for logging
            } else {
                $identifier = $request->input('id_value') ?? $request->input('rfid') ?? $request->input('fingerprint_id');
                $user = $this->attendanceService->resolveUserByBiometric($identifier, $source);
            }

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Identity not recognized. MFA verification failed.'], 404);
            }

            $now = Carbon::now('Asia/Manila');
            $today = $now->toDateString();
            $currentTime = $now->toTimeString();

            // Find all schedules for today
            $dayName = $now->format('l');
            $schedules = Schedule::where('user_id', $user->id)
                                ->where('day_of_week', $dayName)
                                ->orderBy('start_time', 'asc')
                                ->get();

            if ($schedules->isEmpty()) {
                return response()->json(['success' => false, 'message' => "No schedule assigned for $dayName."], 400);
            }

            // Find the most recent log to determine if we are timing in or out
            $log = AttendanceLog::where('user_id', $user->id)
                                ->where('date', $today)
                                ->orderBy('created_at', 'desc')
                                ->first();

            // Select the most relevant schedule block
            $schedule = null;
            if (!$log || $log->time_out) {
                // TIMING IN: Find the first shift that hasn't ended yet
                foreach ($schedules as $s) {
                    if ($now->toTimeString() <= $s->end_time) {
                        $schedule = $s;
                        break;
                    }
                }
                $schedule = $schedule ?? $schedules->last();
            } else {
                // TIMING OUT: Find the shift that corresponds to when they timed in
                $timeIn = $log->time_in;
                foreach ($schedules as $s) {
                    if ($timeIn >= $s->start_time && $timeIn <= $s->end_time) {
                        $schedule = $s;
                        break;
                    }
                }
                $schedule = $schedule ?? $schedules->first();
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'System error during authentication.'], 500);
        }

        // Double-scan protection (cooldown: 1 minute)
        if ($log && $log->created_at->diffInMinutes($now) < 1) {
            return response()->json(['success' => false, 'message' => 'Duplicate scan detected. Please wait a minute.'], 400);
        }

        if (!$log || $log->time_out) {
            // Check-in (New Session)
            $gracePeriodMinutes = 15;
            $startTime = Carbon::parse($today . ' ' . $schedule->start_time);
            $graceTime = (clone $startTime)->addMinutes($gracePeriodMinutes);
            
            $status = ($now <= $graceTime) ? 'On-time' : 'Late';
            
            AttendanceLog::create([
                'user_id' => $user->id,
                'date' => $today,
                'time_in' => $currentTime,
                'status' => $status,
                'source' => $source,
            ]);

            $message = "Welcome, {$user->name}!";
            if ($status === 'Late') {
                $minsLate = $now->diffInMinutes($startTime);
                $message .= " Logged as Late ({$minsLate} mins).";
            } else {
                $message .= " Checked in On-time.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'time' => $currentTime
            ]);
        } else {
            // Check-out (Close Session)
            $log->update(['time_out' => $currentTime]);
            
            // Sync Payroll automatically
            $period = $this->payrollService->getCurrentPeriod($now);
            $this->payrollService->syncForUser($user, $period['start'], $period['end']);

            return response()->json([
                'success' => true,
                'message' => "Goodbye, {$user->name}! Checked out at {$currentTime}.",
                'time' => $currentTime
            ]);
        }
    }

    /**
     * Batch Sync Endpoint for Hardware (Offline Support)
     */
    public function batchSync(Request $request)
    {
        $token = $request->header('X-Hardware-Token') ?? $request->input('token');
        
        if ($token !== config('services.biometric.token', env('BIOMETRIC_HARDWARE_TOKEN'))) {
            return response()->json(['success' => false, 'message' => 'Unauthorized hardware token.'], 401);
        }

        $validated = $request->validate([
            'logs' => 'required|array',
            'logs.*.rfid' => 'required|string',
            'logs.*.fingerprint_id' => 'required|integer',
            'logs.*.scanned_at' => 'required|date',
            'logs.*.source' => 'nullable|string',
        ]);

        $results = $this->attendanceService->processSyncBatch($validated['logs'], $this->payrollService);

        return response()->json([
            'success' => true,
            'processed' => $results['success'],
            'failed' => $results['failed'],
            'details' => $results['messages']
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = User::findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = User::findOrFail($id);
        $schedules = $employee->schedules()->orderByDay()->get();
        return view('employees.edit', compact('employee', 'schedules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id . '|regex:/^[a-zA-Z0-9._-]+$/',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'employee_id' => 'required|string|unique:users,employee_id,' . $id,
            'rfid_card_num' => 'nullable|string|unique:users,rfid_card_num,' . $id,
            'fingerprint_id' => 'nullable|integer|unique:users,fingerprint_id,' . $id,
            'biometric_template' => 'nullable|string|unique:users,biometric_template,' . $id,
            'hourly_rate' => 'required|numeric',
            'role' => 'required|in:professor,employee',
            'tin_id' => 'nullable|string|max:255',
            'sss_id' => 'nullable|string|max:255',
            'philhealth_id' => 'nullable|string|max:255',
            'pagibig_id' => 'nullable|string|max:255',
            'sick_leave_credits' => 'nullable|numeric|min:0',
            'vacation_leave_credits' => 'nullable|numeric|min:0',
            'schedule_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,jfif|max:2048',
            'schedule_file' => 'nullable|mimes:xlsx,xls,csv|max:10240',
        ]);

        // Handle optional password reset
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        unset($validated['schedule_image'], $validated['schedule_file']);
        $employee->update($validated);

        // Handle schedule image upload
        if ($request->hasFile('schedule_image')) {
            $this->handleScheduleImageUpload($employee, $request->file('schedule_image'));
        }

        // Handle schedule Excel upload
        if ($request->hasFile('schedule_file')) {
            try {
                $this->handleScheduleUpload($employee, $request->file('schedule_file'));
            } catch (\Exception $e) {
                return redirect()->route('employees.edit', $employee->id)
                    ->with('warning', 'Employee updated, but schedule Excel import failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }

    /**
     * Download the example schedule template Excel.
     */
    public function downloadTemplate()
    {
        $path = storage_path('app/example_individual_schedule.xlsx');

        if (!file_exists($path)) {
            // Generate it on the fly if it doesn't exist
            $this->generateTemplateExcel($path);
        }

        return response()->download($path, 'schedule_template.xlsx');
    }

    // ──────────────────────────────────────
    // Private Helpers
    // ──────────────────────────────────────

    /**
     * Process and store an individual professor's schedule Image.
     */
    private function handleScheduleImageUpload(User $user, UploadedFile $file): void
    {
        // Delete old image if exists
        if ($user->schedule_image && file_exists(public_path($user->schedule_image))) {
            unlink(public_path($user->schedule_image));
        }

        // Store the file with a clean, identifiable name
        $extension = $file->getClientOriginalExtension();
        $filename = "schedule_{$user->employee_id}_" . time() . ".{$extension}";
        $file->move(public_path('uploads/schedules'), $filename);

        // Save the file path reference on the user
        $user->update(['schedule_image' => 'uploads/schedules/' . $filename]);
    }

    /**
     * Process and store an individual professor's schedule Excel.
     */
    private function handleScheduleUpload(User $user, UploadedFile $file): void
    {
        Excel::import(new ProfessorScheduleImport($user->id), $file);
        
        // Save the file path
        $extension = $file->getClientOriginalExtension();
        $filename = "schedule_data_{$user->employee_id}_" . time() . ".{$extension}";
        
        $dest = public_path('uploads/schedule_files');
        if (!is_dir($dest)) mkdir($dest, 0755, true);
        
        $file->move($dest, $filename);
        $user->update(['schedule_file' => 'uploads/schedule_files/' . $filename]);
    }

    /**
     * Generate an Excel file from AI-detected schedules for record keeping.
     */
    private function generateExcelFromSchedules(User $user, array $schedules): void
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('AI Extracted');

        $sheet->setCellValue('A1', 'day_of_week');
        $sheet->setCellValue('B1', 'start_time');
        $sheet->setCellValue('C1', 'end_time');

        foreach ($schedules as $i => $s) {
            $r = $i + 2;
            $sheet->setCellValue("A{$r}", $s['day_of_week']);
            $sheet->setCellValue("B{$r}", $s['start_time']);
            $sheet->setCellValue("C{$r}", $s['end_time']);
        }

        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10B981'],
            ],
        ]);

        $filename = "ai_schedule_{$user->employee_id}_" . time() . ".xlsx";
        $dest = public_path('uploads/schedule_files');
        if (!is_dir($dest)) mkdir($dest, 0755, true);
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($dest . '/' . $filename);

        $user->update(['schedule_file' => 'uploads/schedule_files/' . $filename]);
    }
    private function generateTemplateExcel(string $path): void
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Schedule');

        // Headers
        $sheet->setCellValue('A1', 'day_of_week');
        $sheet->setCellValue('B1', 'start_time');
        $sheet->setCellValue('C1', 'end_time');

        // Example rows
        $days = [
            ['Monday',    '08:00', '12:00'],
            ['Tuesday',   '13:00', '17:00'],
            ['Wednesday', '08:00', '12:00'],
            ['Thursday',  '13:00', '17:00'],
            ['Friday',    '08:00', '12:00'],
        ];

        foreach ($days as $i => $row) {
            $r = $i + 2;
            $sheet->setCellValue("A{$r}", $row[0]);
            $sheet->setCellValue("B{$r}", $row[1]);
            $sheet->setCellValue("C{$r}", $row[2]);
        }

        // Style headers
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
        ]);

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Ensure directory exists
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);
    }

    // ──────────────────────────────────────
    // Biometric Integrated Enrollment Logic
    // ──────────────────────────────────────

    /**
     * Trigger a new enrollment request from the Admin UI.
     */
    public function enrollRequest(User $employee)
    {
        // Cancel any existing pending enrollments for this user
        BiometricAction::where('user_id', $employee->id)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        $action = BiometricAction::create([
            'user_id' => $employee->id,
            'command' => 'ENROLL',
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Enrollment command issued. Waiting for hardware polling...',
            'action_id' => $action->id
        ]);
    }

    /**
     * Check the status of a specific enrollment action (for UI feedback).
     */
    public function checkEnrollStatus(BiometricAction $action)
    {
        return response()->json([
            'status' => $action->status,
            'fingerprint_id' => $action->fingerprint_id,
            'user' => $action->user->name
        ]);
    }

    /**
     * Endpoint for ESP32 to "Poll" for waiting commands.
     */
    public function checkCommand(Request $request)
    {
        $action = BiometricAction::where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$action) {
            return response()->json(['command' => 'NONE']);
        }

        return response()->json([
            'command' => $action->command,
            'action_id' => $action->id,
            'user_id' => $action->user_id,
            'name' => $action->user->name
        ]);
    }

    /**
     * Endpoint for ESP32 to report the result of an enrollment.
     */
    public function completeEnroll(Request $request)
    {
        $validated = $request->validate([
            'action_id' => 'required|exists:biometric_actions,id',
            'fingerprint_id' => 'required|integer',
            'status' => 'required|in:success,failed'
        ]);

        $action = BiometricAction::findOrFail($validated['action_id']);
        
        if ($validated['status'] === 'success') {
            $action->update([
                'status' => 'success',
                'fingerprint_id' => $validated['fingerprint_id']
            ]);

            // Automatically link the ID to the user
            $action->user->update([
                'fingerprint_id' => $validated['fingerprint_id']
            ]);
        } else {
            $action->update(['status' => 'failed']);
        }

        return response()->json(['success' => true]);
    }
    /**
     * AI Auto-Scan: Process the uploaded schedule image using AI.
     */
    public function autoScan(Request $request, User $employee, AIScheduleParser $parser)
    {
        set_time_limit(120); // Extend PHP timeout for long AI processing

        if (!$employee->schedule_image) {
            return response()->json(['success' => false, 'message' => 'No official schedule image found to scan.'], 400);
        }

        try {
            $parsedSchedules = $parser->parseScheduleImage($employee->schedule_image);

            if (empty($parsedSchedules)) {
                return response()->json(['success' => false, 'message' => 'AI could not detect any schedule data in the image.'], 404);
            }

            // Return the data for the UI to confirm
            return response()->json([
                'success' => true,
                'schedules' => $parsedSchedules,
                'message' => 'AI Scan complete. Please review the extracted times.'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Bulk save AI-detected schedules.
     */
    public function saveAutoScan(Request $request, User $employee)
    {
        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|string',
            'schedules.*.start_time' => 'required|string',
            'schedules.*.end_time' => 'required|string',
        ]);

        \DB::transaction(function() use ($request, $employee) {
            // Clear existing
            Schedule::where('user_id', $employee->id)->delete();

            foreach ($request->schedules as $s) {
                \App\Models\Schedule::create([
                    'user_id' => $employee->id,
                    'day_of_week' => $s['day_of_week'],
                    'start_time' => \Carbon\Carbon::parse($s['start_time'])->format('H:i:s'),
                    'end_time' => \Carbon\Carbon::parse($s['end_time'])->format('H:i:s'),
                ]);
            }

            // Also generate an Excel backup of this AI scan
            $this->generateExcelFromSchedules($employee, $request->schedules);
        });

        return response()->json(['success' => true, 'message' => 'Official schedule successfully synchronized from AI scan.']);
    }
}