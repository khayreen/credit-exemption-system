@extends('layouts.app')

@section('title', __('Service Unavailable'))

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e293b;
        --uitm-amber: #f59e0b;
        --uitm-purple: #7c3aed;
        --uitm-purple-dark: #6d28d9;
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
        min-height: 100vh;
    }

    .error-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .error-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 20px;
        padding: 3rem;
        text-align: center;
        max-width: 550px;
        width: 100%;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    }

    .error-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--uitm-purple), var(--uitm-amber), var(--uitm-purple));
    }

    .error-code-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .error-code {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 8rem;
        font-weight: 700;
        line-height: 1;
        background: linear-gradient(135deg, var(--uitm-purple) 0%, var(--uitm-purple-dark) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.05em;
    }

    .error-code-bg {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: 'IBM Plex Mono', monospace;
        font-size: 12rem;
        font-weight: 700;
        color: var(--neutral-100);
        z-index: -1;
        letter-spacing: -0.05em;
    }

    .error-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.15) 0%, rgba(124, 58, 237, 0.05) 100%);
        border: 2px solid var(--uitm-purple);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .error-icon i {
        font-size: 1.75rem;
        color: var(--uitm-purple);
    }

    .error-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--uitm-purple);
        margin-bottom: 0.75rem;
    }

    .error-message {
        font-size: 1rem;
        color: var(--neutral-600);
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .maintenance-box {
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.1) 0%, rgba(124, 58, 237, 0.05) 100%);
        border: 2px solid var(--uitm-purple);
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        text-align: left;
    }

    .maintenance-box-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--uitm-purple);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .maintenance-box-icon i {
        color: white;
        font-size: 1rem;
    }

    .maintenance-box-content h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        color: #5b21b6;
        margin-bottom: 0.25rem;
    }

    .maintenance-box-content p {
        font-size: 0.85rem;
        color: var(--neutral-600);
        margin: 0;
        line-height: 1.5;
    }

    .status-indicators {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: var(--neutral-50);
        border-radius: 10px;
    }

    .status-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        animation: blink 1.5s ease-in-out infinite;
    }

    .status-dot.active {
        background: var(--uitm-amber);
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    .status-text {
        font-size: 0.8rem;
        color: var(--neutral-600);
    }

    .help-section {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--neutral-200);
    }

    .help-section p {
        font-size: 0.85rem;
        color: var(--neutral-500);
        margin: 0;
    }

    @media (max-width: 576px) {
        .error-card {
            padding: 2rem 1.5rem;
        }

        .error-code {
            font-size: 5rem;
        }

        .error-code-bg {
            font-size: 8rem;
        }

        .error-title {
            font-size: 1.25rem;
        }

        .status-indicators {
            flex-direction: column;
            gap: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="error-container">
    <div class="error-card">
        <div class="error-code-wrapper">
            <span class="error-code-bg">503</span>
            <div class="error-code">503</div>
        </div>

        <div class="error-icon">
            <i class="fas fa-hard-hat"></i>
        </div>

        <h1 class="error-title">Service Unavailable</h1>

        <p class="error-message">
            The application is currently down for scheduled maintenance.
            We'll be back shortly with improvements!
        </p>

        <div class="maintenance-box">
            <div class="maintenance-box-icon">
                <i class="fas fa-wrench"></i>
            </div>
            <div class="maintenance-box-content">
                <h6>Scheduled Maintenance</h6>
                <p>We're performing system updates to improve your experience. This maintenance window is expected to be brief.</p>
            </div>
        </div>

        <div class="status-indicators">
            <div class="status-item">
                <span class="status-dot active"></span>
                <span class="status-text">Maintenance in progress</span>
            </div>
        </div>

        <div class="help-section">
            <p>Need immediate assistance? Contact <strong>helpdesk@uitm.edu.my</strong></p>
        </div>
    </div>
</div>
@endsection
