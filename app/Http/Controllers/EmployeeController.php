<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AttendanceLog;
use App\Models\Schedule;
use App\Imports\ProfessorScheduleImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly PayrollService $payrollService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = User::where('role', '!=', 'admin')->get();
        return view('employees.index', compact('employees'));
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
            'email' => 'required|string|email|max:255|unique:users',
            'employee_id' => 'required|string|unique:users',
            'rfid_card_num' => 'nullable|string|unique:users',
            'biometric_template' => 'nullable|string|unique:users',
            'hourly_rate' => 'required|numeric',
            'role' => 'required|in:professor,employee',
            'tin_id' => 'nullable|string|max:255',
            'sss_num' => 'nullable|string|max:255',
            'philhealth_id' => 'nullable|string|max:255',
            'pagibig_num' => 'nullable|string|max:255',
            'sick_leave_credits' => 'nullable|numeric|min:0',
            'vacation_leave_credits' => 'nullable|numeric|min:0',
            'schedule_file' => 'nullable|mimes:xlsx,xls,csv|max:10240',
        ]);

        $password = 'AISAT-' . $validated['employee_id'];
        $validated['password'] = bcrypt($password);
        unset($validated['schedule_file']);
        
        $user = User::create($validated);

        // Handle schedule Excel upload
        if ($request->hasFile('schedule_file')) {
            $this->handleScheduleUpload($user, $request->file('schedule_file'));
        }

        return redirect()->route('employees.index')->with('success', "Employee added successfully! Default password is: $password");
    }

    /**
     * Attendance Scanning Logic (RFID/Biometric)
     */
    public function scan(Request $request)
    {
        $request->validate([
            'id_value' => 'required|string',
            'source' => 'nullable|in:RFID,Biometric',
        ]);

        $identifier = $request->input('id_value'); 
        $source = $request->input('source', 'RFID'); 

        try {
            $user = User::where('rfid_card_num', $identifier)
                        ->orWhere('employee_id', $identifier)
                        ->orWhere('biometric_template', $identifier)
                        ->first();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Employee identity not recognized.'], 404);
            }

            $now = Carbon::now('Asia/Manila');
            $today = $now->toDateString();
            $currentTime = $now->toTimeString();

            // Find schedule for today
            $dayName = $now->format('l'); // Monday, Tuesday, etc.
            $schedule = Schedule::where('user_id', $user->id)
                                ->where('day_of_week', $dayName)
                                ->first();

            if (!$schedule) {
                return response()->json(['success' => false, 'message' => "No schedule assigned for $dayName."], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'System error during authentication.'], 500);
        }

        $log = AttendanceLog::where('user_id', $user->id)
                            ->where('date', $today)
                            ->orderBy('created_at', 'desc')
                            ->first();

        // Double-scan protection (cooldown: 5 minutes)
        if ($log && $log->created_at->diffInMinutes($now) < 5) {
            return response()->json(['success' => false, 'message' => 'Duplicate scan detected. Please wait a few minutes.'], 400);
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
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'employee_id' => 'required|string|unique:users,employee_id,' . $id,
            'rfid_card_num' => 'nullable|string|unique:users,rfid_card_num,' . $id,
            'biometric_template' => 'nullable|string|unique:users,biometric_template,' . $id,
            'hourly_rate' => 'required|numeric',
            'role' => 'required|in:professor,employee',
            'tin_id' => 'nullable|string|max:255',
            'sss_num' => 'nullable|string|max:255',
            'philhealth_id' => 'nullable|string|max:255',
            'pagibig_num' => 'nullable|string|max:255',
            'sick_leave_credits' => 'nullable|numeric|min:0',
            'vacation_leave_credits' => 'nullable|numeric|min:0',
            'schedule_file' => 'nullable|mimes:xlsx,xls,csv|max:10240',
        ]);

        unset($validated['schedule_file']);
        $employee->update($validated);

        // Handle schedule Excel upload
        if ($request->hasFile('schedule_file')) {
            try {
                $this->handleScheduleUpload($employee, $request->file('schedule_file'));
                return redirect()->route('employees.edit', $employee->id)->with('success', 'Employee updated & schedule imported successfully!');
            } catch (\Exception $e) {
                return redirect()->route('employees.edit', $employee->id)
                    ->with('warning', 'Employee updated, but schedule import failed: ' . $e->getMessage());
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
     * Process and store an individual professor's schedule Excel.
     */
    private function handleScheduleUpload(User $user, UploadedFile $file): void
    {
        // Store the file with a clean, identifiable name
        $filename = "schedules/{$user->employee_id}_schedule.{$file->getClientOriginalExtension()}";
        $file->storeAs('', $filename);

        // Clear existing schedules for this user before importing
        Schedule::where('user_id', $user->id)->delete();

        // Import the schedule entries
        Excel::import(new ProfessorScheduleImport($user->id), $file);

        // Save the file path reference on the user
        $user->update(['schedule_file' => $filename]);
    }

    /**
     * Generate a template Excel file with the expected format.
     */
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
}
