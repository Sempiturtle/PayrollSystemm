<?php
/**
 * Generate Example Schedule Import Excel
 * 
 * Creates a professionally formatted .xlsx file that matches
 * the ScheduleImport expected format:
 *   employee_id | day_of_week | start_time | end_time | effective_date
 *
 * Uses the seeded employees (PROF-001, STAFF-001) plus additional
 * sample professors to demonstrate a realistic multi-professor schedule.
 */

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();

// ── Sheet 1: The actual import data ──────────────────────────
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Professor Schedules');

// Column headers (must match ScheduleImport heading row expectations)
$headers = ['employee_id', 'day_of_week', 'start_time', 'end_time', 'effective_date'];

// Example schedule data — realistic professor timetables
$scheduleData = [
    // Prof. Juan Dela Cruz (PROF-001) — MWF morning, TTh afternoon
    ['PROF-001', 'Monday',    '08:00', '12:00', '2026-04-07'],
    ['PROF-001', 'Tuesday',   '13:00', '17:00', '2026-04-07'],
    ['PROF-001', 'Wednesday', '08:00', '12:00', '2026-04-07'],
    ['PROF-001', 'Thursday',  '13:00', '17:00', '2026-04-07'],
    ['PROF-001', 'Friday',    '08:00', '12:00', '2026-04-07'],

    // Sarah Smith (STAFF-001) — Full weekday office hours
    ['STAFF-001', 'Monday',    '08:00', '17:00', '2026-04-07'],
    ['STAFF-001', 'Tuesday',   '08:00', '17:00', '2026-04-07'],
    ['STAFF-001', 'Wednesday', '08:00', '17:00', '2026-04-07'],
    ['STAFF-001', 'Thursday',  '08:00', '17:00', '2026-04-07'],
    ['STAFF-001', 'Friday',    '08:00', '17:00', '2026-04-07'],
];

// Write headers
foreach ($headers as $col => $header) {
    $cell = $sheet->getCellByColumnAndRow($col + 1, 1);
    $cell->setValue($header);
}

// Write data
foreach ($scheduleData as $rowIndex => $row) {
    foreach ($row as $colIndex => $value) {
        $sheet->getCellByColumnAndRow($colIndex + 1, $rowIndex + 2)->setValue($value);
    }
}

// ── Styling ──────────────────────────────────────────────────

// Header style
$headerRange = 'A1:E1';
$sheet->getStyle($headerRange)->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 11,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '2563EB'], // Blue
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '1E40AF'],
        ],
    ],
]);

// Data style
$lastRow = count($scheduleData) + 1;
$dataRange = "A2:E{$lastRow}";
$sheet->getStyle($dataRange)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => 'D1D5DB'],
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
]);

// Alternate row colors for readability
for ($i = 2; $i <= $lastRow; $i++) {
    if ($i % 2 === 0) {
        $sheet->getStyle("A{$i}:E{$i}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EFF6FF'], // Light blue
            ],
        ]);
    }
}

// Auto-size columns
foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Row height
$sheet->getRowDimension(1)->setRowHeight(25);

// ── Sheet 2: Instructions ────────────────────────────────────
$instrSheet = $spreadsheet->createSheet();
$instrSheet->setTitle('Instructions');

$instructions = [
    ['SCHEDULE IMPORT INSTRUCTIONS'],
    [''],
    ['Column', 'Description', 'Required', 'Example'],
    ['employee_id', 'The employee ID as registered in the system', 'Yes', 'PROF-001'],
    ['day_of_week', 'Full day name or abbreviation (Mon, Tue, etc.)', 'Yes', 'Monday'],
    ['start_time', 'Start time in HH:MM format (24-hour)', 'Yes', '08:00'],
    ['end_time', 'End time in HH:MM format (24-hour)', 'Yes', '17:00'],
    ['effective_date', 'Date when this schedule takes effect (YYYY-MM-DD)', 'No', '2026-04-07'],
    [''],
    ['NOTES:'],
    ['- Each row represents one day of one employee\'s schedule.'],
    ['- One employee can have up to 7 rows (one per day of the week).'],
    ['- If you import a schedule for the same employee + day, it will UPDATE the existing entry.'],
    ['- Accepted day abbreviations: Mon, Tue, Wed, Thu, Fri, Sat, Sun (or M, T, W, Th, F).'],
    ['- Times must be in 24-hour format (e.g., 13:00 for 1:00 PM).'],
    ['- The "Professor Schedules" sheet is the one that gets imported.'],
];

foreach ($instructions as $rowIndex => $row) {
    foreach ((array)$row as $colIndex => $value) {
        $instrSheet->getCellByColumnAndRow($colIndex + 1, $rowIndex + 1)->setValue($value);
    }
}

// Style the instructions header
$instrSheet->getStyle('A1')->applyFromArray([
    'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1E40AF']],
]);

// Style the table header
$instrSheet->getStyle('A3:D3')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
]);

// Style the table body
$instrSheet->getStyle('A4:D8')->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
]);

// Notes style
for ($i = 10; $i <= 16; $i++) {
    $instrSheet->getStyle("A{$i}")->applyFromArray([
        'font' => ['italic' => true, 'color' => ['rgb' => '6B7280']],
    ]);
}

// Auto-size
foreach (range('A', 'D') as $col) {
    $instrSheet->getColumnDimension($col)->setAutoSize(true);
}

// Make the data sheet the default visible sheet
$spreadsheet->setActiveSheetIndex(0);

// ── Save ─────────────────────────────────────────────────────
$outputPath = __DIR__ . '/../storage/app/example_professor_schedule.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($outputPath);

echo "✅ Example schedule Excel created at:\n";
echo "   {$outputPath}\n\n";
echo "Columns: " . implode(' | ', $headers) . "\n";
echo "Rows: " . count($scheduleData) . " schedule entries\n";
echo "Employees: PROF-001 (Prof. Juan Dela Cruz), STAFF-001 (Sarah Smith)\n";
