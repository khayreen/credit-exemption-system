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

    .form-page {
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

    /* Form Card */
    .form-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .form-card-header {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .form-card-header h5 {
        color: var(--industrial-dark);
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-card-header h5 i {
        color: var(--uitm-blue);
    }

    .form-card-body {
        padding: 1.5rem;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-label .text-danger {
        color: var(--danger-color) !important;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-control.is-invalid {
        border-color: var(--danger-color);
    }

    textarea.form-control {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--industrial-gray);
        margin-top: 0.375rem;
    }

    .form-check-input {
        width: 1.125rem;
        height: 1.125rem;
        border: 2px solid #cbd5e1;
        border-radius: 4px;
    }

    .form-check-input:checked {
        background-color: var(--uitm-blue);
        border-color: var(--uitm-blue);
    }

    .form-check-label {
        font-weight: 500;
        color: var(--industrial-dark);
        margin-left: 0.25rem;
    }

    .invalid-feedback {
        font-size: 0.8rem;
        color: var(--danger-color);
    }

    /* Version Badge */
    .version-input {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
    }

    /* Divider */
    .form-divider {
        border: 0;
        border-top: 1px solid #e2e8f0;
        margin: 1.5rem 0;
    }

    /* Submit Button */
    .btn-submit {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: #fff;
        border: none;
        padding: 0.875rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        width: 100%;
        justify-content: center;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        color: #fff;
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

        .form-card-body {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="form-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Create Terms & Conditions</h1>
            <p>Add a new version of terms and conditions</p>
            <div class="header-actions">
                <a href="{{ route('admin.content.terms.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>Back
                </a>
            </div>
        </div>

        <form action="{{ route('admin.content.terms.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-lg-8">
                    <!-- Content Card -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <h5><i class="fas fa-file-alt"></i>Content</h5>
                        </div>
                        <div class="form-card-body">
                            <div class="mb-0">
                                <label class="form-label">Terms & Conditions Content <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="20" required>{{ old('content', $currentTerms->content ?? '') }}</textarea>
                                @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text">You can copy from the existing version and modify as needed.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Version Details Card -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <h5><i class="fas fa-cog"></i>Version Details</h5>
                        </div>
                        <div class="form-card-body">
                            <div class="mb-3">
                                <label class="form-label">Version Number <span class="text-danger">*</span></label>
                                <input type="text" name="version" class="form-control version-input @error('version') is-invalid @enderror"
                                       value="{{ old('version', $nextVersion) }}" required>
                                @error('version')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text">e.g., 1.0, 1.1, 2.0</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Effective Date <span class="text-danger">*</span></label>
                                <input type="date" name="effective_date" class="form-control @error('effective_date') is-invalid @enderror"
                                       value="{{ old('effective_date', now()->format('Y-m-d')) }}" required>
                                @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="set_as_current" value="1" class="form-check-input" id="setAsCurrent" checked>
                                    <label class="form-check-label" for="setAsCurrent">
                                        Set as current active version
                                    </label>
                                </div>
                            </div>

                            <hr class="form-divider">

                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save"></i>Create Version
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
