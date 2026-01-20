<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #0d6efd; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; }
        .details { background: white; padding: 20px; border-left: 4px solid #0d6efd; margin: 20px 0; }
        .details strong { display: inline-block; width: 150px; }
        .button { display: inline-block; padding: 12px 30px; background: #0d6efd; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .button:hover { background: #0b5ed7; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }
        .badge { display: inline-block; background: #ffc107; color: #000; padding: 3px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Staff Registration</h1>
        </div>

        <div class="content">
            <p>Hello {{ $heaUser->name }},</p>

            <p>A new staff member has registered and requires your approval.</p>

            <div class="details">
                <h3>Applicant Details</h3>
                <p><strong>Name:</strong> {{ $registeredUser->name }}</p>
                <p><strong>Email:</strong> {{ $registeredUser->email }}</p>
                <p><strong>Requested Role:</strong>
                    <span class="badge">{{ $roleName }}</span>
                </p>
                <p><strong>Program Assignment:</strong> {{ $programInfo }}</p>
                <p><strong>Submitted:</strong> {{ $registeredUser->created_at->format('d M Y, h:i A') }}</p>
            </div>

            <center>
                <a href="{{ url('/hea/users/pending') }}" class="button">Review Pending Registrations</a>
            </center>

            <p>Please review this registration at your earliest convenience.</p>

            <p>Best regards,<br>
            Credit Exemption System</p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} UiTM Credit Exemption System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
