<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Expired - UiTM Credit Exemption System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --uitm-primary: #1e3a8a;
            --uitm-amber: #f59e0b;
            --uitm-amber-dark: #d97706;
            --uitm-amber-light: #fef3c7;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --font-sans: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'IBM Plex Mono', monospace;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background: var(--slate-100);
            min-height: 100vh;
            color: var(--slate-700);
        }

        /* ========== PAGE HEADER ========== */
        .page-header {
            background: linear-gradient(135deg, var(--uitm-amber) 0%, var(--uitm-amber-dark) 100%);
            position: relative;
            padding: 2.5rem 0;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px),
                linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--uitm-primary), white, var(--uitm-primary));
        }

        .header-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .eyebrow-text {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.75rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .page-title i {
            font-size: 1.75rem;
        }

        .page-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            padding: 3rem 0 4rem;
        }

        /* ========== WARNING CARD ========== */
        .warning-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(245, 158, 11, 0.15);
            border: 1px solid var(--slate-200);
            overflow: hidden;
            text-align: center;
        }

        .warning-icon-wrapper {
            padding: 3rem 2rem 2rem;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.03) 100%);
        }

        .warning-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--uitm-amber) 0%, var(--uitm-amber-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.3);
            position: relative;
        }

        .warning-icon i {
            font-size: 3rem;
            color: white;
            animation: clockTick 1s ease-in-out infinite;
        }

        .warning-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--uitm-amber-dark);
            margin-bottom: 0.75rem;
        }

        .warning-message {
            font-size: 1.0625rem;
            color: var(--slate-600);
            max-width: 500px;
            margin: 0 auto;
        }

        /* ========== INFO SECTION ========== */
        .info-section {
            padding: 2rem;
            border-top: 1px solid var(--slate-200);
        }

        .expiry-info-card {
            background: var(--uitm-amber-light);
            border: 1px solid rgba(245, 158, 11, 0.25);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            text-align: left;
        }

        .expiry-info-card i {
            font-size: 1.5rem;
            color: var(--uitm-amber-dark);
            margin-top: 0.125rem;
        }

        .expiry-info-card-content h6 {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--uitm-amber-dark);
            margin-bottom: 0.375rem;
        }

        .expiry-info-card-content p {
            font-size: 0.875rem;
            color: var(--slate-600);
            margin: 0;
            line-height: 1.5;
        }

        /* ========== HELP CARD ========== */
        .help-card {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.08) 0%, rgba(30, 58, 138, 0.04) 100%);
            border: 1px solid rgba(30, 58, 138, 0.15);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            text-align: left;
        }

        .help-card-icon {
            width: 40px;
            height: 40px;
            background: var(--uitm-primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .help-card-icon i {
            color: white;
            font-size: 1rem;
        }

        .help-card-content h6 {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--uitm-primary);
            margin-bottom: 0.375rem;
        }

        .help-card-content p {
            font-size: 0.875rem;
            color: var(--slate-600);
            margin: 0;
            line-height: 1.5;
        }

        /* ========== FOOTER ========== */
        .card-footer-info {
            padding: 1.5rem 2rem;
            background: var(--slate-50);
            border-top: 1px solid var(--slate-200);
        }

        .card-footer-info p {
            font-size: 0.8125rem;
            color: var(--slate-500);
            margin: 0;
            text-align: center;
        }

        /* ========== ANIMATIONS ========== */
        @keyframes clockTick {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-5deg); }
            75% { transform: rotate(5deg); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .warning-card {
            animation: fadeInUp 0.6s ease forwards;
        }
    </style>
</head>
<body>
    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <div class="header-content">
                <div class="eyebrow-text">UiTM Credit Exemption System</div>
                <h1 class="page-title">
                    <i class="fas fa-clock"></i>
                    Submission Link Expired
                </h1>
                <p class="page-subtitle">This link is no longer valid</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="warning-card">
                        <!-- Warning Icon Section -->
                        <div class="warning-icon-wrapper">
                            <div class="warning-icon">
                                <i class="fas fa-hourglass-end"></i>
                            </div>
                            <h2 class="warning-title">Submission Link Expired</h2>
                            <p class="warning-message">
                                The submission link you're trying to access has expired and is no longer valid for use.
                            </p>
                        </div>

                        <!-- Info Section -->
                        <div class="info-section">
                            <div class="expiry-info-card">
                                <i class="fas fa-info-circle"></i>
                                <div class="expiry-info-card-content">
                                    <h6>About Submission Links</h6>
                                    <p>For security reasons, submission links are only valid for <strong>30 days</strong> from the date they were created. This helps protect the integrity of the credit exemption evaluation process.</p>
                                </div>
                            </div>

                            <div class="help-card">
                                <div class="help-card-icon">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <div class="help-card-content">
                                    <h6>What to do</h6>
                                    <p>Please contact the Resource Person who sent you this link to request a new submission link for your syllabus. They will be able to generate a fresh link with a new 30-day validity period.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="card-footer-info">
                            <p>If you need immediate assistance, please contact the UiTM Credit Exemption Office.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
