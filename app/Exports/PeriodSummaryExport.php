<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeriodSummaryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        return Payroll::with('user')
            ->where('period_start', $this->start)
            ->where('period_end', $this->end)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Employee ID',
            'Total Hours',
            'Late Deductions (Pesos)',
            'Gross Pay',
            'Net Pay',
            'Status',
        ];
    }

    public function map($payroll): array
    {
        return [
            $payroll->user->name,
            $payroll->user->employee_id,
            $payroll->total_hours,
            $payroll->total_deductions,
            $payroll->gross_pay,
            $payroll->net_pay,
            $payroll->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
