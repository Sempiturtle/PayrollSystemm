<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Institutional Executive Summary</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #101D33;
            margin: 0;
            padding: 0;
            background-color: #FDFCF8;
        }
        .header {
            background-color: #101D33;
            color: white;
            padding: 60px 40px;
            text-align: center;
            position: relative;
        }
        .header h1 {
            font-size: 28px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 5px;
        }
        .header p {
            font-size: 10px;
            color: #D4AF37;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-top: 10px;
        }
        .content {
            padding: 40px;
        }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #660000;
            border-bottom: 1px solid #101D3310;
            padding-bottom: 10px;
            margin-bottom: 20px;
            margin-top: 40px;
        }
        .metrics-grid {
            width: 100%;
            margin-bottom: 30px;
        }
        .metric-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #101D3305;
            text-align: center;
        }
        .metric-label {
            font-size: 8px;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .metric-value {
            font-size: 18px;
            font-weight: bold;
            color: #101D33;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            color: #94A3B8;
            padding: 10px;
            border-bottom: 1px solid #101D3305;
        }
        td {
            padding: 15px 10px;
            font-size: 11px;
            border-bottom: 1px solid #101D3305;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 20px 40px;
            font-size: 8px;
            color: #94A3B8;
            text-align: center;
            border-top: 1px solid #101D3305;
        }
        .summary-box {
            background-color: #101D3305;
            padding: 30px;
            border-radius: 20px;
            margin-top: 20px;
        }
        .summary-box p {
            font-size: 12px;
            line-height: 1.6;
            font-style: italic;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Executive Summary</h1>
        <p>Institutional Fiscal & Operational Intelligence Report</p>
        <p style="margin-top: 20px; color: white; font-size: 12px;">{{ $periodStart->format('M d, Y') }} — {{ $periodEnd->format('M d, Y') }}</p>
    </div>

    <div class="content">
        <div class="section-title">Fiscal Performance Parameters</div>
        <table class="metrics-grid">
            <tr>
                <td width="33%">
                    <div class="metric-card">
                        <div class="metric-label">Institutional Gross</div>
                        <div class="metric-value">₱{{ number_format($fiscalMetrics['total_gross'], 2) }}</div>
                    </div>
                </td>
                <td width="33%">
                    <div class="metric-card">
                        <div class="metric-label">Institutional Net</div>
                        <div class="metric-value" style="color: #059669;">₱{{ number_format($fiscalMetrics['total_net'], 2) }}</div>
                    </div>
                </td>
                <td width="33%">
                    <div class="metric-card">
                        <div class="metric-label">Total Retentions</div>
                        <div class="metric-value" style="color: #660000;">₱{{ number_format($fiscalMetrics['total_deductions'], 2) }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">Statutory Contribution Summary</div>
        <table>
            <thead>
                <tr>
                    <th>Identifier</th>
                    <th>Aggregate Amount</th>
                    <th>Institutional Share</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Social Security System (SSS)</td>
                    <td>₱{{ number_format($fiscalMetrics['total_sss'], 2) }}</td>
                    <td>Verified</td>
                </tr>
                <tr>
                    <td>PhilHealth Corporation</td>
                    <td>₱{{ number_format($fiscalMetrics['total_philhealth'], 2) }}</td>
                    <td>Verified</td>
                </tr>
                <tr>
                    <td>Pag-IBIG Fund (HDMF)</td>
                    <td>₱{{ number_format($fiscalMetrics['total_pagibig'], 2) }}</td>
                    <td>Verified</td>
                </tr>
                <tr>
                    <td>Bureau of Internal Revenue (Tax)</td>
                    <td>₱{{ number_format($fiscalMetrics['total_tax'], 2) }}</td>
                    <td>Verified</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">Operational Intelligence</div>
        <table class="metrics-grid">
            <tr>
                <td width="50%">
                    <div class="metric-card">
                        <div class="metric-label">Standard Authentications</div>
                        <div class="metric-value">{{ $attendanceMetrics['standard'] }}</div>
                    </div>
                </td>
                <td width="50%">
                    <div class="metric-card">
                        <div class="metric-label">Latency Events (Late)</div>
                        <div class="metric-value" style="color: #D4AF37;">{{ $attendanceMetrics['latency'] }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">Institutional Governance Summary</div>
        <div class="summary-box">
            <p>
                During the period of {{ $periodStart->format('F d') }} to {{ $periodEnd->format('F d, Y') }}, the institution processed disbursements for {{ $fiscalMetrics['staff_count'] }} active personnel nodes. 
                A total of {{ $fiscalMetrics['finalized_count'] }} records have been finalized and locked under institutional audit protocols. 
                We observed {{ $discrepancyMetrics['total'] }} conflict disclosures, with {{ $discrepancyMetrics['resolved'] }} cases successfully resolved via the administrative review board.
            </p>
        </div>
    </div>

    <div class="footer">
        AISAT INSTITUTIONAL PAYROLL SYSTEM — CONFIDENTIAL EXECUTIVE DOCUMENT — GENERATED {{ now()->format('Y-m-d H:i') }}
    </div>
</body>
</html>
