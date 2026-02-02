<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AttendanceLog;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = User::where('role', '!=', 'admin')->get();
        return view('employees.index', compact('employees'));
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
        ]);

        $password = 'AISAT-' . $validated['employee_id'];
        $validated['password'] = bcrypt($password);
        
        User::create($validated);

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
                            ->first();

        if (!$log) {
            // Check-in
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
                $message .= " You are logged as Late ({$minsLate} mins).";
            } else {
                $message .= " Checked in On-time.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'time' => $currentTime
            ]);
        } else if (!$log->time_out) {
            // Check-out
            $log->update(['time_out' => $currentTime]);
            
            return response()->json([
                'success' => true,
                'message' => "Goodbye, {$user->name}! Checked out at {$currentTime}.",
                'time' => $currentTime
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Already recorded check-in and check-out for today'], 400);
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
        return view('employees.edit', compact('employee'));
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
        ]);

        $employee->update($validated);

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
}
