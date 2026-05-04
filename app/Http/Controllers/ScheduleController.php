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
            User::whereIn('id', $request->user_ids)->update([
                'schedule_file' => null,
                'schedule_image' => null
            ]);
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
            'schedule_image' => 'required|image|mimes:jpeg,png,jpg,gif,jfif|max:2048',
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        $file = $request->file('schedule_image');

        \DB::transaction(function() use ($users, $file) {
            foreach ($users as $user) {
                // Delete old image if exists
                if ($user->schedule_image && file_exists(public_path($user->schedule_image))) {
                    @unlink(public_path($user->schedule_image));
                }

                // Store with identifiable name
                $extension = $file->getClientOriginalExtension();
                $filename = "schedule_{$user->employee_id}_" . time() . ".{$extension}";
                $file->move(public_path('uploads/schedules'), $filename);

                // Update reference
                $user->update(['schedule_image' => 'uploads/schedules/' . $filename]);
            }
        });

        return redirect()->route('schedules.index')->with('success', 'Schedule image applied to ' . $users->count() . ' employees.');
    }
}
