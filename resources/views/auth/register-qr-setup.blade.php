<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set Up Two-Factor Authentication - UiTM Credit Exemption System</title>

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

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                        url('https://malaysiabangkit.com/wp-content/uploads/2024/11/UITM-pelajar.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .setup-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .setup-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.2);
            padding: 3rem;
        }

        .step-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-secondary));
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.25rem;
            margin-right: 1rem;
        }

        .qr-section {
            background: var(--uitm-light);
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
        }

        .recovery-codes {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .recovery-code {
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .btn-verify {
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-secondary));
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(30, 58, 138, 0.3);
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(30, 58, 138, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-card">
            <!-- Header -->
            <div class="text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-shield-alt" style="font-size: 3rem; color: var(--uitm-primary);"></i>
                </div>
                <h2 class="fw-bold mb-2">Set Up Two-Factor Authentication</h2>
                <p class="text-muted">One more step to secure your account, {{ $user->name }}!</p>
            </div>

            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Error!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register.qr.verify') }}">
                @csrf

                <!-- Step 1: Install App -->
                <div class="mb-4">
                    <h5 class="fw-bold">
                        <span class="step-badge">1</span>
                        Install Google Authenticator
                    </h5>
                    <p class="ms-5 text-muted">Download Google Authenticator app on your phone if you haven't already.</p>
                </div>

                <!-- Step 2: Scan QR Code -->
                <div class="mb-4">
                    <h5 class="fw-bold">
                        <span class="step-badge">2</span>
                        Scan QR Code
                    </h5>
                    <div class="qr-section">
                        <img src="{{ $qrCodeUrl }}" alt="QR Code" class="img-fluid mb-3" style="max-width: 250px;">
                        <div class="alert alert-info mb-0">
                            <strong><i class="fas fa-info-circle"></i> Can't scan?</strong><br>
                            Your account will be labeled: <strong>UiTM: {{ $user->email }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Save Recovery Codes -->
                <div class="mb-4">
                    <h5 class="fw-bold">
                        <span class="step-badge">3</span>
                        Save Recovery Codes
                    </h5>
                    <div class="recovery-codes">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                <strong>Important!</strong> Save these codes in a safe place.
                            </div>
                            <button type="button" class="btn btn-sm btn-warning" onclick="copyRecoveryCodes()">
                                <i class="fas fa-copy"></i> Copy All
                            </button>
                        </div>
                        <div class="row">
                            @foreach($recoveryCodes as $code)
                                <div class="col-md-6 mb-2">
                                    <div class="recovery-code">{{ $code }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="recovery_codes_confirmed"
                                   id="recovery_codes_confirmed" value="1" required>
                            <label class="form-check-label fw-bold" for="recovery_codes_confirmed">
                                I have saved my recovery codes in a safe place
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Verify -->
                <div class="mb-4">
                    <h5 class="fw-bold">
                        <span class="step-badge">4</span>
                        Enter Verification Code
                    </h5>
                    <p class="ms-5 text-muted mb-3">Enter the 6-digit code from your Google Authenticator app:</p>
                    <div class="ms-5">
                        <input type="text"
                               class="form-control form-control-lg text-center @error('one_time_password') is-invalid @enderror"
                               id="one_time_password"
                               name="one_time_password"
                               placeholder="000000"
                               maxlength="6"
                               pattern="[0-9]{6}"
                               required
                               autofocus
                               style="font-size: 2rem; letter-spacing: 0.5rem; max-width: 300px;">
                        @error('one_time_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-verify">
                        <i class="fas fa-check-circle me-2"></i>
                        Complete Registration
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-format OTP input (numbers only)
        document.getElementById('one_time_password').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Copy recovery codes function
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
