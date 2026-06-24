<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Schedule;
use App\Models\AttendanceLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Carbon\Carbon;

class AttendanceLimitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function employee_cannot_time_in_more_than_once_per_day(): void
    {
        // Pin time to 08:00:00 on a Monday
        $testNow = Carbon::parse('2026-06-22 08:00:00', 'Asia/Manila');
        Carbon::setTestNow($testNow);

        $employee = User::factory()->employee()->create([
            'employee_id' => 'EMP123',
            'role' => 'employee'
        ]);

        // Assign a schedule for Monday
        Schedule::create([
            'user_id' => $employee->id,
            'day_of_week' => 'Monday',
            'start_time' => '08:00:00',
            'end_time' => '17:00:00',
        ]);

        // 1. First scan - should Time In successfully
        $response1 = $this->postJson(route('attendance.scan'), [
            'rfid' => 'EMP123',
            'source' => 'RFID'
        ]);

        $response1->assertStatus(200);
        $response1->assertJsonPath('success', true);
        $response1->assertJsonFragment(['message' => "Welcome, {$employee->name}! Checked in On-time."]);

        $this->assertDatabaseCount('attendance_logs', 1);

        // Travel forward past cooldown to allow a time out scan (e.g., 6 minutes later)
        Carbon::setTestNow(Carbon::now('Asia/Manila')->addMinutes(6));

        // 2. Second scan - should Time Out successfully
        $response2 = $this->postJson(route('attendance.scan'), [
            'rfid' => 'EMP123',
            'source' => 'RFID'
        ]);

        $response2->assertStatus(200);
        $response2->assertJsonPath('success', true);
        
        $log = AttendanceLog::first();
        $this->assertNotNull($log->time_out);

        // Travel forward past cooldown again
        Carbon::setTestNow(Carbon::now('Asia/Manila')->addMinutes(6));

        // 3. Third scan - should be rejected because the session for today is already completed
        $response3 = $this->postJson(route('attendance.scan'), [
            'rfid' => 'EMP123',
            'source' => 'RFID'
        ]);

        $response3->assertStatus(400);
        $response3->assertJsonPath('success', false);
        $response3->assertJsonFragment(['message' => 'Attendance completed for today. Cannot check in again.']);

        // Database should still only have 1 attendance log record
        $this->assertDatabaseCount('attendance_logs', 1);

        Carbon::setTestNow(); // Clean up test time
    }
}
