<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email - UiTM Credit Exemption System</title>

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
            max-width: 440px;
            margin: 0 auto;
        }

        .form-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #10b981, #34d399);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.25);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .form-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .form-eyebrow {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #10b981;
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
            line-height: 1.6;
        }

        .success-card {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 2px solid #a7f3d0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .success-card i {
            font-size: 1.25rem;
            color: #065f46;
            margin-bottom: 0.5rem;
        }

        .success-card p {
            margin: 0;
            font-size: 0.9rem;
            color: #065f46;
            font-weight: 500;
        }

        .instructions-box {
            background: var(--neutral-50);
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .instructions-box h6 {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--uitm-primary);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .instructions-box ol {
            margin: 0;
            padding-left: 1.25rem;
            font-size: 0.85rem;
            color: var(--neutral-600);
        }

        .instructions-box li {
            margin-bottom: 0.5rem;
            line-height: 1.5;
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
            margin-bottom: 1rem;
        }

        .btn-submit:hover {
            background: var(--uitm-primary-800);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        .btn-submit:disabled {
            background: var(--neutral-400);
            cursor: not-allowed;
            transform: none;
        }

        .countdown-text {
            text-align: center;
            font-size: 0.8rem;
            color: var(--uitm-primary);
            font-weight: 500;
        }

        .alert-custom {
            border-radius: 8px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success-custom {
            background: #ecfdf5;
            color: #065f46;
            border-left: 3px solid #10b981;
        }

        .form-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
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
        }

        .secondary-link a:hover {
            color: var(--uitm-primary);
        }

        .secondary-link strong {
            color: var(--uitm-primary);
            font-weight: 600;
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
                <div class="image-hero-eyebrow">Registration Complete</div>
                <h1 class="image-hero-title">Check Your Email</h1>
                <p class="image-hero-desc">
                    Your registration was successful. We've sent a verification link to your email address. Please verify to activate your account.
                </p>
            </div>

            <div class="image-footer">
                <div class="image-footer-badge">
                    <i class="fas fa-check-circle"></i>
                    <span>Registration Complete</span>
                </div>
                <div class="image-footer-badge">
                    <i class="fas fa-envelope"></i>
                    <span>Verification Pending</span>
                </div>
            </div>
        </div>

        <!-- Right Panel - Content -->
        <div class="form-panel">
            <div class="form-container">
                <div class="form-header animate-fade-in-up">
                    <div class="form-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="form-eyebrow">Registration Successful</div>
                    <h1 class="form-title">Check Your Email</h1>
                    <p class="form-subtitle">We've sent a verification link to your email address</p>
                </div>

                <div class="success-card animate-fade-in-up delay-1">
                    <i class="fas fa-envelope-open-text"></i>
                    <p><strong>Registration Successful!</strong><br>Please check your email to verify your account.</p>
                </div>

                <div class="instructions-box animate-fade-in-up delay-1">
                    <h6><i class="fas fa-info-circle"></i>Next Steps</h6>
                    <ol>
                        <li>Open your email inbox</li>
                        <li>Look for the verification email from UiTM CES</li>
                        <li>Click the verification link in the email</li>
                        <li>You'll be redirected to the login page</li>
                    </ol>
                </div>

                @if (session('resent'))
                    <div class="alert-custom alert-success-custom animate-fade-in-up delay-2">
                        <i class="fas fa-check-circle"></i>
                        <span>A fresh verification link has been sent to your email!</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.resend') }}" id="resendForm" class="animate-fade-in-up delay-2">
                    @csrf
                    <button type="submit" class="btn-submit" id="resendBtn">
                        <i class="fas fa-paper-plane"></i>
                        Resend Verification Email
                    </button>
                    <p class="countdown-text" id="countdown" style="display: none;">
                        Please wait <span id="timer">60</span> seconds before resending
                    </p>
                </form>

                <div class="form-divider animate-fade-in-up delay-3">
                    <span class="form-divider-text">Or</span>
                </div>

                <div class="secondary-link animate-fade-in-up delay-3">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Already verified? <strong>Login to Your Account</strong>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Resend email cooldown (60 seconds)
    let countdown = 60;
    let resendBtn = document.getElementById('resendBtn');
    let countdownEl = document.getElementById('countdown');
    let timerEl = document.getElementById('timer');

    // Check if resend was just clicked
    if (sessionStorage.getItem('resendClicked')) {
        let lastResend = parseInt(sessionStorage.getItem('resendTimestamp'));
        let now = Date.now();
        let elapsed = Math.floor((now - lastResend) / 1000);

        if (elapsed < 60) {
            countdown = 60 - elapsed;
            startCountdown();
        }
    }

    document.getElementById('resendForm').addEventListener('submit', function(e) {
        if (resendBtn.disabled) {
            e.preventDefault();
            return false;
        }

        // Store timestamp
        sessionStorage.setItem('resendClicked', 'true');
        sessionStorage.setItem('resendTimestamp', Date.now());

        // Start countdown after form submits
        setTimeout(startCountdown, 100);
    });

    function startCountdown() {
        resendBtn.disabled = true;
        countdownEl.style.display = 'block';

        let interval = setInterval(function() {
            countdown--;
            timerEl.textContent = countdown;

            if (countdown <= 0) {
                clearInterval(interval);
                resendBtn.disabled = false;
                countdownEl.style.display = 'none';
                countdown = 60;
                sessionStorage.removeItem('resendClicked');
                sessionStorage.removeItem('resendTimestamp');
            }
        }, 1000);
    }
    </script>
</body>
</html>
