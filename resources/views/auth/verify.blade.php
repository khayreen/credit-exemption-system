<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification - UiTM Credit Exemption System</title>
    
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
            max-width: 500px;
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
            text-align: center;
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
            margin-bottom: 2rem;
        }
        
        .verify-description {
            color: #6b7280;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .email-icon-container {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border-radius: 16px;
            padding: 2rem;
            margin: 2rem 0;
            border: 2px solid #bfdbfe;
        }
        
        .email-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--uitm-secondary), var(--uitm-primary));
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
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
        
        .btn-resend {
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-secondary));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(30, 58, 138, 0.3);
            display: inline-block;
            margin-top: 1rem;
        }
        
        .btn-resend:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(30, 58, 138, 0.4);
        }
        
        .btn-resend:focus {
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
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
        
        .instruction-text {
            color: #4b5563;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 576px) {
            .verify-card {
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
    <!-- Back to Home -->
    <a href="{{ url('/') }}" class="back-home d-none d-sm-flex">
        <i class="fas fa-arrow-left"></i>
        Back to Home
    </a>

    <!-- Verify Container -->
    <div class="verify-container">
        <div class="verify-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo">
                    <i class="fas fa-envelope-open"></i>
                </div>
                <h1 class="verify-title">Verify Your Email</h1>
                <p class="verify-subtitle">UiTM Credit Exemption System</p>
            </div>

            <!-- Email Icon -->
            <div class="email-icon-container">
                <div class="email-icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="instruction-text">
                    <strong>Check your email!</strong><br>
                    We've sent a verification link to your email address.
                </div>
            </div>

            <!-- Success Message -->
            @if (session('resent'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            <!-- Instructions -->
            <div class="verify-description">
                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }}, click the button below to request another.
            </div>

            <!-- Resend Form -->
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn-resend">
                    <i class="fas fa-redo me-2"></i>
                    {{ __('Resend Verification Email') }}
                </button>
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
