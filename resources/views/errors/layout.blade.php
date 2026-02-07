<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - UiTM Credit Exemption System</title>

    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --uitm-primary: #1e3a8a;
            --uitm-primary-dark: #1e293b;
            --uitm-amber: #f59e0b;
            --uitm-red: #dc2626;
            --neutral-700: #404040;
            --neutral-600: #525252;
            --neutral-500: #737373;
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
            background: var(--neutral-100);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .error-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .error-card {
            background: white;
            border: 2px solid var(--neutral-200);
            border-radius: 16px;
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        .error-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .error-icon i {
            font-size: 2rem;
            color: white;
        }

        .error-title {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--neutral-700);
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1rem;
            color: var(--neutral-600);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.875rem 1.75rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 58, 138, 0.3);
        }

        @media (max-width: 576px) {
            .error-card {
                padding: 2rem 1.5rem;
            }

            .error-title {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-wrapper">
        <div class="error-card">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="error-title">@yield('title')</h1>
            <p class="error-message">@yield('message')</p>
            <a href="/" class="btn-home">
                <i class="fas fa-home"></i>
                Go to Homepage
            </a>
        </div>
    </div>
</body>
</html>
