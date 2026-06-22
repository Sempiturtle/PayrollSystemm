<?php

namespace App\Services;

use App\Models\User;
use App\Models\Schedule;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ScheduleSyncService
{
    /**
     * Synchronize a user's database schedules back to their stored Excel/CSV file.
     */
    public static function syncDbToFile(User $user): void
    {
        $schedules = Schedule::where('user_id', $user->id)
            ->orderByDay()
            ->orderBy('start_time')
            ->get();

        $filename = $user->schedule_file;
        if (!$filename) {
            // Default to xlsx if none is defined yet
            $filename = "schedules/{$user->employee_id}_schedule.xlsx";
            $user->update(['schedule_file' => $filename]);
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $path = storage_path('app/' . $filename);

        // Ensure parent directory exists
        $directory = dirname($path);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Format data: Headers first, then rows
        $data = [
            ['day_of_week', 'start_time', 'end_time']
        ];

        foreach ($schedules as $sched) {
            $start = \Carbon\Carbon::parse($sched->start_time)->format('h:i A');
            $end = \Carbon\Carbon::parse($sched->end_time)->format('h:i A');
            $data[] = [
                $sched->day_of_week,
                $start,
                $end
            ];
        }

        if ($extension === 'csv') {
            $fp = fopen($path, 'w');
            foreach ($data as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);
        } else {
            // Excel Generation (xlsx / xls)
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $rowNum = 1;
            foreach ($data as $row) {
                $sheet->setCellValue('A' . $rowNum, $row[0]);
                $sheet->setCellValue('B' . $rowNum, $row[1]);
                $sheet->setCellValue('C' . $rowNum, $row[2]);
                $rowNum++;
            }

            if ($extension === 'xls') {
                $writer = new Xls($spreadsheet);
            } else {
                $writer = new Xlsx($spreadsheet);
            }
            $writer->save($path);
        }
    }

    /**
     * Generate the schedule Excel file in-memory and stream it on the fly.
     */
    public static function downloadScheduleOnTheFly(User $user): StreamedResponse
    {
        $schedules = Schedule::where('user_id', $user->id)
            ->orderByDay()
            ->orderBy('start_time')
            ->get();

        $data = [
            ['day_of_week', 'start_time', 'end_time']
        ];

        foreach ($schedules as $sched) {
            $start = \Carbon\Carbon::parse($sched->start_time)->format('h:i A');
            $end = \Carbon\Carbon::parse($sched->end_time)->format('h:i A');
            $data[] = [
                $sched->day_of_week,
                $start,
                $end
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $rowNum = 1;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row[0]);
            $sheet->setCellValue('B' . $rowNum, $row[1]);
            $sheet->setCellValue('C' . $rowNum, $row[2]);
            $rowNum++;
        }

        $writer = new Xlsx($spreadsheet);

        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="Your_Schedule_' . $user->employee_id . '.xlsx"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }
}
