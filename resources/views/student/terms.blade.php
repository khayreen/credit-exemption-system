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

    .terms-container {
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

    /* Quick Navigation */
    .nav-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .nav-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--neutral-700);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .nav-title i {
        color: var(--uitm-primary);
    }

    .nav-link-card {
        display: flex;
        align-items: center;
        padding: 0.6rem 0.8rem;
        background: var(--neutral-50);
        border: 2px solid var(--neutral-200);
        border-radius: 8px;
        color: var(--neutral-700);
        text-decoration: none;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 500;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }

    .nav-link-card:hover {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-color: transparent;
        color: white;
        transform: translateY(-1px);
    }

    .nav-link-card i {
        margin-right: 0.4rem;
        font-size: 0.75rem;
    }

    /* Update Alert */
    .update-alert {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(14, 165, 233, 0.05) 100%);
        border: 2px solid #0ea5e9;
        border-left-width: 4px;
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: var(--neutral-700);
        font-size: 0.9rem;
    }

    .update-alert i {
        color: #0ea5e9;
    }

    /* Terms Card */
    .terms-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .terms-card-header {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        background: var(--neutral-50);
        border-bottom: 2px solid var(--neutral-200);
    }

    .icon-badge {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .icon-badge.primary { background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%); }
    .icon-badge.green { background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%); }
    .icon-badge.amber { background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%); }
    .icon-badge.info { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .icon-badge.pink { background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); }
    .icon-badge.cyan { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
    .icon-badge.orange { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }

    .terms-card-header h3 {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-weight: 700;
        font-size: 1.15rem;
        margin-bottom: 0.25rem;
    }

    .terms-card-header p {
        color: var(--neutral-500);
        font-size: 0.85rem;
        margin-bottom: 0;
    }

    .terms-card-body {
        padding: 1.5rem;
    }

    /* Content Styles */
    .lead-text {
        font-size: 1rem;
        color: var(--neutral-700);
        line-height: 1.7;
        margin-bottom: 1.25rem;
    }

    .section-subtitle {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        font-size: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--neutral-200);
    }

    .section-subtitle i {
        color: var(--uitm-primary);
    }

    .styled-list {
        list-style: none;
        padding-left: 0;
    }

    .styled-list li {
        padding-left: 1.75rem;
        margin-bottom: 0.75rem;
        color: var(--neutral-600);
        line-height: 1.6;
        position: relative;
        font-size: 0.9rem;
    }

    .styled-list li::before {
        content: "\f00c";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        left: 0;
        color: var(--uitm-green);
        font-size: 0.75rem;
    }

    /* Info & Warning Boxes */
    .info-box {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.08) 0%, rgba(14, 165, 233, 0.03) 100%);
        border-left: 4px solid #0ea5e9;
        border-radius: 10px;
        padding: 1.25rem;
        margin: 1.25rem 0;
    }

    .info-box h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        color: #0369a1;
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .info-box p, .info-box ol {
        color: #0c4a6e;
        margin-bottom: 0;
        line-height: 1.6;
        font-size: 0.9rem;
    }

    .warning-box {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, rgba(245, 158, 11, 0.03) 100%);
        border-left: 4px solid var(--uitm-amber);
        border-radius: 10px;
        padding: 1.25rem;
        margin: 1.25rem 0;
    }

    .warning-box h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        color: #92400e;
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .warning-box ul {
        margin-bottom: 0;
        padding-left: 1.25rem;
    }

    .warning-box li {
        color: #78350f;
        margin-bottom: 0.35rem;
        font-size: 0.85rem;
    }

    /* Criteria Cards */
    .criteria-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        height: 100%;
        transition: all 0.2s ease;
    }

    .criteria-card:hover {
        border-color: var(--uitm-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .criteria-number {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 auto 0.75rem;
    }

    .criteria-card h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .criteria-card p {
        color: var(--neutral-600);
        font-size: 0.8rem;
        margin-bottom: 0.75rem;
        line-height: 1.5;
    }

    .criteria-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(30, 58, 138, 0.05) 100%);
        color: var(--uitm-primary);
        border-radius: 16px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Timeline Cards */
    .timeline-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        height: 100%;
    }

    .timeline-step {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 700;
        margin: 0 auto 0.5rem;
        font-size: 0.9rem;
    }

    .timeline-card strong {
        display: block;
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-size: 0.85rem;
        margin-bottom: 0.35rem;
    }

    .timeline-card p {
        color: var(--neutral-500);
        font-size: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .time-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        background: var(--neutral-100);
        color: var(--neutral-600);
        border-radius: 10px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
    }

    /* Document Items */
    .document-item {
        display: flex;
        align-items: start;
        padding: 1rem;
        background: var(--neutral-50);
        border: 2px solid var(--neutral-200);
        border-radius: 10px;
    }

    .document-item i {
        font-size: 1.5rem;
        color: var(--uitm-primary);
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .document-item strong {
        display: block;
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-size: 0.9rem;
        margin-bottom: 0.35rem;
    }

    .document-item p {
        color: var(--neutral-600);
        font-size: 0.8rem;
        margin: 0;
        line-height: 1.5;
    }

    /* Security Items */
    .security-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: var(--neutral-50);
        border: 2px solid var(--neutral-200);
        border-radius: 10px;
    }

    .security-item i {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .security-item strong {
        display: block;
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-size: 0.85rem;
        margin-bottom: 0.2rem;
    }

    .security-item p {
        color: var(--neutral-500);
        font-size: 0.75rem;
        margin: 0;
    }

    /* Contact Cards */
    .contact-card {
        display: flex;
        align-items: start;
        padding: 1.25rem;
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        transition: all 0.2s ease;
        height: 100%;
    }

    .contact-card:hover {
        border-color: var(--uitm-primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .contact-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .contact-card h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-weight: 700;
        margin-bottom: 0.35rem;
        font-size: 0.95rem;
    }

    .contact-card .text-muted {
        font-size: 0.8rem;
    }

    .contact-card a {
        color: var(--uitm-primary);
        font-weight: 600;
        font-size: 0.85rem;
    }

    /* Acknowledgment Card */
    .acknowledgment-card {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(16, 185, 129, 0.03) 100%);
        border: 2px solid var(--uitm-green);
        border-radius: 12px;
        padding: 2rem;
        margin-top: 2rem;
    }

    .acknowledgment-card h4 {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-weight: 700;
        margin-bottom: 0.75rem;
        font-size: 1.1rem;
    }

    .acknowledgment-card p {
        color: var(--neutral-600);
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 0;
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

    /* Scroll to Top Button */
    .scroll-to-top-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 1.25rem;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: all 0.3s ease;
    }

    .scroll-to-top-btn.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .scroll-to-top-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(30, 58, 138, 0.4);
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

        .terms-card-header {
            flex-direction: column;
            text-align: center;
        }

        .icon-badge {
            margin-right: 0;
            margin-bottom: 0.75rem;
        }

        .terms-card-body {
            padding: 1.25rem;
        }

        .nav-link-card {
            font-size: 0.75rem;
            padding: 0.5rem 0.6rem;
        }

        .scroll-to-top-btn {
            bottom: 20px;
            right: 20px;
            width: 45px;
            height: 45px;
            font-size: 1.1rem;
        }
    }

    @media print {
        .page-header, .nav-link-card, .btn-back, .scroll-to-top-btn {
            display: none;
        }

        .terms-card {
            box-shadow: none;
            border: 1px solid #ccc;
            page-break-inside: avoid;
        }
    }
</style>
@endpush

@section('content')
<div class="terms-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="eyebrow">Legal Information</div>
            <h1><i class="fas fa-file-contract me-2"></i>Terms & Conditions</h1>
            <p>Please read these terms and conditions carefully before using our credit exemption services.</p>
        </div>
        <div class="header-illustration">
            <i class="fas fa-file-contract"></i>
        </div>
    </div>

    <!-- Quick Navigation -->
    <div class="nav-card">
        <h5 class="nav-title">
            <i class="fas fa-list-ul"></i>Quick Navigation
        </h5>
        <div class="row g-2">
            <div class="col-md-3 col-6">
                <a href="#section-1" class="nav-link-card">
                    <i class="fas fa-info-circle"></i> Overview
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="#section-2" class="nav-link-card">
                    <i class="fas fa-check-circle"></i> Eligibility
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="#section-3" class="nav-link-card">
                    <i class="fas fa-file-upload"></i> Application
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="#section-4" class="nav-link-card">
                    <i class="fas fa-graduation-cap"></i> Academic Policy
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="#section-5" class="nav-link-card">
                    <i class="fas fa-balance-scale"></i> Rights
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="#section-6" class="nav-link-card">
                    <i class="fas fa-shield-alt"></i> Privacy
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="#section-7" class="nav-link-card">
                    <i class="fas fa-exclamation-triangle"></i> Disclaimer
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="#section-8" class="nav-link-card">
                    <i class="fas fa-phone-alt"></i> Contact
                </a>
            </div>
        </div>
    </div>

    <!-- Last Updated -->
    <div class="update-alert">
        <i class="fas fa-calendar-check me-2"></i>
        <strong>Last Updated:</strong> December 27, 2025 | <strong>Effective Date:</strong> Semester I 2025/2026
    </div>

    <!-- Section 1: Overview -->
    <div class="terms-card" id="section-1">
        <div class="terms-card-header">
            <div class="icon-badge primary">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <h3>1. Overview & Acceptance</h3>
                <p>Understanding the credit exemption system</p>
            </div>
        </div>
        <div class="terms-card-body">
            <p class="lead-text">By accessing and using the UiTM Credit Exemption Management System, you acknowledge that you have read, understood, and agree to be bound by these terms and conditions.</p>

            <div class="info-box">
                <h6><i class="fas fa-bullhorn me-2"></i>What is Credit Exemption?</h6>
                <p>Credit exemption allows students who have previously completed equivalent courses at diploma level to be exempted from retaking similar courses in their bachelor's degree program. This recognition is based on <strong>course content equivalency, academic performance, and HEA-approved criteria</strong>.</p>
            </div>

            <ul class="styled-list">
                <li>All information provided must be <strong>accurate, complete, and truthful</strong></li>
                <li>You consent to the university's verification of all submitted documents</li>
                <li>Misrepresentation or fraudulent information may result in <strong>application rejection and disciplinary action</strong></li>
                <li>These terms are subject to updates in accordance with UiTM academic regulations</li>
            </ul>
        </div>
    </div>

    <!-- Section 2: Eligibility Criteria -->
    <div class="terms-card" id="section-2">
        <div class="terms-card-header">
            <div class="icon-badge green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h3>2. Eligibility Criteria</h3>
                <p>Requirements for credit exemption qualification</p>
            </div>
        </div>
        <div class="terms-card-body">
            <p class="lead-text">Credit exemption eligibility is determined by the Higher Education Authority (HEA) based on strict academic criteria. All three conditions below must be met:</p>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="criteria-card">
                        <div class="criteria-number">1</div>
                        <h6>Course Match</h6>
                        <p>Diploma course must be listed in the approved equivalency database for your degree program</p>
                        <span class="criteria-badge"><i class="fas fa-book me-1"></i> HEA Approved</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="criteria-card">
                        <div class="criteria-number">2</div>
                        <h6>Grade Requirement</h6>
                        <p>Minimum grade of <strong>C (2.00 GPA)</strong> or higher in the diploma course</p>
                        <span class="criteria-badge"><i class="fas fa-chart-line me-1"></i> C or Above</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="criteria-card">
                        <div class="criteria-number">3</div>
                        <h6>Equivalency Match</h6>
                        <p>Course content similarity must be <strong>>80%</strong> as determined by HEA assessment</p>
                        <span class="criteria-badge"><i class="fas fa-percentage me-1"></i> >80% Match</span>
                    </div>
                </div>
            </div>

            <div class="warning-box">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notice</h6>
                <ul>
                    <li>Grades below C (including C-, D+, D, F) do <strong>not qualify</strong> for exemption</li>
                    <li>Courses not listed in the HEA-endorsed equivalency database require special review by Academic Advisors and Resource Persons</li>
                    <li>External institution courses (non-UiTM) undergo additional verification processes</li>
                    <li>Final decisions are subject to faculty and HEA approval</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Section 3: Application Process -->
    <div class="terms-card" id="section-3">
        <div class="terms-card-header">
            <div class="icon-badge amber">
                <i class="fas fa-file-upload"></i>
            </div>
            <div>
                <h3>3. Application Procedures & Responsibilities</h3>
                <p>Student obligations and submission requirements</p>
            </div>
        </div>
        <div class="terms-card-body">
            <h6 class="section-subtitle"><i class="fas fa-clipboard-check me-2"></i>Required Documents</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="document-item">
                        <i class="fas fa-file-pdf"></i>
                        <div>
                            <strong>Official Academic Transcript</strong>
                            <p>Must show course codes, names, credit hours, and grades. Scanned copies must be clear and legible for OCR processing.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="document-item">
                        <i class="fas fa-book-open"></i>
                        <div>
                            <strong>Course Syllabus (if required)</strong>
                            <p>Detailed course outline may be requested for courses not in the equivalency database. Resource Persons may contact external lecturers for verification.</p>
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="section-subtitle"><i class="fas fa-user-check me-2"></i>Student Responsibilities</h6>
            <ul class="styled-list">
                <li><strong>Document Authenticity:</strong> All submitted documents must be genuine, unaltered, and officially issued by the awarding institution</li>
                <li><strong>Timely Submission:</strong> Applications must be submitted within the designated period for each semester (refer to Academic Calendar)</li>
                <li><strong>Complete Information:</strong> Provide accurate personal details, program information, campus, faculty, and previous institution data</li>
                <li><strong>Response to Queries:</strong> Respond promptly to any requests for additional information from Academic Advisors or Resource Persons</li>
                <li><strong>Status Monitoring:</strong> Regularly check application status through the dashboard for updates and required actions</li>
            </ul>

            <div class="info-box">
                <h6><i class="fas fa-robot me-2"></i>OCR Technology & Verification</h6>
                <p>Our system uses <strong>Optical Character Recognition (OCR)</strong> powered by Google Cloud Vision API to automatically extract course information from transcripts. While this technology is highly accurate, all extracted data undergoes human verification by academic staff. Students are responsible for reviewing OCR-extracted courses for accuracy.</p>
            </div>
        </div>
    </div>

    <!-- Section 4: Academic Policies -->
    <div class="terms-card" id="section-4">
        <div class="terms-card-header">
            <div class="icon-badge info">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div>
                <h3>4. Academic Policies & Regulations</h3>
                <p>University regulations governing credit exemptions</p>
            </div>
        </div>
        <div class="terms-card-body">
            <h6 class="section-subtitle"><i class="fas fa-gavel me-2"></i>UiTM Credit Exemption Policies (2025)</h6>
            <ul class="styled-list">
                <li><strong>Program Eligibility:</strong> Credit exemptions are currently available for five (5) Bachelor's degree programs in Computer Science and Information Systems (CDCS230, CDCS251, CDCS253, CDCS255, CDCS266)</li>
                <li><strong>Maximum Credit Exemption:</strong> The total exempted credit hours must comply with UiTM Senate regulations and faculty-specific limits</li>
                <li><strong>Exemption Validity:</strong> Approved exemptions are valid only for the program and semester specified in the endorsement letter</li>
                <li><strong>Transfer Students:</strong> Students transferring from other UiTM campuses must reapply for exemption verification at the new campus</li>
                <li><strong>Course Retakes:</strong> If an exempted course is voluntarily retaken, the new grade will replace the exemption status</li>
                <li><strong>GPA Calculation:</strong> Exempted courses typically do not contribute to GPA calculation (marked as "Exempt" or "Ex" on transcripts)</li>
            </ul>

            <h6 class="section-subtitle"><i class="fas fa-clock me-2"></i>Processing Timeline</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-3 col-6">
                    <div class="timeline-card">
                        <div class="timeline-step">1</div>
                        <strong>Submission</strong>
                        <p>Student submits application</p>
                        <span class="time-badge">Day 0</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="timeline-card">
                        <div class="timeline-step">2</div>
                        <strong>Academic Advisor</strong>
                        <p>Initial review & validation</p>
                        <span class="time-badge">3-5 days</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="timeline-card">
                        <div class="timeline-step">3</div>
                        <strong>Resource Person</strong>
                        <p>Course content verification</p>
                        <span class="time-badge">5-10 days</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="timeline-card">
                        <div class="timeline-step">4</div>
                        <strong>HEA Approval</strong>
                        <p>Final endorsement</p>
                        <span class="time-badge">14-21 days</span>
                    </div>
                </div>
            </div>
            <p class="text-muted small"><i class="fas fa-info-circle me-1"></i>Timelines are estimates and may vary depending on application volume and complexity.</p>
        </div>
    </div>

    <!-- Section 5: Student Rights & Appeal -->
    <div class="terms-card" id="section-5">
        <div class="terms-card-header">
            <div class="icon-badge pink">
                <i class="fas fa-balance-scale"></i>
            </div>
            <div>
                <h3>5. Student Rights & Appeal Process</h3>
                <p>Your rights and recourse options</p>
            </div>
        </div>
        <div class="terms-card-body">
            <h6 class="section-subtitle"><i class="fas fa-hand-holding-heart me-2"></i>Your Rights</h6>
            <ul class="styled-list">
                <li><strong>Transparency:</strong> Right to understand the evaluation criteria and decision-making process</li>
                <li><strong>Fair Review:</strong> All applications are reviewed objectively based on HEA-approved criteria</li>
                <li><strong>Status Updates:</strong> Right to track application progress through the online system</li>
                <li><strong>Feedback:</strong> Right to request clarification on rejected courses</li>
                <li><strong>Appeal:</strong> Right to appeal decisions within the specified timeframe (14 days from notification)</li>
            </ul>

            <div class="info-box">
                <h6><i class="fas fa-redo me-2"></i>Appeal Procedure</h6>
                <ol>
                    <li>Submit a written appeal to your Academic Advisor within <strong>14 days</strong> of receiving the decision</li>
                    <li>Provide supporting evidence (updated syllabus, course descriptions, lecturer recommendations)</li>
                    <li>Appeals are reviewed by a panel consisting of the Program Coordinator, Resource Person, and HEA representative</li>
                    <li>Final appeal decisions are communicated within <strong>21 working days</strong></li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Section 6: Data Privacy & Security -->
    <div class="terms-card" id="section-6">
        <div class="terms-card-header">
            <div class="icon-badge cyan">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <h3>6. Data Privacy & Security</h3>
                <p>How we protect your information</p>
            </div>
        </div>
        <div class="terms-card-body">
            <h6 class="section-subtitle"><i class="fas fa-lock me-2"></i>Information We Collect</h6>
            <ul class="styled-list">
                <li><strong>Personal Data:</strong> Name, IC number, matric number, contact information, campus, faculty, program</li>
                <li><strong>Academic Records:</strong> Transcripts, course codes, grades, credit hours, previous institution details</li>
                <li><strong>Application Data:</strong> Submission dates, status updates, decisions, justifications</li>
                <li><strong>System Logs:</strong> Login activities, file uploads, document access (for security and audit purposes)</li>
            </ul>

            <h6 class="section-subtitle"><i class="fas fa-user-shield me-2"></i>Data Protection Measures</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="security-item">
                        <i class="fas fa-key"></i>
                        <div>
                            <strong>Two-Factor Authentication (2FA)</strong>
                            <p>Google Authenticator required for account security</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="security-item">
                        <i class="fas fa-fingerprint"></i>
                        <div>
                            <strong>UUID-Based Encryption</strong>
                            <p>All records use unique identifiers for enhanced security</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="security-item">
                        <i class="fas fa-file-signature"></i>
                        <div>
                            <strong>Digital Signatures</strong>
                            <p>All uploaded documents are cryptographically signed (SHA256)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="security-item">
                        <i class="fas fa-database"></i>
                        <div>
                            <strong>Secure Storage</strong>
                            <p>Data stored in encrypted databases with access controls</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="warning-box">
                <h6><i class="fas fa-user-secret me-2"></i>Your Privacy Responsibilities</h6>
                <ul>
                    <li>Keep your login credentials and 2FA codes confidential</li>
                    <li>Do not share your account with others</li>
                    <li>Log out after each session, especially on shared computers</li>
                    <li>Report any suspected unauthorized access immediately to HEA</li>
                </ul>
            </div>

            <p class="text-muted small mt-3"><i class="fas fa-gavel me-1"></i>UiTM complies with the Personal Data Protection Act 2010 (PDPA). Your data is used solely for academic purposes and will not be shared with third parties without your consent, except as required by law.</p>
        </div>
    </div>

    <!-- Section 7: Disclaimer & Limitations -->
    <div class="terms-card" id="section-7">
        <div class="terms-card-header">
            <div class="icon-badge orange">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3>7. Disclaimer & Limitations</h3>
                <p>Important legal information</p>
            </div>
        </div>
        <div class="terms-card-body">
            <ul class="styled-list">
                <li><strong>No Guarantee of Approval:</strong> Submission of an application does not guarantee credit exemption approval. All decisions are based on academic merit and HEA criteria</li>
                <li><strong>System Availability:</strong> While we strive for 24/7 availability, the system may be temporarily unavailable for maintenance. Planned downtime will be announced in advance</li>
                <li><strong>OCR Accuracy:</strong> OCR technology is provided as a convenience. The university is not liable for extraction errors. Students must verify all extracted information</li>
                <li><strong>External Links:</strong> Links to external websites (Academic Calendar, institution databases) are provided for convenience. UiTM is not responsible for external content</li>
                <li><strong>Policy Changes:</strong> UiTM reserves the right to modify credit exemption policies in accordance with Senate decisions. Changes will be communicated through official channels</li>
                <li><strong>Force Majeure:</strong> The university is not liable for delays or failures due to circumstances beyond reasonable control (natural disasters, system failures, etc.)</li>
            </ul>

            <div class="info-box">
                <h6><i class="fas fa-scroll me-2"></i>Governing Law</h6>
                <p>These terms and conditions are governed by the laws of Malaysia and UiTM Academic Regulations. Any disputes shall be resolved in accordance with UiTM's established grievance procedures.</p>
            </div>
        </div>
    </div>

    <!-- Section 8: Contact Information -->
    <div class="terms-card" id="section-8">
        <div class="terms-card-header">
            <div class="icon-badge primary">
                <i class="fas fa-phone-alt"></i>
            </div>
            <div>
                <h3>8. Contact & Support</h3>
                <p>Get help with your credit exemption application</p>
            </div>
        </div>
        <div class="terms-card-body">
            <p class="lead-text">For questions, assistance, or concerns regarding credit exemption, please contact the appropriate office:</p>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <h6>Academic Advisor</h6>
                            <p class="text-muted mb-1">For application queries and initial consultation</p>
                            <p class="mb-0"><strong>Contact through your faculty's Academic Office</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <h6>Higher Education Authority (HEA)</h6>
                            <p class="text-muted mb-1">For policy inquiries and final approvals</p>
                            <p class="mb-0"><a href="mailto:hea@uitm.edu.my">hea@uitm.edu.my</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div>
                            <h6>Technical Support</h6>
                            <p class="text-muted mb-1">For system issues and login problems</p>
                            <p class="mb-0"><a href="mailto:helpdesk@uitm.edu.my">helpdesk@uitm.edu.my</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <h6>Academic Calendar</h6>
                            <p class="text-muted mb-1">Check important dates and deadlines</p>
                            <p class="mb-0">
                                <a href="https://hea.uitm.edu.my/index.php/calendars/academic-calendar" target="_blank">
                                    View Calendar <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acknowledgment Section -->
    <div class="acknowledgment-card">
        <div class="d-flex align-items-start">
            <div style="font-size: 2.5rem; color: var(--uitm-green); margin-right: 1.25rem;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div style="flex: 1;">
                <h4>Acknowledgment of Terms</h4>
                <p>By continuing to use the UiTM Credit Exemption Management System, you acknowledge that you have read, understood, and agreed to these terms and conditions. If you do not agree with any part of these terms, please refrain from using the system and contact your Academic Advisor for alternative procedures.</p>
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

<!-- Scroll to Top Button -->
<button id="scrollToTopBtn" class="scroll-to-top-btn" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
</button>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');

    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.add('show');
        } else {
            scrollToTopBtn.classList.remove('show');
        }
    });

    // Smooth scroll to top when clicked
    scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Smooth scroll for quick navigation links
    document.querySelectorAll('.nav-link-card').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 20;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>
@endpush
