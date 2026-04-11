<?php
/**
 * Generate the individual schedule template Excel.
 */

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Schedule');

// Headers
$sheet->setCellValue('A1', 'day_of_week');
$sheet->setCellValue('B1', 'start_time');
$sheet->setCellValue('C1', 'end_time');

// Example rows
$days = [
    ['Monday',    '08:00', '12:00'],
    ['Tuesday',   '13:00', '17:00'],
    ['Wednesday', '08:00', '12:00'],
    ['Thursday',  '13:00', '17:00'],
    ['Friday',    '08:00', '12:00'],
];

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

$outputPath = __DIR__ . '/../storage/app/example_individual_schedule.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($outputPath);

echo "✅ Template created: {$outputPath}\n";
