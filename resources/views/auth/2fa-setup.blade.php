<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>2FA Setup - UiTM Credit Exemption System</title>

    <!-- Fonts - IBM Plex Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --uitm-primary: #1e3a8a;
            --uitm-primary-800: #1e293b;
            --uitm-red: #dc2626;
            --uitm-amber: #f59e0b;
            --neutral-900: #171717;
            --neutral-700: #404040;
            --neutral-500: #737373;
            --neutral-400: #a3a3a3;
            --neutral-200: #e5e5e5;
            --neutral-100: #f5f5f5;
            --neutral-50: #fafafa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--neutral-50);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .page-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        .image-panel {
            position: relative;
            background: url('https://malaysiabangkit.com/wp-content/uploads/2024/11/UITM-pelajar.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem;
            overflow: hidden;
        }

        .image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.92) 0%, rgba(23, 23, 23, 0.88) 100%);
            z-index: 1;
        }

        .image-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.07;
            background-image:
                linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 2;
        }

        .image-content {
            position: relative;
            z-index: 3;
            color: white;
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            background: var(--uitm-amber);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--neutral-900);
        }

        .brand-text {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .brand-text .accent {
            color: var(--uitm-red);
        }

        .image-hero {
            position: relative;
            z-index: 3;
            max-width: 480px;
        }

        .image-hero-eyebrow {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--uitm-amber);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .image-hero-eyebrow::before {
            content: '';
            width: 24px;
            height: 2px;
            background: var(--uitm-amber);
        }

        .image-hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 1.25rem;
            color: white;
        }

        .image-hero-desc {
            font-size: 1rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.85);
        }

        .image-footer {
            position: relative;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .image-footer-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .image-footer-badge i {
            color: var(--uitm-amber);
        }

        .form-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 3rem;
            background: white;
            position: relative;
            overflow-y: auto;
        }

        .form-container {
            width: 100%;
            max-width: 480px;
        }

        .form-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--uitm-primary), #3b82f6);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 24px rgba(30, 58, 138, 0.25);
        }

        .form-icon i {
            font-size: 2rem;
            color: white;
        }

        .form-eyebrow {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--uitm-primary);
            margin-bottom: 0.75rem;
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--neutral-900);
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            font-size: 0.9rem;
            color: var(--neutral-500);
        }

        .alert-info-custom {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.05), rgba(59, 130, 246, 0.05));
            border: 2px solid rgba(30, 58, 138, 0.1);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: var(--uitm-primary);
        }

        .alert-info-custom strong {
            font-weight: 600;
        }

        .step-section {
            margin-bottom: 2rem;
        }

        .step-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .step-badge {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--uitm-primary), #3b82f6);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .step-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--uitm-primary);
            margin: 0;
        }

        .step-content {
            margin-left: 52px;
        }

        .step-content p {
            font-size: 0.85rem;
            color: var(--neutral-600);
            margin-bottom: 1rem;
        }

        .auth-apps {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .auth-app-card {
            background: var(--neutral-50);
            border: 2px solid var(--neutral-200);
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            transition: all 0.2s ease;
        }

        .auth-app-card:hover {
            border-color: var(--uitm-primary);
            background: rgba(30, 58, 138, 0.02);
        }

        .auth-app-card h6 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--neutral-900);
            margin-bottom: 0.25rem;
        }

        .auth-app-card small {
            font-size: 0.75rem;
            color: var(--neutral-500);
        }

        .qr-section {
            background: var(--neutral-50);
            border: 2px solid var(--neutral-200);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }

        .qr-section img {
            max-width: 180px;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .manual-setup {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(251, 191, 36, 0.05));
            border: 2px solid rgba(245, 158, 11, 0.2);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .manual-setup h6 {
            font-size: 0.8rem;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 0.5rem;
        }

        .manual-setup p {
            font-size: 0.8rem;
            color: #92400e;
            margin-bottom: 0.5rem;
        }

        .secret-key-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin: 0.75rem 0;
        }

        .secret-key {
            background: white;
            border: 2px solid rgba(245, 158, 11, 0.3);
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--neutral-900);
            letter-spacing: 1px;
        }

        .btn-copy {
            background: var(--uitm-amber);
            color: white;
            border: none;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-copy:hover {
            background: #d97706;
        }

        .manual-setup small {
            font-size: 0.75rem;
            color: #92400e;
        }

        .otp-container {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 1rem 0;
        }

        .otp-input {
            width: 45px;
            height: 55px;
            border: 2px solid var(--neutral-200);
            border-radius: 8px;
            font-size: 1.25rem;
            font-weight: 700;
            font-family: 'IBM Plex Mono', monospace;
            text-align: center;
            transition: all 0.2s ease;
            background: var(--neutral-50);
        }

        .otp-input:focus {
            border-color: var(--uitm-primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
            outline: none;
        }

        .otp-input.is-invalid {
            border-color: var(--uitm-red);
        }

        #one_time_password {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.8rem;
            color: var(--uitm-red);
            text-align: center;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .form-text {
            font-size: 0.8rem;
            color: var(--neutral-500);
            text-align: center;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            letter-spacing: 0.025em;
            text-transform: uppercase;
            color: white;
            background: var(--uitm-primary);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }

        .btn-submit:hover {
            background: var(--uitm-primary-800);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }

        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }

        @media (max-width: 1024px) {
            .page-wrapper {
                grid-template-columns: 1fr;
            }

            .image-panel {
                display: none;
            }

            .form-panel {
                min-height: 100vh;
                padding: 2rem;
            }
        }

        @media (max-width: 576px) {
            .form-panel {
                padding: 1.5rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .form-container {
                max-width: 100%;
            }

            .auth-apps {
                grid-template-columns: 1fr;
            }

            .otp-container {
                gap: 6px;
            }

            .otp-input {
                width: 38px;
                height: 48px;
                font-size: 1rem;
            }

            .step-content {
                margin-left: 0;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <!-- Left Panel - Image -->
        <div class="image-panel">
            <div class="image-overlay"></div>
            <div class="image-pattern"></div>

            <div class="image-content">
                <div class="brand-header">
                    <div class="brand-logo">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="brand-text">
                        UiTM<span class="accent">CES</span>
                    </div>
                </div>
            </div>

            <div class="image-hero">
                <div class="image-hero-eyebrow">Account Security</div>
                <h1 class="image-hero-title">Set Up 2FA</h1>
                <p class="image-hero-desc">
                    Two-factor authentication adds an extra layer of security to your account. You'll need to enter a code from your authenticator app every time you log in.
                </p>
            </div>

            <div class="image-footer">
                <div class="image-footer-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Enhanced Security</span>
                </div>
                <div class="image-footer-badge">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Authenticator App</span>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="form-panel">
            <div class="form-container">
                <div class="form-header animate-fade-in-up">
                    <div class="form-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="form-eyebrow">Account Security</div>
                    <h1 class="form-title">Set Up 2FA</h1>
                    <p class="form-subtitle">Secure your account with two-factor authentication</p>
                </div>

                <div class="alert-info-custom animate-fade-in-up delay-1">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Important:</strong> Two-factor authentication adds an extra layer of security. You'll need to enter a code from your authenticator app every time you log in.
                </div>

                <form method="POST" action="{{ route('2fa.setup.verify') }}" id="otpForm" autocomplete="off">
                    @csrf

                    <!-- Step 1: Install App -->
                    <div class="step-section animate-fade-in-up delay-1">
                        <div class="step-header">
                            <div class="step-badge">1</div>
                            <h5 class="step-title">Install Authenticator App</h5>
                        </div>
                        <div class="step-content">
                            <p>Download one of these authenticator apps on your mobile device:</p>
                            <div class="auth-apps">
                                <div class="auth-app-card">
                                    <h6><i class="fab fa-google me-1"></i>Google Authenticator</h6>
                                    <small>iOS & Android</small>
                                </div>
                                <div class="auth-app-card">
                                    <h6><i class="fas fa-key me-1"></i>Microsoft Authenticator</h6>
                                    <small>iOS & Android</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Scan QR Code -->
                    <div class="step-section animate-fade-in-up delay-1">
                        <div class="step-header">
                            <div class="step-badge">2</div>
                            <h5 class="step-title">Scan QR Code</h5>
                        </div>
                        <div class="step-content">
                            <p>Open your authenticator app and scan this QR code:</p>
                            <div class="qr-section">
                                <img src="{{ $qrCodeUrl }}" alt="QR Code">
                            </div>

                            <div class="manual-setup">
                                <h6><i class="fas fa-keyboard me-1"></i>Can't scan? Manual Setup:</h6>
                                <p>In your authenticator app, choose "Enter a setup key" and use:</p>
                                <div class="secret-key-container">
                                    <code id="secretKey" class="secret-key">{{ $secretKey }}</code>
                                    <button type="button" class="btn-copy" onclick="copySecretKey()">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                <small>Account: {{ $userEmail }} | Type: Time based</small>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Verify Code -->
                    <div class="step-section animate-fade-in-up delay-2">
                        <div class="step-header">
                            <div class="step-badge">3</div>
                            <h5 class="step-title">Verify Code</h5>
                        </div>
                        <div class="step-content">
                            <p>Enter the 6-digit code from your authenticator app:</p>

                            <!-- Hidden input for form submission -->
                            <input type="text"
                                   id="one_time_password"
                                   name="one_time_password"
                                   pattern="[0-9]{6}"
                                   autocomplete="off"
                                   required>

                            <!-- Visible OTP boxes -->
                            <div class="otp-container">
                                <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" data-index="0">
                                <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" data-index="1">
                                <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" data-index="2">
                                <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" data-index="3">
                                <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" data-index="4">
                                <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" data-index="5">
                            </div>

                            @error('one_time_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text">Enter the 6-digit code from your authenticator app</small>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit animate-fade-in-up delay-2">
                        <i class="fas fa-check-circle"></i>
                        Enable Two-Factor Authentication
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function copySecretKey() {
        const secretKey = document.getElementById('secretKey').textContent;
        navigator.clipboard.writeText(secretKey).then(() => {
            alert('Secret key copied to clipboard!');
        }).catch(err => {
            const textArea = document.createElement('textarea');
            textArea.value = secretKey;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Secret key copied to clipboard!');
        });
    }

    // OTP Input Handler
    const otpInputs = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('one_time_password');
    const otpForm = document.getElementById('otpForm');

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
            updateHiddenInput();
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                otpInputs[index - 1].focus();
            }
            if (e.key === 'Enter') {
                e.preventDefault();
                updateHiddenInput();
                otpForm.submit();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
            for (let i = 0; i < pastedData.length && index + i < otpInputs.length; i++) {
                otpInputs[index + i].value = pastedData[i];
            }
            const nextIndex = Math.min(index + pastedData.length, otpInputs.length - 1);
            otpInputs[nextIndex].focus();
            updateHiddenInput();
        });
    });

    function updateHiddenInput() {
        const otpValue = Array.from(otpInputs).map(input => input.value).join('');
        hiddenInput.value = otpValue;
    }

    otpForm.addEventListener('submit', function(e) {
        updateHiddenInput();
        if (hiddenInput.value.length !== 6) {
            e.preventDefault();
            alert('Please enter all 6 digits of your verification code.');
            return false;
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        updateHiddenInput();
    });
    </script>
</body>
</html>
