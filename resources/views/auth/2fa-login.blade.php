<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Two-Factor Authentication - UiTM Credit Exemption System</title>

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
            justify-content: center;
            padding: 3rem;
            background: white;
            position: relative;
        }

        .form-container {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .back-link {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--neutral-500);
            text-decoration: none;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .back-link:hover {
            color: var(--uitm-primary);
            background: var(--neutral-100);
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
            margin-bottom: 0.5rem;
        }

        .user-email {
            font-size: 0.875rem;
            color: var(--uitm-primary);
            font-weight: 600;
        }

        .otp-container {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin: 2rem 0;
        }

        .otp-input {
            width: 50px;
            height: 60px;
            border: 2px solid var(--neutral-200);
            border-radius: 10px;
            font-size: 1.5rem;
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
            background: #fef2f2;
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

        .form-help {
            font-size: 0.8rem;
            color: var(--neutral-500);
            text-align: center;
            margin-bottom: 1.5rem;
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
        }

        .btn-submit:hover {
            background: var(--uitm-primary-800);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        .form-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 2rem 0;
        }

        .form-divider::before,
        .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--neutral-200);
        }

        .form-divider-text {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--neutral-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .secondary-link {
            text-align: center;
        }

        .secondary-link a {
            font-size: 0.875rem;
            color: var(--neutral-600);
            text-decoration: none;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .secondary-link a:hover {
            color: var(--uitm-primary);
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
        .delay-3 { animation-delay: 0.3s; opacity: 0; }

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

            .back-link {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 2rem;
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

            .otp-container {
                gap: 8px;
            }

            .otp-input {
                width: 42px;
                height: 52px;
                font-size: 1.25rem;
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
                <div class="image-hero-eyebrow">Security Verification</div>
                <h1 class="image-hero-title">Two-Factor Authentication</h1>
                <p class="image-hero-desc">
                    Your account is protected with two-factor authentication. Enter the 6-digit code from your authenticator app to complete the login.
                </p>
            </div>

            <div class="image-footer">
                <div class="image-footer-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>2FA Protected</span>
                </div>
                <div class="image-footer-badge">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Authenticator App</span>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="form-panel">
            <a href="{{ route('login') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to Login
            </a>

            <div class="form-container">
                <div class="form-header animate-fade-in-up">
                    <div class="form-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="form-eyebrow">Security Check</div>
                    <h1 class="form-title">Verification Code</h1>
                    <p class="form-subtitle">Enter the 6-digit code from your authenticator app</p>
                    <p class="user-email">{{ $email }}</p>
                </div>

                <form method="POST" action="{{ route('2fa.login.verify') }}" id="otpForm" autocomplete="off">
                    @csrf

                    <!-- Hidden input for form submission -->
                    <input type="text"
                           id="one_time_password"
                           name="one_time_password"
                           pattern="[0-9]{6}"
                           autocomplete="off"
                           required>

                    <!-- Visible OTP boxes -->
                    <div class="otp-container animate-fade-in-up delay-1">
                        <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" data-index="0" autofocus>
                        <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" data-index="1">
                        <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" data-index="2">
                        <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" data-index="3">
                        <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" data-index="4">
                        <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" data-index="5">
                    </div>

                    @error('one_time_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <p class="form-help animate-fade-in-up delay-1">Enter the 6-digit code from your authenticator app</p>

                    <div class="animate-fade-in-up delay-2">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-sign-in-alt"></i>
                            Verify and Login
                        </button>
                    </div>
                </form>

                <div class="form-divider animate-fade-in-up delay-3">
                    <span class="form-divider-text">Or</span>
                </div>

                <div class="secondary-link animate-fade-in-up delay-3">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left"></i>
                        Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // OTP Input Handler
    const otpInputs = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('one_time_password');
    const otpForm = document.getElementById('otpForm');

    otpInputs.forEach((input, index) => {
        // Handle input event
        input.addEventListener('input', function(e) {
            // Only allow numbers
            this.value = this.value.replace(/[^0-9]/g, '');

            // Auto-focus to next input
            if (this.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }

            // Update hidden input with concatenated value
            updateHiddenInput();
        });

        // Handle backspace
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                otpInputs[index - 1].focus();
            }
            // Handle Enter key to submit form
            if (e.key === 'Enter') {
                e.preventDefault();
                updateHiddenInput();
                otpForm.submit();
            }
        });

        // Handle paste event
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');

            // Distribute pasted digits across all inputs
            for (let i = 0; i < pastedData.length && index + i < otpInputs.length; i++) {
                otpInputs[index + i].value = pastedData[i];
            }

            // Focus the next empty input or the last one
            const nextIndex = Math.min(index + pastedData.length, otpInputs.length - 1);
            otpInputs[nextIndex].focus();

            // Update hidden input
            updateHiddenInput();
        });
    });

    // Function to update hidden input with all OTP values
    function updateHiddenInput() {
        const otpValue = Array.from(otpInputs).map(input => input.value).join('');
        hiddenInput.value = otpValue;
    }

    // CRITICAL: Update hidden input BEFORE form submission
    otpForm.addEventListener('submit', function(e) {
        updateHiddenInput();

        // Validate that we have 6 digits
        if (hiddenInput.value.length !== 6) {
            e.preventDefault();
            alert('Please enter all 6 digits of your verification code.');
            return false;
        }
    });

    // Also update on page load in case of browser autofill
    document.addEventListener('DOMContentLoaded', function() {
        updateHiddenInput();
    });
    </script>
</body>
</html>
