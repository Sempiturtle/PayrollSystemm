<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\LeaveController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Leaves Management
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');

    // Personal Records
    Route::get('/attendance', [EmployeeController::class, 'history'])->name('attendance.history');
    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
    Route::get('/payrolls/{payroll}/download', [PayrollController::class, 'downloadPayslip'])->name('payrolls.download');

    // Admin Only Routes
    Route::middleware('admin')->group(function () {
        // Employee Management
        Route::resource('employees', EmployeeController::class);
        
        // Schedule Management
        Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/{user}', [ScheduleController::class, 'show'])->name('schedules.show');
        Route::delete('/schedules', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

        // Payroll Management
        // Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
        Route::post('/payrolls/generate', [PayrollController::class, 'generate'])->name('payrolls.generate');
        // Route::get('/payrolls/{payroll}/download', [PayrollController::class, 'downloadPayslip'])->name('payrolls.download');

        // Leave Approvals
        Route::patch('/leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update');

        // Schedule Template Download
        Route::get('/schedule-template', [EmployeeController::class, 'downloadTemplate'])->name('schedule.template');
        // Attendance Routes
        Route::get('/attendance/scanner', [EmployeeController::class, 'scanner'])->name('attendance.scanner');
        Route::post('/attendance/scan', [EmployeeController::class, 'scan'])->name('attendance.scan');
    });
});

require __DIR__.'/auth.php';
