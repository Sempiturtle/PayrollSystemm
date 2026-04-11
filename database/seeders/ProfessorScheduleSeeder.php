<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProfessorScheduleImport;

class ProfessorScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Define some distinct schedule templates
        $scheduleTemplates = [
            [
                ['Monday',    '08:00', '12:00'],
                ['Tuesday',   '13:00', '17:00'],
                ['Wednesday', '08:00', '12:00'],
                ['Thursday',  '13:00', '17:00'],
                ['Friday',    '08:00', '12:00'],
            ],
            [
                ['Tuesday',   '08:00', '12:00'],
                ['Thursday',  '08:00', '12:00'],
                ['Friday',    '13:00', '17:00'],
                ['Saturday',  '09:00', '14:00'],
            ],
            [
                ['Monday',    '13:00', '18:00'],
                ['Wednesday', '13:00', '18:00'],
                ['Friday',    '13:00', '18:00'],
            ],
            [
                ['Monday',    '09:00', '15:00'],
                ['Tuesday',   '09:00', '15:00'],
                ['Wednesday', '09:00', '15:00'],
                ['Thursday',  '09:00', '15:00'],
            ]
        ];

        // Ensure we have at least 4 professors
        $professors = User::where('role', 'professor')->get();
        if ($professors->count() < 4) {
            $needed = 4 - $professors->count();
            for ($i = 0; $i < $needed; $i++) {
                $num = 100 + $i;
                $user = User::create([
                    'name' => "Prof. Demo {$num}",
                    'email' => "prof{$num}@example.com",
                    'employee_id' => "PROF-{$num}",
                    'role' => 'professor',
                    'hourly_rate' => 500.00,
                    'password' => bcrypt('password'),
                ]);
            }
            $professors = User::where('role', 'professor')->get();
        }

        // Loop through and assign distinct schedules
        foreach ($professors as $index => $professor) {
            $template = $scheduleTemplates[$index % count($scheduleTemplates)];
            
            // 1. Generate Excel 
            $fileName = "schedules/{$professor->employee_id}_schedule.xlsx";
            $path = storage_path("app/{$fileName}");
            
            $this->generateTemplateExcel($path, $template);

            // 2. Clear old schedules
            Schedule::where('user_id', $professor->id)->delete();

            // 3. Import new schedules
            try {
                Excel::import(new ProfessorScheduleImport($professor->id), $path);
                $this->command->info("Generated unique schedule excel for {$professor->name} at storage/app/{$fileName}");
            } catch (\Exception $e) {
                $this->command->error("Failed to import for {$professor->name}: " . $e->getMessage());
            }

            // 4. Update profile
            $professor->update(['schedule_file' => $fileName]);
        }
    }

    private function generateTemplateExcel(string $path, array $days): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Schedule');

        // Headers
        $sheet->setCellValue('A1', 'day_of_week');
        $sheet->setCellValue('B1', 'start_time');
        $sheet->setCellValue('C1', 'end_time');

        foreach ($days as $i => $row) {
            $r = $i + 2;
            $sheet->setCellValue("A{$r}", $row[0]);
            $sheet->setCellValue("B{$r}", $row[1]);
            $sheet->setCellValue("C{$r}", $row[2]);
        }

        // Style headers
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
        ]);

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Ensure directory exists
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
    }
}
