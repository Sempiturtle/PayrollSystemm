<?php

namespace App\Exports;

use App\Models\AttendanceLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $date;

    public function __construct($date = null)
    {
        $this->date = $date ?? now()->toDateString();
    }

    public function collection()
    {
        return AttendanceLog::with('user')
            ->where('date', $this->date)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Employee ID',
            'Date',
            'Time In',
            'Time Out',
            'Status',
        ];
    }

    public function map($log): array
    {
        return [
            $log->user->name,
            $log->user->employee_id,
            $log->date->format('Y-m-d'),
            $log->time_in,
            $log->time_out,
            $log->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
