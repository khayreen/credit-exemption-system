<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - UiTM Credit Exemption System</title>
    
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
        }
        
        .reset-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
            padding: 1rem;
        }
        
        .reset-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            transition: all 0.3s ease;
        }
        
        .reset-card:hover {
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
        
        .reset-title {
            color: var(--uitm-dark);
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .reset-subtitle {
            color: var(--uitm-secondary);
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-floating > .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem 1rem;
            height: auto;
            font-size: 1rem;
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
        
        .btn-reset {
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
        
        .btn-reset:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(30, 58, 138, 0.4);
        }
        
        .btn-reset:focus {
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }
        
        .back-login {
            color: var(--uitm-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .back-login:hover {
            color: var(--uitm-primary);
            text-decoration: underline;
        }
        
        .back-home {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }
        
        .back-home:hover {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-2px);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
            padding: 1rem;
            font-weight: 500;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
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
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .back-link a {
            color: var(--uitm-secondary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-link a:hover {
            color: var(--uitm-primary);
            text-decoration: underline;
        }
        
        .invalid-feedback {
            color: #dc2626;
            font-weight: 500;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        .form-control.is-invalid {
            border-color: #dc2626;
        }
        
        @media (max-width: 576px) {
            .reset-card {
                padding: 2rem;
                margin: 1rem;
            }
            
            .back-home {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 1rem;
                display: inline-flex;
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

    <!-- Back to Home -->
    <a href="{{ url('/') }}" class="back-home d-none d-sm-flex">
        <i class="fas fa-arrow-left"></i>
        Back to Home
    </a>

    <!-- Reset Container -->
    <div class="reset-container">
        <div class="reset-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo">
                    <i class="fas fa-key"></i>
                </div>
                <h1 class="reset-title">Reset Password</h1>
                <p class="reset-subtitle">Enter your email address and we'll send you a password reset link</p>
            </div>

            <!-- Alert Messages -->
            @if (session('status'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Reset Form -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Field -->
                <div class="form-floating">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           placeholder="Enter your email address">
                    <label for="email">
                        <i class="fas fa-envelope me-2"></i>{{ __('Email Address') }}
                    </label>
                    @error('email')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Reset Button -->
                <button type="submit" class="btn btn-reset">
                    <i class="fas fa-paper-plane me-2"></i>
                    {{ __('Send Password Reset Link') }}
                </button>

                <!-- Back to Login Link -->
                <div class="text-center">
                    <a class="back-login" href="{{ route('login') }}">
                        <i class="fas fa-arrow-left me-1"></i>
                        {{ __('Back to Login') }}
                    </a>
                </div>
            </form>

            <!-- Back to Home for Mobile -->
            <div class="text-center mt-3 d-sm-none">
                <a href="{{ url('/') }}" class="back-home">
                    <i class="fas fa-arrow-left"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
