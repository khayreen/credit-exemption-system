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

    /* Page Header */
    .page-header {
        position: relative;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-radius: 16px;
        padding: 2rem;
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
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .page-header p {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 0;
    }

    .btn-header-back {
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-header-back:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
        transform: translateY(-1px);
    }

    /* Info Alert */
    .info-alert {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.08) 0%, rgba(30, 58, 138, 0.03) 100%);
        border: 2px solid var(--uitm-primary);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-alert-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .info-alert-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--uitm-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .info-alert-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        color: var(--uitm-primary);
        margin: 0;
    }

    .info-alert ol {
        margin: 0;
        padding-left: 1.25rem;
        color: var(--neutral-700);
        font-size: 0.9rem;
    }

    .info-alert ol li {
        margin-bottom: 0.35rem;
    }

    .info-alert ol li:last-child {
        margin-bottom: 0;
    }

    /* Industrial Card */
    .industrial-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .industrial-card-header {
        background: var(--neutral-50);
        border-bottom: 2px solid var(--neutral-200);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .industrial-card-header.primary {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-bottom-color: var(--uitm-primary-dark);
        color: white;
    }

    .industrial-card-header.secondary {
        background: linear-gradient(135deg, var(--neutral-600) 0%, var(--neutral-700) 100%);
        border-bottom-color: var(--neutral-700);
        color: white;
    }

    .industrial-card-header.dark {
        background: linear-gradient(135deg, var(--neutral-800) 0%, var(--neutral-900) 100%);
        border-bottom-color: var(--neutral-900);
        color: white;
    }

    .industrial-card-header.info {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        border-bottom-color: #0284c7;
        color: white;
    }

    .industrial-card-header-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .industrial-card-header h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        margin: 0;
    }

    .industrial-card-body {
        padding: 1.5rem;
        overflow: visible;
    }

    /* Ensure card doesn't clip dropdown */
    .industrial-card {
        overflow: visible;
    }

    /* Form Styling */
    .form-label {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
    }

    .form-label .text-danger {
        color: var(--uitm-red);
    }

    .form-control,
    .form-select {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        border: 2px solid var(--neutral-200);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--uitm-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: var(--uitm-red);
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--neutral-500);
    }

    .invalid-feedback {
        font-size: 0.8rem;
        color: var(--uitm-red);
    }

    /* Search Button */
    .btn-search {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        padding: 0.875rem 1.5rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        width: 100%;
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        color: white;
    }

    .btn-search:disabled {
        opacity: 0.7;
        transform: none;
    }

    /* Result Cards */
    .result-card {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .result-card.equivalent {
        background: linear-gradient(to right, rgba(16, 185, 129, 0.08) 0%, rgba(16, 185, 129, 0.02) 100%);
        border: 2px solid var(--uitm-green);
    }

    .result-card.not-equivalent {
        background: linear-gradient(to right, rgba(220, 38, 38, 0.08) 0%, rgba(220, 38, 38, 0.02) 100%);
        border: 2px solid var(--uitm-red);
    }

    .result-card.not-found {
        background: linear-gradient(to right, rgba(245, 158, 11, 0.08) 0%, rgba(245, 158, 11, 0.02) 100%);
        border: 2px solid var(--uitm-amber);
    }

    .result-card-body {
        padding: 1.5rem;
    }

    .result-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .result-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }

    .result-icon.success {
        background: var(--uitm-green);
    }

    .result-icon.danger {
        background: var(--uitm-red);
    }

    .result-icon.warning {
        background: var(--uitm-amber);
    }

    .result-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
    }

    .result-title.success {
        color: var(--uitm-green);
    }

    .result-title.danger {
        color: var(--uitm-red);
    }

    .result-title.warning {
        color: var(--uitm-amber);
    }

    .result-subtitle {
        font-size: 0.85rem;
        color: var(--neutral-500);
    }

    /* Course Info Box */
    .course-info-box {
        background: var(--neutral-50);
        border: 1px solid var(--neutral-200);
        border-radius: 10px;
        padding: 1rem;
    }

    .course-info-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: var(--neutral-500);
        margin-bottom: 0.5rem;
    }

    .course-info-value {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.95rem;
        color: var(--neutral-800);
    }

    .course-code-mono {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-primary);
    }

    .credit-hour-text {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
        color: var(--neutral-600);
    }

    .result-info-banner {
        background: rgba(255, 255, 255, 0.6);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        text-align: center;
        font-size: 0.85rem;
        color: var(--neutral-600);
    }

    /* Request Button */
    .btn-request {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        width: 100%;
    }

    .btn-request:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        color: white;
    }

    /* Warning Alert */
    .warning-alert {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);
        border: 2px solid var(--uitm-amber);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
    }

    .warning-alert-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .warning-alert-header i {
        color: var(--uitm-amber);
    }

    .warning-alert-header h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--uitm-amber);
        margin: 0;
    }

    .warning-alert p {
        font-size: 0.85rem;
        color: var(--neutral-700);
        margin: 0;
    }

    /* Secondary Button */
    .btn-secondary-industrial {
        background: var(--neutral-100);
        border: 2px solid var(--neutral-300);
        color: var(--neutral-700);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-secondary-industrial:hover {
        background: var(--neutral-200);
        border-color: var(--neutral-400);
        color: var(--neutral-800);
    }

    /* Sidebar Cards */
    .sidebar-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .sidebar-card-header {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .sidebar-card-header.secondary {
        background: linear-gradient(135deg, var(--neutral-600) 0%, var(--neutral-700) 100%);
        color: white;
    }

    .sidebar-card-header.dark {
        background: linear-gradient(135deg, var(--neutral-800) 0%, var(--neutral-900) 100%);
        color: white;
    }

    .sidebar-card-header-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .sidebar-card-header h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        margin: 0;
    }

    .sidebar-card-body {
        padding: 1.25rem;
    }

    .sidebar-card-body p {
        font-size: 0.85rem;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
    }

    .sidebar-card-body p:last-child {
        margin-bottom: 0;
    }

    .sidebar-card-body strong {
        font-weight: 600;
        color: var(--neutral-800);
    }

    .guidelines-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .guidelines-list li {
        position: relative;
        padding-left: 1.25rem;
        font-size: 0.85rem;
        color: var(--neutral-700);
        margin-bottom: 0.75rem;
    }

    .guidelines-list li:last-child {
        margin-bottom: 0;
    }

    .guidelines-list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0.5rem;
        width: 6px;
        height: 6px;
        background: var(--uitm-primary);
        border-radius: 50%;
    }

    /* Hide original select */
    .searchable-select {
        display: none;
    }

    /* Searchable Dropdown Styles */
    .searchable-dropdown-wrapper {
        position: relative;
        width: 100%;
        z-index: 100;
    }

    .searchable-dropdown-wrapper:has(.searchable-dropdown-menu.show) {
        z-index: 99999;
    }

    /* Ensure dropdown appears above sidebar */
    .col-lg-8 {
        position: relative;
        z-index: 10;
    }

    .col-lg-4 {
        position: relative;
        z-index: 1;
    }

    /* Ensure containers don't clip dropdown */
    .row,
    .row > div,
    .industrial-card,
    .industrial-card-body,
    .mb-4,
    .mb-3 {
        overflow: visible !important;
    }

    /* Form row containing dropdowns */
    .industrial-card-body > .row {
        position: relative;
        z-index: 100;
    }

    .searchable-dropdown-input {
        width: 100%;
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.5;
        color: var(--neutral-800);
        background-color: #fff;
        background-clip: padding-box;
        border: 2px solid var(--neutral-200);
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
        z-index: 1;
    }

    .searchable-dropdown-input:focus {
        color: var(--neutral-800);
        background-color: #fff;
        border-color: var(--uitm-primary);
        outline: 0;
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .searchable-dropdown-arrow {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: var(--neutral-500);
        font-size: 0.75rem;
        z-index: 2;
    }

    .searchable-dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 99999;
        max-height: 300px;
        overflow-y: auto;
        background-color: #fff;
        border: 2px solid var(--neutral-200);
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
        margin-top: 0.25rem;
    }

    .searchable-dropdown-menu.show {
        display: block;
    }

    .searchable-dropdown-option {
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: all 0.15s ease;
        background-color: #fff;
        font-size: 0.9rem;
        color: var(--neutral-700);
        border-bottom: 1px solid var(--neutral-100);
    }

    .searchable-dropdown-option:last-child {
        border-bottom: none;
    }

    .searchable-dropdown-option:hover {
        background-color: var(--neutral-50);
    }

    .searchable-dropdown-option.selected {
        background-color: rgba(30, 58, 138, 0.08);
        color: var(--uitm-primary);
        font-weight: 500;
    }

    .searchable-dropdown-option.no-results {
        color: var(--neutral-500);
        cursor: default;
    }

    .searchable-dropdown-option.no-results:hover {
        background-color: #fff;
    }

    .searchable-dropdown-option mark {
        background-color: rgba(245, 158, 11, 0.3);
        padding: 0.1em 0;
        font-weight: 600;
        border-radius: 2px;
    }

    /* Result cards positioning */
    #equivalentResult,
    #notEquivalentResult,
    #notFoundResult,
    #requestForm {
        position: relative;
        z-index: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.35rem;
        }

        .result-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-header-back {
            width: 100%;
            justify-content: center;
            margin-top: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="eyebrow">New Request</div>
                    <h1><i class="fas fa-search me-2"></i>Course Equivalency Checker</h1>
                    <p>Check if your diploma course is equivalent to a UiTM degree course</p>
                </div>
                <a href="{{ route('student.equivalency.request.index') }}" class="btn-header-back">
                    <i class="fas fa-list"></i>
                    My Requests
                </a>
            </div>
        </div>
    </div>

    <!-- Validation Errors Display -->
    @if($errors->any())
    <div class="alert alert-danger border-2 mb-4" style="border-radius: 12px; border-color: #dc2626 !important;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(220, 38, 38, 0.15); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation-circle" style="font-size: 1.1rem; color: #dc2626;"></i>
                </div>
            </div>
            <div>
                <h6 class="alert-heading mb-2" style="font-weight: 600; color: #dc2626;">Submission Failed</h6>
                <ul class="mb-0" style="padding-left: 1.25rem; color: #7f1d1d;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Success Message Display -->
    @if(session('success'))
    <div class="alert alert-success border-2 mb-4" style="border-radius: 12px; border-color: #10b981 !important;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(16, 185, 129, 0.15); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="font-size: 1.1rem; color: #10b981;"></i>
                </div>
            </div>
            <div>
                <p class="mb-0" style="color: #065f46;">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Information Alert -->
    <div class="info-alert">
        <div class="info-alert-header">
            <div class="info-alert-icon">
                <i class="fas fa-info"></i>
            </div>
            <h5 class="info-alert-title">How This Works</h5>
        </div>
        <ol>
            <li>Select your diploma course and the UiTM degree course you want to compare</li>
            <li>Click "Check Equivalency" to see if they are already recognized as equivalent</li>
            @if($hasExemptionApplication)
            <li>If no equivalency exists, you can submit a request for review by your Program Coordinator</li>
            <li><strong>Important:</strong> You can only request equivalency for courses that appear in your transcript</li>
            @else
            <li>If no equivalency exists and you want to request a review, you'll need to submit a Credit Exemption Application first</li>
            @endif
        </ol>
    </div>

    @if(!$hasExemptionApplication)
    <!-- Notice for students without application -->
    <div class="alert alert-warning border-2 mb-4" style="border-radius: 12px; border-color: #f59e0b !important;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(245, 158, 11, 0.15); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-info-circle" style="font-size: 1.1rem; color: #f59e0b;"></i>
                </div>
            </div>
            <div>
                <h6 class="alert-heading mb-1" style="font-weight: 600; color: #92400e;">Browse Mode</h6>
                <p class="mb-0" style="font-size: 0.9rem; color: #78350f;">
                    You can check existing equivalencies, but to <strong>request new equivalencies</strong>, you need to
                    <a href="{{ route('student.application.create') }}" style="color: #92400e; font-weight: 600;">submit a Credit Exemption Application</a> first.
                </p>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-lg-8">
            <!-- Equivalency Checker Form -->
            <div class="industrial-card">
                <div class="industrial-card-header primary">
                    <div class="industrial-card-header-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h5>Search Courses</h5>
                </div>
                <div class="industrial-card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label">Diploma Course <span class="text-danger">*</span></label>
                            <select id="diploma_course_select" name="diploma_course" class="form-select searchable-select">
                                <option value="">Select diploma course...</option>
                                @foreach($diplomaCourses as $course)
                                    <option value="{{ $course->diploma_course_code }}">
                                        {{ $course->diploma_course_code }} - {{ $course->diploma_course_name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text">Start typing to search diploma courses from all institutions</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">UiTM Degree Course <span class="text-danger">*</span></label>
                            <select id="degree_course_select" name="degree_course" class="form-select searchable-select">
                                <option value="">Select degree course...</option>
                                @foreach($degreeCourses as $course)
                                    <option value="{{ $course->degree_course_code }}">{{ $course->degree_course_code }} - {{ $course->degree_course_name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text">Start typing to search UiTM degree courses</small>
                        </div>
                    </div>

                    <button type="button" id="checkEquivalencyBtn" class="btn-search">
                        <i class="fas fa-search me-2"></i>Check Equivalency
                    </button>
                </div>
            </div>

            <!-- Equivalent Result Card (Hidden Initially) -->
            <div id="equivalentResult" class="result-card equivalent" style="display: none;">
                <div class="result-card-body">
                    <div class="result-header">
                        <div class="result-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h5 class="result-title success">Courses Are Equivalent</h5>
                            <p class="result-subtitle">This course pairing is recognized for credit transfer</p>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="course-info-box">
                                <div class="course-info-label">Diploma Course</div>
                                <div class="course-info-value" id="resultDiplomaInfo"></div>
                                <div class="credit-hour-text mt-2">Credit Hours: <span id="resultDiplomaCreditHour"></span></div>
                                <div class="credit-hour-text mt-1" id="resultInstitution"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="course-info-box">
                                <div class="course-info-label">UiTM Degree Course</div>
                                <div class="course-info-value" id="resultDegreeInfo"></div>
                                <div class="credit-hour-text mt-2">Credit Hours: <span id="resultDegreeCreditHour"></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="result-info-banner">
                        <i class="fas fa-lightbulb me-1"></i>
                        You can use this information when filling out your credit exemption application
                    </div>
                </div>
            </div>

            <!-- Not Equivalent Result Card (Hidden Initially) -->
            <div id="notEquivalentResult" class="result-card not-equivalent" style="display: none;">
                <div class="result-card-body">
                    <div class="result-header">
                        <div class="result-icon danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <h5 class="result-title danger">Courses Are Not Equivalent</h5>
                            <p class="result-subtitle">Match percentage is below the required threshold</p>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="course-info-box">
                                <div class="course-info-label">Diploma Course</div>
                                <div class="course-info-value" id="notEquivDiplomaInfo"></div>
                                <div class="credit-hour-text mt-2">Credit Hours: <span id="notEquivDiplomaCreditHour"></span></div>
                                <div class="credit-hour-text mt-1" id="notEquivInstitution"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="course-info-box">
                                <div class="course-info-label">UiTM Degree Course</div>
                                <div class="course-info-value" id="notEquivDegreeInfo"></div>
                                <div class="credit-hour-text mt-2">Credit Hours: <span id="notEquivDegreeCreditHour"></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="result-info-banner" style="background: rgba(220, 38, 38, 0.1); border-radius: 8px; padding: 1rem;">
                        <i class="fas fa-info-circle me-2" style="color: #dc2626;"></i>
                        <span style="color: #7f1d1d; font-size: 0.9rem;">
                            This course pairing has already been reviewed and does not meet the requirements for credit exemption.
                        </span>
                    </div>
                </div>
            </div>

            <!-- Not Found Result Card (Hidden Initially) -->
            <div id="notFoundResult" class="result-card not-found" style="display: none;">
                <div class="result-card-body">
                    <div class="result-header">
                        <div class="result-icon warning">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <h5 class="result-title warning">No Equivalency Found</h5>
                            <p class="result-subtitle">This course pairing is not in our system</p>
                        </div>
                    </div>

                    <div class="course-info-box mb-3">
                        <div class="course-info-label mb-3">Selected Courses</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="course-info-label">Diploma Course</div>
                                <div class="course-info-value" id="notFoundDiploma"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="course-info-label">UiTM Degree Course</div>
                                <div class="course-info-value" id="notFoundDegree"></div>
                            </div>
                        </div>
                    </div>

                    <div class="result-info-banner mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        @if($hasExemptionApplication)
                        You can request a manual review to determine if these courses can be considered equivalent
                        @else
                        To request a manual review, you need to submit a Credit Exemption Application first
                        @endif
                    </div>

                    @if($hasExemptionApplication)
                    <button type="button" id="requestEquivalencyBtn" class="btn-request">
                        <i class="fas fa-paper-plane me-2"></i>Request Equivalency Review
                    </button>

                    <!-- Error message when course not in transcript (shown by JS) -->
                    <div id="notInTranscriptError" class="mt-3" style="display: none; background: rgba(220, 38, 38, 0.1); border: 2px solid #dc2626; border-radius: 8px; padding: 1rem;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-ban me-3" style="color: #dc2626; font-size: 1.25rem; margin-top: 2px;"></i>
                            <div>
                                <h6 style="font-weight: 600; color: #dc2626; margin-bottom: 0.5rem;">Request Not Allowed</h6>
                                <p style="color: #7f1d1d; font-size: 0.9rem; margin-bottom: 0.5rem;">
                                    You cannot request equivalency for <strong id="notInTranscriptCourse"></strong> because this course does not exist in your transcript.
                                </p>
                                <p style="color: #7f1d1d; font-size: 0.85rem; margin-bottom: 0;">
                                    You can only request equivalency for courses you have taken during your diploma. Please check your transcript in the sidebar.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Error message when degree course not in program syllabus (shown by JS) -->
                    <div id="notInProgramSyllabusError" class="mt-3" style="display: none; background: rgba(220, 38, 38, 0.1); border: 2px solid #dc2626; border-radius: 8px; padding: 1rem;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-ban me-3" style="color: #dc2626; font-size: 1.25rem; margin-top: 2px;"></i>
                            <div>
                                <h6 style="font-weight: 600; color: #dc2626; margin-bottom: 0.5rem;">Request Not Allowed</h6>
                                <p style="color: #7f1d1d; font-size: 0.9rem; margin-bottom: 0.5rem;">
                                    You cannot request equivalency for <strong id="notInSyllabusCourse"></strong> because this course does not exist in your degree program syllabus (<strong>{{ $student->program_code ?? 'Unknown' }}</strong>).
                                </p>
                                <p style="color: #7f1d1d; font-size: 0.85rem; margin-bottom: 0;">
                                    You can only request equivalency for courses that are part of your degree program curriculum.
                                </p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="apply-first-notice" style="background: rgba(245, 158, 11, 0.1); border: 2px solid #f59e0b; border-radius: 8px; padding: 1rem; text-align: center;">
                        <i class="fas fa-lock me-2" style="color: #f59e0b;"></i>
                        <span style="color: #92400e; font-size: 0.9rem;">To request equivalency review, please <a href="{{ route('student.application.create') }}" style="color: #92400e; font-weight: 600;">submit a Credit Exemption Application</a> first.</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Request Form (Hidden Initially) - Only available with exemption application -->
            @if($hasExemptionApplication)
            <div id="requestForm" class="industrial-card" style="display: none;">
                <div class="industrial-card-header info">
                    <div class="industrial-card-header-icon">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <h5>Submit Equivalency Request</h5>
                </div>
                <div class="industrial-card-body">
                    <form id="equivalencyRequestForm" action="{{ route('student.equivalency.request.store') }}" method="POST">
                        @csrf

                        <!-- Course Information (from checker selection) -->
                        <div class="mb-4 p-3" style="background: var(--neutral-50); border-radius: 8px; border: 1px solid var(--neutral-200);">
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label mb-1" style="font-size: 0.75rem; text-transform: uppercase; color: var(--neutral-500);">Diploma Course</label>
                                    <div id="display_diploma_course" style="font-weight: 600; color: var(--neutral-800);"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1" style="font-size: 0.75rem; text-transform: uppercase; color: var(--neutral-500);">UiTM Degree Course</label>
                                    <div id="display_degree_course" style="font-weight: 600; color: var(--neutral-800);"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields for course codes and names -->
                        <input type="hidden" name="diploma_course_code" id="hidden_diploma_code">
                        <input type="hidden" name="diploma_course_name" id="hidden_diploma_name">
                        <input type="hidden" name="suggested_degree_course_code" id="hidden_degree_code">
                        <input type="hidden" name="suggested_degree_course_name" id="hidden_degree_name">

                        @error('diploma_course_code')
                        <div class="alert alert-danger mb-4" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                        </div>
                        @enderror

                        @error('suggested_degree_course_code')
                        <div class="alert alert-danger mb-4" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                        </div>
                        @enderror

                        <!-- Diploma Institution Information -->
                        <div class="mb-4">
                            <label class="form-label">Diploma Institution <span class="text-danger">*</span></label>
                            <select name="diploma_institution" class="form-select searchable-select @error('diploma_institution') is-invalid @enderror" required>
                                <option value="">Select institution...</option>
                                @foreach($institutions as $institution)
                                    <option value="{{ $institution->name }}" {{ old('diploma_institution') == $institution->name ? 'selected' : '' }}>
                                        {{ $institution->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text">Institution where you completed this diploma course</small>
                            @error('diploma_institution')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- External Lecturer Contact Information -->
                        <div class="warning-alert">
                            <div class="warning-alert-header">
                                <i class="fas fa-exclamation-triangle"></i>
                                <h6>Important: Official Syllabus Verification</h6>
                            </div>
                            <p>To ensure authenticity, we will request the official course syllabus directly from your lecturer. Please provide your lecturer's official institutional email address.</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label">Lecturer's Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="external_lecturer_name" class="form-control @error('external_lecturer_name') is-invalid @enderror"
                                       value="{{ old('external_lecturer_name') }}"
                                       placeholder="e.g., Dr. Ahmad bin Abdullah"
                                       maxlength="255" required>
                                <small class="form-text">Full name of the lecturer who taught this diploma course</small>
                                @error('external_lecturer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lecturer's Official Email <span class="text-danger">*</span></label>
                                <input type="email" name="external_lecturer_email" class="form-control @error('external_lecturer_email') is-invalid @enderror"
                                       value="{{ old('external_lecturer_email') }}"
                                       placeholder="e.g., ahmad.abdullah@university.edu.my"
                                       maxlength="255" required>
                                <small class="form-text">Must be official institutional email address</small>
                                @error('external_lecturer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between flex-wrap gap-2">
                            <button type="button" id="cancelRequestBtn" class="btn-secondary-industrial">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" id="submitBtn" class="btn-request" style="width: auto;">
                                <span id="submitBtnText">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Request
                                </span>
                                <span id="submitBtnLoading" class="d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Submitting...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Current Program Info -->
            <div class="sidebar-card">
                <div class="sidebar-card-header secondary">
                    <div class="sidebar-card-header-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h6>Your Current Program</h6>
                </div>
                <div class="sidebar-card-body">
                    <p><strong>Program:</strong><br>{{ $student->program_name ?? 'Not Set' }}</p>
                    <p><strong>Campus:</strong><br>{{ $student->campus ?? 'Not Set' }}</p>
                    <p><strong>Student ID:</strong><br><span class="course-code-mono">{{ $student->matric_no ?? Auth::user()->name }}</span></p>
                </div>
            </div>

            <!-- Guidelines -->
            <div class="sidebar-card">
                <div class="sidebar-card-header dark">
                    <div class="sidebar-card-header-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h6>Guidelines</h6>
                </div>
                <div class="sidebar-card-body">
                    <ul class="guidelines-list">
                        <li>Use the search tool to check existing equivalencies first</li>
                        <li>Only submit a request if no equivalency is found</li>
                        <li>Provide accurate lecturer contact information</li>
                        <li>Your Program Coordinator will review the request</li>
                        <li>You will be notified of the decision via email</li>
                    </ul>
                </div>
            </div>

            <!-- Your Transcript Courses -->
            @if($transcriptCourses->isNotEmpty())
            <div class="sidebar-card">
                <div class="sidebar-card-header" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white;">
                    <div class="sidebar-card-header-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h6>Your Transcript Courses</h6>
                </div>
                <div class="sidebar-card-body" style="max-height: 300px; overflow-y: auto;">
                    <p class="mb-2" style="font-size: 0.8rem; color: var(--neutral-600);">
                        <i class="fas fa-info-circle me-1"></i>
                        You can only request equivalency for these courses:
                    </p>
                    <div class="list-group list-group-flush">
                        @foreach($transcriptCourses as $course)
                        <div class="list-group-item px-0 py-2 border-0" style="background: transparent;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="course-code-mono" style="font-size: 0.85rem;">{{ $course->course_code }}</span>
                                    <div style="font-size: 0.8rem; color: var(--neutral-600);">{{ \Illuminate\Support\Str::limit($course->course_name, 30) }}</div>
                                </div>
                                <span class="badge" style="background: {{ in_array($course->grade, ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C']) ? '#10b981' : '#ef4444' }}; font-size: 0.7rem;">
                                    {{ $course->grade }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Initializing searchable dropdowns...');

    // Initialize searchable dropdowns
    $('.searchable-select').each(function() {
        initSearchableDropdown($(this));
    });

    // Now initialize the equivalency checker
    initializeEquivalencyChecker();

    console.log('All dropdowns initialized!');
});

// Initialize a single searchable dropdown
function initSearchableDropdown($select) {
    var options = [];
    var selectedValue = '';
    var selectedText = $.trim($select.find('option:first').text());

    // Collect all options (trim whitespace)
    $select.find('option').each(function() {
        var value = $(this).val();
        var text = $.trim($(this).text());

        if (value) {
            options.push({
                value: value,
                text: text
            });
        }
    });

    // Create wrapper
    var $wrapper = $('<div class="searchable-dropdown-wrapper"></div>');
    var $input = $('<input type="text" class="searchable-dropdown-input" placeholder="' + selectedText + '" readonly>');
    var $arrow = $('<span class="searchable-dropdown-arrow"><i class="fas fa-chevron-down"></i></span>');
    var $menu = $('<div class="searchable-dropdown-menu"></div>');

    $wrapper.append($input);
    $wrapper.append($arrow);
    $wrapper.append($menu);

    // Insert after select and hide select
    $select.after($wrapper);

    // Populate menu with fuzzy search
    function populateMenu(searchTerm) {
        $menu.empty();
        var filteredOptions = options;

        if (searchTerm) {
            searchTerm = searchTerm.toLowerCase();
            // Fuzzy search: match anywhere in the text
            filteredOptions = options.filter(function(opt) {
                return opt.text.toLowerCase().indexOf(searchTerm) > -1;
            });
        }

        if (filteredOptions.length === 0) {
            $menu.append('<div class="searchable-dropdown-option no-results">No results found</div>');
        } else {
            filteredOptions.forEach(function(opt) {
                var $option = $('<div class="searchable-dropdown-option" data-value="' + opt.value + '"></div>');

                // Highlight matching text
                if (searchTerm) {
                    var idx = opt.text.toLowerCase().indexOf(searchTerm);
                    if (idx > -1) {
                        var before = opt.text.substring(0, idx);
                        var match = opt.text.substring(idx, idx + searchTerm.length);
                        var after = opt.text.substring(idx + searchTerm.length);
                        $option.html(before + '<mark>' + match + '</mark>' + after);
                    } else {
                        $option.text(opt.text);
                    }
                } else {
                    $option.text(opt.text);
                }

                if (opt.value === selectedValue) {
                    $option.addClass('selected');
                }

                $menu.append($option);
            });
        }
    }

    // Open dropdown
    $input.on('click', function() {
        $input.removeAttr('readonly');
        $input.val('');
        $input.focus();
        $menu.addClass('show');
        populateMenu('');
    });

    // Search as you type
    $input.on('input', function() {
        populateMenu($input.val());
    });

    // Select option
    $menu.on('click', '.searchable-dropdown-option:not(.no-results)', function() {
        var value = $(this).data('value');

        // Get the original text from options array
        var selectedOption = options.find(function(opt) { return opt.value === value; });
        var text = selectedOption ? selectedOption.text : $(this).text();

        selectedValue = value;
        $select.val(value).trigger('change');
        $input.val(text);
        $input.attr('readonly', 'readonly');
        $menu.removeClass('show');
    });

    // Close on click outside
    $(document).on('click', function(e) {
        if (!$wrapper[0].contains(e.target)) {
            $input.attr('readonly', 'readonly');
            $menu.removeClass('show');
            if (selectedValue) {
                var selectedOpt = options.find(function(opt) { return opt.value === selectedValue; });
                if (selectedOpt) {
                    $input.val(selectedOpt.text);
                }
            } else {
                $input.val('');
                $input.attr('placeholder', selectedText);
            }
        }
    });
}

function initializeEquivalencyChecker() {
    const studentProgramCode = '{{ $student->program_code ?? "" }}';
    const csrfToken = '{{ csrf_token() }}';
    const hasExemptionApplication = {{ $hasExemptionApplication ? 'true' : 'false' }};

    // Store transcript course codes for validation
    const transcriptCourseCodes = [
        @if($hasExemptionApplication && $transcriptCourses->isNotEmpty())
            @foreach($transcriptCourses as $course)
                '{{ strtoupper($course->course_code) }}',
            @endforeach
        @endif
    ];

    // Store program syllabus course codes for validation
    const programSyllabusCourses = [
        @if($programSyllabusCourses->isNotEmpty())
            @foreach($programSyllabusCourses as $course)
                '{{ strtoupper($course->code) }}',
            @endforeach
        @endif
    ];

    console.log('Transcript courses:', transcriptCourseCodes);
    console.log('Program syllabus courses:', programSyllabusCourses);

    // Check if student has a program code
    if (!studentProgramCode) {
        console.error('No program code found for student');
        alert('Error: Your program code is not set. Please contact the administrator.');
        return;
    }

    console.log('Has exemption application:', hasExemptionApplication);

    // Function to check if course is in transcript
    function isCourseInTranscript(courseCode) {
        const upperCode = courseCode.toUpperCase().trim();
        return transcriptCourseCodes.some(function(tc) {
            // Exact match or partial match
            return tc === upperCode || tc.includes(upperCode) || upperCode.includes(tc);
        });
    }

    // Function to check if degree course is in student's program syllabus
    function isCourseInProgramSyllabus(courseCode) {
        const upperCode = courseCode.toUpperCase().trim();
        return programSyllabusCourses.some(function(pc) {
            // Exact match only for program syllabus
            return pc === upperCode;
        });
    }

    // Hide error messages and form when diploma course changes
    $('#diploma_course_select').on('change', function() {
        $('#notInTranscriptError').slideUp();
        $('#notInProgramSyllabusError').slideUp();
        $('#requestForm').slideUp();
        // Also hide result cards when selection changes
        $('#equivalentResult').slideUp();
        $('#notEquivalentResult').slideUp();
        $('#notFoundResult').slideUp();
    });

    // Hide error messages and form when degree course changes
    $('#degree_course_select').on('change', function() {
        $('#notInTranscriptError').slideUp();
        $('#notInProgramSyllabusError').slideUp();
        $('#requestForm').slideUp();
        // Also hide result cards when selection changes
        $('#equivalentResult').slideUp();
        $('#notEquivalentResult').slideUp();
        $('#notFoundResult').slideUp();
    });

    // Check Equivalency Button
    $('#checkEquivalencyBtn').on('click', function() {
        const diplomaCode = $('#diploma_course_select').val();
        const degreeCode = $('#degree_course_select').val();

        if (!diplomaCode || !degreeCode) {
            alert('Please select both diploma and degree courses');
            return;
        }

        // Get selected option text for display
        const diplomaText = $('#diploma_course_select option:selected').text();
        const degreeText = $('#degree_course_select option:selected').text();

        // Show loading state
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Checking...');

        // Hide previous results
        $('#equivalentResult').hide();
        $('#notEquivalentResult').hide();
        $('#notFoundResult').hide();
        $('#requestForm').hide();

        $.ajax({
            url: '{{ route("student.api.equivalency.check") }}',
            method: 'POST',
            data: {
                diploma_code: diplomaCode,
                degree_code: degreeCode,
                program_code: studentProgramCode,
                _token: csrfToken
            },
            success: function(response) {
                if (response.found) {
                    // Check if equivalent based on match_percentage
                    if (response.is_equivalent) {
                        // Show EQUIVALENT result (match_percentage >= 80%)
                        $('#resultDiplomaInfo').text(`${diplomaCode} - ${response.data.diploma_course_name}`);
                        $('#resultDiplomaCreditHour').text(parseFloat(response.data.diploma_credit_hour).toFixed(2));
                        $('#resultInstitution').text(response.data.diploma_institution);
                        $('#resultDegreeInfo').text(`${degreeCode} - ${response.data.degree_course_name}`);
                        $('#resultDegreeCreditHour').text(parseFloat(response.data.degree_credit_hour).toFixed(2));

                        $('#equivalentResult').slideDown();
                    } else {
                        // Show NOT EQUIVALENT result (match_percentage < 80%)
                        $('#notEquivDiplomaInfo').text(`${diplomaCode} - ${response.data.diploma_course_name}`);
                        $('#notEquivDiplomaCreditHour').text(parseFloat(response.data.diploma_credit_hour).toFixed(2));
                        $('#notEquivInstitution').text(response.data.diploma_institution);
                        $('#notEquivDegreeInfo').text(`${degreeCode} - ${response.data.degree_course_name}`);
                        $('#notEquivDegreeCreditHour').text(parseFloat(response.data.degree_credit_hour).toFixed(2));

                        $('#notEquivalentResult').slideDown();
                    }
                } else {
                    // Show NOT FOUND result
                    $('#notFoundDiploma').text(diplomaText || diplomaCode);
                    $('#notFoundDegree').text(degreeText || degreeCode);

                    $('#notFoundResult').slideDown();
                }
            },
            error: function(xhr) {
                alert('Error checking equivalency. Please try again.');
                console.error(xhr);
            },
            complete: function() {
                $('#checkEquivalencyBtn').prop('disabled', false)
                    .html('<i class="fas fa-search me-2"></i>Check Equivalency');
            }
        });
    });

    // Request Equivalency Button (for NOT FOUND result)
    $('#requestEquivalencyBtn').on('click', function() {
        const diplomaCode = $('#diploma_course_select').val();
        const degreeCode = $('#degree_course_select').val();

        // Get selected option text (format: "CODE - NAME")
        const diplomaText = $('#diploma_course_select option:selected').text().trim();
        const degreeText = $('#degree_course_select option:selected').text().trim();

        // CHECK 1: Is diploma course in student's transcript?
        if (!isCourseInTranscript(diplomaCode)) {
            // NOT in transcript - show error, hide form
            $('#notInTranscriptCourse').text(diplomaCode);
            $('#notInTranscriptError').slideDown();
            $('#notInProgramSyllabusError').slideUp();
            $('#requestForm').slideUp();

            // Scroll to error
            $('html, body').animate({
                scrollTop: $('#notInTranscriptError').offset().top - 100
            }, 500);
            return;
        }

        // Hide transcript error if previously shown
        $('#notInTranscriptError').slideUp();

        // CHECK 2: Is degree course in student's program syllabus?
        if (!isCourseInProgramSyllabus(degreeCode)) {
            // NOT in program syllabus - show error, hide form
            $('#notInSyllabusCourse').text(degreeCode);
            $('#notInProgramSyllabusError').slideDown();
            $('#requestForm').slideUp();

            // Scroll to error
            $('html, body').animate({
                scrollTop: $('#notInProgramSyllabusError').offset().top - 100
            }, 500);
            return;
        }

        // Hide program syllabus error if previously shown
        $('#notInProgramSyllabusError').slideUp();

        // Extract course names from the text (remove course code prefix)
        const diplomaName = diplomaText.includes(' - ') ? diplomaText.split(' - ').slice(1).join(' - ').trim() : diplomaText;
        const degreeName = degreeText.includes(' - ') ? degreeText.split(' - ').slice(1).join(' - ').trim() : degreeText;

        // Populate hidden fields
        $('#hidden_diploma_code').val(diplomaCode);
        $('#hidden_diploma_name').val(diplomaName);
        $('#hidden_degree_code').val(degreeCode);
        $('#hidden_degree_name').val(degreeName);

        // Display course info in form
        $('#display_diploma_course').text(diplomaText);
        $('#display_degree_course').text(degreeText);

        // Show request form
        $('#requestForm').slideDown();

        // Scroll to form
        $('html, body').animate({
            scrollTop: $('#requestForm').offset().top - 100
        }, 500);
    });

    // Cancel Request Button
    $('#cancelRequestBtn').on('click', function() {
        $('#requestForm').slideUp();
        $('#hidden_diploma_code').val('');
        $('#hidden_diploma_name').val('');
        $('#hidden_degree_code').val('');
        $('#hidden_degree_name').val('');
        $('#display_diploma_course').text('');
        $('#display_degree_course').text('');
        $('select[name="diploma_institution"]').val('').trigger('change');
        $('input[name="external_lecturer_name"]').val('');
        $('input[name="external_lecturer_email"]').val('');
    });

    // Form submission - only if form exists (student has exemption application)
    const form = document.getElementById('equivalencyRequestForm');
    if (form) {
        const submitBtn = document.getElementById('submitBtn');
        const submitBtnText = document.getElementById('submitBtnText');
        const submitBtnLoading = document.getElementById('submitBtnLoading');

        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add('was-validated');

                const firstError = form.querySelector(':invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return false;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitBtnText.classList.add('d-none');
            submitBtnLoading.classList.remove('d-none');
        });
    }
}
</script>
@endpush
