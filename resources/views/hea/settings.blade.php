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
        --warning-color: #ea580c;
        --info-color: #0d9488;
    }

    .settings-page {
        font-family: 'IBM Plex Sans', sans-serif;
        background: var(--industrial-light);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .page-header h1 {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .page-header p {
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
        font-size: 1rem;
    }

    .header-actions {
        position: absolute;
        top: 50%;
        right: 2rem;
        transform: translateY(-50%);
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    /* Alert Messages */
    .alert-custom {
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-custom.success {
        background: rgba(5, 150, 105, 0.08);
        border: 1px solid rgba(5, 150, 105, 0.2);
        border-left: 4px solid var(--success-color);
        color: var(--success-color);
    }

    .alert-custom.danger {
        background: rgba(220, 38, 38, 0.08);
        border: 1px solid rgba(220, 38, 38, 0.2);
        border-left: 4px solid var(--danger-color);
        color: var(--danger-color);
    }

    /* Settings Card */
    .settings-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .settings-card-header {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .settings-card-header h5 {
        color: var(--industrial-dark);
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .settings-card-header h5 i {
        color: var(--uitm-blue);
    }

    .settings-card-body {
        padding: 1.5rem;
    }

    /* Setting Item */
    .setting-item {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .setting-item:last-child {
        margin-bottom: 0;
    }

    .setting-item-header {
        background: var(--industrial-light);
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .setting-item-header strong {
        color: var(--industrial-dark);
        font-weight: 600;
        font-size: 0.95rem;
    }

    .setting-item-header small {
        display: block;
        color: var(--industrial-gray);
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .setting-item-body {
        padding: 1.25rem;
    }

    /* Form Elements */
    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    textarea.form-control {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--industrial-gray);
        margin-top: 0.5rem;
    }

    .form-text a {
        color: var(--uitm-blue);
        text-decoration: none;
    }

    .form-text a:hover {
        text-decoration: underline;
    }

    /* Submit Button */
    .btn-submit {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: #fff;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px 0 rgba(30, 58, 138, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px 0 rgba(30, 58, 138, 0.4);
        color: #fff;
    }

    /* Info Card */
    .info-card {
        background: rgba(13, 148, 136, 0.05);
        border: 1px solid rgba(13, 148, 136, 0.2);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-card i {
        color: var(--info-color);
        font-size: 1.1rem;
    }

    .info-card span {
        color: var(--industrial-gray);
        font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .header-actions {
            position: static;
            transform: none;
            margin-top: 1rem;
        }

        .settings-card-body {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1>System Settings</h1>
            <p>Configure system-wide settings</p>
            <div class="header-actions">
                <a href="{{ route('hea.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>Back to Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert-custom success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-custom danger">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <!-- Settings Form -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h5><i class="fas fa-cogs"></i>System Settings</h5>
            </div>
            <div class="settings-card-body">
                <form method="POST" action="{{ route('hea.settings.update') }}">
                    @csrf
                    @method('PATCH')

                    @foreach($settings as $setting)
                    <div class="setting-item">
                        <div class="setting-item-header">
                            <strong>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</strong>
                            @if($setting->description)
                            <small>{{ $setting->description }}</small>
                            @endif
                        </div>
                        <div class="setting-item-body">
                            @if($setting->type === 'textarea')
                            <textarea name="{{ $setting->key }}"
                                      class="form-control"
                                      rows="8"
                                      placeholder="Enter {{ strtolower(str_replace('_', ' ', $setting->key)) }}">{{ $setting->value }}</textarea>
                            @elseif($setting->type === 'url')
                            <input type="url"
                                   name="{{ $setting->key }}"
                                   class="form-control"
                                   value="{{ $setting->value }}"
                                   placeholder="https://example.com">
                            <div class="form-text">
                                <i class="fas fa-external-link-alt me-1"></i>
                                <a href="{{ $setting->value }}" target="_blank" rel="noopener">Test link</a>
                            </div>
                            @else
                            <input type="text"
                                   name="{{ $setting->key }}"
                                   class="form-control"
                                   value="{{ $setting->value }}"
                                   placeholder="Enter {{ strtolower(str_replace('_', ' ', $setting->key)) }}">
                            @endif
                        </div>
                    </div>
                    @endforeach

                    <div class="text-center mt-4">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i>Update Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="info-card">
            <i class="fas fa-info-circle"></i>
            <span>Changes to these settings will affect the entire system. Please review carefully before saving.</span>
        </div>
    </div>
</div>
@endsection
