<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>2FA Setup - UiTM Credit Exemption System</title>

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
            padding: 2rem 0;
        }

        .setup-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 500px;
            padding: 1rem;
        }

        .setup-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            transition: all 0.3s ease;
        }

        .setup-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.15);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-secondary));
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: 700;
            box-shadow: 0 16px 32px rgba(30, 58, 138, 0.3);
            margin-bottom: 1rem;
        }

        .setup-title {
            color: var(--uitm-dark);
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .setup-subtitle {
            color: var(--uitm-secondary);
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .step-section {
            margin-bottom: 2rem;
        }

        .step-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .step-badge {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-secondary));
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            margin-right: 1rem;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        .step-title {
            color: var(--uitm-primary);
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }

        .step-content {
            margin-left: 56px;
        }

        .auth-apps {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .auth-app-card {
            background: rgba(59, 130, 246, 0.05);
            border: 2px solid rgba(59, 130, 246, 0.1);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }

        .auth-app-card h6 {
            color: var(--uitm-dark);
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .auth-app-card small {
            color: #6b7280;
            font-size: 0.8rem;
        }

        .qr-section {
            background: rgba(59, 130, 246, 0.05);
            border: 2px solid rgba(59, 130, 246, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .qr-section img {
            max-width: 200px;
            border-radius: 12px;
        }

        .manual-setup {
            background: rgba(245, 158, 11, 0.05);
            border: 2px solid rgba(245, 158, 11, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .manual-setup h6 {
            color: #92400e;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .secret-key-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin: 0.5rem 0;
        }

        .secret-key {
            background: white;
            border: 2px solid rgba(245, 158, 11, 0.3);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-family: 'Courier New', monospace;
            font-size: 1rem;
            font-weight: 600;
            color: var(--uitm-dark);
            letter-spacing: 2px;
        }

        .btn-copy {
            background: linear-gradient(135deg, var(--uitm-accent), #fbbf24);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-copy:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .otp-container {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .otp-input {
            width: 50px;
            height: 60px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1.5rem;
            text-align: center;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .otp-input:focus {
            border-color: var(--uitm-secondary);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
            outline: none;
        }

        .otp-input.is-invalid {
            border-color: #dc2626;
        }

        #one_time_password {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .invalid-feedback {
            color: #dc2626;
            font-weight: 500;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .form-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
            display: block;
        }

        .btn-setup {
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
            margin-top: 1rem;
        }

        .btn-setup:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(30, 58, 138, 0.4);
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

        @media (max-width: 576px) {
            .setup-card {
                padding: 2rem;
                margin: 1rem;
            }

            .auth-apps {
                grid-template-columns: 1fr;
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
    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <!-- Setup Container -->
    <div class="setup-container">
        <div class="setup-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="setup-title">Set Up 2FA</h1>
                <p class="setup-subtitle">Secure your account with two-factor authentication</p>
            </div>

            <!-- Alert -->
            <div class="alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Important:</strong> Two-factor authentication adds an extra layer of security. You'll need to enter a code from your authenticator app every time you log in.
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('2fa.verify') }}">
                @csrf

                <!-- Step 1: Install App -->
                <div class="step-section">
                    <div class="step-header">
                        <div class="step-badge">1</div>
                        <h5 class="step-title">Install Authenticator App</h5>
                    </div>
                    <div class="step-content">
                        <p style="color: #6b7280; font-size: 0.95rem; margin-bottom: 1rem;">Download one of these authenticator apps on your mobile device:</p>
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
                <div class="step-section">
                    <div class="step-header">
                        <div class="step-badge">2</div>
                        <h5 class="step-title">Scan QR Code</h5>
                    </div>
                    <div class="step-content">
                        <p style="color: #6b7280; font-size: 0.95rem; margin-bottom: 1rem;">Open your authenticator app and scan this QR code:</p>
                        <div class="qr-section">
                            <img src="{{ $qrCodeUrl }}" alt="QR Code">
                        </div>

                        <!-- Manual Setup -->
                        <div class="manual-setup">
                            <h6><i class="fas fa-keyboard me-1"></i>Can't scan? Manual Setup:</h6>
                            <p style="color: #92400e; font-size: 0.85rem; margin-bottom: 0.5rem;">In your authenticator app, choose "Enter a setup key" and use:</p>
                            <div class="secret-key-container">
                                <code id="secretKey" class="secret-key">{{ $secretKey }}</code>
                                <button type="button" class="btn btn-copy" onclick="copySecretKey()">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <small style="color: #92400e; font-size: 0.8rem;">Account: {{ Auth::user()->email }} | Type: Time based</small>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Verify Code -->
                <div class="step-section">
                    <div class="step-header">
                        <div class="step-badge">3</div>
                        <h5 class="step-title">Verify Code</h5>
                    </div>
                    <div class="step-content">
                        <p style="color: #6b7280; font-size: 0.95rem; margin-bottom: 1rem;">Enter the 6-digit code from your authenticator app:</p>

                        <!-- Hidden input for form submission -->
                        <input type="text"
                               id="one_time_password"
                               name="one_time_password"
                               pattern="[0-9]{6}"
                               required>

                        <!-- Visible OTP boxes -->
                        <div class="otp-container">
                            <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="0" autofocus>
                            <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="1">
                            <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="2">
                            <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="3">
                            <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="4">
                            <input type="text" class="otp-input @error('one_time_password') is-invalid @enderror" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="5">
                        </div>

                        @error('one_time_password')
                            <div class="invalid-feedback" style="display: block; text-align: center;">{{ $message }}</div>
                        @enderror
                        <small class="form-text" style="text-align: center;">Enter the 6-digit code from your authenticator app</small>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-setup">
                    <i class="fas fa-check-circle me-2"></i>Enable Two-Factor Authentication
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function copySecretKey() {
        const secretKey = document.getElementById('secretKey').textContent;
        navigator.clipboard.writeText(secretKey).then(() => {
            alert('Secret key copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy:', err);
            // Fallback for older browsers
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
    </script>
</body>
</html>
