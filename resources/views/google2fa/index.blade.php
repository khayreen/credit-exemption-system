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
            padding: 20px 0;
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
            text-align: center;
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
            margin-bottom: 2rem;
        }
        
        .setup-description {
            color: #6b7280;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .qr-container {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin: 2rem auto;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            border: 2px solid #e5e7eb;
            text-align: center;
            max-width: 300px;
        }
        
        .qr-container img,
        .qr-container svg {
            max-width: 250px;
            max-height: 250px;
            width: 100%;
            height: auto;
            border-radius: 8px;
            border: none;
            outline: none;
            display: block;
            margin: 0 auto;
        }
        
        .warning-message {
            background: linear-gradient(135deg, #fef3c7, #fed7aa);
            color: #92400e;
            padding: 1rem;
            border-radius: 12px;
            margin: 1.5rem 0;
            font-weight: 500;
            border: 2px solid #f59e0b;
            text-align: center;
        }
        
        .btn-continue {
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-secondary));
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(30, 58, 138, 0.3);
            display: inline-block;
            margin-top: 1rem;
        }
        
        .btn-continue:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(30, 58, 138, 0.4);
        }
        
        .btn-continue:focus {
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }
        
        .steps {
            text-align: left;
            margin: 2rem 0;
        }
        
        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding: 0.5rem;
        }
        
        .step-number {
            background: var(--uitm-secondary);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .step-text {
            color: var(--uitm-dark);
            font-weight: 500;
            line-height: 1.5;
        }
        
        @media (max-width: 576px) {
            .setup-card {
                padding: 2rem;
                margin: 1rem;
            }
            
            .qr-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Setup Container -->
    <div class="setup-container">
        <div class="setup-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="setup-title">Set up Google Authenticator</h1>
                <p class="setup-subtitle">Secure your UiTM CES account</p>
            </div>

            <!-- Setup Instructions -->
            <div class="setup-description">
                Set up your two-factor authentication by scanning the barcode below. 
                Alternatively, you can use the code provided.
            </div>

            <!-- Steps -->
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-text">Download Google Authenticator app on your mobile device</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-text">Scan the QR code below or enter the secret code manually</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-text">Enter the 6-digit code from your app to verify setup</div>
                </div>
            </div>

            <!-- QR Code -->
            <div class="qr-container">
                {!! $qrCodeUrl !!}
            </div>

            <!-- Manual Entry Code -->
            <div style="margin-top: 1.5rem; padding: 1rem; background: #f3f4f6; border-radius: 8px;">
                <p class="text-muted mb-2" style="font-size: 0.9rem;">Or enter this code manually:</p>
                <code style="font-size: 1.1rem; font-weight: 600; color: #1e3a8a; background: white; padding: 0.5rem 1rem; border-radius: 6px; display: inline-block;">{{ $secret }}</code>
            </div>

            <!-- Important Disclaimer -->
            <div class="warning-message">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Important:</strong> You must set up your Google Authenticator app before continuing. 
                You will be unable to login otherwise.
            </div>

            <!-- Continue Button -->
            <a href="{{ route('2fa.verify') }}" class="btn-continue">
                <i class="fas fa-arrow-right me-2"></i>
                Continue to Verification
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>