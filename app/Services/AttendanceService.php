<?php

namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AttendanceService
{
    /**
     * Identify a user based on biometric or RFID data.
     */
    public function resolveUserByBiometric(string $identifier, string $source = 'RFID'): ?User
    {
        $query = User::query();

        if ($source === 'Biometric') {
            return $query->where('fingerprint_slot', $identifier)  // ← was fingerprint_id
                ->first();
        }

        return $query->where('rfid_card_num', $identifier)
            ->orWhere('employee_id', $identifier)
            ->first();
    }

    public function resolveUserByMFA(string $rfid, int|string $fingerprintId): ?User
    {
        return User::where(function ($q) use ($rfid) {
            $q->where('rfid_card_num', $rfid)
                ->orWhere('employee_id', $rfid);
        })
            ->where('fingerprint_slot', $fingerprintId)
            ->first();
    }

    /**
     * Process a batch of attendance logs from hardware (Offline Sync).
     */
    public function processSyncBatch(array $logs, PayrollService $payrollService): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'messages' => [],
        ];

        foreach ($logs as $data) {
            try {
                $rfid = $data['rfid'] ?? null;
                $fingerprintId = $data['fingerprint_id'] ?? null;
                $scannedAt = Carbon::parse($data['scanned_at'], 'Asia/Manila');
                $source = $data['source'] ?? 'MFA';

                // Resolve User
                $user = $this->resolveUserByMFA($rfid, $fingerprintId);
                if (! $user) {
                    $results['failed']++;
                    $results['messages'][] = "Identity not recognized for RFID: $rfid, FP: $fingerprintId";

                    continue;
                }

                // Check for existing log to prevent duplicates (same user, same minute)
                $exists = AttendanceLog::where('user_id', $user->id)
                    ->where('date', $scannedAt->toDateString())
                    ->where('time_in', '>=', $scannedAt->copy()->subMinute()->toTimeString())
                    ->where('time_in', '<=', $scannedAt->copy()->addMinute()->toTimeString())
                    ->exists();

                if ($exists) {
                    $results['failed']++;
                    $results['messages'][] = "Duplicate scan ignored for {$user->name} at {$scannedAt->toDateTimeString()}";

                    continue;
                }

                // Find schedule to determine status
                $dayName = $scannedAt->format('l');
                $schedule = Schedule::where('user_id', $user->id)
                    ->where('day_of_week', $dayName)
                    ->first();

                if (! $schedule) {
                    $results['failed']++;
                    $results['messages'][] = "No schedule for {$user->name} on $dayName.";

                    continue;
                }

                // Check-in logic (Sync usually handles check-ins or both)
                $startTime = Carbon::parse($scannedAt->toDateString().' '.$schedule->start_time);
                $graceTime = (clone $startTime)->addMinutes(15);
                $status = ($scannedAt <= $graceTime) ? 'On-time' : 'Late';

                $log = AttendanceLog::create([
                    'user_id' => $user->id,
                    'date' => $scannedAt->toDateString(),
                    'time_in' => $scannedAt->toTimeString(),
                    'status' => $status,
                    'source' => $source.' (Synced)',
                ]);

                // Sync Payroll for the affected date
                $period = $payrollService->getCurrentPeriod($scannedAt);
                $payrollService->syncForUser($user, $period['start'], $period['end']);

                $results['success']++;
            } catch (\Exception $e) {
                $results['failed']++;
                $results['messages'][] = 'Error processing log: '.$e->getMessage();
            }
        }

        return $results;
    }

    public function getFilteredLogs(array $filters, bool $paginate = true)
    {
        $query = AttendanceLog::with(['user.schedules']);

        if (! empty($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to']);
        }

        if (! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $query->orderBy('date', 'desc')
            ->orderBy('time_in', 'desc');

        return $paginate ? $query->paginate(10) : $query->get();
    }

    /**
     * Generate CSV content for attendance logs.
     */
    public function generateExport(Collection $logs): string
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Date', 'Employee', 'Check In', 'Check Out', 'Status', 'Duration (Hrs)', 'Source']);

        foreach ($logs as $log) {
            $duration = '-';
            if ($log->time_in && $log->time_out) {
                $in = Carbon::parse($log->date->toDateString().' '.$log->time_in);
                $out = Carbon::parse($log->date->toDateString().' '.$log->time_out);
                
                $dayName = $log->date->format('l');
                $daySchedules = $log->user->schedules->where('day_of_week', $dayName);
                
                if ($daySchedules->isNotEmpty()) {
                    $overlapHours = 0;
                    foreach ($daySchedules as $sched) {
                        $schedStart = Carbon::parse($log->date->toDateString().' '.$sched->start_time);
                        $schedEnd = Carbon::parse($log->date->toDateString().' '.$sched->end_time);
                        
                        $overlapStart = $in->greaterThan($schedStart) ? $in : $schedStart;
                        $overlapEnd = $out->lessThan($schedEnd) ? $out : $schedEnd;
                        
                        if ($overlapStart->lessThan($overlapEnd)) {
                            $overlapHours += $overlapStart->diffInSeconds($overlapEnd) / 3600;
                        }
                    }
                    $duration = number_format($overlapHours, 2);
                } else {
                    $duration = number_format($in->diffInMinutes($out) / 60, 2);
                }
            }

            fputcsv($handle, [
                $log->date->format('Y-m-d'),
                $log->user->name ?? 'Unknown',
                $log->time_in,
                $log->time_out ?? 'N/A',
                $log->status,
                $duration,
                $log->source,
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }

    /**
     * Record a fingerprint attendance log from ESP32.
     * Handles Time In and Time Out automatically.
     */
    public function recordBiometricLog(User $user, string $source, string $deviceUid): array
    {
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();

        // Check if there is an existing log for today
        $existingLog = AttendanceLog::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // ── DUPLICATE CHECK ─────────────────────────────────
        // Prevent double-tap within 1 minute
        if ($existingLog && !$existingLog->time_out) {
            $recentLog = AttendanceLog::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->where(function ($q) use ($now) {
                    $q->where('time_in', '>=', $now->copy()->subMinute()->toTimeString())
                        ->where('time_in', '<=', $now->copy()->addMinute()->toTimeString());
                })
                ->first();

            if ($recentLog) {
                return [
                    'log' => $recentLog,
                    'action' => 'Duplicate',
                ];
            }
        }

        // If they have already completed a time out, reject any additional taps
        if ($existingLog && $existingLog->time_out) {
            return [
                'log' => $existingLog,
                'action' => 'Duplicate',
            ];
        }

        // ── TIME OUT CHECK ───────────────────────────────────
        // If already has time_in today with no time_out → this is a Time Out
        if ($existingLog && $existingLog->time_in && !$existingLog->time_out) {
            $existingLog->update([
                'time_out' => $now->toTimeString(),
                'source' => $existingLog->source.' / FP Time-Out ('.$deviceUid.')',
            ]);

            return [
                'log' => $existingLog->fresh(),
                'action' => 'Time Out',
            ];
        }

        // ── TIME IN ──────────────────────────────────────────
        // Determine status using schedule (same as your processSyncBatch)
        $dayName = $now->format('l');
        $schedule = Schedule::where('user_id', $user->id)
            ->where('day_of_week', $dayName)
            ->first();

        $status = 'On-time';
        if ($schedule) {
            $startTime = Carbon::parse($today.' '.$schedule->start_time, 'Asia/Manila');
            $graceTime = $startTime->copy()->addMinutes(15);
            $status = ($now->lte($graceTime)) ? 'On-time' : 'Late';
        } else {
            // No schedule found — still log but mark accordingly
            $status = 'No Schedule';
        }

        $log = AttendanceLog::create([
            'user_id' => $user->id,
            'date' => $today,
            'time_in' => $now->toTimeString(),
            'status' => $status,
            'source' => 'Fingerprint ('.$deviceUid.')',
        ]);

        return [
            'log' => $log,
            'action' => 'Time In',
        ];
    }
}
