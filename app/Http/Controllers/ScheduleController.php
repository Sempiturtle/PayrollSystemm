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
     * Delete all schedules (admin reset).
     */
    public function destroy()
    {
        Schedule::truncate();
        return redirect()->route('schedules.index')->with('success', 'All schedules have been cleared.');
    }
}
