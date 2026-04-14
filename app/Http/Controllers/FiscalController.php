<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FiscalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentYear = Carbon::now()->year;

        $ytdData = Payroll::where('user_id', $user->id)
            ->whereYear('period_end', $currentYear)
            ->get();

        $totals = [
            'sss' => $ytdData->sum('sss_deduction'),
            'philhealth' => $ytdData->sum('philhealth_deduction'),
            'pagibig' => $ytdData->sum('pagibig_deduction'),
            'tax' => $ytdData->sum('tax_deduction'),
            'gross' => $ytdData->sum('gross_pay'),
            'net' => $ytdData->sum('net_pay'),
        ];

        return view('fiscal.index', compact('user', 'totals', 'ytdData', 'currentYear'));
    }
}
