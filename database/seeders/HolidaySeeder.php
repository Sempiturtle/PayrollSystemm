<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Holiday;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            ['name' => "New Year's Day", 'date' => '2026-01-01', 'type' => 'Regular Holiday', 'is_paid' => true],
            ['name' => "Chinese New Year", 'date' => '2026-02-17', 'type' => 'Special Non-Working', 'is_paid' => false],
            ['name' => "EDSA Revolution Anniversary", 'date' => '2026-02-25', 'type' => 'Special Non-Working', 'is_paid' => false],
            ['name' => "Maundy Thursday", 'date' => '2026-04-02', 'type' => 'Regular Holiday', 'is_paid' => true],
            ['name' => "Good Friday", 'date' => '2026-04-03', 'type' => 'Regular Holiday', 'is_paid' => true],
            ['name' => "Black Saturday", 'date' => '2026-04-04', 'type' => 'Special Non-Working', 'is_paid' => false],
            ['name' => "Araw ng Kagitingan", 'date' => '2026-04-09', 'type' => 'Regular Holiday', 'is_paid' => true],
            ['name' => "Labor Day", 'date' => '2026-05-01', 'type' => 'Regular Holiday', 'is_paid' => true],
            ['name' => "Independence Day", 'date' => '2026-06-12', 'type' => 'Regular Holiday', 'is_paid' => true],
            ['name' => "Ninoy Aquino Day", 'date' => '2026-08-21', 'type' => 'Special Non-Working', 'is_paid' => false],
            ['name' => "National Heroes Day", 'date' => '2026-08-31', 'type' => 'Regular Holiday', 'is_paid' => true],
            ['name' => "All Saints' Day", 'date' => '2026-11-01', 'type' => 'Special Non-Working', 'is_paid' => false],
            ['name' => "All Souls' Day", 'date' => '2026-11-02', 'type' => 'Special Non-Working', 'is_paid' => false],
            ['name' => "Bonifacio Day", 'date' => '2026-11-30', 'type' => 'Regular Holiday', 'is_paid' => true],
            ['name' => "Feast of the Immaculate Conception", 'date' => '2026-12-08', 'type' => 'Special Non-Working', 'is_paid' => false],
            ['name' => "Christmas Day", 'date' => '2026-12-25', 'type' => 'Regular Holiday', 'is_paid' => true],
            ['name' => "Rizal Day", 'date' => '2026-12-30', 'type' => 'Regular Holiday', 'is_paid' => true],
            ['name' => "Last Day of the Year", 'date' => '2026-12-31', 'type' => 'Special Non-Working', 'is_paid' => false],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(['date' => $holiday['date']], $holiday);
        }
    }
}
