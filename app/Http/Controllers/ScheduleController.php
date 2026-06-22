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

        $employees = $query->orderBy('name')->paginate(10);

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

    /**
     * Store a manually created schedule slot.
     */
    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'day_of_week'    => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i|after:start_time',
            'effective_from' => 'nullable|date',
        ]);

        // Check for time overlap on the same day for this user
        $hasOverlap = Schedule::where('user_id', $user->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function ($query) use ($validated) {
                $query->where(function ($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'] . ':00')
                      ->where('end_time', '>', $validated['start_time'] . ':00');
                });
            })
            ->exists();

        if ($hasOverlap) {
            return back()->withErrors(['start_time' => 'This class schedule overlaps with an existing slot for this day.']);
        }

        Schedule::create([
            'user_id'        => $user->id,
            'day_of_week'    => $validated['day_of_week'],
            'start_time'     => $validated['start_time'] . ':00',
            'end_time'       => $validated['end_time'] . ':00',
            'effective_from' => $validated['effective_from'],
        ]);

        // Sync changes back to Excel/CSV file
        \App\Services\ScheduleSyncService::syncDbToFile($user);

        return back()->with('success', 'Class schedule slot added and synced to file.');
    }

    /**
     * Update a manually edited schedule slot.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'day_of_week'    => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i|after:start_time',
            'effective_from' => 'nullable|date',
        ]);

        // Check for time overlap excluding current slot
        $hasOverlap = Schedule::where('user_id', $schedule->user_id)
            ->where('id', '!=', $schedule->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function ($query) use ($validated) {
                $query->where(function ($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'] . ':00')
                      ->where('end_time', '>', $validated['start_time'] . ':00');
                });
            })
            ->exists();

        if ($hasOverlap) {
            return back()->withErrors(['start_time' => 'This class schedule overlaps with an existing slot for this day.']);
        }

        $schedule->update([
            'day_of_week'    => $validated['day_of_week'],
            'start_time'     => $validated['start_time'] . ':00',
            'end_time'       => $validated['end_time'] . ':00',
            'effective_from' => $validated['effective_from'],
        ]);

        // Sync changes back to Excel/CSV file
        \App\Services\ScheduleSyncService::syncDbToFile($schedule->user);

        return back()->with('success', 'Class schedule slot updated and synced to file.');
    }

    /**
     * Delete a single schedule slot.
     */
    public function destroy(Schedule $schedule)
    {
        $user = $schedule->user;
        $schedule->delete();

        // Sync changes back to Excel/CSV file
        \App\Services\ScheduleSyncService::syncDbToFile($user);

        return back()->with('success', 'Class schedule slot deleted and synced to file.');
    }

    /**
     * Clear all schedules from the system (Factory Reset).
     */
    public function destroyAll()
    {
        Schedule::query()->delete();
        User::query()->update(['schedule_file' => null]);
        return redirect()->route('schedules.index')->with('success', 'All schedules successfully cleared from database.');
    }
}
