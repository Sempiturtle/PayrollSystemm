<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\PayrollController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Only Routes
    Route::middleware('admin')->group(function () {
        // Employee Management
        Route::resource('employees', EmployeeController::class);
        
        // Schedule Management
        Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules/import', [ScheduleController::class, 'import'])->name('schedules.import');

        // Payroll Management
        Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
        Route::post('/payrolls/generate', [PayrollController::class, 'generate'])->name('payrolls.generate');
    });
    
    // Attendance Routes (Scanner needs to be accessible, but maybe scan logic is protected)
    Route::get('/attendance/scanner', [EmployeeController::class, 'scanner'])->name('attendance.scanner');
    Route::post('/attendance/scan', [EmployeeController::class, 'scan'])->name('attendance.scan');
});

require __DIR__.'/auth.php';
