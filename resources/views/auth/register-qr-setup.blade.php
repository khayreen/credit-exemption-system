<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set Up Two-Factor Authentication - UiTM Credit Exemption System</title>

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
            max-width: 520px;
        }

        .form-header {
            margin-bottom: 1.5rem;
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
            margin-bottom: 0.5rem;
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--neutral-900);
            margin-bottom: 0.25rem;
        }

        .form-subtitle {
            font-size: 0.85rem;
            color: var(--neutral-500);
        }

        .alert-danger-custom {
            background: #fef2f2;
            border: 2px solid #fecaca;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger-custom ul {
            margin: 0.5rem 0 0 0;
            padding-left: 1.25rem;
            font-size: 0.85rem;
            color: #991b1b;
        }

        .step-section {
            margin-bottom: 1.5rem;
        }

        .step-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .step-badge {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--uitm-primary), #3b82f6);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .step-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--uitm-primary);
            margin: 0;
        }

        .step-content {
            margin-left: 44px;
        }

        .step-content p {
            font-size: 0.85rem;
            color: var(--neutral-600);
            margin-bottom: 0.75rem;
        }

        .qr-section {
            background: var(--neutral-50);
            border: 2px solid var(--neutral-200);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }

        .qr-section img {
            max-width: 200px;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-info-qr {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.05), rgba(59, 130, 246, 0.05));
            border: 2px solid rgba(30, 58, 138, 0.1);
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.8rem;
            color: var(--uitm-primary);
            margin: 0;
        }

        .recovery-codes {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(251, 191, 36, 0.05));
            border: 2px solid rgba(245, 158, 11, 0.3);
            border-radius: 12px;
            padding: 1.25rem;
        }

        .recovery-codes-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .recovery-codes-header span {
            font-size: 0.85rem;
            color: #92400e;
            font-weight: 500;
        }

        .btn-copy-codes {
            background: var(--uitm-amber);
            color: white;
            border: none;
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-copy-codes:hover {
            background: #d97706;
        }

        .recovery-codes-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .recovery-code {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            background: white;
            border-radius: 6px;
            text-align: center;
            color: var(--neutral-900);
        }

        .form-check-custom {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .form-check-custom input {
            margin-top: 0.25rem;
            width: 18px;
            height: 18px;
            accent-color: var(--uitm-primary);
        }

        .form-check-custom label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #92400e;
        }

        .verification-input {
            width: 100%;
            max-width: 200px;
            padding: 0.75rem 1rem;
            font-size: 1.5rem;
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 700;
            text-align: center;
            letter-spacing: 0.5rem;
            border: 2px solid var(--neutral-200);
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .verification-input:focus {
            outline: none;
            border-color: var(--uitm-primary);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .verification-input.is-invalid {
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
                font-size: 1.25rem;
            }

            .form-container {
                max-width: 100%;
            }

            .recovery-codes-grid {
                grid-template-columns: 1fr;
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
                <div class="image-hero-eyebrow">Final Step</div>
                <h1 class="image-hero-title">Complete Your Registration</h1>
                <p class="image-hero-desc">
                    Set up two-factor authentication to secure your account. This is the final step of your registration process.
                </p>
            </div>

            <div class="image-footer">
                <div class="image-footer-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Account Security</span>
                </div>
                <div class="image-footer-badge">
                    <i class="fas fa-check-circle"></i>
                    <span>Almost Done</span>
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
                    <div class="form-eyebrow">Final Step</div>
                    <h1 class="form-title">Set Up Two-Factor Authentication</h1>
                    <p class="form-subtitle">One more step to secure your account, {{ $user->name }}!</p>
                </div>

                @if ($errors->any())
                    <div class="alert-danger-custom animate-fade-in-up delay-1">
                        <strong><i class="fas fa-exclamation-circle me-2"></i>Error!</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.qr.verify') }}">
                    @csrf

                    <!-- Step 1: Install App -->
                    <div class="step-section animate-fade-in-up delay-1">
                        <div class="step-header">
                            <div class="step-badge">1</div>
                            <h5 class="step-title">Install Google Authenticator</h5>
                        </div>
                        <div class="step-content">
                            <p>Download Google Authenticator app on your phone if you haven't already.</p>
                        </div>
                    </div>

                    <!-- Step 2: Scan QR Code -->
                    <div class="step-section animate-fade-in-up delay-1">
                        <div class="step-header">
                            <div class="step-badge">2</div>
                            <h5 class="step-title">Scan QR Code</h5>
                        </div>
                        <div class="step-content">
                            <div class="qr-section">
                                <img src="{{ $qrCodeUrl }}" alt="QR Code">
                                <div class="alert-info-qr">
                                    <strong><i class="fas fa-info-circle me-1"></i>Can't scan?</strong><br>
                                    Your account will be labeled: <strong>UiTM: {{ $user->email }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Save Recovery Codes -->
                    <div class="step-section animate-fade-in-up delay-1">
                        <div class="step-header">
                            <div class="step-badge">3</div>
                            <h5 class="step-title">Save Recovery Codes</h5>
                        </div>
                        <div class="step-content">
                            <div class="recovery-codes">
                                <div class="recovery-codes-header">
                                    <span><i class="fas fa-exclamation-triangle me-1"></i>Important! Save these codes safely.</span>
                                    <button type="button" class="btn-copy-codes" onclick="copyRecoveryCodes()">
                                        <i class="fas fa-copy me-1"></i>Copy All
                                    </button>
                                </div>
                                <div class="recovery-codes-grid">
                                    @foreach($recoveryCodes as $code)
                                        <div class="recovery-code">{{ $code }}</div>
                                    @endforeach
                                </div>
                                <div class="form-check-custom">
                                    <input type="checkbox" name="recovery_codes_confirmed" id="recovery_codes_confirmed" value="1" required>
                                    <label for="recovery_codes_confirmed">I have saved my recovery codes in a safe place</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Verify -->
                    <div class="step-section animate-fade-in-up delay-2">
                        <div class="step-header">
                            <div class="step-badge">4</div>
                            <h5 class="step-title">Enter Verification Code</h5>
                        </div>
                        <div class="step-content">
                            <p>Enter the 6-digit code from your Google Authenticator app:</p>
                            <input type="text"
                                   class="verification-input @error('one_time_password') is-invalid @enderror"
                                   id="one_time_password"
                                   name="one_time_password"
                                   placeholder="000000"
                                   maxlength="6"
                                   pattern="[0-9]{6}"
                                   required
                                   autofocus>
                            @error('one_time_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn-submit animate-fade-in-up delay-2">
                        <i class="fas fa-check-circle"></i>
                        Complete Registration
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('one_time_password').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        function copyRecoveryCodes() {
            const codes = @json($recoveryCodes);
            const text = codes.join('\n');

            navigator.clipboard.writeText(text).then(function() {
                alert('Recovery codes copied to clipboard!\nPaste them somewhere safe.');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
</body>
</html>
