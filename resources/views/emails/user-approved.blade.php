<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
        .details { background: white; padding: 20px; border-left: 4px solid #28a745; margin: 20px 0; }
        .programs { background: #e7f3ff; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .badge { display: inline-block; background: #007bff; color: white; padding: 5px 10px; margin: 3px; border-radius: 3px; font-size: 12px; }
        .badge-warning { background: #ffc107; color: #000; }
        .button { display: inline-block; padding: 12px 30px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Account Approved!</h1>
        </div>

        <div class="content">
            <p>Dear {{ $user->name }},</p>

            @if($role === 'hea_personnel')
                <p>Congratulations! Your HEA Personnel account has been approved by the system administrator.</p>
            @else
                <p>Congratulations! Your account has been approved by HEA personnel.</p>
            @endif

            <div class="details">
                <h3>📋 Account Details</h3>
                <strong>Role:</strong> {{ ucwords(str_replace('_', ' ', $role)) }}<br>
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Status:</strong> <span style="color: #28a745;">Active</span>
            </div>

            @if(!empty($programs))
            <div class="programs">
                <h3>📚 Assigned Programs ({{ count($programs) }})</h3>
                <p>You have been assigned to manage the following programs:</p>
                @foreach($programs as $programCode)
                    <span class="badge">{{ $programCode }}</span>
                @endforeach

                @php
                    $requestedPrograms = json_decode($user->requested_programs, true) ?? [];
                    $addedPrograms = array_diff($programs, $requestedPrograms);
                    $removedPrograms = array_diff($requestedPrograms, $programs);
                @endphp

                @if(count($addedPrograms) > 0)
                    <p class="mt-3"><strong>Additional programs assigned by HEA:</strong><br>
                    @foreach($addedPrograms as $prog)
                        <span class="badge badge-warning">{{ $prog }} (added)</span>
                    @endforeach
                    </p>
                @endif

                @if(count($removedPrograms) > 0)
                    <p class="mt-3"><strong>Programs removed by HEA:</strong><br>
                    <small class="text-muted">
                    @foreach($removedPrograms as $prog)
                        {{ $prog }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                    </small>
                    </p>
                @endif
            </div>
            @endif

            <center>
                <a href="{{ url('/login') }}" class="button">Login to Your Account</a>
            </center>

            <h3>🎯 What's Next?</h3>
            <ul>
                <li>Login to access your dashboard</li>
                @if(!empty($programs))
                <li>Review pending applications in your assigned programs</li>
                @endif
                <li>Complete your profile information</li>
                <li>Enable Two-Factor Authentication (recommended)</li>
            </ul>

            <p>If you have any questions, please contact HEA personnel.</p>

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
