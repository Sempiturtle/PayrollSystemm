<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payroll->user->name }} - {{ $payroll->period_end }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; }
        .container { width: 100%; margin: 0 auto; max-width: 800px; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #1e293b; font-size: 28px; }
        .header p { margin: 5px 0 0; color: #64748b; font-size: 14px; }
        .info-grid { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .info-grid td { padding: 8px 0; vertical-align: top; }
        .info-label { font-weight: bold; color: #64748b; width: 150px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th, .table td { padding: 12px; border: 1px solid #e2e8f0; text-align: left; }
        .table th { background-color: #f8fafc; font-weight: bold; color: #1e293b; }
        .table .amount { text-align: right; font-family: monospace; font-size: 15px; }
        .total-row { background-color: #f1f5f9; font-weight: bold; }
        .net-pay { font-size: 18px; color: #4f46e5; font-weight: bold; }
        .footer { text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sempiturtle College</h1>
            <p>Official Employee Payslip</p>
        </div>

        <table class="info-grid">
            <tr>
                <td class="info-label">Employee Name:</td>
                <td>{{ $payroll->user->name }}</td>
                <td class="info-label">Period Start:</td>
                <td>{{ \Carbon\Carbon::parse($payroll->period_start)->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">Employee ID:</td>
                <td>{{ $payroll->user->employee_id }}</td>
                <td class="info-label">Period End:</td>
                <td>{{ \Carbon\Carbon::parse($payroll->period_end)->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">Department:</td>
                <td>Faculty</td>
                <td class="info-label">Generated On:</td>
                <td>{{ now()->format('F j, Y Hi') }}</td>
            </tr>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="amount">Hours / Mins</th>
                    <th class="amount">Rate / Deduct</th>
                    <th class="amount">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Pay (Total Hours)</td>
                    <td class="amount">{{ number_format($payroll->total_hours, 2) }} hrs</td>
                    <td class="amount">${{ number_format($payroll->user->hourly_rate, 2) }}/hr</td>
                    <td class="amount">${{ number_format($payroll->gross_pay, 2) }}</td>
                </tr>
                <tr>
                    <td>Late Deductions</td>
                    <td class="amount">{{ number_format($payroll->late_minutes, 0) }} mins</td>
                    <td class="amount">--</td>
                    <td class="amount" style="color: #e11d48;">-${{ number_format($payroll->total_deductions, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Gross Pay</td>
                    <td class="amount">${{ number_format($payroll->gross_pay, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total Deductions</td>
                    <td class="amount" style="color: #e11d48;">-${{ number_format($payroll->total_deductions, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; background-color: #eef2ff;" class="net-pay">NET PAY</td>
                    <td class="amount net-pay" style="background-color: #eef2ff;">${{ number_format($payroll->net_pay, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>This is a computer-generated document. No signature is required.</p>
            <p>&copy; {{ date('Y') }} Sempiturtle College Payroll System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
