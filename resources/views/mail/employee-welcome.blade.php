<div style="font-family: 'Segoe UI', Tahoma, Geneva, sans-serif; line-height: 1.6; color: #2c3e50;">
    <!-- Header -->
    <div style="background: #0d6efd; padding: 35px 20px; text-align: center; color: white;">
        <h1 style="margin: 0; font-size: 28px; font-weight: 700;">Vecura Wellness Clinic</h1>
        <p style="margin: 8px 0 0 0; font-size: 14px; font-weight: 400;">Human Resources Department</p>
    </div>

    <!-- Main Content -->
    <div style="background: #ffffff; padding: 35px 30px; max-width: 600px; margin: 0 auto; border-bottom: 1px solid #e0e0e0;">
        <p style="font-size: 15px; font-weight: 600; color: #2c3e50; margin: 0 0 20px 0;">
            Hello {{ $employee->FullName }},
        </p>

        <p style="font-size: 14px; line-height: 1.8; color: #34495e; margin: 0 0 20px 0;">
            We are delighted to welcome you to <strong>Vecura Wellness Clinic</strong>! We are excited about your upcoming journey with us and confident that you will make meaningful contributions to our team.
        </p>

        <!-- Your Details Section -->
        <div style="background: #f8f9fa; border-left: 4px solid #0d6efd; padding: 16px; margin: 25px 0; border-radius: 3px;">
            <h3 style="color: #0d6efd; font-size: 12px; font-weight: 700; text-transform: uppercase; margin: 0 0 12px 0; letter-spacing: 0.5px;">Your Employee Details</h3>
            <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #ecf0f1;">
                    <td style="padding: 8px 0; font-weight: 600; color: #2c3e50; width: 40%;">Employee Code</td>
                    <td style="padding: 8px 0; color: #34495e;">{{ $employee->UserCode }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #ecf0f1;">
                    <td style="padding: 8px 0; font-weight: 600; color: #2c3e50;">Designation</td>
                    <td style="padding: 8px 0; color: #34495e;">{{ $employee->designation?->Designation ?? 'TBD' }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #ecf0f1;">
                    <td style="padding: 8px 0; font-weight: 600; color: #2c3e50;">Department</td>
                    <td style="padding: 8px 0; color: #34495e;">{{ $employee->department?->DepartmentName ?? 'TBD' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: 600; color: #2c3e50;">Branch</td>
                    <td style="padding: 8px 0; color: #34495e;">{{ $employee->branch?->branch_name ?? 'TBD' }}</td>
                </tr>
            </table>
        </div>

        <!-- Important Information -->
        <div style="background: #f0f8ff; border-left: 4px solid #0d6efd; padding: 16px; margin: 25px 0; border-radius: 3px;">
            <h3 style="color: #0d6efd; font-size: 12px; font-weight: 700; text-transform: uppercase; margin: 0 0 12px 0; letter-spacing: 0.5px;">Next Steps</h3>
            <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #34495e; line-height: 1.8;">
                <li style="margin-bottom: 8px;"><strong>System Password:</strong> <code style="background: #fff; padding: 2px 6px; border: 1px solid #ddd; border-radius: 3px; font-family: monospace;">Vecura@123</code> (change on first login)</li>
                <li style="margin-bottom: 8px;">Your <strong>offer letter</strong> is attached to this email</li>
                <li style="margin-bottom: 8px;">Report to <strong>HR on your joining date</strong> for orientation</li>
                <li>HR will send joining instructions and onboarding details</li>
            </ul>
        </div>

        <p style="font-size: 14px; line-height: 1.8; color: #34495e; margin: 20px 0 0 0;">
            If you have any questions before your joining date, please contact our HR department. We look forward to working with you!
        </p>
    </div>

    <!-- Footer -->
    <div style="background: #f8f9fa; padding: 25px 20px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #7f8c8d; line-height: 1.6;">
            <strong>Vecura Wellness Clinic</strong><br>
            Human Resources Department<br>
            <span style="font-size: 11px;">© 2026 Vecura Wellness Clinic. All rights reserved.</span>
        </p>
    </div>
</div>
