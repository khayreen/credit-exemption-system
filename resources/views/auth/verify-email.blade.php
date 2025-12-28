<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email - UiTM Credit Exemption System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --uitm-primary: #1e3a8a;
            --uitm-secondary: #3b82f6;
            --uitm-accent: #f59e0b;
            --uitm-dark: #1f2937;
            --uitm-light: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                        url('https://malaysiabangkit.com/wp-content/uploads/2024/11/UITM-pelajar.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verify-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 500px;
            padding: 1rem;
        }

        .verify-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            transition: all 0.3s ease;
        }

        .verify-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.15);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-secondary));
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: 700;
            box-shadow: 0 16px 32px rgba(30, 58, 138, 0.3);
            margin-bottom: 1.5rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .verify-title {
            color: var(--uitm-dark);
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .verify-subtitle {
            color: #6b7280;
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border: none;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-success i {
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        .info-box {
            background: rgba(59, 130, 246, 0.05);
            border: 2px solid rgba(59, 130, 246, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-box h6 {
            color: var(--uitm-primary);
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .info-box ol {
            margin: 0;
            padding-left: 1.5rem;
            color: #6b7280;
        }

        .info-box li {
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }

        .btn-resend {
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-secondary));
            color: white;
            border: none;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(30, 58, 138, 0.3);
            margin-bottom: 1rem;
        }

        .btn-resend:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(30, 58, 138, 0.4);
        }

        .btn-resend:disabled {
            background: linear-gradient(135deg, #9ca3af, #6b7280);
            cursor: not-allowed;
            transform: none;
        }

        .back-link {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .back-link a {
            color: var(--uitm-secondary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-link a:hover {
            color: var(--uitm-primary);
            text-decoration: underline;
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .floating-circle:nth-child(1) {
            width: 60px;
            height: 60px;
            top: 15%;
            left: 15%;
            animation-delay: 0s;
        }

        .floating-circle:nth-child(2) {
            width: 100px;
            height: 100px;
            top: 70%;
            right: 10%;
            animation-delay: 2s;
        }

        .floating-circle:nth-child(3) {
            width: 40px;
            height: 40px;
            bottom: 25%;
            left: 25%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
        }

        .countdown {
            color: var(--uitm-secondary);
            font-weight: 600;
            font-size: 0.9rem;
        }

        @media (max-width: 576px) {
            .verify-card {
                padding: 2rem;
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <!-- Verify Email Container -->
    <div class="verify-container">
        <div class="verify-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo">
                    <i class="fas fa-envelope"></i>
                </div>
                <h1 class="verify-title">Check Your Email</h1>
                <p class="verify-subtitle">We've sent a verification link to your email address</p>
            </div>

            <!-- Success Alert -->
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                <strong>Registration Successful!</strong><br>
                Please check your email to verify your account.
            </div>

            <!-- Instructions Box -->
            <div class="info-box">
                <h6><i class="fas fa-info-circle me-2"></i>Next Steps:</h6>
                <ol>
                    <li>Open your email inbox</li>
                    <li>Look for the verification email from UiTM CES</li>
                    <li>Click the verification link in the email</li>
                    <li>You'll be redirected to the login page</li>
                </ol>
            </div>

            <!-- Resend Email Form -->
            <form method="POST" action="{{ route('verification.resend') }}" id="resendForm">
                @csrf
                <button type="submit" class="btn btn-resend" id="resendBtn">
                    <i class="fas fa-paper-plane me-2"></i>Resend Verification Email
                </button>
                <p class="text-center countdown" id="countdown" style="display: none;">
                    Please wait <span id="timer">60</span> seconds before resending
                </p>
            </form>

            @if (session('resent'))
                <div class="alert alert-success text-center mb-3" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; border: none; border-radius: 12px; padding: 1rem; font-size: 0.9rem;">
                    <i class="fas fa-check-circle me-2"></i>
                    A fresh verification link has been sent to your email!
                </div>
            @endif

            <!-- Back to Login Link -->
            <div class="back-link">
                <span class="text-muted">Already verified?</span>
                <a href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt me-1"></i>Login to Your Account
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
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
