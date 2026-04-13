<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Holiday;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('date', 'desc')->get();
        return view('holidays.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date',
            'type' => 'required|in:Regular Holiday,Special Non-Working,Suspension',
            'is_paid' => 'boolean',
            'is_double_pay' => 'boolean',
            'description' => 'nullable|string',
        ]);

        Holiday::create($validated);

        return redirect()->route('holidays.index')->with('success', 'Holiday/Suspension declared successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday record removed.');
    }
}
