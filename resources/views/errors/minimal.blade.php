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
            align-items: center;
            justify-content: center;
        }

        .error-container {
            text-align: center;
            padding: 2rem;
        }

        .error-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
        }

        .error-code {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            padding-right: 1.5rem;
            border-right: 2px solid var(--neutral-200);
        }

        .error-message {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--neutral-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .error-actions {
            margin-top: 2rem;
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
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        @media (max-width: 576px) {
            .error-content {
                flex-direction: column;
                gap: 1rem;
            }

            .error-code {
                padding-right: 0;
                border-right: none;
                padding-bottom: 1rem;
                border-bottom: 2px solid var(--neutral-200);
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-content">
            <div class="error-code">@yield('code')</div>
            <div class="error-message">@yield('message')</div>
        </div>
        <div class="error-actions">
            <a href="/" class="btn-home">
                <i class="fas fa-home"></i>
                Go to Homepage
            </a>
        </div>
    </div>
</body>
</html>
