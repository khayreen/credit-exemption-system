<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UiTM Credit Exemption System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
            overflow-x: hidden;
        }
        
        /* Main Navigation */
        .main-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--uitm-primary) !important;
            text-decoration: none;
        }
        
        .navbar-brand .accent {
            color: var(--uitm-red);
        }
        
        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-login {
            background: transparent;
            color: var(--uitm-primary);
            border: 2px solid var(--uitm-primary);
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: var(--uitm-primary);
            color: white;
        }
        
        .btn-register {
            background: var(--uitm-red);
            color: white;
            border: 2px solid var(--uitm-red);
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            background: transparent;
            color: var(--uitm-red);
        }
        
        /* Hero Section */
        .hero-section {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), 
                        url('https://malaysiabangkit.com/wp-content/uploads/2024/11/UITM-pelajar.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .hero-content {
            text-align: center;
            color: white;
            max-width: 800px;
            padding: 0 20px;
            animation: fadeInUp 1s ease-out;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            margin-bottom: 20px;
            opacity: 0.9;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 30px;
            line-height: 1.1;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .hero-description {
            font-size: 1.3rem;
            font-weight: 400;
            line-height: 1.6;
            margin-bottom: 50px;
            opacity: 0.95;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        .btn-primary-hero {
            background: var(--uitm-red);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
            display: inline-block;
        }
        
        .btn-primary-hero:hover {
            background: #b91c1c;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(220, 38, 38, 0.4);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-description {
                font-size: 1.1rem;
            }
            
            .btn-primary-hero {
                width: 100%;
                max-width: 300px;
            }
            
            .auth-buttons {
                flex-direction: column;
                gap: 5px;
            }
            
            .btn-login,
            .btn-register {
                font-size: 0.9rem;
                padding: 6px 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Main Navigation -->
    <nav class="main-navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ url('/') }}" class="navbar-brand">
                    <i class="fas fa-university me-2"></i>
                    UiTM<span class="accent">CES</span>
                </a>
                
                <div class="auth-buttons">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/home') }}" class="btn-login">
                                <i class="fas fa-dashboard me-1"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-login">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-register">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-subtitle">Universiti Teknologi MARA</div>
            <h1 class="hero-title">Your Fast Track to Graduation</h1>
            <p class="hero-description">
            Turn your previous achievements into credits. With smart OCR to instantly process your documents, our effortless digital system helps you save time and money on your degree.</p>
            <div class="text-center">
                <a href="{{ route('login') }}" class="btn-primary-hero">
                    Apply Now
                </a>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>