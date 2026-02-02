<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', '!=', 'admin')->get();

        foreach ($users as $user) {
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            foreach ($days as $day) {
                Schedule::create([
                    'user_id' => $user->id,
                    'day_of_week' => $day,
                    'start_time' => '08:00:00',
                    'end_time' => '17:00:00',
                    'effective_from' => now()->startOfMonth(),
                ]);
            }
        }
    }
}
