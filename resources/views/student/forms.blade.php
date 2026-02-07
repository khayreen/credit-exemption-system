@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e293b;
        --uitm-primary-light: #3b82f6;
        --uitm-red: #dc2626;
        --uitm-amber: #f59e0b;
        --uitm-green: #10b981;
        --neutral-900: #171717;
        --neutral-800: #262626;
        --neutral-700: #404040;
        --neutral-600: #525252;
        --neutral-500: #737373;
        --neutral-400: #a3a3a3;
        --neutral-300: #d4d4d4;
        --neutral-200: #e5e5e5;
        --neutral-100: #f5f5f5;
        --neutral-50: #fafafa;
    }

    body {
        font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--neutral-100);
    }

    .forms-container {
        width: 100%;
        padding: 0 1rem;
    }

    /* Page Header */
    .page-header {
        position: relative;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-radius: 16px;
        padding: 2.5rem;
        color: white;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 32px 32px;
        pointer-events: none;
    }

    .page-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-header .eyebrow {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--uitm-amber);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-header .eyebrow::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background: var(--uitm-amber);
        border-radius: 2px;
    }

    .page-header h1 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .page-header p {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 0;
        max-width: 600px;
    }

    .header-illustration {
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 6rem;
        opacity: 0.1;
    }

    /* Form Cards */
    .form-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        padding: 1.5rem;
        height: 100%;
        display: flex;
        align-items: start;
        transition: all 0.2s ease;
    }

    .form-card:hover {
        border-color: var(--neutral-300);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .form-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin-right: 1.25rem;
        flex-shrink: 0;
    }

    .form-card-icon.primary {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
    }

    .form-card-icon.green {
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
    }

    .form-card-icon.amber {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
    }

    .form-card-icon.teal {
        background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
    }

    .form-card-content {
        flex: 1;
    }

    .form-card-content h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .form-card-content p {
        font-size: 0.85rem;
        color: var(--neutral-600);
        margin-bottom: 0.75rem;
        line-height: 1.5;
    }

    .form-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.75rem;
        color: var(--neutral-500);
        margin-bottom: 1rem;
    }

    .form-meta span {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-download {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-download.primary {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
    }

    .btn-download.green {
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
        border: none;
        color: white;
    }

    .btn-download.amber {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
        border: none;
        color: white;
    }

    .btn-download.teal {
        background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
        border: none;
        color: white;
    }

    .btn-download:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        color: white;
    }

    /* HEA Portal Card */
    .hea-portal-card {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hea-portal-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
    }

    .hea-portal-card h4 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .hea-portal-card p {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .btn-hea-portal {
        background: white;
        color: var(--uitm-primary);
        border: none;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        position: relative;
        z-index: 1;
    }

    .btn-hea-portal:hover {
        background: var(--neutral-100);
        color: var(--uitm-primary);
        transform: translateY(-1px);
    }

    /* Tips Card */
    .tips-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .tips-card h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .tips-card h5 i {
        color: var(--uitm-amber);
    }

    .tip-item {
        display: flex;
        align-items: start;
        padding: 1rem;
        background: var(--neutral-50);
        border: 1px solid var(--neutral-200);
        border-radius: 10px;
        height: 100%;
    }

    .tip-number {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 700;
        font-size: 0.85rem;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .tip-item h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .tip-item p {
        color: var(--neutral-600);
        font-size: 0.8rem;
        margin: 0;
        line-height: 1.5;
    }

    /* Back Button */
    .btn-back {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .header-illustration {
            display: none;
        }

        .form-card {
            flex-direction: column;
            text-align: center;
        }

        .form-card-icon {
            margin: 0 auto 1rem;
        }

        .form-meta {
            justify-content: center;
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@section('content')
<div class="forms-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="eyebrow">Resources</div>
            <h1><i class="fas fa-file-download me-2"></i>Download Forms</h1>
            <p>Access all required forms and documents for your credit exemption application.</p>
        </div>
        <div class="header-illustration">
            <i class="fas fa-folder-open"></i>
        </div>
    </div>

    <!-- Available Forms -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="form-card">
                <div class="form-card-icon primary">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="form-card-content">
                    <h5>Credit Exemption Application Form</h5>
                    <p>The main application form for requesting credit exemption. Required for all applications.</p>
                    <div class="form-meta">
                        <span><i class="fas fa-file"></i> PDF Format</span>
                        <span><i class="fas fa-calendar"></i> Updated Jan 2026</span>
                    </div>
                    <a href="https://hea.uitm.edu.my" target="_blank" class="btn-download primary">
                        <i class="fas fa-external-link-alt"></i> Download from HEA
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-card">
                <div class="form-card-icon green">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="form-card-content">
                    <h5>Course Equivalency Request Form</h5>
                    <p>Form for requesting evaluation of courses not in the equivalency database.</p>
                    <div class="form-meta">
                        <span><i class="fas fa-file"></i> PDF Format</span>
                        <span><i class="fas fa-calendar"></i> Updated Jan 2026</span>
                    </div>
                    <a href="https://hea.uitm.edu.my" target="_blank" class="btn-download green">
                        <i class="fas fa-external-link-alt"></i> Download from HEA
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-card">
                <div class="form-card-icon amber">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="form-card-content">
                    <h5>Appeal Form</h5>
                    <p>Form for appealing rejected credit exemption applications.</p>
                    <div class="form-meta">
                        <span><i class="fas fa-file"></i> PDF Format</span>
                        <span><i class="fas fa-calendar"></i> Updated Jan 2026</span>
                    </div>
                    <a href="https://hea.uitm.edu.my" target="_blank" class="btn-download amber">
                        <i class="fas fa-external-link-alt"></i> Download from HEA
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-card">
                <div class="form-card-icon teal">
                    <i class="fas fa-book"></i>
                </div>
                <div class="form-card-content">
                    <h5>Guidelines & Procedures</h5>
                    <p>Complete guide to the credit exemption process and requirements.</p>
                    <div class="form-meta">
                        <span><i class="fas fa-file"></i> PDF Format</span>
                        <span><i class="fas fa-calendar"></i> Updated Jan 2026</span>
                    </div>
                    <a href="https://hea.uitm.edu.my" target="_blank" class="btn-download teal">
                        <i class="fas fa-external-link-alt"></i> Download from HEA
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- HEA Forms Portal -->
    <div class="hea-portal-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4><i class="fas fa-university me-2"></i>HEA Forms Portal</h4>
                <p>Access additional forms and official documents from the Higher Education Authority (HEA) website. All forms are regularly updated to reflect the latest requirements.</p>
            </div>
            <a href="https://hea.uitm.edu.my" target="_blank" class="btn-hea-portal">
                <i class="fas fa-external-link-alt me-1"></i> Visit HEA Portal
            </a>
        </div>
    </div>

    <!-- Tips Section -->
    <div class="tips-card">
        <h5><i class="fas fa-lightbulb me-2"></i>Tips for Completing Forms</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="tip-item">
                    <div class="tip-number">1</div>
                    <div>
                        <h6>Use Latest Versions</h6>
                        <p>Always download the most recent version of forms from the HEA website.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tip-item">
                    <div class="tip-number">2</div>
                    <div>
                        <h6>Complete All Fields</h6>
                        <p>Fill in all required fields accurately. Incomplete forms may delay processing.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tip-item">
                    <div class="tip-number">3</div>
                    <div>
                        <h6>Attach Documents</h6>
                        <p>Include all supporting documents such as transcripts and course syllabi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Dashboard Button -->
    <div class="text-center mt-4 mb-5">
        <a href="{{ route('student.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection
