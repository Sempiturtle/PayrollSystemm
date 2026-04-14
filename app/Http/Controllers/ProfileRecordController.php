<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileRecordController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Mask statutory IDs for security (e.g. 123-456-789 -> XXX-XXX-789)
        $maskedTin = $this->maskId($user->tin_id);
        $maskedSss = $this->maskId($user->sss_id);
        $maskedPhilhealth = $this->maskId($user->philhealth_id);
        $maskedPagibig = $this->maskId($user->pagibig_id);

        // Load the user's schedules
        $schedules = $user->schedules()->orderByDay()->get();

        return view('profile.records', compact('user', 'maskedTin', 'maskedSss', 'maskedPhilhealth', 'maskedPagibig', 'schedules'));
    }

    public function downloadSchedule()
    {
        $user = Auth::user();
        
        if (!$user->schedule_file) {
            return back()->with('error', 'No schedule file found for your account.');
        }

        $path = storage_path('app/' . $user->schedule_file);

        if (!file_exists($path)) {
            return back()->with('error', 'The schedule file could not be found on the server.');
        }

        return response()->download($path, "Your_Schedule_" . $user->employee_id . ".xlsx");
    }

    private function maskId($id)
    {
        if (!$id) return 'Not Set';
        $length = strlen($id);
        if ($length <= 4) return 'XXXX';
        return str_repeat('X', $length - 4) . substr($id, -4);
    }
}
