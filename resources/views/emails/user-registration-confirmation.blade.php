<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1a4f8b; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
        .details { background: white; padding: 20px; border-left: 4px solid #1a4f8b; margin: 20px 0; }
        .programs { background: #e7f3ff; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .badge { display: inline-block; background: #007bff; color: white; padding: 5px 10px; margin: 3px; border-radius: 3px; font-size: 12px; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>UiTM Credit Exemption System</h1>
        </div>

        <div class="content">
            @if($status === 'approved')
                {{-- Auto-approved (Student/External Lecturer) --}}
                <h2>Registration Successful!</h2>

                <p>Dear {{ $user->name }},</p>

                <p>Your account has been created successfully. You can now login to the system.</p>

                <div class="details">
                    <strong>Login Email:</strong> {{ $user->email }}<br>
                    <strong>Role:</strong> {{ ucwords(str_replace('_', ' ', $user->current_role)) }}<br>
                    <strong>Status:</strong> <span style="color: #28a745;">Active</span>
                </div>

                <p><strong>Next Steps:</strong></p>
                <ul>
                    <li>Login at: <a href="{{ url('/login') }}">{{ url('/login') }}</a></li>
                    <li>Complete your profile information</li>
                    <li>Enable Two-Factor Authentication (recommended)</li>
                </ul>

            @elseif($status === 'pending_hea')
                {{-- Pending HEA Approval (AA/PC/RP) --}}
                <h2>Registration Submitted</h2>

                <p>Dear {{ $user->name }},</p>

                <p>Thank you for registering with the UiTM Credit Exemption System.</p>

                <div class="details">
                    <strong>Email:</strong> {{ $user->email }}<br>
                    <strong>Requested Role:</strong> {{ ucwords(str_replace('_', ' ', $user->requested_role)) }}<br>
                    <strong>Status:</strong> <span style="color: #ffc107;">Pending HEA Approval</span>
                </div>

                @if($user->requested_programs)
                    @php
                        $requestedPrograms = json_decode($user->requested_programs);
                    @endphp
                    <div class="programs">
                        <strong>Requested Programs ({{ count($requestedPrograms) }}):</strong><br>
                        <div style="margin-top: 10px;">
                            @foreach($requestedPrograms as $programCode)
                                <span class="badge">{{ $programCode }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="warning">
                    <strong>What Happens Next?</strong>
                    <ul>
                        <li>HEA personnel will review your registration and program requests</li>
                        <li>You will receive an email once your account is reviewed</li>
                        <li>This process typically takes 24-48 hours</li>
                    </ul>
                </div>

                <p>If you have any questions, please contact HEA personnel.</p>

            @elseif($status === 'pending_admin')
                {{-- Pending Admin Approval (HEA) --}}
                <h2>HEA Registration Submitted</h2>

                <p>Dear {{ $user->name }},</p>

                <p>Your HEA personnel registration has been submitted and is pending system administrator approval.</p>

                <div class="details">
                    <strong>Email:</strong> {{ $user->email }}<br>
                    <strong>Requested Role:</strong> HEA Personnel<br>
                    <strong>Status:</strong> <span style="color: #ffc107;">Pending Admin Approval</span>
                </div>

                <div class="warning">
                    <strong>What Happens Next?</strong>
                    <ul>
                        <li>The system administrator will review your HEA registration request</li>
                        <li>You will receive an email once your request is processed</li>
                        <li>This process typically takes 24-48 hours</li>
                    </ul>
                </div>

                <p>If you did not request this registration, please contact the system administrator immediately.</p>

            @endif

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
