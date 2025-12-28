<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Course Equivalency List Available</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            min-width: 140px;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .stats-container {
            display: flex;
            justify-content: space-around;
            margin: 25px 0;
            text-align: center;
        }
        .stat-box {
            flex: 1;
            padding: 15px;
            margin: 0 5px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
            font-weight: bold;
            transition: transform 0.2s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .cta-secondary {
            background: #6c757d;
        }
        .note-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .note-box h4 {
            margin-top: 0;
            color: #856404;
        }
        .note-box ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .note-box li {
            margin: 5px 0;
            color: #856404;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #666;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
        }
        @media only screen and (max-width: 600px) {
            .stats-container {
                flex-direction: column;
            }
            .stat-box {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎓 New Course Equivalency List Published</h1>
            <p>Credit Exemption System - UiTM</p>
        </div>

        <div class="content">
            @if($student)
            <p>Dear {{ $student->user->name }},</p>
            @else
            <p>Dear Student,</p>
            @endif

            <p>Great news! A new course equivalency list has been published for your program. This list shows which diploma courses can be credited towards your degree.</p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Category:</span>
                    <span class="info-value">
                        @if($list->isInternal())
                            🏠 CS110 (UiTM Diploma in Computer Science)
                        @else
                            🌐 {{ $list->source_institution }}
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Target Program:</span>
                    <span class="info-value">{{ $list->program_code }} - {{ $list->program_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Semester:</span>
                    <span class="info-value">{{ $list->semester }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Published:</span>
                    <span class="info-value">{{ $list->published_at->format('d M Y') }}</span>
                </div>
            </div>

            <div class="stats-container">
                <div class="stat-box">
                    <div class="stat-number">{{ $list->total_equivalencies }}</div>
                    <div class="stat-label">Total Mappings</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number" style="color: #28a745;">{{ $list->eligible_count }}</div>
                    <div class="stat-label">Eligible (≥80%)</div>
                </div>
            </div>

            @if($list->isInternal())
            <div class="note-box">
                <h4>⭐ CS110 Students Advantage</h4>
                <p><strong>As a CS110 diploma holder, you typically qualify for the most credit exemptions</strong> due to high curriculum similarity with {{ $list->program_code }}.</p>
            </div>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $viewUrl }}" class="cta-button">
                    📋 View Equivalency List
                </a>
                <a href="{{ $applyUrl }}" class="cta-button cta-secondary">
                    📝 Apply for Credit Exemption
                </a>
            </div>

            <div class="note-box">
                <h4>📌 Important Reminders</h4>
                <ul>
                    <li><strong>Eligibility requires BOTH:</strong> Course match ≥80% AND your grade must be C or above</li>
                    <li>Review the list to see which of your diploma courses qualify</li>
                    <li>Prepare your official transcript before applying</li>
                    <li>Contact your academic advisor if you have questions</li>
                </ul>
            </div>

            <p style="margin-top: 30px;">This equivalency list is valid for <strong>{{ $list->semester }}</strong> semester.</p>

            <p>Best regards,<br>
            <strong>UiTM Credit Exemption System</strong><br>
            Higher Education Affairs Unit</p>
        </div>

        <div class="footer">
            <p><strong>UiTM Credit Exemption System</strong></p>
            <p>This is an automated notification. Please do not reply to this email.</p>
            <p>For assistance, contact your academic advisor or visit the HEA office.</p>
        </div>
    </div>
</body>
</html>
