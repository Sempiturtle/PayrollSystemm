<?php

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Headings
$sheet->setCellValue('A1', 'day_of_week');
$sheet->setCellValue('B1', 'start_time');
$sheet->setCellValue('C1', 'end_time');

$data = [
    // Monday
    ['Monday', '07:00 AM', '11:00 AM'],
    ['Monday', '11:00 AM', '11:55 AM'],
    ['Monday', '11:55 AM', '12:50 PM'],
    ['Monday', '01:10 PM', '03:00 PM'],
    ['Monday', '03:00 PM', '04:00 PM'],
    ['Monday', '04:00 PM', '05:00 PM'],
    ['Monday', '05:00 PM', '07:00 PM'],
    ['Monday', '07:00 PM', '10:00 PM'],

    // Tuesday
    ['Tuesday', '07:00 AM', '08:00 AM'],
    ['Tuesday', '08:00 AM', '09:00 AM'],
    ['Tuesday', '09:00 AM', '11:00 AM'],
    ['Tuesday', '11:00 AM', '12:50 PM'],
    ['Tuesday', '01:10 PM', '03:00 PM'],
    ['Tuesday', '03:00 PM', '04:00 PM'],
    ['Tuesday', '07:00 PM', '10:00 PM'],

    // Wednesday
    ['Wednesday', '07:00 AM', '09:00 AM'],
    ['Wednesday', '09:00 AM', '11:00 AM'],
    ['Wednesday', '11:00 AM', '11:55 AM'],
    ['Wednesday', '11:55 AM', '12:50 PM'],
    ['Wednesday', '02:05 PM', '04:00 PM'],
    ['Wednesday', '04:00 PM', '05:00 PM'],
    ['Wednesday', '05:00 PM', '07:00 PM'],

    // Thursday
    ['Thursday', '07:00 AM', '08:00 AM'],
    ['Thursday', '08:00 AM', '09:00 AM'],
    ['Thursday', '09:00 AM', '11:00 AM'],
    ['Thursday', '11:00 AM', '11:55 AM'],
    ['Thursday', '11:55 AM', '12:50 PM'],
    ['Thursday', '01:10 PM', '02:05 PM'],
    ['Thursday', '03:00 PM', '05:00 PM'],
    ['Thursday', '07:00 PM', '10:00 PM'],

    // Friday
    ['Friday', '07:00 AM', '10:00 AM'],
    ['Friday', '10:00 AM', '11:00 AM'],
    ['Friday', '11:00 AM', '11:55 AM'],
    ['Friday', '11:55 AM', '12:50 PM'],
    ['Friday', '01:10 PM', '03:00 PM'],
    ['Friday', '03:00 PM', '04:00 PM'],
    ['Friday', '04:00 PM', '06:00 PM'],
    ['Friday', '06:00 PM', '07:00 PM'],

    // Saturday
    ['Saturday', '07:00 AM', '10:00 AM'],
    ['Saturday', '10:00 AM', '11:00 AM'],
    ['Saturday', '11:00 AM', '12:50 PM'],
    ['Saturday', '01:10 PM', '03:00 PM'],
    ['Saturday', '03:00 PM', '04:00 PM'],
    ['Saturday', '04:00 PM', '06:00 PM'],
];

$row = 2;
foreach ($data as $item) {
    $sheet->setCellValue('A' . $row, $item[0]);
    $sheet->setCellValue('B' . $row, $item[1]);
    $sheet->setCellValue('C' . $row, $item[2]);
    $row++;
}

// Styling
$sheet->getStyle('A1:C1')->getFont()->setBold(true);
foreach (range('A', 'C') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$writer->save('public/Official_Schedule_Lumaban.xlsx');

echo "Excel file generated successfully at public/Official_Schedule_Lumaban.xlsx\n";
