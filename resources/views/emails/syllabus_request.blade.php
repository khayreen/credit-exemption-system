<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .course-details {
            background-color: white;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #003366;
        }
        .course-details p {
            margin: 5px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #218838;
        }
        .notes {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        .warning {
            color: #856404;
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Course Syllabus Request</h1>
        <p>UiTM Credit Exemption Management System</p>
    </div>

    <div class="content">
        <p>Dear <strong>{{ $lecturerName }}</strong>,</p>

        <p style="text-align: justify;">I am reaching out to request your kind assistance. My name is {{ $resourcePersonName }}, and I am assisting with the course equivalency evaluation for Universiti Teknologi MARA (UiTM). We are currently reviewing a subject previously offered at your institution and require the complete course syllabus to proceed with our assessment.</p>

        <p style="text-align: justify;">Please refer to the course details below and submit the syllabus using the secure link provided. We greatly appreciate your time and cooperation.</p>

        <div class="course-details">
            <p style="margin: 8px 0;"><strong>{{ $diplomaCourseCode }}</strong></p>
            <p style="margin: 8px 0; font-weight: 600;">{{ $diplomaCourseName }}</p>
            <p style="margin: 8px 0;">{{ number_format($diplomaCreditHours, 2) }} credit hours</p>
            <p style="margin: 8px 0;">{{ $diplomaInstitution }}</p>
            <p style="margin: 8px 0;">{{ $diplomaProgram }}</p>
        </div>

        @if($requestNotes)
        <div class="notes">
            <h4 style="margin-top: 0;">Additional Notes from Resource Person:</h4>
            <p>{{ $requestNotes }}</p>
        </div>
        @endif

        <p>Please click the button below to securely submit the requested syllabus:</p>

        <div style="text-align: center;">
            <a href="{{ $submissionUrl }}" class="button">Submit Course Syllabus</a>
        </div>

        <p>Or copy and paste this link into your browser:</p>
        <p style="background-color: #f0f0f0; padding: 10px; word-break: break-all; font-size: 12px;">
            {{ $submissionUrl }}
        </p>

        @if($tokenExpiresAt)
        <div class="warning">
            <strong>⚠️ Important:</strong> This link will expire on <strong>{{ $tokenExpiresAt->format('d F Y, h:i A') }}</strong>. Please submit the syllabus before this date.
        </div>
        @endif

        <p>If you have any questions or need assistance, please contact our Resource Person team.</p>

        <p>Thank you for your cooperation in helping us evaluate this credit exemption request.</p>

        <p>Best regards,<br>
        <strong>Resource Person Team</strong><br>
        UiTM Credit Exemption Management System</p>
    </div>

    <div class="footer">
        <p>This is an automated email from UiTM Credit Exemption Management System.</p>
        <p>Please do not reply directly to this email.</p>
        <p>&copy; {{ date('Y') }} Universiti Teknologi MARA (UiTM). All rights reserved.</p>
    </div>
</body>
</html>
