<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BiometricController;
use App\Http\Controllers\AttendanceController;

Route::post('attendance/finger', [AttendanceController::class, 'recordByFinger']);
// ESP32 polls this every 3 seconds
Route::get('/enroll/pending', function () {
    $pending = Cache::get('enroll_pending');
    return response()->json($pending ?? ['status' => 'idle']);
});

// ESP32 posts here after successful enrollment
Route::post('/enroll/confirm', [BiometricController::class, 'confirm']);

// ESP32 posts here if enrollment failed on device
Route::post('/enroll/fail', [BiometricController::class, 'fail']);