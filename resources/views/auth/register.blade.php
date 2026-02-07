<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - UiTM Credit Exemption System</title>

    <!-- Fonts - IBM Plex Sans & Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

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
            --neutral-300: #d4d4d4;
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
        .register-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ==================== LEFT PANEL - IMAGE ==================== */
        .register-image-panel {
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

        .register-image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(30, 58, 138, 0.92) 0%,
                rgba(23, 23, 23, 0.88) 100%
            );
            z-index: 1;
        }

        .register-image-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.07;
            background-image:
                linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 2;
        }

        .register-image-content {
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

        /* Feature List */
        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .feature-item i {
            width: 20px;
            height: 20px;
            background: rgba(245, 158, 11, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            color: var(--uitm-amber);
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
        .register-form-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 5rem 3rem 3rem 3rem;
            background: white;
            position: relative;
            overflow-y: auto;
        }

        .form-container {
            width: 100%;
            max-width: 440px;
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
            z-index: 10;
        }

        .back-link:hover {
            color: var(--uitm-primary);
            background: var(--neutral-100);
        }

        /* Form Header */
        .form-header {
            margin-bottom: 2rem;
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

        /* Progress Steps */
        .form-progress {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            padding: 1rem;
            background: var(--neutral-50);
            border-radius: 8px;
        }

        .progress-step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: var(--neutral-400);
        }

        .progress-step.active {
            color: var(--uitm-primary);
            font-weight: 600;
        }

        .progress-step-number {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--neutral-200);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .progress-step.active .progress-step-number {
            background: var(--uitm-primary);
            color: white;
        }

        .progress-divider {
            flex: 1;
            height: 2px;
            background: var(--neutral-200);
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.25rem;
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

        .form-label-custom .required {
            color: var(--uitm-red);
        }

        .form-input,
        .form-select-custom {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            font-family: inherit;
            color: var(--neutral-900);
            background: var(--neutral-50);
            border: 2px solid var(--neutral-200);
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .form-input:focus,
        .form-select-custom:focus {
            outline: none;
            border-color: var(--uitm-primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .form-input.is-invalid,
        .form-select-custom.is-invalid {
            border-color: var(--uitm-red);
            background: #fef2f2;
        }

        .form-input.is-invalid:focus,
        .form-select-custom.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--neutral-500);
            margin-top: 0.375rem;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.8rem;
            color: var(--uitm-red);
            margin-top: 0.375rem;
            font-weight: 500;
        }

        /* Role Selection */
        .role-select-wrapper {
            position: relative;
        }

        .role-select-wrapper::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--neutral-400);
            pointer-events: none;
        }

        .role-select-wrapper select {
            appearance: none;
            padding-right: 2.5rem;
        }

        /* Conditional Fields */
        .conditional-fields {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.03) 0%, rgba(59, 130, 246, 0.05) 100%);
            border: 2px solid rgba(30, 58, 138, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.25rem;
        }

        .conditional-fields-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(30, 58, 138, 0.1);
        }

        .conditional-fields-icon {
            width: 36px;
            height: 36px;
            background: var(--uitm-primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
        }

        .conditional-fields-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--uitm-primary);
        }

        /* Program Cards */
        .program-card {
            background: white;
            border: 2px solid var(--neutral-200);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.2s ease;
        }

        .program-card:hover {
            border-color: var(--uitm-primary);
        }

        .program-card:last-child {
            margin-bottom: 0;
        }

        .program-card .form-check-input:checked ~ .form-check-label {
            color: var(--uitm-primary);
        }

        .program-groups {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--neutral-100);
        }

        .program-groups-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--neutral-500);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .group-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .group-badge-check {
            display: none;
        }

        .group-badge-label {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--neutral-600);
            background: var(--neutral-100);
            border: 2px solid var(--neutral-200);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .group-badge-check:checked + .group-badge-label {
            background: var(--uitm-primary);
            border-color: var(--uitm-primary);
            color: white;
        }

        /* Selected Groups Display */
        .selected-groups-display {
            min-height: 40px;
            padding: 0.5rem 0.75rem;
            background: var(--neutral-50);
            border: 2px solid var(--neutral-200);
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .selected-groups-display .badge {
            background: var(--uitm-primary);
            font-weight: 500;
            font-size: 0.75rem;
            padding: 0.375rem 0.625rem;
            margin: 0.125rem;
        }

        .selected-groups-placeholder {
            font-size: 0.8rem;
            color: var(--neutral-400);
        }

        /* Category Cards */
        .category-card {
            background: white;
            border: 2px solid var(--neutral-200);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .category-card:hover {
            border-color: var(--uitm-primary);
        }

        .category-card.selected {
            border-color: var(--uitm-primary);
            background: rgba(30, 58, 138, 0.02);
        }

        .category-card:last-child {
            margin-bottom: 0;
        }

        .category-programs {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--neutral-100);
        }

        .category-program-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--neutral-600);
            margin-bottom: 0.25rem;
        }

        .category-program-item:last-child {
            margin-bottom: 0;
        }

        .category-program-item i {
            color: #10b981;
            font-size: 0.7rem;
        }

        /* HEA Note */
        .hea-note {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1.25rem;
        }

        .hea-note-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .hea-note-icon {
            color: #d97706;
            font-size: 1.25rem;
        }

        .hea-note-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #92400e;
        }

        .hea-note-text {
            font-size: 0.85rem;
            color: #a16207;
            line-height: 1.5;
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
            margin-top: 1.5rem;
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

        /* Login Link */
        .login-link {
            text-align: center;
        }

        .login-link a {
            font-size: 0.875rem;
            color: var(--neutral-600);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .login-link a:hover {
            color: var(--uitm-primary);
        }

        .login-link strong {
            color: var(--uitm-primary);
            font-weight: 600;
        }

        /* Validation Error */
        .validation-error {
            font-size: 0.8rem;
            color: var(--uitm-red);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
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
            .register-wrapper {
                grid-template-columns: 1fr;
            }

            .register-image-panel {
                display: none;
            }

            .register-form-panel {
                min-height: 100vh;
                max-height: none;
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
            .register-form-panel {
                padding: 1.5rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .form-container {
                max-width: 100%;
            }

            .form-progress {
                display: none;
            }

            .conditional-fields {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <!-- Left Panel - Image -->
        <div class="register-image-panel">
            <div class="register-image-overlay"></div>
            <div class="register-image-pattern"></div>

            <!-- Brand Header -->
            <div class="register-image-content">
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
                <div class="image-hero-eyebrow animate-fade-in-left delay-1">Join the Platform</div>
                <h1 class="image-hero-title animate-fade-in-left delay-2">
                    Start Your Credit Transfer Journey
                </h1>
                <p class="image-hero-desc animate-fade-in-left delay-3">
                    Register to access the Credit Exemption System. Whether you're a student or staff member, we've streamlined the process for you.
                </p>
                <div class="feature-list animate-fade-in-left delay-4">
                    <div class="feature-item">
                        <i class="fas fa-check"></i>
                        <span>Quick registration process</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check"></i>
                        <span>Role-based access control</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check"></i>
                        <span>Secure two-factor authentication</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check"></i>
                        <span>Email verification for security</span>
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
                    <i class="fas fa-user-check"></i>
                    <span>Verified Access</span>
                </div>
                <div class="image-footer-badge">
                    <i class="fas fa-certificate"></i>
                    <span>HEA Compliant</span>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="register-form-panel">
            <!-- Back Link -->
            <a href="{{ route('login') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to Login
            </a>

            <div class="form-container">
                    <!-- Form Header -->
                    <div class="form-header animate-fade-in-up delay-1">
                        <div class="form-eyebrow">Create Account</div>
                        <h1 class="form-title">Register for UiTM CES</h1>
                        <p class="form-subtitle">Fill in your details to create an account</p>
                    </div>

                    <!-- Progress Steps -->
                    <div class="form-progress animate-fade-in-up delay-2">
                        <div class="progress-step active">
                            <span class="progress-step-number">1</span>
                            <span>Account</span>
                        </div>
                        <div class="progress-divider"></div>
                        <div class="progress-step">
                            <span class="progress-step-number">2</span>
                            <span>Role</span>
                        </div>
                        <div class="progress-divider"></div>
                        <div class="progress-step">
                            <span class="progress-step-number">3</span>
                            <span>Verify</span>
                        </div>
                    </div>

                    <!-- Registration Form -->
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <!-- Name Field -->
                        <div class="form-group animate-fade-in-up delay-2">
                            <label class="form-label-custom" for="name">
                                <i class="fas fa-user"></i>Full Name <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-input @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                placeholder="Enter your full name"
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="form-group animate-fade-in-up delay-2">
                            <label class="form-label-custom" for="email">
                                <i class="fas fa-envelope"></i>Email Address <span class="required">*</span>
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-input @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                required
                                placeholder="you@uitm.edu.my"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="form-group animate-fade-in-up delay-3">
                            <label class="form-label-custom" for="password">
                                <i class="fas fa-lock"></i>Password <span class="required">*</span>
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-input @error('password') is-invalid @enderror"
                                required
                                placeholder="Create a strong password"
                            >
                            <div class="form-hint">Minimum 8 characters</div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="form-group animate-fade-in-up delay-3">
                            <label class="form-label-custom" for="password_confirmation">
                                <i class="fas fa-lock"></i>Confirm Password <span class="required">*</span>
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-input"
                                required
                                placeholder="Confirm your password"
                            >
                        </div>

                        <!-- Role Selection -->
                        <div class="form-group animate-fade-in-up delay-4">
                            <label class="form-label-custom" for="requested_role">
                                <i class="fas fa-user-tag"></i>I am registering as <span class="required">*</span>
                            </label>
                            <div class="role-select-wrapper">
                                <select
                                    id="requested_role"
                                    name="requested_role"
                                    class="form-select-custom @error('requested_role') is-invalid @enderror"
                                    required
                                >
                                    <option value="">Select your role</option>
                                    <option value="student" {{ old('requested_role') == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="academic_advisor" {{ old('requested_role') == 'academic_advisor' ? 'selected' : '' }}>Academic Advisor</option>
                                    <option value="program_coordinator" {{ old('requested_role') == 'program_coordinator' ? 'selected' : '' }}>Program Coordinator</option>
                                    <option value="resource_person" {{ old('requested_role') == 'resource_person' ? 'selected' : '' }}>Resource Person</option>
                                    <option value="hea_personnel" {{ old('requested_role') == 'hea_personnel' ? 'selected' : '' }}>HEA Personnel</option>
                                </select>
                            </div>
                            @error('requested_role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-hint">
                                <i class="fas fa-info-circle me-1"></i>External lecturers receive secure access links from resource persons.
                            </div>
                        </div>

                        <!-- Student Fields -->
                        <div id="student-fields" style="display: none;">
                            <div class="conditional-fields">
                                <div class="conditional-fields-header">
                                    <div class="conditional-fields-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="conditional-fields-title">Student Information</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label-custom" for="matric_no">
                                        Matric Number <span class="required">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="matric_no"
                                        name="matric_no"
                                        class="form-input"
                                        value="{{ old('matric_no') }}"
                                        placeholder="e.g., 2023123456"
                                    >
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label-custom" for="program_id">
                                        Current Degree Program <span class="required">*</span>
                                    </label>
                                    <select id="program_id" name="program_id" class="form-select-custom">
                                        <option value="">Select your degree program</option>
                                        @foreach($degreePrograms as $program)
                                            <option value="{{ $program->id }}">{{ $program->code }} - {{ $program->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Advisor Fields -->
                        <div id="academic-advisor-fields" style="display: none;">
                            <div class="conditional-fields">
                                <div class="conditional-fields-header">
                                    <div class="conditional-fields-icon">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="conditional-fields-title">Academic Advisor Information</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label-custom">
                                        Programme & Groups You Will Manage <span class="required">*</span>
                                    </label>
                                    <div class="form-hint" style="margin-top: 0; margin-bottom: 0.75rem;">
                                        Select one or more programme-group combinations
                                    </div>

                                    <!-- Selected Groups Display -->
                                    <div class="selected-groups-display" id="aa-selected-groups">
                                        <span class="selected-groups-placeholder" id="aa-placeholder">No programme-groups selected</span>
                                    </div>

                                    <!-- Programme Cards -->
                                    @foreach($programGroups as $programCode => $programData)
                                    <div class="program-card">
                                        <div class="form-check">
                                            <input class="form-check-input aa-program-check" type="checkbox" id="aa_prog_{{ $programCode }}" value="{{ $programCode }}">
                                            <label class="form-check-label fw-semibold" for="aa_prog_{{ $programCode }}">
                                                {{ $programCode }} - {{ $programData['name'] }}
                                            </label>
                                        </div>
                                        <div class="program-groups" id="aa_groups_{{ $programCode }}" style="display: none;">
                                            <div class="program-groups-label">Select Groups:</div>
                                            <div class="group-badges">
                                                @foreach($programData['groups'] as $group)
                                                <div>
                                                    <input class="group-badge-check aa-group-check" type="checkbox" data-program="{{ $programCode }}" id="aa_group_{{ $programCode }}_{{ $group }}" value="{{ $group }}">
                                                    <label class="group-badge-label" for="aa_group_{{ $programCode }}_{{ $group }}">{{ $group }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                    <!-- Hidden Input -->
                                    <input type="hidden" name="program_groups" id="aa_program_groups" value="">
                                    <div class="validation-error" id="aa-validation-error" style="display: none;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Please select at least one programme-group combination
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Program Coordinator Fields -->
                        <div id="program-coordinator-fields" style="display: none;">
                            <div class="conditional-fields">
                                <div class="conditional-fields-header">
                                    <div class="conditional-fields-icon">
                                        <i class="fas fa-users-cog"></i>
                                    </div>
                                    <div class="conditional-fields-title">Program Coordinator Information</div>
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label-custom">
                                        Program Category <span class="required">*</span>
                                    </label>
                                    <div class="form-hint" style="margin-top: 0; margin-bottom: 0.75rem;">
                                        Select ONE category to coordinate
                                    </div>

                                    @foreach($coordinatorCategories as $categoryKey => $categoryData)
                                    <div class="category-card">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="program_category" id="pc_{{ $categoryKey }}" value="{{ $categoryKey }}">
                                            <label class="form-check-label fw-semibold" for="pc_{{ $categoryKey }}">
                                                {{ $categoryData['label'] }}: {{ $categoryData['description'] }}
                                            </label>
                                        </div>
                                        <div class="category-programs">
                                            @foreach($categoryData['programs'] as $programCode)
                                            <div class="category-program-item">
                                                <i class="fas fa-check-circle"></i>
                                                <strong>{{ $programCode }}</strong> - {{ $programGroups[$programCode]['name'] ?? 'Unknown Program' }}
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Resource Person Fields -->
                        <div id="resource-person-fields" style="display: none;">
                            <div class="conditional-fields">
                                <div class="conditional-fields-header">
                                    <div class="conditional-fields-icon">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <div class="conditional-fields-title">Resource Person Information</div>
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label-custom" for="rp_degree_program">
                                        Degree Program to Support <span class="required">*</span>
                                    </label>
                                    <select id="rp_degree_program" name="degree_program" class="form-select-custom">
                                        <option value="">Select degree program</option>
                                        @foreach($programGroups as $programCode => $programData)
                                        <option value="{{ $programCode }}">{{ $programCode }} - {{ $programData['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-hint">Select the degree program you will provide resource support for</div>
                                </div>
                            </div>
                        </div>

                        <!-- HEA Note -->
                        <div id="hea-note" style="display: none;">
                            <div class="hea-note">
                                <div class="hea-note-header">
                                    <i class="fas fa-exclamation-triangle hea-note-icon"></i>
                                    <div class="hea-note-title">HEA Personnel Registration</div>
                                </div>
                                <p class="hea-note-text">
                                    Your registration will be reviewed by the system administrator.
                                    You will receive an email once your request is processed.
                                </p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit animate-fade-in-up delay-5">
                            <i class="fas fa-user-plus"></i>
                            Create Account
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="form-divider animate-fade-in-up delay-5">
                        <span class="form-divider-text">Or</span>
                    </div>

                    <div class="login-link animate-fade-in-up delay-5">
                        <a href="{{ route('login') }}">
                            Already have an account? <strong>Sign In</strong>
                        </a>
                    </div>
                </div>
        </div>
    </div>

    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('requested_role');
        const studentFields = document.getElementById('student-fields');
        const academicAdvisorFields = document.getElementById('academic-advisor-fields');
        const programCoordinatorFields = document.getElementById('program-coordinator-fields');
        const resourcePersonFields = document.getElementById('resource-person-fields');
        const heaNote = document.getElementById('hea-note');

        // Academic Advisor - Programme-Group Selection Logic
        document.querySelectorAll('.aa-program-check').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const program = this.value;
                const groupsDiv = document.getElementById(`aa_groups_${program}`);

                if (this.checked) {
                    groupsDiv.style.display = 'block';
                } else {
                    groupsDiv.style.display = 'none';
                    // Uncheck all groups
                    document.querySelectorAll(`#aa_groups_${program} .aa-group-check`).forEach(gc => {
                        gc.checked = false;
                    });
                }

                updateAASelectedGroups();
            });
        });

        document.querySelectorAll('.aa-group-check').forEach(checkbox => {
            checkbox.addEventListener('change', updateAASelectedGroups);
        });

        function updateAASelectedGroups() {
            const selected = [];
            const programGroupMap = {};

            document.querySelectorAll('.aa-group-check:checked').forEach(checkbox => {
                const program = checkbox.getAttribute('data-program');
                const group = checkbox.value;

                if (!programGroupMap[program]) {
                    programGroupMap[program] = [];
                }
                programGroupMap[program].push(group);

                selected.push({
                    program_code: program,
                    group: group
                });
            });

            // Update display
            const displayDiv = document.getElementById('aa-selected-groups');
            const placeholder = document.getElementById('aa-placeholder');

            if (selected.length > 0) {
                let html = '';
                for (const program in programGroupMap) {
                    const groups = programGroupMap[program];
                    html += `<span class="badge bg-primary me-1 mb-1">${program}: ${groups.join(', ')}</span>`;
                }
                displayDiv.innerHTML = html;
            } else {
                displayDiv.innerHTML = '<span class="selected-groups-placeholder" id="aa-placeholder">No programme-groups selected</span>';
            }

            // Update hidden input
            document.getElementById('aa_program_groups').value = JSON.stringify(selected);

            // Validation
            const errorMsg = document.getElementById('aa-validation-error');
            if (selected.length === 0) {
                errorMsg.style.display = 'flex';
            } else {
                errorMsg.style.display = 'none';
            }
        }

        // Role change handler
        roleSelect.addEventListener('change', function() {
            const role = this.value;

            // Hide all conditional fields
            studentFields.style.display = 'none';
            academicAdvisorFields.style.display = 'none';
            programCoordinatorFields.style.display = 'none';
            resourcePersonFields.style.display = 'none';
            heaNote.style.display = 'none';

            // Disable all conditional fields
            document.querySelectorAll('#student-fields input, #student-fields select').forEach(el => {
                el.disabled = true;
                el.required = false;
            });
            document.querySelectorAll('#academic-advisor-fields input, #academic-advisor-fields select').forEach(el => {
                el.disabled = true;
                el.required = false;
            });
            document.querySelectorAll('#program-coordinator-fields input, #program-coordinator-fields select').forEach(el => {
                el.disabled = true;
                el.required = false;
            });
            document.querySelectorAll('#resource-person-fields input, #resource-person-fields select').forEach(el => {
                el.disabled = true;
                el.required = false;
            });

            // Show relevant fields based on role
            if (role === 'student') {
                studentFields.style.display = 'block';
                document.querySelectorAll('#student-fields input, #student-fields select').forEach(el => {
                    el.disabled = false;
                    if (el.id !== 'program_id') el.required = true;
                });
            } else if (role === 'academic_advisor') {
                academicAdvisorFields.style.display = 'block';
                document.querySelectorAll('#academic-advisor-fields input:not([type="checkbox"])').forEach(el => {
                    el.disabled = false;
                });
                // Enable checkboxes
                document.querySelectorAll('#academic-advisor-fields input[type="checkbox"]').forEach(el => {
                    el.disabled = false;
                });
            } else if (role === 'program_coordinator') {
                programCoordinatorFields.style.display = 'block';
                document.querySelectorAll('#program-coordinator-fields input, #program-coordinator-fields select').forEach(el => {
                    el.disabled = false;
                    el.required = true;
                });
            } else if (role === 'resource_person') {
                resourcePersonFields.style.display = 'block';
                document.querySelectorAll('#resource-person-fields input, #resource-person-fields select').forEach(el => {
                    el.disabled = false;
                    el.required = true;
                });
            } else if (role === 'hea_personnel') {
                heaNote.style.display = 'block';
            }
        });

        // Trigger on page load if old value exists
        if (roleSelect.value) {
            roleSelect.dispatchEvent(new Event('change'));
        }

        // Category card selection visual feedback
        document.querySelectorAll('.category-card input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.category-card').forEach(card => {
                    card.classList.remove('selected');
                });
                if (this.checked) {
                    this.closest('.category-card').classList.add('selected');
                }
            });
        });
    });
    </script>
</body>
</html>
