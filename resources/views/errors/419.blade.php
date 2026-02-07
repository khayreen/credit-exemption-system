@extends('layouts.app')

@section('title', __('Page Expired'))

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e293b;
        --uitm-amber: #f59e0b;
        --uitm-amber-dark: #d97706;
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
        background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-primary), var(--uitm-amber));
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
        background: linear-gradient(135deg, var(--uitm-amber) 0%, var(--uitm-amber-dark) 100%);
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
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.05) 100%);
        border: 2px solid var(--uitm-amber);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .error-icon i {
        font-size: 1.75rem;
        color: var(--uitm-amber);
    }

    .error-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--uitm-amber-dark);
        margin-bottom: 0.75rem;
    }

    .error-message {
        font-size: 1rem;
        color: var(--neutral-600);
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .session-box {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);
        border: 2px solid var(--uitm-amber);
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        text-align: left;
    }

    .session-box-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--uitm-amber);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .session-box-icon i {
        color: white;
        font-size: 1rem;
    }

    .session-box-content h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        color: #92400e;
        margin-bottom: 0.25rem;
    }

    .session-box-content p {
        font-size: 0.85rem;
        color: var(--neutral-600);
        margin: 0;
        line-height: 1.5;
    }

    .btn-group-error {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-primary-industrial {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary-industrial:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(30, 58, 138, 0.3);
    }

    .btn-secondary-industrial {
        background: white;
        border: 2px solid var(--neutral-300);
        color: var(--neutral-700);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-secondary-industrial:hover {
        background: var(--neutral-50);
        border-color: var(--neutral-400);
        color: var(--neutral-600);
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

        .btn-group-error {
            flex-direction: column;
        }

        .btn-group-error a,
        .btn-group-error button {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="error-container">
    <div class="error-card">
        <div class="error-code-wrapper">
            <span class="error-code-bg">419</span>
            <div class="error-code">419</div>
        </div>

        <div class="error-icon">
            <i class="fas fa-hourglass-end"></i>
        </div>

        <h1 class="error-title">Page Expired</h1>

        <p class="error-message">
            Your session has expired due to inactivity.
            Please refresh the page and try again.
        </p>

        <div class="session-box">
            <div class="session-box-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="session-box-content">
                <h6>Session Timeout</h6>
                <p>For your security, sessions expire after a period of inactivity. This helps protect your account from unauthorized access.</p>
            </div>
        </div>

        <div class="btn-group-error">
            <button onclick="location.reload()" class="btn-primary-industrial">
                <i class="fas fa-redo"></i>
                Refresh Page
            </button>
            <a href="{{ url()->previous() }}" class="btn-secondary-industrial">
                <i class="fas fa-arrow-left"></i>
                Go Back
            </a>
        </div>
    </div>
</div>
@endsection
