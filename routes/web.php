<?php

use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscrepancyReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FiscalController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileRecordController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::post('/attendance/scan', [EmployeeController::class, 'scan'])->name('attendance.scan');
Route::post('/attendance/sync', [EmployeeController::class, 'batchSync'])->name('attendance.sync');
Route::get('/attendance/check-command', [EmployeeController::class, 'checkCommand'])->name('attendance.check-command');
Route::post('/attendance/complete-enroll', [EmployeeController::class, 'completeEnroll'])->name('attendance.complete-enroll');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Leaves Management
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');

    // Personal Records
    Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');
    Route::get('/fiscal', [FiscalController::class, 'index'])->name('fiscal.index');
    Route::get('/profile/records', [ProfileRecordController::class, 'index'])->name('profile.records');
    Route::get('/profile/schedule/download', [ProfileRecordController::class, 'downloadSchedule'])->name('profile.schedule.download');

    Route::get('/attendance', [EmployeeController::class, 'history'])->name('attendance.history');
    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
    Route::get('/payrolls/{payroll}/download', [PayrollController::class, 'downloadPayslip'])->name('payrolls.download');
    Route::get('/payrolls/{payroll}', [PayrollController::class, 'show'])->name('payrolls.show');

    // Discrepancy reporting
    Route::post('/discrepancies', [DiscrepancyReportController::class, 'store'])->name('discrepancies.store');
    Route::get('/my-disputes', [DiscrepancyReportController::class, 'myDisputes'])->name('discrepancies.mine');

    // Admin Only Routes
    Route::middleware('admin')->group(function () {
        // Employee Management
        Route::resource('employees', EmployeeController::class);

        // Schedule Management
        Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/{user}', [ScheduleController::class, 'show'])->name('schedules.show');
        Route::delete('/schedules', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
        Route::delete('/schedules/clear-bulk', [ScheduleController::class, 'bulkDestroy'])->name('schedules.bulkDestroy');
        Route::post('/schedules/bulk-upload', [ScheduleController::class, 'bulkUpload'])->name('schedules.bulkUpload');

        // Holiday Management
        Route::resource('holidays', HolidayController::class)->only(['index', 'store', 'update', 'destroy']);

        // Payroll Management
        Route::post('/payrolls/generate', [PayrollController::class, 'generate'])->name('payrolls.generate');
        Route::patch('/payrolls/{payroll}/finalize', [PayrollController::class, 'finalize'])->name('payrolls.finalize');

        // Settings & Configurations
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/create', [SettingsController::class, 'store'])->name('settings.store');
        Route::post('/settings/sync', [SettingsController::class, 'syncDefaults'])->name('settings.sync');
        Route::resource('admins', AdminManagementController::class)->except(['show']);
        Route::get('/audit-logs', AuditLogController::class)->name('audit-logs.index');

        // Discrepancy Reports (Admin)
        Route::get('/admin/discrepancies', [DiscrepancyReportController::class, 'index'])->name('admin.discrepancies.index');
        Route::patch('/admin/discrepancies/{report}', [DiscrepancyReportController::class, 'update'])->name('admin.discrepancies.update');

        // Leave Approvals
        Route::patch('/leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
        Route::post('/leaves/bulk', [LeaveController::class, 'bulkUpdate'])->name('leaves.bulk');

        // Schedule Template Download
        Route::get('/schedule-template', [EmployeeController::class, 'downloadTemplate'])->name('schedule.template');
        // Attendance Routes
        Route::get('/attendance/monitoring', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/manual', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::patch('/attendance/{attendanceLog}', [AttendanceController::class, 'update'])->name('attendance.update');
        Route::delete('/attendance/{attendanceLog}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
        Route::get('/attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');

        Route::get('/attendance/scanner', [EmployeeController::class, 'scanner'])->name('attendance.scanner');

        // Integrated Biometric Enrollment
        Route::post('/employees/{employee}/enroll', [EmployeeController::class, 'enroll'])
            ->name('employees.enroll');

        Route::get('/biometrics/actions/{actionId}', [EmployeeController::class, 'pollEnrollment']);
        Route::get('/biometrics/actions/{action}', [EmployeeController::class, 'checkEnrollStatus'])->name('biometrics.status');

        // Export & Reporting Routes
        Route::get('/exports/attendance-today', [ExportController::class, 'attendanceToday'])->name('exports.attendance-today');
        Route::get('/exports/payroll-insight', [ExportController::class, 'payrollInsight'])->name('exports.payroll-insight');
        Route::get('/exports/period-summary', [ExportController::class, 'periodSummary'])->name('exports.period-summary');
    });
});

require __DIR__.'/auth.php';
