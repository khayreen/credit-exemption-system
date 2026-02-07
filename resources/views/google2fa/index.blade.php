<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>2FA Setup - UiTM Credit Exemption System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --uitm-blue: #1e3a8a;
            --uitm-blue-light: #3b82f6;
            --uitm-amber: #f59e0b;
            --industrial-dark: #0f172a;
            --industrial-gray: #334155;
            --industrial-light: #f1f5f9;
            --success-color: #059669;
            --danger-color: #dc2626;
            --warning-color: #ea580c;
            --info-color: #0d9488;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background: linear-gradient(135deg, var(--industrial-dark) 0%, var(--uitm-blue) 50%, var(--industrial-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .setup-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 520px;
        }

        .setup-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-blue-light), var(--uitm-amber));
        }

        .logo {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.75rem;
            margin-bottom: 1rem;
            backdrop-filter: blur(10px);
        }

        .setup-title {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            letter-spacing: -0.025em;
        }

        .setup-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .card-body {
            padding: 2rem;
        }

        .setup-description {
            color: var(--industrial-gray);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* Steps Section */
        .steps-container {
            background: var(--industrial-light);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .steps-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--industrial-gray);
            margin-bottom: 1rem;
        }

        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.875rem;
        }

        .step:last-child {
            margin-bottom: 0;
        }

        .step-number {
            background: var(--uitm-blue);
            color: #fff;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8rem;
            font-family: 'IBM Plex Mono', monospace;
            margin-right: 0.875rem;
            flex-shrink: 0;
        }

        .step-text {
            color: var(--industrial-dark);
            font-size: 0.9rem;
            font-weight: 500;
            line-height: 1.5;
            padding-top: 0.125rem;
        }

        /* QR Code Section */
        .qr-section {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .qr-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--industrial-gray);
            margin-bottom: 1rem;
        }

        .qr-container {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 2px solid #e2e8f0;
            display: inline-block;
        }

        .qr-container img,
        .qr-container svg {
            max-width: 200px;
            max-height: 200px;
            width: 100%;
            height: auto;
            display: block;
        }

        /* Manual Code Section */
        .manual-code-section {
            background: var(--industrial-light);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .manual-code-label {
            font-size: 0.8rem;
            color: var(--industrial-gray);
            margin-bottom: 0.75rem;
        }

        .secret-code {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 1rem;
            font-weight: 600;
            color: var(--uitm-blue);
            background: #fff;
            padding: 0.625rem 1rem;
            border-radius: 8px;
            display: inline-block;
            letter-spacing: 0.1em;
            border: 1px solid #e2e8f0;
        }

        /* Warning Message */
        .warning-message {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-left: 4px solid var(--uitm-amber);
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .warning-message i {
            color: var(--uitm-amber);
            font-size: 1.1rem;
            margin-top: 0.125rem;
        }

        .warning-message-content {
            font-size: 0.875rem;
            color: #92400e;
            line-height: 1.5;
        }

        .warning-message-content strong {
            font-weight: 600;
        }

        /* Continue Button */
        .btn-continue {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
            color: #fff;
            border: none;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px 0 rgba(30, 58, 138, 0.3);
        }

        .btn-continue:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px 0 rgba(30, 58, 138, 0.4);
        }

        .btn-continue:active {
            transform: translateY(0);
        }

        @media (max-width: 576px) {
            body {
                padding: 1rem;
            }

            .card-header {
                padding: 1.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .qr-container {
                padding: 1rem;
            }

            .secret-code {
                font-size: 0.85rem;
                padding: 0.5rem 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-card">
            <!-- Card Header -->
            <div class="card-header">
                <div class="logo">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="setup-title">Set Up 2FA Security</h1>
                <p class="setup-subtitle">Two-Factor Authentication Setup</p>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <!-- Description -->
                <p class="setup-description">
                    Secure your account by setting up two-factor authentication.
                    Scan the QR code below with your authenticator app.
                </p>

                <!-- Steps -->
                <div class="steps-container">
                    <div class="steps-title">Setup Instructions</div>
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-text">Download Google Authenticator or any TOTP app on your mobile device</div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-text">Scan the QR code below or enter the secret code manually</div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-text">Enter the 6-digit code from your app to verify setup</div>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="qr-section">
                    <div class="qr-label">Scan QR Code</div>
                    <div class="qr-container">
                        {!! $qrCodeUrl !!}
                    </div>
                </div>

                <!-- Manual Entry Code -->
                <div class="manual-code-section">
                    <p class="manual-code-label">Or enter this code manually:</p>
                    <code class="secret-code">{{ $secret }}</code>
                </div>

                <!-- Warning Message -->
                <div class="warning-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="warning-message-content">
                        <strong>Important:</strong> You must complete 2FA setup before continuing.
                        Save the secret code in a secure location as a backup.
                    </div>
                </div>

                <!-- Continue Button -->
                <a href="{{ route('2fa.verify') }}" class="btn-continue">
                    Continue to Verification
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
