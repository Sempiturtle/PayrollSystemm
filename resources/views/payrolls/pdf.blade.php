<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Earnings Statement - {{ $payroll->user->name }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.6; margin: 0; padding: 40px; background: #fff; }
        .header-section { border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 20px; }
        .company-name { font-size: 22px; font-weight: 900; color: #4f46e5; text-transform: uppercase; letter-spacing: -0.5px; }
        .document-type { font-size: 10px; font-bold: bold; color: #64748b; text-transform: uppercase; letter-spacing: 2px; }
        
        .metadata-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .metadata-grid td { width: 25%; padding: 4px 0; }
        .label { font-weight: bold; color: #94a3b8; text-transform: uppercase; font-size: 9px; }
        .value { font-weight: bold; color: #1e293b; }

        .main-grid { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .main-grid > tbody > tr > td { vertical-align: top; padding: 0 10px; border-right: 1px solid #f1f5f9; }
        .main-grid > tbody > tr > td:last-child { border-right: none; }

        .section-title { font-size: 10px; font-weight: 900; color: #4f46e5; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 10px; }
        
        .entry-table { width: 100%; border-collapse: collapse; }
        .entry-table td { padding: 6px 0; border-bottom: 1px solid #f8fafc; }
        .entry-table tr:last-child td { border-bottom: none; }
        .amount { text-align: right; font-family: 'Courier New', Courier, monospace; font-weight: bold; font-size: 12px; }
        .sub-label { display: block; font-size: 8px; color: #94a3b8; font-weight: normal; }

        .summary-box { background: #f8fafc; border-radius: 8px; padding: 15px; margin-top: 30px; }
        .net-pay-row { font-size: 18px; color: #4f46e5; font-weight: 900; }
        
        .footer { position: absolute; bottom: 40px; left: 40px; right: 40px; text-align: center; color: #94a3b8; font-size: 9px; }
        .seal { font-family: monospace; background: #eef2ff; color: #6366f1; padding: 5px 10px; border-radius: 4px; display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header-section">
        <div class="company-name">AISAT College</div>
        <div class="document-type">Official Statement of Earnings & Deductions</div>
    </div>

    <table class="metadata-grid">
        <tr>
            <td class="label">Employee Name</td>
            <td class="value">{{ $payroll->user->name }}</td>
            <td class="label">Payroll Period</td>
            <td class="value">{{ \Carbon\Carbon::parse($payroll->period_start)->format('M d') }} - {{ \Carbon\Carbon::parse($payroll->period_end)->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td class="label">Employee ID</td>
            <td class="value">{{ $payroll->user->employee_id }}</td>
            <td class="label">Designation</td>
            <td class="value">{{ ucfirst($payroll->user->role) }}</td>
        </tr>
        <tr>
            <td class="label">Tax Status</td>
            <td class="value">S / ME (TRAIN)</td>
            <td class="label">Rate of Pay</td>
            <td class="value">₱{{ number_format($payroll->user->hourly_rate, 2) }} / Hr</td>
        </tr>
    </table>

    <table class="main-grid">
        <tbody>
            <tr>
                <!-- EARNINGS -->
                <td>
                    <div class="section-title">Earnings Breakdown</div>
                    <table class="entry-table">
                        <tr>
                            <td>
                                <strong>Regular Pay</strong>
                                <span class="sub-label">Hours Worked: {{ number_format($payroll->total_hours, 2) }} hrs</span>
                            </td>
                            <td class="amount">₱{{ number_format($payroll->gross_pay, 2) }}</td>
                        </tr>
                        {{-- Future Overtime/Allowances can go here --}}
                        <tr style="height: 100px;"><td></td><td></td></tr>
                    </table>
                </td>

                <!-- DEDUCTIONS -->
                <td>
                    <div class="section-title">Statutory & Adjustments</div>
                    <table class="entry-table">
                        @if($payroll->late_deduction > 0)
                        <tr>
                            <td>
                                <strong>Tardiness / Late</strong>
                                <span class="sub-label">Total Late: {{ $payroll->late_minutes }} minutes</span>
                            </td>
                            <td class="amount" style="color: #ef4444;">-₱{{ number_format($payroll->late_deduction, 2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>
                                <strong>SSS Contribution</strong>
                                <span class="sub-label">EE Share (2025 Standard)</span>
                            </td>
                            <td class="amount" style="color: #ef4444;">-₱{{ number_format($payroll->sss_deduction, 2) }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>PhilHealth</strong>
                                <span class="sub-label">EE Share (5% Total Premium)</span>
                            </td>
                            <td class="amount" style="color: #ef4444;">-₱{{ number_format($payroll->philhealth_deduction, 2) }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Pag-IBIG / HDMF</strong>
                                <span class="sub-label">Fixed Employee Share</span>
                            </td>
                            <td class="amount" style="color: #ef4444;">-₱{{ number_format($payroll->pagibig_deduction, 2) }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Withholding Tax</strong>
                                <span class="sub-label">TRAIN Monthly Schedule</span>
                            </td>
                            <td class="amount" style="color: #ef4444;">-₱{{ number_format($payroll->tax_deduction, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="summary-box">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%;">
                    <div class="label">Total Gross Earnings</div>
                    <div style="font-size: 14px; font-weight: bold;">₱{{ number_format($payroll->gross_pay, 2) }}</div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="label">Total Deductions</div>
                    <div style="font-size: 14px; font-weight: bold; color: #ef4444;">-₱{{ number_format($payroll->total_deductions, 2) }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 15px; border-top: 1px solid #e2e8f0; margin-top: 10px;">
                    <table style="width: 100%;">
                        <tr>
                            <td class="net-pay-row">NET TAKE-HOME PAY</td>
                            <td class="net-pay-row" style="text-align: right;">₱{{ number_format($payroll->net_pay, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div>DIGITAL FISCAL SEAL (INTEGRITY HASH)</div>
        <div class="seal">{{ $payroll->getVerificationFingerprint() }}</div>
        <p style="margin-top: 15px;">
            This document is electronically generated by the AISAT Payroll Intelligence System.<br>
            It is a binding record of compensation for the period indicated and does not require a physical signature.<br>
            &copy; {{ date('Y') }} AISAT College. All rights reserved.
        </p>
    </div>
</body>
</html>
