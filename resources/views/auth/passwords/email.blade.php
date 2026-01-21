<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - UiTM Credit Exemption System</title>

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

        /* ==================== SPLIT LAYOUT ==================== */
        .page-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ==================== LEFT PANEL - IMAGE ==================== */
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

        /* ==================== RIGHT PANEL - FORM ==================== */
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
            max-width: 400px;
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
            border-radius: 50%;
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
            line-height: 1.6;
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

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label-custom {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--neutral-700);
            margin-bottom: 0.5rem;
        }

        .form-label-custom i {
            margin-right: 0.5rem;
            color: var(--neutral-400);
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            font-size: 0.9375rem;
            font-family: inherit;
            color: var(--neutral-900);
            background: var(--neutral-50);
            border: 2px solid var(--neutral-200);
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--uitm-primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .form-input.is-invalid {
            border-color: var(--uitm-red);
            background: #fef2f2;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.8rem;
            color: var(--uitm-red);
            margin-top: 0.5rem;
            font-weight: 500;
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
        }

        .secondary-link a:hover {
            color: var(--uitm-primary);
        }

        .secondary-link strong {
            color: var(--uitm-primary);
            font-weight: 600;
        }

        /* ==================== ANIMATIONS ==================== */
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

        /* ==================== RESPONSIVE ==================== */
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
                <div class="image-hero-eyebrow">Password Recovery</div>
                <h1 class="image-hero-title">Forgot Your Password?</h1>
                <p class="image-hero-desc">
                    Don't worry, it happens to the best of us. Enter your email address and we'll send you a secure link to reset your password.
                </p>
            </div>

            <div class="image-footer">
                <div class="image-footer-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Secure & Encrypted</span>
                </div>
                <div class="image-footer-badge">
                    <i class="fas fa-clock"></i>
                    <span>Link expires in 60 min</span>
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
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="form-eyebrow">Password Recovery</div>
                    <h1 class="form-title">Reset Your Password</h1>
                    <p class="form-subtitle">Enter your email address and we'll send you a link to reset your password</p>
                </div>

                @if (session('status'))
                    <div class="alert-custom alert-success-custom animate-fade-in-up delay-1">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group animate-fade-in-up delay-1">
                        <label class="form-label-custom" for="email">
                            <i class="fas fa-envelope"></i>{{ __('Email Address') }}
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-input @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            autofocus
                            placeholder="you@uitm.edu.my"
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="animate-fade-in-up delay-2">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('Send Reset Link') }}
                        </button>
                    </div>
                </form>

                <div class="form-divider animate-fade-in-up delay-3">
                    <span class="form-divider-text">Or</span>
                </div>

                <div class="secondary-link animate-fade-in-up delay-3">
                    <a href="{{ route('login') }}">
                        Remember your password? <strong>Sign In</strong>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
