<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
        .details { background: white; padding: 20px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .reason { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registration Review</h1>
        </div>

        <div class="content">
            <p>Dear {{ $user->name }},</p>

            <p>Thank you for your interest in the UiTM Credit Exemption System.</p>

            <p>After review, we are unable to approve your registration at this time.</p>

            <div class="details">
                <h3>📋 Application Details</h3>
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Requested Role:</strong> {{ ucwords(str_replace('_', ' ', $user->requested_role)) }}<br>
                <strong>Status:</strong> <span style="color: #dc3545;">Not Approved</span>
            </div>

            <div class="reason">
                <h3>Reason:</h3>
                <p>{{ $rejectionReason }}</p>
            </div>

            <p>If you believe this is an error or have any questions, please contact:</p>
            <ul>
                <li>Email: {{ config('app.admin_email', 'hea@uitm.edu.my') }}</li>
                <li>Include your name and email address in your inquiry</li>
            </ul>

            <p>Best regards,<br>
            UiTM Credit Exemption System</p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} UiTM Credit Exemption System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
