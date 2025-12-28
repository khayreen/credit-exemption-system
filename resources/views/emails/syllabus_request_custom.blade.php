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
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #003366 0%, #004d99 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .custom-message {
            background-color: #f9f9f9;
            padding: 20px;
            border-left: 4px solid #003366;
            margin: 20px 0;
            white-space: pre-wrap;
            line-height: 1.8;
        }
        .info-section {
            margin: 25px 0;
        }
        .info-section h3 {
            color: #003366;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #003366;
            font-size: 18px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #666;
            padding: 8px 15px 8px 0;
            width: 40%;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            padding: 8px 0;
            color: #333;
            vertical-align: top;
        }
        .justification-box {
            background-color: #fff8e1;
            border: 1px solid #ffd54f;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .justification-box h4 {
            margin: 0 0 10px 0;
            color: #f57c00;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f0f7ff;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background-color: #28a745;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 2px 5px rgba(40, 167, 69, 0.3);
        }
        .button:hover {
            background-color: #218838;
        }
        .url-box {
            background-color: #f0f0f0;
            padding: 12px;
            margin: 15px 0;
            word-break: break-all;
            font-size: 12px;
            border-radius: 4px;
            font-family: monospace;
        }
        .warning-box {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
        .warning-box strong {
            color: #856404;
            font-size: 16px;
        }
        .footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
            border-top: 1px solid #dee2e6;
            color: #666;
            font-size: 12px;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📚 Course Syllabus Request</h1>
            <p>UiTM Credit Exemption Management System</p>
        </div>

        <div class="content">
            <p style="font-size: 16px;">{{ $greeting }}</p>

            <div class="custom-message">
                {{ $customMessage }}
            </div>

            <!-- Diploma Course Information -->
            <div class="info-section">
                <h3>📖 Diploma Course Information</h3>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Course Code:</div>
                        <div class="info-value"><strong style="color: #003366; font-size: 16px;">{{ $diplomaCourseCode }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Course Name:</div>
                        <div class="info-value"><strong>{{ $diplomaCourseName }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Credit Hours:</div>
                        <div class="info-value">{{ $diplomaCreditHours }} credit hours</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Institution:</div>
                        <div class="info-value">{{ $diplomaInstitution }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Program:</div>
                        <div class="info-value">{{ $diplomaProgram }}</div>
                    </div>
                </div>
            </div>

            <!-- Submission Button -->
            <div class="button-container">
                <p style="margin: 0 0 15px 0; font-size: 16px;"><strong>Click the button below to securely submit the syllabus:</strong></p>
                <a href="{{ $submissionUrl }}" class="button">📤 Submit Course Syllabus</a>
            </div>

            <p style="text-align: center; margin: 15px 0; color: #666;">Or copy and paste this secure link into your browser:</p>
            <div class="url-box">{{ $submissionUrl }}</div>

            <!-- Expiration Warning -->
            @if($tokenExpiresAt)
            <div class="warning-box">
                <strong>⚠️ Important:</strong> This submission link will expire on <strong>{{ $tokenExpiresAt->format('l, d F Y \a\t h:i A') }}</strong>.<br>
                <span style="font-size: 14px;">Please submit the syllabus before this date to avoid any delays.</span>
            </div>
            @endif

            <!-- Contact Information -->
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
                <p><strong>Need Assistance?</strong></p>
                <p>If you have any questions or require clarification, please contact our Resource Person team at the UiTM Credit Exemption Management System.</p>
            </div>

            <p style="margin-top: 30px;">Thank you for your cooperation and prompt response in helping us evaluate this credit exemption request.</p>

            <p style="margin-top: 20px;">
                Best regards,<br>
                <strong>Resource Person Team</strong><br>
                <span style="color: #666;">UiTM Credit Exemption Management System</span><br>
                <span style="color: #666;">Universiti Teknologi MARA</span>
            </p>
        </div>

        <div class="footer">
            <p><strong>This is an automated email from UiTM Credit Exemption Management System.</strong></p>
            <p>Please do not reply directly to this email. Use the submission link above to provide the requested documents.</p>
            <p style="margin-top: 15px;">&copy; {{ date('Y') }} Universiti Teknologi MARA (UiTM). All rights reserved.</p>
        </div>
    </div>
</body>
</html>
