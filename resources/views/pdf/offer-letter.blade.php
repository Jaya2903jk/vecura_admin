<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: white;
        }
        .container {
            max-width: 850px;
            margin: 0 auto;
            padding: 45px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #0d6efd;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .date {
            text-align: right;
            margin-bottom: 30px;
            font-size: 13px;
            color: #34495e;
        }
        .recipient {
            margin-bottom: 30px;
        }
        .recipient p {
            margin: 4px 0;
            font-size: 14px;
        }
        .recipient .name {
            font-weight: 600;
            font-size: 15px;
        }
        .content {
            margin: 30px 0;
            font-size: 14px;
            line-height: 1.8;
            color: #34495e;
        }
        .content p {
            margin-bottom: 14px;
        }
        .content strong {
            color: #2c3e50;
        }
        .section-title {
            color: #fff;
            background: #0d6efd;
            font-weight: 600;
            font-size: 12px;
            margin-top: 24px;
            margin-bottom: 14px;
            padding: 10px 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 3px;
        }
        table {
            width: 100%;
            margin: 16px 0;
            border-collapse: collapse;
            font-size: 13px;
        }
        table tr {
            border-bottom: 1px solid #ecf0f1;
        }
        table td {
            padding: 12px 14px;
        }
        table td:first-child {
            font-weight: 600;
            width: 40%;
            background: #f8f9fa;
            color: #2c3e50;
        }
        table td:last-child {
            color: #34495e;
        }
        .highlight-box {
            background: #f0f8ff;
            border-left: 4px solid #0d6efd;
            padding: 16px;
            margin: 20px 0;
            border-radius: 3px;
            font-size: 13px;
            line-height: 1.7;
        }
        .highlight-box strong {
            display: block;
            margin-bottom: 10px;
            color: #0d6efd;
        }
        .highlight-box ul {
            margin-left: 20px;
            padding: 0;
        }
        .highlight-box li {
            margin-bottom: 6px;
        }
        .signature {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        .sig-block {
            text-align: center;
        }
        .sig-block .line {
            border-top: 1px solid #2c3e50;
            margin: 20px 0 8px;
            height: 1px;
        }
        .sig-block p {
            font-weight: 600;
            font-size: 12px;
            color: #2c3e50;
            margin: 6px 0 0 0;
        }
        .sig-title {
            font-size: 11px;
            color: #7f8c8d;
            font-weight: normal;
            margin-top: 4px;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            font-size: 11px;
            color: #7f8c8d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>VECURA WELLNESS CLINIC</h1>
            <p>OFFER OF EMPLOYMENT</p>
        </div>

        <div class="date">
            <strong>Date:</strong> {{ $date }}
        </div>

        <!-- Recipient -->
        <div class="recipient">
            <p class="name">{{ $employee->FullName }}</p>
            <p>{{ $employee->EmailId }}</p>
        </div>

        <!-- Body -->
        <div class="content">
            <p>Dear {{ $employee->FullName }},</p>

            <p>We are pleased to offer you a position with <strong>{{ $company['name'] }}</strong>. We believe your qualifications and commitment to patient care will be a valuable addition to our team.</p>

            <!-- Position Details -->
            <div class="section-title">Position Details</div>
            <table>
                <tr>
                    <td>Employee Code</td>
                    <td>{{ $employee->UserCode }}</td>
                </tr>
                <tr>
                    <td>Position</td>
                    <td>{{ $employee->designation?->Designation ?? 'As Discussed' }}</td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td>{{ $employee->department?->DepartmentName ?? 'As Discussed' }}</td>
                </tr>
                <tr>
                    <td>Location</td>
                    <td>{{ $employee->branch?->branch_name ?? 'Corporate' }}</td>
                </tr>
                <tr>
                    <td>Employment Type</td>
                    <td>Permanent Full-Time</td>
                </tr>
                <tr>
                    <td>Joining Date</td>
                    <td>As mutually agreed upon</td>
                </tr>
            </table>

            <!-- Key Terms -->
            <div class="section-title">Key Terms</div>
            <ul style="margin-left: 20px; color: #34495e; font-size: 14px;">
                <li style="margin-bottom: 8px;">This offer is subject to background verification, health clearance, and reference checks</li>
                <li style="margin-bottom: 8px;">You will comply with all company policies and professional conduct standards</li>
                <li style="margin-bottom: 8px;">Probation period: As per company policy</li>
                <li style="margin-bottom: 8px;">Confidentiality and professional conduct requirements apply</li>
            </ul>

            <!-- Next Steps -->
            <div class="highlight-box">
                <strong>Next Steps:</strong>
                <ul>
                    <li>Confirm acceptance in writing at your earliest convenience</li>
                    <li>Report to HR on your joining date for orientation</li>
                    <li>Submit required documents within the first week</li>
                    <li>HR will send joining instructions separately</li>
                </ul>
            </div>

            <p>We look forward to welcoming you to Vecura Wellness Clinic. If you have any questions, please contact our HR department.</p>
        </div>

        <!-- Signatures -->
        <div class="signature">
            <div class="sig-block">
                <p>For Vecura Wellness Clinic</p>
                <div class="line"></div>
                <p class="sig-title">HR Manager / Authorized Signatory</p>
            </div>
            <div class="sig-block">
                <p>Employee Acknowledgment</p>
                <div class="line"></div>
                <p class="sig-title">Signature & Date</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ $company['name'] }}</strong></p>
            <p>{{ $company['address'] }} | {{ $company['contact'] }}</p>
            <p style="margin-top: 8px;">© {{ date('Y') }} {{ $company['name'] }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
