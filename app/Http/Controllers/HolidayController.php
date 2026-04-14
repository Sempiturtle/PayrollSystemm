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
            'pay_option' => 'required|in:unpaid,paid,double',
            'description' => 'nullable|string',
        ]);

        $holidayData = $validated;
        $holidayData['is_paid'] = in_array($validated['pay_option'], ['paid', 'double']);
        $holidayData['is_double_pay'] = $validated['pay_option'] === 'double';

        Holiday::create($holidayData);

        return redirect()->route('holidays.index')->with('success', 'Holiday/Suspension declared successfully.');
    }

    public function update(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date,' . $holiday->id,
            'type' => 'required|in:Regular Holiday,Special Non-Working,Suspension',
            'pay_option' => 'required|in:unpaid,paid,double',
            'description' => 'nullable|string',
        ]);

        $holidayData = $validated;
        $holidayData['is_paid'] = in_array($validated['pay_option'], ['paid', 'double']);
        $holidayData['is_double_pay'] = $validated['pay_option'] === 'double';

        $holiday->update($holidayData);

        return redirect()->route('holidays.index')->with('success', 'Holiday record updated successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday record removed.');
    }
}
