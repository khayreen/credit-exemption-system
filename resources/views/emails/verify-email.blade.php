<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            padding: 20px;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #800080 0%, #9932cc 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .email-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);
        }

        .uitm-logo {
            min-width: 120px;
            height: 70px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            padding: 0 20px;
        }

        .uitm-logo-text {
            color: white;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 3px;
        }

        .system-name {
            color: white;
            font-size: 16px;
            font-weight: 500;
            margin-top: 8px;
            opacity: 0.95;
        }

        .email-body {
            padding: 35px 30px;
        }

        .greeting {
            font-size: 15px;
            color: #6b7280;
            margin-bottom: 8px;
            font-style: italic;
        }

        .user-name {
            font-size: 24px;
            color: #800080;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .main-message {
            font-size: 15px;
            color: #374151;
            line-height: 1.7;
            margin-bottom: 12px;
        }

        .highlight {
            color: #800080;
            font-weight: 600;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .verify-button {
            display: inline-block;
            background: linear-gradient(135deg, #800080 0%, #9932cc 100%);
            color: white !important;
            text-decoration: none;
            padding: 16px 45px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 20px rgba(128, 0, 128, 0.3);
            transition: all 0.3s ease;
        }

        .verify-button * {
            color: white !important;
        }

        .verify-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(128, 0, 128, 0.4);
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
            margin: 25px 0;
        }

        .additional-info {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.6;
            margin-top: 20px;
        }

        .alternative-text {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 15px;
            text-align: center;
        }

        .alternative-url {
            font-size: 11px;
            color: #800080;
            word-break: break-all;
            background: #f9fafb;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            margin-top: 8px;
        }

        .email-footer {
            background: #f9fafb;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer-text {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .copyright {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 15px;
        }

        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 25px 20px;
            }

            .verify-button {
                padding: 14px 35px;
                font-size: 15px;
            }

            .user-name {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="uitm-logo">
                <div class="uitm-logo-text">UiTM</div>
            </div>
            <div class="system-name">Credit Exemption System</div>
        </div>

        <!-- Body -->
        <div class="email-body">
            <!-- Greeting -->
            <div class="greeting">
                Assalamualaikum and Salam Sejahtera,
            </div>
            <div class="user-name">{{ $userName }}</div>

            <!-- Main Message -->
            <p class="main-message">
                Thank you for registering with the <span class="highlight">UiTM Credit Exemption Management System</span>.
            </p>

            <p class="main-message">
                To complete your registration and activate your account, please verify your email address by clicking the button below:
            </p>

            <!-- Verify Button -->
            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="verify-button" style="color: white !important; text-decoration: none;">
                    ✓ VERIFY EMAIL ADDRESS
                </a>
            </div>

            <div class="divider"></div>

            <!-- Additional Info -->
            <div class="additional-info">
                <strong>What happens next?</strong><br>
                After verifying your email, you'll be able to log in and set up two-factor authentication (2FA) for enhanced security.
            </div>

            <div class="additional-info" style="margin-top: 15px;">
                <strong>Important:</strong> This verification link will expire in <strong>60 minutes</strong>. If you didn't create an account, please ignore this email.
            </div>

            <!-- Alternative Link -->
            <div class="alternative-text">
                If the button doesn't work, copy and paste this link:
            </div>
            <div class="alternative-url">
                {{ $verificationUrl }}
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-text">
                Need help? Contact us at <strong>support@uitm.edu.my</strong>
            </div>

            <div class="copyright">
                © {{ date('Y') }} Universiti Teknologi MARA (UiTM)<br>
                All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
