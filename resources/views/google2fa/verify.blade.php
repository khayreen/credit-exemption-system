<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>2FA Verification - UiTM Credit Exemption System</title>
    
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
            --uitm-red: #dc2626;
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
            max-width: 450px;
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
        
        .verify-title {
            color: var(--uitm-dark);
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .verify-subtitle {
            color: var(--uitm-secondary);
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        
        .verify-description {
            color: #6b7280;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-floating > .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem 1rem;
            height: auto;
            font-size: 1.2rem;
            font-weight: 600;
            text-align: center;
            letter-spacing: 3px;
            transition: all 0.3s ease;
        }
        
        .form-floating > .form-control:focus {
            border-color: var(--uitm-secondary);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
        }
        
        .form-floating > label {
            color: #6b7280;
            font-weight: 500;
        }
        
        .btn-verify {
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
            margin-bottom: 1.5rem;
        }
        
        .btn-verify:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(30, 58, 138, 0.4);
        }
        
        .btn-verify:focus {
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
            padding: 1rem;
            font-weight: 500;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }
        
        .code-input-help {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #1e40af;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            text-align: center;
        }
        
        .authenticator-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 0.5rem;
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
    <!-- Verify Container -->
    <div class="verify-container">
        <div class="verify-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h1 class="verify-title">Verify 2FA</h1>
                <p class="verify-subtitle">Two-Factor Authentication</p>
            </div>

            <!-- Description -->
            <div class="verify-description">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="authenticator-icon">
                        <i class="fab fa-google"></i>
                    </div>
                    <span>Enter the code from your Google Authenticator app</span>
                </div>
            </div>

            <!-- Help Text -->
            <div class="code-input-help">
                <i class="fas fa-info-circle me-2"></i>
                Open your Google Authenticator app and enter the 6-digit code for UiTM CES
            </div>

            <!-- Alert Messages -->
            @if (session('error'))
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Verification Form -->
            <form method="POST" action="{{ route('2fa.verify.post') }}">
                @csrf

                <!-- OTP Field -->
                <div class="form-floating">
                    <input id="one_time_password" type="number" class="form-control" 
                           name="one_time_password" required autofocus
                           placeholder="Enter 6-digit code"
                           maxlength="6" min="000000" max="999999">
                    <label for="one_time_password">
                        <i class="fas fa-key me-2"></i>6-Digit Code
                    </label>
                </div>

                <!-- Verify Button -->
                <button type="submit" class="btn btn-verify">
                    <i class="fas fa-check-circle me-2"></i>
                    Verify & Login
                </button>
            </form>

            <!-- Additional Help -->
            <div class="text-center">
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    Code refreshes every 30 seconds
                </small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auto-focus and format script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('one_time_password');
            
            // Auto-format the input
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 6) {
                    value = value.substring(0, 6);
                }
                e.target.value = value;
            });
            
            // Auto-submit when 6 digits are entered
            input.addEventListener('input', function(e) {
                if (e.target.value.length === 6) {
                    // Optional: Auto-submit form when 6 digits entered
                    // e.target.form.submit();
                }
            });
        });
    </script>
</body>
</html>