<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - UiTM Credit Exemption System</title>

    <!-- Fonts - IBM Plex Sans & Mono -->
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
            --uitm-primary-700: #334155;
            --uitm-red: #dc2626;
            --uitm-red-dark: #b91c1c;
            --uitm-amber: #f59e0b;
            --uitm-amber-light: #fbbf24;
            --neutral-900: #171717;
            --neutral-800: #262626;
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

        .font-mono {
            font-family: 'IBM Plex Mono', monospace;
        }

        /* ==================== SPLIT LAYOUT ==================== */
        .login-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ==================== LEFT PANEL - IMAGE ==================== */
        .login-image-panel {
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

        .login-image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(30, 58, 138, 0.92) 0%,
                rgba(23, 23, 23, 0.88) 100%
            );
            z-index: 1;
        }

        .login-image-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.07;
            background-image:
                linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 2;
        }

        .login-image-content {
            position: relative;
            z-index: 3;
            color: white;
        }

        /* Brand Header */
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
            letter-spacing: -0.01em;
        }

        .brand-text .accent {
            color: var(--uitm-red);
        }

        /* Hero Content */
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
            margin-bottom: 2rem;
        }

        /* Stats Row */
        .image-stats {
            display: flex;
            gap: 2.5rem;
        }

        .image-stat {
            text-align: left;
        }

        .image-stat-value {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 1.75rem;
            font-weight: 600;
            color: white;
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .image-stat-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Footer */
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
        .login-form-panel {
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

        /* Back Link */
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

        /* Form Header */
        .form-header {
            margin-bottom: 2.5rem;
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
            line-height: 1.2;
        }

        .form-subtitle {
            font-size: 0.9rem;
            color: var(--neutral-500);
        }

        /* Alert Messages */
        .alert-custom {
            border-radius: 8px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .alert-custom i {
            margin-top: 0.125rem;
            flex-shrink: 0;
        }

        .alert-success-custom {
            background: #ecfdf5;
            color: #065f46;
            border-left: 3px solid #10b981;
        }

        .alert-info-custom {
            background: #eff6ff;
            color: #1e40af;
            border-left: 3px solid #3b82f6;
        }

        .alert-warning-custom {
            background: #fffbeb;
            color: #92400e;
            border-left: 3px solid #f59e0b;
        }

        .alert-danger-custom {
            background: #fef2f2;
            color: #991b1b;
            border-left: 3px solid #ef4444;
        }

        .alert-custom ul {
            margin: 0.5rem 0 0 0;
            padding-left: 1.25rem;
        }

        .alert-custom li {
            margin-bottom: 0.25rem;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label-custom {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--neutral-700);
            margin-bottom: 0.5rem;
            letter-spacing: 0.01em;
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

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .invalid-feedback {
            display: block;
            font-size: 0.8rem;
            color: var(--uitm-red);
            margin-top: 0.5rem;
            font-weight: 500;
        }

        /* Checkbox */
        .form-check-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .form-check-custom {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input-custom {
            width: 18px;
            height: 18px;
            border: 2px solid var(--neutral-300);
            border-radius: 4px;
            cursor: pointer;
            accent-color: var(--uitm-primary);
        }

        .form-check-label-custom {
            font-size: 0.875rem;
            color: var(--neutral-700);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--uitm-primary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--uitm-red);
        }

        /* Submit Button */
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

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Divider */
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

        /* Secondary Links */
        .secondary-links {
            text-align: center;
        }

        .secondary-link-item {
            display: block;
            margin-bottom: 0.75rem;
        }

        .secondary-link-item:last-child {
            margin-bottom: 0;
        }

        .secondary-link {
            font-size: 0.875rem;
            color: var(--neutral-600);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .secondary-link:hover {
            color: var(--uitm-primary);
        }

        .secondary-link strong {
            color: var(--uitm-primary);
            font-weight: 600;
        }

        .secondary-link strong:hover {
            color: var(--uitm-red);
        }

        /* ==================== ANIMATIONS ==================== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }

        .animate-fade-in-left {
            animation: fadeInLeft 0.6s ease forwards;
        }

        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        .delay-3 { animation-delay: 0.3s; opacity: 0; }
        .delay-4 { animation-delay: 0.4s; opacity: 0; }
        .delay-5 { animation-delay: 0.5s; opacity: 0; }

        @media (prefers-reduced-motion: reduce) {
            .animate-fade-in-up,
            .animate-fade-in-left {
                animation: none;
                opacity: 1;
            }
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 1024px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }

            .login-image-panel {
                display: none;
            }

            .login-form-panel {
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
            .login-form-panel {
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
    <div class="login-wrapper">
        <!-- Left Panel - Image -->
        <div class="login-image-panel">
            <div class="login-image-overlay"></div>
            <div class="login-image-pattern"></div>

            <!-- Brand Header -->
            <div class="login-image-content">
                <div class="brand-header animate-fade-in-left">
                    <div class="brand-logo">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="brand-text">
                        UiTM<span class="accent">CES</span>
                    </div>
                </div>
            </div>

            <!-- Hero Content -->
            <div class="image-hero">
                <div class="image-hero-eyebrow animate-fade-in-left delay-1">Credit Exemption System</div>
                <h1 class="image-hero-title animate-fade-in-left delay-2">
                    Streamline Your Academic Journey
                </h1>
                <p class="image-hero-desc animate-fade-in-left delay-3">
                    Transfer your diploma credits seamlessly with our intelligent OCR-powered system. Fast processing, transparent tracking.
                </p>
                <div class="image-stats animate-fade-in-left delay-4">
                    <div class="image-stat">
                        <div class="image-stat-value">98.5%</div>
                        <div class="image-stat-label">OCR Accuracy</div>
                    </div>
                    <div class="image-stat">
                        <div class="image-stat-value">48h</div>
                        <div class="image-stat-label">Avg. Processing</div>
                    </div>
                    <div class="image-stat">
                        <div class="image-stat-value">12K+</div>
                        <div class="image-stat-label">Applications</div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="image-footer animate-fade-in-left delay-5">
                <div class="image-footer-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Secure & Encrypted</span>
                </div>
                <div class="image-footer-badge">
                    <i class="fas fa-clock"></i>
                    <span>24/7 Access</span>
                </div>
                <div class="image-footer-badge">
                    <i class="fas fa-certificate"></i>
                    <span>HEA Compliant</span>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="login-form-panel">
            <a href="{{ url('/') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to Home
            </a>

            <div class="form-container">
                <!-- Form Header -->
                <div class="form-header animate-fade-in-up">
                    <div class="form-eyebrow">Account Access</div>
                    <h1 class="form-title">Sign in to your account</h1>
                    <p class="form-subtitle">Enter your credentials to access the system</p>
                </div>

                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="alert-custom alert-success-custom animate-fade-in-up delay-1">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('verified'))
                    <div class="alert-custom alert-success-custom animate-fade-in-up delay-1">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ __('Your email address has been successfully verified! You may now log in.') }}</span>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert-custom alert-info-custom animate-fade-in-up delay-1">
                        <i class="fas fa-info-circle"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert-custom alert-info-custom animate-fade-in-up delay-1">
                        <i class="fas fa-info-circle"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert-custom alert-warning-custom animate-fade-in-up delay-1">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ session('warning') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert-custom alert-danger-custom animate-fade-in-up delay-1">
                        <i class="fas fa-times-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert-custom alert-danger-custom animate-fade-in-up delay-1">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>{{ __('Please fix the following errors:') }}</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group animate-fade-in-up delay-2">
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

                    <!-- Password Field -->
                    <div class="form-group animate-fade-in-up delay-3">
                        <label class="form-label-custom" for="password">
                            <i class="fas fa-lock"></i>{{ __('Password') }}
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input @error('password') is-invalid @enderror"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="form-check-row animate-fade-in-up delay-4">
                        <div class="form-check-custom">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                class="form-check-input-custom"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label class="form-check-label-custom" for="remember">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="animate-fade-in-up delay-5">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-sign-in-alt"></i>
                            {{ __('Sign In') }}
                        </button>
                    </div>
                </form>

                <!-- Secondary Links -->
                <div class="form-divider animate-fade-in-up delay-5">
                    <span class="form-divider-text">Or</span>
                </div>

                <div class="secondary-links animate-fade-in-up delay-5">
                    @if (Route::has('register'))
                        <div class="secondary-link-item">
                            <a href="{{ route('register') }}" class="secondary-link">
                                Don't have an account? <strong>Create Account</strong>
                            </a>
                        </div>
                    @endif
                    <div class="secondary-link-item">
                        <a href="{{ route('registration.status') }}" class="secondary-link">
                            <i class="fas fa-search me-1"></i>Check Registration Status
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
