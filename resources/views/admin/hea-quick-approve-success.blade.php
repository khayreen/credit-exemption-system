@extends('layouts.app')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap');

    :root {
        --uitm-blue: #1e3a8a;
        --uitm-blue-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success-color: #059669;
        --danger-color: #dc2626;
    }

    .success-page {
        font-family: 'IBM Plex Sans', sans-serif;
        background: var(--industrial-light);
        min-height: 100vh;
        padding: 2rem 0;
        display: flex;
        align-items: center;
    }

    .success-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        max-width: 500px;
        margin: 0 auto;
    }

    .success-header {
        background: linear-gradient(135deg, var(--success-color) 0%, #10b981 100%);
        padding: 2.5rem 2rem;
        text-align: center;
        position: relative;
    }

    .success-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--uitm-amber), #fbbf24, var(--uitm-amber));
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border: 3px solid rgba(255, 255, 255, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .success-icon i {
        font-size: 2.5rem;
        color: #fff;
    }

    .success-header h1 {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }

    .success-body {
        padding: 2.5rem 2rem;
        text-align: center;
    }

    .success-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--industrial-dark);
        margin-bottom: 1rem;
    }

    .user-name {
        color: var(--uitm-blue);
        font-weight: 700;
    }

    .success-message {
        color: var(--industrial-gray);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .notification-card {
        background: rgba(13, 148, 136, 0.08);
        border: 1px solid rgba(13, 148, 136, 0.2);
        border-left: 4px solid #0d9488;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 2rem;
        text-align: left;
    }

    .notification-card h6 {
        color: #0d9488;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notification-card p {
        color: var(--industrial-gray);
        font-size: 0.9rem;
        margin: 0;
    }

    .notification-card .email {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .btn-login {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: #fff;
        border: none;
        padding: 1rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px 0 rgba(30, 58, 138, 0.3);
    }

    .btn-login:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px 0 rgba(30, 58, 138, 0.4);
    }
</style>
@endpush

@section('content')
<div class="success-page">
    <div class="container-fluid">
        <div class="success-card">
            <div class="success-header">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1>HEA Registration Approved</h1>
            </div>

            <div class="success-body">
                <h2 class="success-title">Success!</h2>

                <p class="success-message">
                    <span class="user-name">{{ $user->name }}</span> has been approved as HEA Personnel.
                </p>

                <div class="notification-card">
                    <h6><i class="fas fa-envelope"></i> Notification Sent</h6>
                    <p>An approval email has been sent to <span class="email">{{ $user->email }}</span> with login instructions.</p>
                </div>

                <a href="{{ url('/login') }}" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Go to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
