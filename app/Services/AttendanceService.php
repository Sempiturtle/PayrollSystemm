<?php

namespace App\Services;

use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AttendanceService
{
    /**
     * Fetch filtered attendance logs with necessary relations.
     */
    public function getFilteredLogs(array $filters): Collection
    {
        $query = AttendanceLog::with('user');

        if (!empty($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('date', 'desc')
                    ->orderBy('time_in', 'desc')
                    ->get();
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
                $in = Carbon::parse($log->date->toDateString() . ' ' . $log->time_in);
                $out = Carbon::parse($log->date->toDateString() . ' ' . $log->time_out);
                $duration = number_format($in->diffInMinutes($out) / 60, 2);
            }

            fputcsv($handle, [
                $log->date->format('Y-m-d'),
                $log->user->name ?? 'Unknown',
                $log->time_in,
                $log->time_out ?? 'N/A',
                $log->status,
                $duration,
                $log->source
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }
}
