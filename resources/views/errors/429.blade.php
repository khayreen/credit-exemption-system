@extends('layouts.app')

@section('title', __('Too Many Requests'))

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e293b;
        --uitm-amber: #f59e0b;
        --uitm-orange: #ea580c;
        --uitm-orange-dark: #c2410c;
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
        background: linear-gradient(90deg, var(--uitm-orange), var(--uitm-amber), var(--uitm-orange));
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
        background: linear-gradient(135deg, var(--uitm-orange) 0%, var(--uitm-orange-dark) 100%);
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
        background: linear-gradient(135deg, rgba(234, 88, 12, 0.15) 0%, rgba(234, 88, 12, 0.05) 100%);
        border: 2px solid var(--uitm-orange);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .error-icon i {
        font-size: 1.75rem;
        color: var(--uitm-orange);
    }

    .error-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--uitm-orange);
        margin-bottom: 0.75rem;
    }

    .error-message {
        font-size: 1rem;
        color: var(--neutral-600);
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .rate-limit-box {
        background: linear-gradient(135deg, rgba(234, 88, 12, 0.1) 0%, rgba(234, 88, 12, 0.05) 100%);
        border: 2px solid var(--uitm-orange);
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        text-align: left;
    }

    .rate-limit-box-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--uitm-orange);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .rate-limit-box-icon i {
        color: white;
        font-size: 1rem;
    }

    .rate-limit-box-content h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        color: #9a3412;
        margin-bottom: 0.25rem;
    }

    .rate-limit-box-content p {
        font-size: 0.85rem;
        color: var(--neutral-600);
        margin: 0;
        line-height: 1.5;
    }

    .countdown-wrapper {
        background: var(--neutral-50);
        border: 2px solid var(--neutral-200);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 2rem;
    }

    .countdown-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--neutral-500);
        margin-bottom: 0.5rem;
    }

    .countdown-timer {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 2rem;
        font-weight: 700;
        color: var(--uitm-primary);
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

        .btn-group-error a {
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
            <span class="error-code-bg">429</span>
            <div class="error-code">429</div>
        </div>

        <div class="error-icon">
            <i class="fas fa-tachometer-alt"></i>
        </div>

        <h1 class="error-title">Too Many Requests</h1>

        <p class="error-message">
            You have made too many requests in a short period.
            Please wait a moment before trying again.
        </p>

        <div class="rate-limit-box">
            <div class="rate-limit-box-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="rate-limit-box-content">
                <h6>Rate Limit Protection</h6>
                <p>This limit helps protect our system from overload and ensures fair access for all users. Please wait before making additional requests.</p>
            </div>
        </div>

        <div class="countdown-wrapper">
            <div class="countdown-label">Please wait before trying again</div>
            <div class="countdown-timer" id="countdown">01:00</div>
        </div>

        <div class="btn-group-error">
            <a href="{{ route('home') }}" class="btn-primary-industrial">
                <i class="fas fa-home"></i>
                Go to Homepage
            </a>
            <a href="javascript:history.back()" class="btn-secondary-industrial">
                <i class="fas fa-arrow-left"></i>
                Go Back
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let timeLeft = 60;
    const countdownEl = document.getElementById('countdown');

    const timer = setInterval(function() {
        timeLeft--;
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownEl.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

        if (timeLeft <= 0) {
            clearInterval(timer);
            countdownEl.textContent = 'Ready!';
            countdownEl.style.color = '#10b981';
        }
    }, 1000);
});
</script>
@endpush
