<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display schedules grouped by employee as weekly timetables.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('role', '!=', 'admin')
            ->whereHas('schedules')
            ->with(['schedules' => fn($q) => $q->orderByDay()]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $employees = $query->orderBy('name')->get();

        // Summary stats
        $totalScheduled  = Schedule::distinct('user_id')->count('user_id');
        $totalEntries    = Schedule::count();

        return view('schedules.index', compact('employees', 'search', 'totalScheduled', 'totalEntries'));
    }

    /**
     * Show a single employee's weekly schedule.
     */
    public function show(User $user)
    {
        $schedules = $user->schedules()->orderByDay()->get();
        return view('schedules.show', compact('user', 'schedules'));
    }

    /**
     * Delete schedules for selected employees.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate(['user_ids' => 'required|array', 'user_ids.*' => 'exists:users,id']);
        
        \DB::transaction(function() use ($request) {
            Schedule::whereIn('user_id', $request->user_ids)->delete();
            User::whereIn('id', $request->user_ids)->update(['schedule_file' => null]);
        });

        return redirect()->route('schedules.index')->with('success', count($request->user_ids) . ' schedules cleared.');
    }

    /**
     * Batch upload a single schedule to multiple employees.
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'user_ids'      => 'required|array',
            'user_ids.*'    => 'exists:users,id',
            'schedule_file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        $file = $request->file('schedule_file');

        \DB::transaction(function() use ($users, $file) {
            foreach ($users as $user) {
                // Clear existing
                Schedule::where('user_id', $user->id)->delete();
                
                // Import (using the same library as EmployeeController)
                \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ProfessorScheduleImport($user->id), $file);
                
                // Update file reference
                $user->update(['schedule_file' => 'bulk_assigned_' . now()->format('YmdHis')]);
            }
        });

        return redirect()->route('schedules.index')->with('success', 'Schedule applied to ' . $users->count() . ' employees.');
    }
}
