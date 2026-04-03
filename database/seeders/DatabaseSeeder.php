<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AttendanceLog;
use App\Models\Payroll;
use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Core Users
        $admin = User::firstOrCreate(['email' => 'admin@aisat.edu.ph'], [
            'name' => 'AISAT Admin',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'employee_id' => 'ADMIN-001',
        ]);

        $juan = User::firstOrCreate(['email' => 'juan@aisat.edu.ph'], [
            'name' => 'Prof. Juan Dela Cruz',
            'password' => bcrypt('prof123'),
            'role' => 'professor',
            'employee_id' => 'PROF-001',
            'rfid_card_num' => 'RFID123456',
            'hourly_rate' => 500.00,
        ]);

        $sarah = User::firstOrCreate(['email' => 'sarah@aisat.edu.ph'], [
            'name' => 'Sarah Smith',
            'password' => bcrypt('staff123'),
            'role' => 'employee',
            'employee_id' => 'STAFF-001',
            'rfid_card_num' => 'RFID789012',
            'hourly_rate' => 350.00,
        ]);

        // 2. Schedules
        $this->call(ScheduleSeeder::class);

        // 3. Sample Attendance Logs (Today)
        $today = Carbon::now('Asia/Manila')->toDateString();
        
        AttendanceLog::firstOrCreate(['user_id' => $juan->id, 'date' => $today], [
            'time_in' => '07:45:00',
            'status' => 'On-time',
            'source' => 'RFID'
        ]);

        AttendanceLog::firstOrCreate(['user_id' => $sarah->id, 'date' => $today], [
            'time_in' => '08:25:00',
            'status' => 'Late',
            'source' => 'Biometric'
        ]);

        // 4. Sample Payroll History (Past 3 Months)
        $months = [
            ['month' => -3, 'amount' => 45000],
            ['month' => -2, 'amount' => 48500],
            ['month' => -1, 'amount' => 52000],
        ];

        foreach ($months as $m) {
            $date = Carbon::now('Asia/Manila')->addMonths($m['month']);
            Payroll::firstOrCreate(
                [
                    'user_id' => $juan->id,
                    'period_start' => (clone $date)->startOfMonth()->toDateString(),
                    'period_end' => (clone $date)->endOfMonth()->toDateString(),
                ],
                [
                    'total_hours' => 160,
                    'late_minutes' => 0,
                    'total_deductions' => 0,
                    'gross_pay' => $m['amount'] * 0.6,
                    'net_pay' => $m['amount'] * 0.6,
                    'status' => 'Released'
                ]
            );
            
            Payroll::firstOrCreate(
                [
                    'user_id' => $sarah->id,
                    'period_start' => (clone $date)->startOfMonth()->toDateString(),
                    'period_end' => (clone $date)->endOfMonth()->toDateString(),
                ],
                [
                    'total_hours' => 160,
                    'late_minutes' => 45,
                    'total_deductions' => 500,
                    'gross_pay' => $m['amount'] * 0.4,
                    'net_pay' => ($m['amount'] * 0.4) - 500,
                    'status' => 'Released'
                ]
            );
        }
    }
}
