<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payroll->user->name }} - {{ $payroll->period_end }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; }
        .container { width: 100%; margin: 0 auto; max-width: 800px; }
        .header { text-align: left; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; margin-bottom: 30px; }
        .header table { width: 100%; border-collapse: collapse; }
        .header .logo { width: 60px; height: 60px; }
        .header .college-info { padding-left: 15px; }
        .header h1 { margin: 0; color: #1e293b; font-size: 26px; font-weight: 800; letter-spacing: -1px; text-transform: uppercase; }
        .header p { margin: 2px 0 0; color: #4f46e5; font-size: 12px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
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
            <table>
                <tr>
                    <td style="width: 60px;">
                        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="AISAT Logo">
                    </td>
                    <td class="college-info">
                        <h1>AISAT College</h1>
                        <p>Attendance & Payroll Intelligence — Official Statement</p>
                    </td>
                </tr>
            </table>
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
                    <td class="amount">₱{{ number_format($payroll->user->hourly_rate, 2) }}/hr</td>
                    <td class="amount">₱{{ number_format($payroll->gross_pay, 2) }}</td>
                </tr>
                <tr>
                    <td>Late Deductions</td>
                    <td class="amount">{{ number_format($payroll->late_minutes, 0) }} mins</td>
                    <td class="amount">--</td>
                    <td class="amount" style="color: #e11d48;">-₱{{ number_format($payroll->total_deductions - ($payroll->sss_deduction + $payroll->philhealth_deduction + $payroll->pagibig_deduction + $payroll->tax_deduction), 2) }}</td>
                </tr>
                <tr>
                    <td>SSS Contribution</td>
                    <td class="amount">--</td>
                    <td class="amount">4.5% approx</td>
                    <td class="amount" style="color: #e11d48;">-₱{{ number_format($payroll->sss_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>PhilHealth Contribution</td>
                    <td class="amount">--</td>
                    <td class="amount">2% approx</td>
                    <td class="amount" style="color: #e11d48;">-₱{{ number_format($payroll->philhealth_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>Pag-IBIG Contribution</td>
                    <td class="amount">--</td>
                    <td class="amount">Fixed</td>
                    <td class="amount" style="color: #e11d48;">-₱{{ number_format($payroll->pagibig_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>Withholding Tax (WHT)</td>
                    <td class="amount">--</td>
                    <td class="amount">TRAIN Law</td>
                    <td class="amount" style="color: #e11d48;">-₱{{ number_format($payroll->tax_deduction, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Gross Pay</td>
                    <td class="amount">₱{{ number_format($payroll->gross_pay, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total Deductions</td>
                    <td class="amount" style="color: #e11d48;">-₱{{ number_format($payroll->total_deductions, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; background-color: #eef2ff;" class="net-pay">NET PAY</td>
                    <td class="amount net-pay" style="background-color: #eef2ff;">₱{{ number_format($payroll->net_pay, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p style="margin-bottom: 10px; font-weight: bold; color: #475569;">
                DOCUMENT VERIFICATION FINGERPRINT (DIGITAL SEAL)<br>
                <span style="font-family: monospace; font-size: 10px; color: #6366f1; background: #f5f3ff; padding: 4px 8px; border-radius: 4px;">{{ $payroll->getVerificationFingerprint() }}</span>
            </p>
            <p>This is a computer-generated document secured via AISAT Integrity Hash. No signature is required. <br>
            Verification of this hash can be performed by the Finance Department against the system ledger.</p>
            <p>&copy; {{ date('Y') }} AISAT College Payroll Intelligence. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
