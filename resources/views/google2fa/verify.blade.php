<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>2FA Verification - UiTM Credit Exemption System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --uitm-blue: #1e3a8a;
            --uitm-blue-light: #3b82f6;
            --uitm-amber: #f59e0b;
            --industrial-dark: #0f172a;
            --industrial-gray: #334155;
            --industrial-light: #f1f5f9;
            --success-color: #059669;
            --danger-color: #dc2626;
            --warning-color: #ea580c;
            --info-color: #0d9488;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background: linear-gradient(135deg, var(--industrial-dark) 0%, var(--uitm-blue) 50%, var(--industrial-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .verify-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
        }

        .verify-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-blue-light), var(--uitm-amber));
        }

        .logo {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.75rem;
            margin-bottom: 1rem;
            backdrop-filter: blur(10px);
        }

        .verify-title {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            letter-spacing: -0.025em;
        }

        .verify-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .card-body {
            padding: 2rem;
        }

        /* Info Box */
        .info-box {
            background: var(--industrial-light);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .info-box-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--success-color), #10b981);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .info-box-text {
            font-size: 0.9rem;
            color: var(--industrial-gray);
            line-height: 1.5;
        }

        /* Alert Messages */
        .alert-custom {
            background: rgba(220, 38, 38, 0.08);
            border: 1px solid rgba(220, 38, 38, 0.2);
            border-left: 4px solid var(--danger-color);
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--danger-color);
        }

        .alert-custom i {
            font-size: 1.1rem;
        }

        .alert-custom span {
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Code Input */
        .code-input-group {
            margin-bottom: 1.5rem;
        }

        .code-input-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--industrial-gray);
            margin-bottom: 0.75rem;
        }

        .code-input-label i {
            color: var(--uitm-blue);
        }

        .code-input {
            width: 100%;
            padding: 1rem 1.25rem;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            letter-spacing: 0.5em;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            color: var(--industrial-dark);
            transition: all 0.2s ease;
        }

        .code-input:focus {
            outline: none;
            border-color: var(--uitm-blue);
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1);
        }

        .code-input::placeholder {
            color: #cbd5e1;
            letter-spacing: 0.25em;
        }

        /* Verify Button */
        .btn-verify {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
            color: #fff;
            border: none;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px 0 rgba(30, 58, 138, 0.3);
            margin-bottom: 1.5rem;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px 0 rgba(30, 58, 138, 0.4);
        }

        .btn-verify:active {
            transform: translateY(0);
        }

        /* Footer Info */
        .footer-info {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .timer-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--industrial-light);
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--industrial-gray);
        }

        .timer-badge i {
            color: var(--uitm-blue);
        }

        .timer-badge span {
            font-family: 'IBM Plex Mono', monospace;
        }

        @media (max-width: 576px) {
            body {
                padding: 1rem;
            }

            .card-header {
                padding: 1.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .code-input {
                font-size: 1.25rem;
                padding: 0.875rem 1rem;
                letter-spacing: 0.3em;
            }
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-card">
            <!-- Card Header -->
            <div class="card-header">
                <div class="logo">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h1 class="verify-title">Verify 2FA Code</h1>
                <p class="verify-subtitle">Two-Factor Authentication</p>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <!-- Info Box -->
                <div class="info-box">
                    <div class="info-box-icon">
                        <i class="fab fa-google"></i>
                    </div>
                    <div class="info-box-text">
                        Open your Google Authenticator app and enter the 6-digit code for UiTM CES
                    </div>
                </div>

                <!-- Alert Messages -->
                @if (session('error'))
                <div class="alert-custom">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                <!-- Verification Form -->
                <form method="POST" action="{{ route('2fa.verify.post') }}">
                    @csrf

                    <!-- Code Input -->
                    <div class="code-input-group">
                        <label class="code-input-label">
                            <i class="fas fa-key"></i>
                            Enter 6-Digit Code
                        </label>
                        <input
                            id="one_time_password"
                            type="text"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            class="code-input"
                            name="one_time_password"
                            required
                            autofocus
                            autocomplete="one-time-code"
                            placeholder="000000"
                            maxlength="6"
                        >
                    </div>

                    <!-- Verify Button -->
                    <button type="submit" class="btn-verify">
                        <i class="fas fa-check-circle"></i>
                        Verify & Login
                    </button>
                </form>

                <!-- Footer Info -->
                <div class="footer-info">
                    <div class="timer-badge">
                        <i class="fas fa-sync-alt"></i>
                        Code refreshes every <span>30</span> seconds
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto-focus and format script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('one_time_password');

            // Auto-format the input - only allow digits
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 6) {
                    value = value.substring(0, 6);
                }
                e.target.value = value;
            });

            // Prevent non-numeric input
            input.addEventListener('keypress', function(e) {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
