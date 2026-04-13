<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Display a listing of the leaves.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $leaves = Leave::with('user')->orderBy('created_at', 'desc')->get();
        } else {
            $leaves = $user->leaves()->orderBy('created_at', 'desc')->get();
        }

        return view('leaves.index', compact('leaves'));
    }

    /**
     * Store a newly created leave in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type'       => 'required|string|in:Sick,Vacation,Personal',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string|max:500',
        ]);

        Auth::user()->leaves()->create([
            'type'       => $request->type,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'reason'     => $request->reason,
            'status'     => 'Pending',
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Update the specified leave status (Admin only).
     */
    public function update(Request $request, Leave $leave)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|string|in:Approved,Rejected',
        ]);

        $leave->update([
            'status' => $request->status,
        ]);

        $statusColor = $request->status === 'Approved' ? 'success' : 'error';
        return redirect()->route('leaves.index')->with($statusColor, "Leave request marked as {$request->status}.");
    }

    /**
     * Batch update multiple leaves.
     */
    public function bulkUpdate(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'leave_ids' => 'required|array',
            'leave_ids.*' => 'exists:leaves,id',
            'status'    => 'required|string|in:Approved,Rejected',
        ]);

        \DB::transaction(function () use ($request) {
            Leave::whereIn('id', $request->leave_ids)->update([
                'status' => $request->status,
            ]);
        });

        $statusColor = $request->status === 'Approved' ? 'success' : 'error';
        return redirect()->route('leaves.index')->with($statusColor, "Batch operation complete: " . count($request->leave_ids) . " requests {$request->status}.");
    }
}
