<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UiTM Credit Exemption System</title>

    <!-- Fonts - IBM Plex Sans & Mono for Industrial Institutional Design -->
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
            --uitm-secondary: #3b82f6;
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
            --surface-secondary: #f8fafc;
            --surface-tertiary: #f1f5f9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow-x: hidden;
            color: var(--neutral-800);
            line-height: 1.6;
        }

        .font-mono {
            font-family: 'IBM Plex Mono', monospace;
        }

        /* Section Eyebrow Text */
        .eyebrow {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--uitm-primary);
            margin-bottom: 0.75rem;
        }

        .eyebrow-light {
            color: var(--uitm-amber);
        }

        /* ==================== NAVBAR ==================== */
        .main-navbar {
            background: #ffffff;
            border-bottom: 3px solid var(--uitm-primary);
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: box-shadow 0.3s ease;
        }

        .main-navbar.scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-inner {
            display: flex;
            align-items: stretch;
            min-height: 70px;
        }

        .navbar-brand-wrapper {
            display: flex;
            align-items: center;
            padding-right: 2rem;
            border-right: 1px solid var(--neutral-200);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--uitm-primary) !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand .accent {
            color: var(--uitm-red);
        }

        .navbar-brand i {
            font-size: 1.25rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-left: 2rem;
            flex: 1;
        }

        .nav-link-item {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--neutral-700);
            text-decoration: none;
            padding: 0.5rem 1rem;
            position: relative;
            transition: color 0.2s ease;
        }

        .nav-link-item:hover {
            color: var(--uitm-primary);
        }

        .nav-link-item::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 1rem;
            right: 1rem;
            height: 2px;
            background: var(--uitm-red);
            transform: scaleX(0);
            transition: transform 0.2s ease;
        }

        .nav-link-item:hover::after {
            transform: scaleX(1);
        }

        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-left: auto;
        }

        .btn-nav-login {
            background: transparent;
            color: var(--uitm-primary);
            border: 2px solid var(--uitm-primary);
            padding: 0.5rem 1.25rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.025em;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-nav-login:hover {
            background: var(--uitm-primary);
            color: white;
        }

        .btn-nav-register {
            background: var(--uitm-red);
            color: white;
            border: 2px solid var(--uitm-red);
            padding: 0.5rem 1.25rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.025em;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-nav-register:hover {
            background: var(--uitm-red-dark);
            border-color: var(--uitm-red-dark);
            color: white;
        }

        /* Mobile Nav Toggle */
        .nav-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--uitm-primary);
            cursor: pointer;
            padding: 0.5rem;
        }

        /* ==================== HERO SECTION ==================== */
        .hero-section {
            position: relative;
            min-height: 100vh;
            background: url('https://malaysiabangkit.com/wp-content/uploads/2024/11/UITM-pelajar.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding-top: 70px;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        /* Dark Overlay */
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(30, 58, 138, 0.85) 0%,
                rgba(23, 23, 23, 0.8) 100%
            );
            z-index: 1;
        }

        /* Subtle Grid Pattern on Overlay */
        .hero-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.05;
            background-image:
                linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 80px 80px;
            z-index: 2;
        }

        .hero-container {
            position: relative;
            z-index: 3;
            width: 100%;
        }

        .hero-grid {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .hero-content {
            color: white;
            max-width: 800px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }

        .hero-badge-icon {
            width: 24px;
            height: 24px;
            background: var(--uitm-amber);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: var(--neutral-900);
        }

        .hero-badge-text {
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.025em;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }

        .hero-title .highlight {
            color: var(--uitm-amber);
        }

        .hero-description {
            font-size: 1.125rem;
            font-weight: 400;
            line-height: 1.7;
            opacity: 0.9;
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-ctas {
            display: flex;
            gap: 1rem;
            margin-bottom: 2.5rem;
            justify-content: center;
        }

        .btn-hero-primary {
            background: var(--uitm-red);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.025em;
            text-transform: uppercase;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(220, 38, 38, 0.4);
        }

        .btn-hero-primary:hover {
            background: var(--uitm-red-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.5);
        }

        .btn-hero-secondary {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 1rem 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.025em;
            text-transform: uppercase;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
        }

        /* Trust Indicators */
        .trust-indicators {
            display: flex;
            gap: 2rem;
            justify-content: center;
        }

        .trust-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .trust-item i {
            color: var(--uitm-amber);
            font-size: 0.875rem;
        }

        /* ==================== STATISTICS BAR ==================== */
        .stats-section {
            background: var(--neutral-900);
            padding: 3rem 0;
            position: relative;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
        }

        .stat-block {
            text-align: center;
            padding: 1.5rem 2rem;
            position: relative;
        }

        .stat-block:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 60%;
            background: var(--neutral-700);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--uitm-amber);
            font-size: 1.25rem;
        }

        .stat-number {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 2.5rem;
            font-weight: 600;
            color: white;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label-block {
            font-size: 0.8rem;
            color: var(--neutral-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ==================== HOW IT WORKS ==================== */
        .workflow-section {
            padding: 6rem 0;
            background: var(--surface-secondary);
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--neutral-900);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1rem;
            color: var(--neutral-500);
            max-width: 600px;
            margin: 0 auto;
        }

        .workflow-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            position: relative;
        }

        .workflow-grid::before {
            content: '';
            position: absolute;
            top: 60px;
            left: calc(12.5% + 1rem);
            right: calc(12.5% + 1rem);
            height: 2px;
            background: linear-gradient(90deg, var(--uitm-primary), var(--uitm-amber));
            z-index: 0;
        }

        .workflow-step {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-number {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--uitm-primary);
            margin-bottom: 1rem;
        }

        .step-icon-wrapper {
            width: 120px;
            height: 120px;
            background: white;
            border: 2px solid var(--neutral-200);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            transition: all 0.3s ease;
        }

        .step-icon-wrapper:hover {
            border-color: var(--uitm-primary);
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(30, 58, 138, 0.15);
        }

        .step-icon {
            font-size: 2.5rem;
            color: var(--uitm-primary);
        }

        .step-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--neutral-900);
            margin-bottom: 0.5rem;
        }

        .step-desc {
            font-size: 0.875rem;
            color: var(--neutral-500);
            line-height: 1.5;
        }

        /* ==================== FEATURES GRID ==================== */
        .features-section {
            padding: 6rem 0;
            background: white;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .feature-card {
            background: var(--surface-secondary);
            border: 1px solid var(--neutral-200);
            border-radius: 8px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: var(--uitm-primary);
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .feature-card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--neutral-200);
        }

        .feature-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .feature-icon-wrapper.student {
            background: rgba(30, 58, 138, 0.1);
            color: var(--uitm-primary);
        }

        .feature-icon-wrapper.staff {
            background: rgba(220, 38, 38, 0.1);
            color: var(--uitm-red);
        }

        .feature-icon-wrapper.admin {
            background: rgba(245, 158, 11, 0.1);
            color: var(--uitm-amber);
        }

        .feature-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--neutral-900);
        }

        .feature-subtitle {
            font-size: 0.8rem;
            color: var(--neutral-500);
            margin-top: 0.25rem;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: var(--neutral-700);
            padding: 0.5rem 0;
        }

        .feature-list li i {
            color: #16a34a;
            margin-top: 0.25rem;
            flex-shrink: 0;
        }

        /* ==================== FOOTER ==================== */
        .main-footer {
            background: var(--neutral-900);
            color: var(--neutral-400);
            padding-top: 4rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 4rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid var(--neutral-800);
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-brand .accent {
            color: var(--uitm-red);
        }

        .footer-desc {
            font-size: 0.875rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .footer-contact {
            font-size: 0.875rem;
        }

        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .footer-contact-item i {
            color: var(--uitm-amber);
            width: 16px;
        }

        .footer-heading {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: white;
            margin-bottom: 1.5rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            font-size: 0.875rem;
            color: var(--neutral-400);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            padding: 1.5rem 0;
            text-align: center;
        }

        .footer-copyright {
            font-size: 0.8rem;
        }

        /* ==================== ANIMATIONS ==================== */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-in-delay-1 { transition-delay: 0.1s; }
        .fade-in-delay-2 { transition-delay: 0.2s; }
        .fade-in-delay-3 { transition-delay: 0.3s; }
        .fade-in-delay-4 { transition-delay: 0.4s; }

        @media (prefers-reduced-motion: reduce) {
            .fade-in {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-block:nth-child(2)::after {
                display: none;
            }

            .workflow-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 3rem;
            }

            .workflow-grid::before {
                display: none;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .nav-links {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .navbar-brand-wrapper {
                border-right: none;
                padding-right: 0;
            }

            .nav-toggle {
                display: block;
            }

            .auth-buttons {
                display: none;
            }

            .auth-buttons.show {
                display: flex;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 1rem;
                border-top: 1px solid var(--neutral-200);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }

            .auth-buttons.show a {
                width: 100%;
                text-align: center;
                padding: 0.75rem 1rem;
            }

            .hero-section {
                min-height: auto;
                padding: 8rem 0 4rem;
            }

            .hero-title {
                font-size: 2.25rem;
            }

            .hero-description {
                font-size: 1rem;
            }

            .hero-ctas {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                width: 100%;
                justify-content: center;
            }

            .trust-indicators {
                flex-direction: column;
                gap: 0.75rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-block::after {
                display: none !important;
            }

            .stat-block {
                padding: 1.5rem;
                border-bottom: 1px solid var(--neutral-800);
            }

            .stat-block:last-child {
                border-bottom: none;
            }

            .workflow-section,
            .features-section {
                padding: 4rem 0;
            }

            .section-title {
                font-size: 1.75rem;
            }

            .workflow-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 480px) {
            .hero-badge {
                font-size: 0.7rem;
            }

            .hero-title {
                font-size: 1.875rem;
            }

            .stat-number {
                font-size: 2rem;
            }

            .step-icon-wrapper {
                width: 100px;
                height: 100px;
            }

            .step-icon {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Main Navigation -->
    <nav class="main-navbar" id="mainNavbar">
        <div class="container">
            <div class="navbar-inner">
                <div class="navbar-brand-wrapper">
                    <a href="{{ url('/') }}" class="navbar-brand">
                        <i class="fas fa-university"></i>
                        UiTM<span class="accent">CES</span>
                    </a>
                </div>

                <div class="nav-links">
                    <a href="#workflow" class="nav-link-item">How It Works</a>
                    <a href="#features" class="nav-link-item">Features</a>
                </div>

                <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="auth-buttons" id="authButtons">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/home') }}" class="btn-nav-login">
                                <i class="fas fa-th-large me-1"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-nav-login">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-nav-register">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-pattern"></div>
        <div class="container hero-container">
            <div class="hero-grid">
                <div class="hero-content">
                    <div class="hero-badge">
                        <div class="hero-badge-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span class="hero-badge-text">Universiti Teknologi MARA Official System</span>
                    </div>

                    <h1 class="hero-title">
                        Your Previous Credits,<br>
                        <span class="highlight">Transferred Seamlessly</span>
                    </h1>

                    <p class="hero-description">
                        Transform your diploma achievements into degree credits with our intelligent OCR-powered system.
                        Fast processing, transparent tracking, and compliant with Higher Education Authority standards.
                    </p>

                    <div class="hero-ctas">
                        <a href="{{ route('register') }}" class="btn-hero-primary">
                            <i class="fas fa-graduation-cap"></i>
                            Apply for Exemption
                        </a>
                        <a href="{{ route('login') }}" class="btn-hero-secondary">
                            <i class="fas fa-user-tie"></i>
                            Staff Portal
                        </a>
                    </div>

                    <div class="trust-indicators">
                        <div class="trust-item">
                            <i class="fas fa-lock"></i>
                            <span>Secure & Encrypted</span>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-clock"></i>
                            <span>24/7 Access</span>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-certificate"></i>
                            <span>HEA Compliant</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Bar -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-block fade-in">
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-number font-mono">12,450+</div>
                    <div class="stat-label-block">Applications Processed</div>
                </div>
                <div class="stat-block fade-in fade-in-delay-1">
                    <div class="stat-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="stat-number font-mono">48h</div>
                    <div class="stat-label-block">Average Processing</div>
                </div>
                <div class="stat-block fade-in fade-in-delay-2">
                    <div class="stat-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stat-number font-mono">35,800+</div>
                    <div class="stat-label-block">Credits Transferred</div>
                </div>
                <div class="stat-block fade-in fade-in-delay-3">
                    <div class="stat-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="stat-number font-mono">98.5%</div>
                    <div class="stat-label-block">OCR Accuracy</div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="workflow-section" id="workflow">
        <div class="container">
            <div class="section-header">
                <p class="eyebrow">How It Works</p>
                <h2 class="section-title">Four Simple Steps to Credit Transfer</h2>
                <p class="section-subtitle">
                    Our streamlined digital workflow ensures efficient processing from application submission to final credit transfer.
                </p>
            </div>

            <div class="workflow-grid">
                <div class="workflow-step fade-in">
                    <div class="step-number">01</div>
                    <div class="step-icon-wrapper">
                        <i class="fas fa-cloud-upload-alt step-icon"></i>
                    </div>
                    <h3 class="step-title">Submit Application</h3>
                    <p class="step-desc">Upload your transcript and select courses for credit exemption request.</p>
                </div>

                <div class="workflow-step fade-in fade-in-delay-1">
                    <div class="step-number">02</div>
                    <div class="step-icon-wrapper">
                        <i class="fas fa-microchip step-icon"></i>
                    </div>
                    <h3 class="step-title">Automated Analysis</h3>
                    <p class="step-desc">OCR technology extracts course data and checks against equivalency database.</p>
                </div>

                <div class="workflow-step fade-in fade-in-delay-2">
                    <div class="step-number">03</div>
                    <div class="step-icon-wrapper">
                        <i class="fas fa-user-check step-icon"></i>
                    </div>
                    <h3 class="step-title">Expert Review</h3>
                    <p class="step-desc">Academic advisors verify and approve qualifying course exemptions.</p>
                </div>

                <div class="workflow-step fade-in fade-in-delay-3">
                    <div class="step-number">04</div>
                    <div class="step-icon-wrapper">
                        <i class="fas fa-check-double step-icon"></i>
                    </div>
                    <h3 class="step-title">Credit Transfer</h3>
                    <p class="step-desc">Approved exemptions are recorded and credits applied to your academic record.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-header">
                <p class="eyebrow">Features</p>
                <h2 class="section-title">Built for Every User</h2>
                <p class="section-subtitle">
                    Role-specific tools and dashboards designed for efficient credit exemption management.
                </p>
            </div>

            <div class="features-grid">
                <div class="feature-card fade-in">
                    <div class="feature-card-header">
                        <div class="feature-icon-wrapper student">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <h3 class="feature-title">For Students</h3>
                            <p class="feature-subtitle">Apply and track your exemptions</p>
                        </div>
                    </div>
                    <ul class="feature-list">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Easy transcript upload with drag-and-drop</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Real-time application status tracking</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Automatic course matching suggestions</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Email notifications on status updates</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>View exemption history and credits</span>
                        </li>
                    </ul>
                </div>

                <div class="feature-card fade-in fade-in-delay-1">
                    <div class="feature-card-header">
                        <div class="feature-icon-wrapper staff">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div>
                            <h3 class="feature-title">For Academic Staff</h3>
                            <p class="feature-subtitle">Review and manage applications</p>
                        </div>
                    </div>
                    <ul class="feature-list">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Dashboard with pending application queue</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>OCR-extracted data for quick review</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>One-click approve, reject, or forward</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>External expert syllabus requests</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Course equivalency management</span>
                        </li>
                    </ul>
                </div>

                <div class="feature-card fade-in fade-in-delay-2">
                    <div class="feature-card-header">
                        <div class="feature-icon-wrapper admin">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div>
                            <h3 class="feature-title">For Administrators</h3>
                            <p class="feature-subtitle">System oversight and reporting</p>
                        </div>
                    </div>
                    <ul class="feature-list">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Comprehensive audit trail logging</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Analytics and processing statistics</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>User management and role assignment</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Equivalency database maintenance</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>HEA compliance reporting</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand-col">
                    <div class="footer-brand">
                        <i class="fas fa-university"></i>
                        UiTM<span class="accent">CES</span>
                    </div>
                    <p class="footer-desc">
                        Credit Exemption System for Universiti Teknologi MARA.
                        Streamlining the transfer of academic credits for students transitioning from diploma to degree programs.
                    </p>
                    <div class="footer-contact">
                        <div class="footer-contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>40450 Shah Alam, Selangor, Malaysia</span>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>credit-exemption@uitm.edu.my</span>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+603-5544 2000</span>
                        </div>
                    </div>
                </div>

                <div class="footer-links-col">
                    <h4 class="footer-heading">Official Links</h4>
                    <ul class="footer-links">
                        <li><a href="https://www.uitm.edu.my" target="_blank" rel="noopener">UiTM Official Website</a></li>
                        <li><a href="https://student.uitm.edu.my" target="_blank" rel="noopener">Student Portal (SIS)</a></li>
                        <li><a href="https://icress.uitm.edu.my" target="_blank" rel="noopener">iCRESS</a></li>
                        <li><a href="https://mqa.gov.my" target="_blank" rel="noopener">MQA Portal</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-copyright">
                    &copy; {{ date('Y') }} Universiti Teknologi MARA. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile nav toggle
        const navToggle = document.getElementById('navToggle');
        const authButtons = document.getElementById('authButtons');

        navToggle.addEventListener('click', () => {
            authButtons.classList.toggle('show');
        });

        // Close mobile nav when clicking outside
        document.addEventListener('click', (e) => {
            if (!navToggle.contains(e.target) && !authButtons.contains(e.target)) {
                authButtons.classList.remove('show');
            }
        });

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const navHeight = navbar.offsetHeight;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navHeight;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
