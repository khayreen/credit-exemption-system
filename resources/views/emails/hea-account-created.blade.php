<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1a4f8b; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
        .credentials { background: white; padding: 20px; border-left: 4px solid #1a4f8b; margin: 20px 0; }
        .credential-row { margin: 10px 0; }
        .credential-label { font-weight: bold; color: #1a4f8b; }
        .credential-value { font-family: monospace; background: #f0f0f0; padding: 5px 10px; display: inline-block; }
        .button { display: inline-block; padding: 12px 30px; background: #1a4f8b; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>UiTM Credit Exemption System</h1>
            <p>Higher Education Authority (HEA) Portal</p>
        </div>

        <div class="content">
            <h2>Welcome to UiTM Credit Exemption System!</h2>

            <p>Dear {{ $user->name }},</p>

            <p>Your HEA Personnel account has been created by the system administrator. You now have full access to the Credit Exemption Management System.</p>

            <div class="credentials">
                <h3>🔐 Your Login Credentials</h3>

                <div class="credential-row">
                    <span class="credential-label">Email:</span><br>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>

                <div class="credential-row">
                    <span class="credential-label">Temporary Password:</span><br>
                    <span class="credential-value">{{ $password }}</span>
                </div>

                <div class="credential-row">
                    <span class="credential-label">Login URL:</span><br>
                    <a href="{{ url('/login') }}">{{ url('/login') }}</a>
                </div>
            </div>

            <div class="warning">
                <strong>⚠️ IMPORTANT SECURITY NOTICE:</strong>
                <ul>
                    <li>Please change your password immediately after first login</li>
                    <li>Do not share your credentials with anyone</li>
                    <li>Enable Two-Factor Authentication (2FA) in your profile settings</li>
                    <li>This is a temporary password - change it within 24 hours</li>
                </ul>
            </div>

            <center>
                <a href="{{ url('/login') }}" class="button">Login to Your Account</a>
            </center>

            <h3>🎯 Your Responsibilities as HEA Personnel:</h3>
            <ul>
                <li>Review and approve user registrations (Academic Advisors, Coordinators, Resource Persons)</li>
                <li>Assign programs to approved users</li>
                <li>Manage system-wide equivalency lists</li>
                <li>Monitor application workflows and user activities</li>
                <li>Invite additional HEA personnel when needed</li>
            </ul>

            <p>If you did not expect this account or have any questions, please contact the system administrator immediately.</p>

            <p>Best regards,<br>
            UiTM Credit Exemption System<br>
            System Administrator</p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} UiTM Credit Exemption System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
