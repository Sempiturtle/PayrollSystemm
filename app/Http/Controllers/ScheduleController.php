<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use App\Imports\ScheduleImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('user')->get();
        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('schedules.create');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ScheduleImport, $request->file('file'));
            return redirect()->route('schedules.index')->with('success', 'Schedules imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }
            return redirect()->route('schedules.create')->withErrors($errors);
        } catch (\Exception $e) {
            return redirect()->route('schedules.create')->with('error', 'Critical Error: ' . $e->getMessage());
        }
    }
}
