<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Successful - UiTM Credit Exemption System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --uitm-primary: #1e3a8a;
            --uitm-primary-dark: #1e2d5b;
            --uitm-amber: #f59e0b;
            --uitm-green: #10b981;
            --uitm-green-dark: #059669;
            --uitm-green-light: #d1fae5;
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
            background: linear-gradient(135deg, var(--uitm-green) 0%, var(--uitm-green-dark) 100%);
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
                linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px),
                linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px);
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
            background: linear-gradient(90deg, var(--uitm-amber), white, var(--uitm-amber));
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
            color: rgba(255, 255, 255, 0.8);
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
        }

        .page-title i {
            font-size: 1.75rem;
        }

        .page-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.85);
            margin: 0;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            padding: 3rem 0 4rem;
        }

        /* ========== SUCCESS CARD ========== */
        .success-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(16, 185, 129, 0.15);
            border: 1px solid var(--slate-200);
            overflow: hidden;
            text-align: center;
        }

        .success-icon-wrapper {
            padding: 3rem 2rem 2rem;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(16, 185, 129, 0.02) 100%);
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--uitm-green) 0%, var(--uitm-green-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
            animation: scaleIn 0.5s ease forwards, pulse 2s ease-in-out infinite 0.5s;
        }

        .success-icon i {
            font-size: 3rem;
            color: white;
        }

        .success-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--uitm-green-dark);
            margin-bottom: 0.75rem;
        }

        .success-message {
            font-size: 1.0625rem;
            color: var(--slate-600);
            max-width: 500px;
            margin: 0 auto;
        }

        .success-message strong {
            color: var(--slate-800);
        }

        /* ========== DETAILS SECTION ========== */
        .details-section {
            padding: 2rem;
            border-top: 1px solid var(--slate-200);
        }

        .details-card {
            background: var(--uitm-green-light);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .details-card-title {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--uitm-green-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            text-align: left;
        }

        @media (max-width: 576px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }

        .detail-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--slate-500);
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .detail-value {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--slate-800);
        }

        /* ========== INFO CARD ========== */
        .info-card {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.08) 0%, rgba(30, 58, 138, 0.04) 100%);
            border: 1px solid rgba(30, 58, 138, 0.15);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            text-align: left;
        }

        .info-card-icon {
            width: 40px;
            height: 40px;
            background: var(--uitm-primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-card-icon i {
            color: white;
            font-size: 1rem;
        }

        .info-card-content h6 {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--uitm-primary);
            margin-bottom: 0.375rem;
        }

        .info-card-content p {
            font-size: 0.875rem;
            color: var(--slate-600);
            margin: 0;
            line-height: 1.5;
        }

        /* ========== SECURITY FOOTER ========== */
        .security-footer {
            padding: 1.5rem 2rem;
            background: var(--slate-50);
            border-top: 1px solid var(--slate-200);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .security-footer i {
            color: var(--uitm-green);
        }

        .security-footer span {
            font-size: 0.8125rem;
            color: var(--slate-500);
        }

        /* ========== CONTACT INFO ========== */
        .contact-info {
            text-align: center;
            margin-top: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            border: 1px solid var(--slate-200);
        }

        .contact-info p {
            font-size: 0.875rem;
            color: var(--slate-500);
            margin: 0;
        }

        /* ========== ANIMATIONS ========== */
        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
            }
            50% {
                box-shadow: 0 8px 40px rgba(16, 185, 129, 0.5);
            }
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

        .success-card {
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
                    <i class="fas fa-check-circle"></i>
                    Submission Successful
                </h1>
                <p class="page-subtitle">Your syllabus has been received and recorded</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="success-card">
                        <!-- Success Icon Section -->
                        <div class="success-icon-wrapper">
                            <div class="success-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <h2 class="success-title">Submission Successful!</h2>
                            <p class="success-message">
                                Thank you, <strong>{{ $externalRequest->external_lecturer_name }}</strong>, for submitting the complete syllabus.
                            </p>
                        </div>

                        <!-- Details Section -->
                        <div class="details-section">
                            <div class="details-card">
                                <div class="details-card-title">
                                    <i class="fas fa-file-alt"></i>
                                    Submission Details
                                </div>
                                <div class="details-grid">
                                    <div class="detail-item">
                                        <span class="detail-label">Course Code</span>
                                        <span class="detail-value">{{ $submission->course_code }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Course Name</span>
                                        <span class="detail-value">{{ $submission->course_name }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Credit Hours</span>
                                        <span class="detail-value">{{ $submission->credit_hours }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Institution</span>
                                        <span class="detail-value">{{ $submission->institution_name }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Submitted At</span>
                                        <span class="detail-value">{{ $externalRequest->submitted_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">File</span>
                                        <span class="detail-value">{{ $submission->syllabus_file_original_name }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-card-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="info-card-content">
                                    <h6>What happens next?</h6>
                                    <p>Your syllabus submission has been forwarded to the Resource Person for review. The credit exemption evaluation process will continue, and the student will be notified of any updates regarding their application.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Security Footer -->
                        <div class="security-footer">
                            <i class="fas fa-shield-alt"></i>
                            <span>Your submission has been securely stored and digitally signed for authenticity.</span>
                        </div>
                    </div>

                    <div class="contact-info">
                        <p>If you have any questions about this submission, please contact the UiTM Credit Exemption Office.</p>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
