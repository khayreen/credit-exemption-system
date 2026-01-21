<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Check Registration Status - UiTM Credit Exemption System</title>

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
            justify-content: center;
            padding: 3rem;
            background: white;
            position: relative;
            overflow-y: auto;
        }

        .form-container {
            width: 100%;
            max-width: 480px;
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
        }

        .alert-warning-custom {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(251, 191, 36, 0.05));
            border: 2px solid rgba(245, 158, 11, 0.3);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: #92400e;
        }

        .alert-warning-custom ul {
            margin: 0.5rem 0 0 0;
            padding-left: 1.25rem;
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
            margin-bottom: 1rem;
        }

        .btn-submit:hover {
            background: var(--uitm-primary-800);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        .btn-secondary-custom {
            width: 100%;
            padding: 0.875rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            color: var(--neutral-700);
            background: transparent;
            border: 2px solid var(--neutral-200);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-secondary-custom:hover {
            border-color: var(--uitm-primary);
            color: var(--uitm-primary);
            background: rgba(30, 58, 138, 0.02);
        }

        /* Status Result Styles */
        .status-result {
            text-align: center;
        }

        .status-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .status-icon.warning { background: linear-gradient(135deg, #fbbf24, #f59e0b); }
        .status-icon.success { background: linear-gradient(135deg, #34d399, #10b981); }
        .status-icon.danger { background: linear-gradient(135deg, #f87171, #ef4444); }

        .status-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .status-user-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--neutral-900);
            margin-bottom: 0.25rem;
        }

        .status-user-email {
            font-size: 0.9rem;
            color: var(--neutral-500);
            margin-bottom: 1.5rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 50px;
            margin-bottom: 1.5rem;
        }

        .status-badge.warning { background: #fef3c7; color: #92400e; }
        .status-badge.success { background: #d1fae5; color: #065f46; }
        .status-badge.danger { background: #fee2e2; color: #991b1b; }

        .status-details {
            background: var(--neutral-50);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .status-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--neutral-200);
        }

        .status-detail-row:last-child {
            border-bottom: none;
        }

        .status-detail-label {
            font-size: 0.85rem;
            color: var(--neutral-500);
            font-weight: 500;
        }

        .status-detail-value {
            font-size: 0.85rem;
            color: var(--neutral-900);
            font-weight: 600;
        }

        .rejection-reason {
            background: #fee2e2;
            border: 2px solid #fecaca;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .rejection-reason h6 {
            font-size: 0.85rem;
            font-weight: 600;
            color: #991b1b;
            margin-bottom: 0.5rem;
        }

        .rejection-reason p {
            font-size: 0.85rem;
            color: #991b1b;
            margin: 0;
        }

        .next-steps {
            background: var(--neutral-50);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .next-steps h6 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--uitm-primary);
            margin-bottom: 0.5rem;
        }

        .next-steps ul {
            margin: 0;
            padding-left: 1.25rem;
            font-size: 0.85rem;
            color: var(--neutral-600);
        }

        .next-steps li {
            margin-bottom: 0.25rem;
        }

        .btn-success-custom {
            width: 100%;
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            letter-spacing: 0.025em;
            text-transform: uppercase;
            color: white;
            background: #10b981;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            text-decoration: none;
        }

        .btn-success-custom:hover {
            background: #059669;
            color: white;
            transform: translateY(-1px);
        }

        .footer-note {
            background: var(--neutral-50);
            border-radius: 8px;
            padding: 1rem;
            font-size: 0.8rem;
            color: var(--neutral-500);
            text-align: center;
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
                <div class="image-hero-eyebrow">Staff Registration</div>
                <h1 class="image-hero-title">Check Registration Status</h1>
                <p class="image-hero-desc">
                    Staff registrations require approval from HEA personnel. Enter your email to check the current status of your registration request.
                </p>
            </div>

            <div class="image-footer">
                <div class="image-footer-badge">
                    <i class="fas fa-user-check"></i>
                    <span>Staff Verification</span>
                </div>
                <div class="image-footer-badge">
                    <i class="fas fa-clock"></i>
                    <span>Usually 1-2 Days</span>
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
                @if(!isset($user))
                    <!-- Search Form -->
                    <div class="form-header animate-fade-in-up">
                        <div class="form-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="form-eyebrow">Staff Registration</div>
                        <h1 class="form-title">Check Status</h1>
                        <p class="form-subtitle">Enter your email address to check the status of your staff registration</p>
                    </div>

                    @if(session('not_found'))
                        <div class="alert-warning-custom animate-fade-in-up delay-1">
                            <strong><i class="fas fa-exclamation-triangle me-2"></i>No registration found</strong> for <strong>{{ session('checked_email') }}</strong>
                            <ul>
                                <li>You haven't registered yet</li>
                                <li>You registered with a different email address</li>
                                <li>You registered as a student (no approval needed)</li>
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('registration.status.check') }}" class="animate-fade-in-up delay-1">
                        @csrf
                        <div class="form-group">
                            <label for="email" class="form-label-custom">Email Address</label>
                            <input type="email"
                                   class="form-input @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', session('checked_email')) }}"
                                   placeholder="Enter the email you used to register"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-search"></i>
                            Check Status
                        </button>

                        <a href="{{ route('login') }}" class="btn-secondary-custom">
                            <i class="fas fa-arrow-left"></i>
                            Back to Login
                        </a>
                    </form>

                @else
                    <!-- Status Results -->
                    <div class="status-result animate-fade-in-up">
                        <div class="status-icon {{ $statusInfo['status_color'] }}">
                            <i class="{{ $statusInfo['status_icon'] }}"></i>
                        </div>
                        <h3 class="status-user-name">{{ $user->name }}</h3>
                        <p class="status-user-email">{{ $checked_email }}</p>
                        <span class="status-badge {{ $statusInfo['status_color'] }}">{{ $statusInfo['status_label'] }}</span>

                        <div class="status-details">
                            <div class="status-detail-row">
                                <span class="status-detail-label"><i class="fas fa-user-tag me-2"></i>Role Requested</span>
                                <span class="status-detail-value">{{ $statusInfo['role_requested'] }}</span>
                            </div>
                            <div class="status-detail-row">
                                <span class="status-detail-label"><i class="fas fa-calendar me-2"></i>Registered</span>
                                <span class="status-detail-value">{{ $statusInfo['registered_at']->format('M j, Y g:i A') }}</span>
                            </div>
                            @if($statusInfo['status'] === 'pending')
                                <div class="status-detail-row">
                                    <span class="status-detail-label"><i class="fas fa-hourglass-half me-2"></i>Waiting</span>
                                    <span class="status-detail-value">{{ $statusInfo['days_waiting'] }} {{ Str::plural('day', $statusInfo['days_waiting']) }}</span>
                                </div>
                            @endif
                            @if($statusInfo['status'] === 'approved' && isset($statusInfo['approved_at']))
                                <div class="status-detail-row">
                                    <span class="status-detail-label"><i class="fas fa-check me-2"></i>Approved</span>
                                    <span class="status-detail-value">{{ $statusInfo['approved_at']->format('M j, Y g:i A') }}</span>
                                </div>
                                <div class="status-detail-row">
                                    <span class="status-detail-label"><i class="fas fa-envelope me-2"></i>Email Verified</span>
                                    <span class="status-detail-value">
                                        @if($statusInfo['email_verified'])
                                            <span style="color: #10b981;"><i class="fas fa-check-circle"></i> Yes</span>
                                        @else
                                            <span style="color: #f59e0b;"><i class="fas fa-clock"></i> Pending</span>
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>

                        @if($statusInfo['status'] === 'rejected' && !empty($statusInfo['rejection_reason']))
                            <div class="rejection-reason">
                                <h6><i class="fas fa-info-circle me-2"></i>Rejection Reason</h6>
                                <p>{{ $statusInfo['rejection_reason'] }}</p>
                            </div>
                        @endif

                        <div class="next-steps">
                            <h6><i class="fas fa-arrow-right me-2"></i>Next Steps</h6>
                            <ul>
                                @foreach($statusInfo['next_steps'] as $step)
                                    <li>{{ $step }}</li>
                                @endforeach
                            </ul>
                        </div>

                        @if($statusInfo['status'] === 'approved' && $statusInfo['email_verified'])
                            <a href="{{ route('login') }}" class="btn-success-custom">
                                <i class="fas fa-sign-in-alt"></i>
                                Login to Your Account
                            </a>
                        @endif

                        <a href="{{ route('registration.status') }}" class="btn-submit">
                            <i class="fas fa-search"></i>
                            Check Another Email
                        </a>

                        <a href="{{ route('login') }}" class="btn-secondary-custom">
                            <i class="fas fa-arrow-left"></i>
                            Back to Login
                        </a>
                    </div>
                @endif

                <div class="footer-note animate-fade-in-up delay-2">
                    <i class="fas fa-info-circle me-1"></i>
                    Need help? Contact the HEA office for assistance with your registration.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
