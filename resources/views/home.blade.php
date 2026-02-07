@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-blue: #1e3a8a;
        --uitm-blue-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success: #059669;
    }

    body {
        font-family: 'IBM Plex Sans', sans-serif;
        background-color: var(--industrial-light);
    }

    .home-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 70vh;
    }

    .welcome-card {
        background: white;
        border-radius: 24px;
        padding: 3rem;
        text-align: center;
        max-width: 500px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
    }

    .welcome-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        box-shadow: 0 8px 25px rgba(30, 58, 138, 0.25);
    }

    .welcome-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .welcome-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--industrial-dark);
        margin-bottom: 0.75rem;
    }

    .welcome-subtitle {
        color: var(--industrial-gray);
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    .alert-success-industrial {
        background: rgba(5, 150, 105, 0.1);
        border: 1px solid rgba(5, 150, 105, 0.2);
        border-left: 4px solid var(--success);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        color: var(--success);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        text-align: left;
    }

    .btn-dashboard {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-dashboard:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 58, 138, 0.3);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="home-container">
        <div class="welcome-card">
            <div class="welcome-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="welcome-title">Welcome to UiTM CES</h2>
            <p class="welcome-subtitle">Credit Exemption System</p>

            @if (session('status'))
                <div class="alert-success-industrial">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <p style="color: var(--industrial-gray); margin-bottom: 1.5rem;">
                You are successfully logged in. Access your dashboard to manage your account.
            </p>

            <a href="{{ route('home') }}" class="btn-dashboard">
                <i class="fas fa-tachometer-alt"></i>
                Go to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
