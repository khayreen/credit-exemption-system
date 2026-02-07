@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e293b;
        --uitm-primary-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --uitm-green: #10b981;
        --uitm-red: #dc2626;
        --uitm-cyan: #0ea5e9;
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

    .profile-container {
        width: 100%;
        padding: 0 1rem;
    }

    /* Page Header */
    .page-header {
        position: relative;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-radius: 16px;
        padding: 2rem 2.5rem;
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

    /* Alerts */
    .industrial-alert {
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .industrial-alert.success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
        border: 2px solid var(--uitm-green);
    }

    .industrial-alert.danger {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
        border: 2px solid var(--uitm-red);
    }

    .industrial-alert.warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);
        border: 2px solid var(--uitm-amber);
    }

    .industrial-alert-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .industrial-alert.success .industrial-alert-icon {
        background: var(--uitm-green);
        color: white;
    }

    .industrial-alert.danger .industrial-alert-icon {
        background: var(--uitm-red);
        color: white;
    }

    .industrial-alert.warning .industrial-alert-icon {
        background: var(--uitm-amber);
        color: white;
    }

    .industrial-alert-content {
        flex: 1;
    }

    .industrial-alert-content h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .industrial-alert.success .industrial-alert-content h6 { color: #059669; }
    .industrial-alert.danger .industrial-alert-content h6 { color: var(--uitm-red); }
    .industrial-alert.warning .industrial-alert-content h6 { color: #b45309; }

    .industrial-alert-content p {
        font-size: 0.875rem;
        color: var(--neutral-700);
        margin: 0;
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
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        color: white;
    }

    .industrial-card-header.primary {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
    }

    .industrial-card-header.green {
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
    }

    .industrial-card-header.amber {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
    }

    .industrial-card-header.cyan {
        background: linear-gradient(135deg, var(--uitm-cyan) 0%, #0284c7 100%);
    }

    .industrial-card-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .industrial-card-header h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
    }

    .industrial-card-header p {
        font-size: 0.8rem;
        opacity: 0.85;
        margin: 0.25rem 0 0 0;
    }

    .industrial-card-body {
        padding: 1.5rem;
    }

    /* Form Styling */
    .form-label-industrial {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label-industrial i {
        color: var(--uitm-primary);
        font-size: 0.9rem;
    }

    .form-label-industrial .lock-icon {
        color: var(--neutral-400);
        font-size: 0.75rem;
        margin-left: auto;
    }

    .form-control-industrial {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        border: 2px solid var(--neutral-200);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-control-industrial:focus {
        border-color: var(--uitm-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        outline: none;
    }

    .form-control-industrial:disabled,
    .form-control-industrial[readonly] {
        background: var(--neutral-50);
        border-color: var(--neutral-200);
        color: var(--neutral-600);
    }

    .form-control-industrial.is-invalid {
        border-color: var(--uitm-red);
    }

    .form-text-industrial {
        font-size: 0.8rem;
        color: var(--neutral-500);
        margin-top: 0.35rem;
    }

    .invalid-feedback {
        font-size: 0.8rem;
        color: var(--uitm-red);
        margin-top: 0.35rem;
    }

    /* Photo Upload */
    .photo-upload-wrapper {
        text-align: center;
    }

    .profile-photo-display {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--neutral-200);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        margin-bottom: 1rem;
    }

    .btn-photo-change {
        background: white;
        border: 2px solid var(--uitm-primary);
        color: var(--uitm-primary);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-photo-change:hover {
        background: var(--uitm-primary);
        color: white;
    }

    /* Program Display Box */
    .program-display-box {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.08) 0%, rgba(30, 58, 138, 0.02) 100%);
        border: 2px solid var(--uitm-primary);
        border-radius: 12px;
        padding: 1.25rem;
    }

    .program-code-display {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--uitm-primary);
        margin-bottom: 0.35rem;
    }

    .program-name-display {
        font-size: 1rem;
        font-weight: 600;
        color: var(--neutral-800);
        margin-bottom: 0.75rem;
    }

    .program-help-text {
        font-size: 0.85rem;
        color: var(--neutral-600);
        padding-top: 0.75rem;
        border-top: 1px solid var(--neutral-200);
        margin-bottom: 0;
    }

    .btn-program-change {
        background: white;
        border: 2px solid var(--uitm-amber);
        color: #b45309;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-program-change:hover {
        background: var(--uitm-amber);
        color: white;
    }

    /* Action Buttons */
    .btn-primary-industrial {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary-industrial:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    .btn-secondary-industrial {
        background: white;
        border: 2px solid var(--neutral-300);
        color: var(--neutral-700);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary-industrial:hover {
        background: var(--neutral-50);
        border-color: var(--neutral-400);
        color: var(--neutral-800);
    }

    .btn-outline-primary-industrial {
        background: white;
        border: 2px solid var(--uitm-primary);
        color: var(--uitm-primary);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
    }

    .btn-outline-primary-industrial:hover {
        background: var(--uitm-primary);
        color: white;
    }

    /* Modal Styling */
    .modal-industrial .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .modal-industrial .modal-header {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-bottom: none;
        padding: 1.25rem 1.5rem;
    }

    .modal-industrial .modal-header.amber {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
    }

    .modal-industrial .modal-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        color: white;
        font-size: 1.1rem;
    }

    .modal-industrial .modal-body {
        padding: 1.5rem;
    }

    .modal-industrial .modal-footer {
        border-top: 1px solid var(--neutral-200);
        padding: 1rem 1.5rem;
    }

    .current-program-box {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.08) 0%, rgba(220, 38, 38, 0.02) 100%);
        border: 2px solid var(--uitm-red);
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
    }

    .current-program-box .program-code-display {
        color: var(--uitm-red);
    }

    /* Character Counter */
    #charCount {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
    }

    /* Password Strength */
    .password-strength-bar {
        height: 6px;
        border-radius: 3px;
        background: var(--neutral-200);
        margin-top: 0.5rem;
        overflow: hidden;
    }

    .password-strength-fill {
        height: 100%;
        transition: all 0.3s ease;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            text-align: center;
        }

        .page-header h1 {
            font-size: 1.35rem;
        }

        .industrial-card-body {
            padding: 1.25rem;
        }

        .profile-photo-display {
            width: 120px;
            height: 120px;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="eyebrow">Account Settings</div>
            <h1><i class="fas fa-user-circle me-2"></i>My Profile</h1>
            <p>Manage your personal information and account settings</p>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="industrial-alert success">
        <div class="industrial-alert-icon">
            <i class="fas fa-check"></i>
        </div>
        <div class="industrial-alert-content">
            <p>{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
    <div class="industrial-alert danger">
        <div class="industrial-alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="industrial-alert-content">
            <p>{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Pending Program Change Request Banner -->
    @if($pendingRequest)
    <div class="industrial-alert warning">
        <div class="industrial-alert-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="industrial-alert-content">
            <h6><i class="fas fa-hourglass-half me-1"></i>Program Change Request Pending</h6>
            <p class="mb-1">
                <strong>Requested Program:</strong> {{ $pendingRequest->requested_program_code }} - {{ $pendingRequest->requested_program_name }}
            </p>
            <p class="mb-0" style="font-size: 0.8rem;">
                <i class="fas fa-calendar me-1"></i>Submitted {{ $pendingRequest->created_at->diffForHumans() }} - Awaiting Academic Advisor review
            </p>
        </div>
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Section 1: Personal Information -->
        <div class="industrial-card">
            <div class="industrial-card-header primary">
                <div class="industrial-card-header-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h5>Personal Information</h5>
                    <p>Update your personal details and contact information</p>
                </div>
            </div>
            <div class="industrial-card-body">
                <div class="row">
                    <!-- Profile Photo -->
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="photo-upload-wrapper">
                            @if (Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile Photo" class="profile-photo-display">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=200&background=1e3a8a&color=fff" alt="Profile Photo" class="profile-photo-display">
                            @endif
                            <div>
                                <label for="photo" class="btn-photo-change">
                                    <i class="fas fa-camera me-1"></i>Change Photo
                                </label>
                                <input type="file" name="photo" id="photo" class="d-none" accept="image/*">
                                <p class="form-text-industrial mt-2">JPG, PNG or GIF (MAX. 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="col-md-9">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label-industrial">
                                    <i class="fas fa-id-card"></i>Full Name
                                </label>
                                <input type="text" class="form-control-industrial @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone_number" class="form-label-industrial">
                                    <i class="fas fa-phone"></i>Phone Number
                                </label>
                                <input type="text" class="form-control-industrial @error('phone_number') is-invalid @enderror"
                                       id="phone_number" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($student)
                            <div class="col-12">
                                <label for="home_address" class="form-label-industrial">
                                    <i class="fas fa-home"></i>Home Address
                                </label>
                                <textarea class="form-control-industrial @error('home_address') is-invalid @enderror"
                                          id="home_address" name="home_address" rows="2">{{ old('home_address', $student->home_address) }}</textarea>
                                @error('home_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Academic Information (Read-Only) -->
        @if($student)
        <div class="industrial-card">
            <div class="industrial-card-header green">
                <div class="industrial-card-header-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <h5>Academic Information</h5>
                    <p>Your enrolled program and academic details</p>
                </div>
            </div>
            <div class="industrial-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-industrial">
                            <i class="fas fa-id-badge"></i>Matric Number
                            <span class="lock-icon" title="Cannot be changed"><i class="fas fa-lock"></i></span>
                        </label>
                        <input type="text" class="form-control-industrial" value="{{ $student->matric_no }}" readonly disabled>
                        <p class="form-text-industrial">Contact Registry Office to update</p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-industrial">
                            <i class="fas fa-university"></i>Campus
                            <span class="lock-icon" title="Cannot be changed"><i class="fas fa-lock"></i></span>
                        </label>
                        <input type="text" class="form-control-industrial" value="{{ $student->campus }}" readonly disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-industrial">
                            <i class="fas fa-building"></i>Faculty
                            <span class="lock-icon" title="Cannot be changed"><i class="fas fa-lock"></i></span>
                        </label>
                        <input type="text" class="form-control-industrial" value="{{ $student->faculty ? $student->faculty->name : 'Not set' }}" readonly disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-industrial">
                            <i class="fas fa-calendar-alt"></i>Intake Semester
                            <span class="lock-icon" title="Cannot be changed"><i class="fas fa-lock"></i></span>
                        </label>
                        <input type="text" class="form-control-industrial" value="{{ $student->intake_semester ?? 'Not set' }}" readonly disabled>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                            <label class="form-label-industrial mb-0">
                                <i class="fas fa-award"></i>Current Degree Program
                                <span class="lock-icon" title="Cannot be changed directly"><i class="fas fa-lock"></i></span>
                            </label>
                            @if(!$pendingRequest)
                            <button type="button" class="btn-program-change" data-bs-toggle="modal" data-bs-target="#programChangeModal">
                                <i class="fas fa-exchange-alt me-1"></i>Request Program Change
                            </button>
                            @endif
                        </div>
                        <div class="program-display-box">
                            <div class="program-code-display">{{ $student->program_code }}</div>
                            <div class="program-name-display">{{ $student->program_name }}</div>
                            <p class="program-help-text">
                                <i class="fas fa-info-circle me-1"></i>
                                This determines which course equivalencies are available to you. If you registered with the wrong program, click "Request Program Change" to submit a correction request.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Previous Education -->
        <div class="industrial-card">
            <div class="industrial-card-header cyan">
                <div class="industrial-card-header-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div>
                    <h5>Previous Education</h5>
                    <p>Your diploma and previous institution details</p>
                </div>
            </div>
            <div class="industrial-card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label-industrial">
                            <i class="fas fa-building"></i>Institution Type
                        </label>
                        <input type="text" class="form-control-industrial" value="UiTM Diploma (CS110)" readonly disabled>
                        <p class="form-text-industrial">Registered during application submission</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Section 4: Account Security -->
        <div class="industrial-card">
            <div class="industrial-card-header amber">
                <div class="industrial-card-header-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h5>Account Security</h5>
                    <p>Manage your email and security settings</p>
                </div>
            </div>
            <div class="industrial-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-industrial">
                            <i class="fas fa-envelope"></i>Email Address
                            <span class="lock-icon" title="Cannot be changed for security"><i class="fas fa-lock"></i></span>
                        </label>
                        <input type="email" class="form-control-industrial" value="{{ Auth::user()->email }}" readonly disabled>
                        <p class="form-text-industrial">
                            <i class="fas fa-exclamation-triangle me-1"></i>Email cannot be changed for security reasons
                        </p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-industrial">
                            <i class="fas fa-key"></i>Password
                        </label>
                        <button type="button" class="btn-outline-primary-industrial" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-lock"></i>Change Password
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Button -->
        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('student.dashboard') }}" class="btn-secondary-industrial">
                <i class="fas fa-times"></i>Cancel
            </a>
            <button type="submit" class="btn-primary-industrial">
                <i class="fas fa-save"></i>Save Changes
            </button>
        </div>
    </form>
</div>

<!-- Program Change Request Modal -->
@if($student && !$pendingRequest)
<div class="modal fade modal-industrial" id="programChangeModal" tabindex="-1" aria-labelledby="programChangeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header amber">
                <h5 class="modal-title" id="programChangeModalLabel">
                    <i class="fas fa-exchange-alt me-2"></i>Request Program Change
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.requestProgramChange') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Warning Alert -->
                    <div class="industrial-alert warning mb-4">
                        <div class="industrial-alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="industrial-alert-content">
                            <h6>Important Notice</h6>
                            <p>Changing your program will affect your course equivalencies. Existing applications may need re-evaluation. <strong>You must upload your official degree offer letter</strong> for verification. Your Academic Advisor will verify the document before approving your request.</p>
                        </div>
                    </div>

                    <!-- Current Program -->
                    <div class="mb-4">
                        <label class="form-label-industrial">Current Program</label>
                        <div class="current-program-box">
                            <div class="program-code-display">{{ $student->program_code }}</div>
                            <div style="color: var(--neutral-700);">{{ $student->program_name }}</div>
                        </div>
                    </div>

                    <!-- Requested Program -->
                    <div class="mb-4">
                        <label for="requested_program_code" class="form-label-industrial">
                            New Program <span style="color: var(--uitm-red);">*</span>
                        </label>
                        <select class="form-control-industrial @error('requested_program_code') is-invalid @enderror"
                                id="requested_program_code" name="requested_program_code" required>
                            <option value="">-- Select New Program --</option>
                            @foreach($supportedPrograms as $program)
                                @if($program->code !== $student->program_code)
                                <option value="{{ $program->code }}">{{ $program->code }} - {{ $program->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('requested_program_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Reason -->
                    <div class="mb-4">
                        <label for="reason" class="form-label-industrial">
                            Reason for Change <span style="color: var(--uitm-red);">*</span>
                        </label>
                        <textarea class="form-control-industrial @error('reason') is-invalid @enderror"
                                  id="reason" name="reason" rows="4"
                                  placeholder="Please provide a detailed reason for requesting this program change (minimum 50 characters)..."
                                  required minlength="50" maxlength="1000"></textarea>
                        <p class="form-text-industrial">
                            <span id="charCount">0</span> / 1000 characters (minimum 50 required)
                        </p>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Offer Letter (Required) -->
                    <div class="mb-3">
                        <label for="supporting_document" class="form-label-industrial">
                            Degree Offer Letter <span style="color: var(--uitm-red);">*</span>
                        </label>
                        <input type="file" class="form-control-industrial @error('supporting_document') is-invalid @enderror"
                               id="supporting_document" name="supporting_document"
                               accept=".pdf,.jpg,.jpeg,.png" required>
                        <p class="form-text-industrial">
                            <i class="fas fa-exclamation-circle me-1" style="color: var(--uitm-red);"></i>
                            <strong>REQUIRED:</strong> Upload your official degree offer letter from UiTM. PDF, JPG, or PNG (MAX. 5MB)
                        </p>
                        @error('supporting_document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-industrial" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>Cancel
                    </button>
                    <button type="submit" class="btn-primary-industrial">
                        <i class="fas fa-paper-plane"></i>Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Change Password Modal -->
<div class="modal fade modal-industrial" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="fas fa-key me-2"></i>Change Password
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.changePassword') }}" method="POST" id="changePasswordForm">
                @csrf
                <div class="modal-body">
                    <!-- Security Notice -->
                    <div class="industrial-alert warning mb-4" style="border-color: var(--uitm-cyan); background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(14, 165, 233, 0.05) 100%);">
                        <div class="industrial-alert-icon" style="background: var(--uitm-cyan);">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="industrial-alert-content">
                            <p style="color: var(--neutral-700);">For your security, you'll need to enter your current password to make changes. Choose a strong password with at least 8 characters.</p>
                        </div>
                    </div>

                    <!-- Current Password -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label-industrial">
                            Current Password <span style="color: var(--uitm-red);">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control-industrial @error('current_password') is-invalid @enderror"
                                   id="current_password" name="current_password" required
                                   placeholder="Enter your current password" style="border-radius: 8px 0 0 8px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')" style="border-radius: 0 8px 8px 0; border: 2px solid var(--neutral-200); border-left: none;">
                                <i class="fas fa-eye" id="current_password_icon"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label for="new_password" class="form-label-industrial">
                            New Password <span style="color: var(--uitm-red);">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control-industrial @error('new_password') is-invalid @enderror"
                                   id="new_password" name="new_password" required
                                   placeholder="Enter your new password" minlength="8" style="border-radius: 8px 0 0 8px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')" style="border-radius: 0 8px 8px 0; border: 2px solid var(--neutral-200); border-left: none;">
                                <i class="fas fa-eye" id="new_password_icon"></i>
                            </button>
                        </div>
                        <p class="form-text-industrial">
                            <span id="passwordLength">0</span> / 8 characters minimum
                        </p>
                        <!-- Password Strength -->
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <small style="color: var(--neutral-500);">Strength:</small>
                            <div class="password-strength-bar flex-grow-1">
                                <div class="password-strength-fill" id="passwordStrengthBar" style="width: 0%;"></div>
                            </div>
                            <small id="passwordStrengthText" style="font-weight: 600;"></small>
                        </div>
                        @error('new_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label-industrial">
                            Confirm New Password <span style="color: var(--uitm-red);">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control-industrial"
                                   id="new_password_confirmation" name="new_password_confirmation" required
                                   placeholder="Re-enter your new password" minlength="8" style="border-radius: 8px 0 0 8px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')" style="border-radius: 0 8px 8px 0; border: 2px solid var(--neutral-200); border-left: none;">
                                <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                            </button>
                        </div>
                        <p class="form-text-industrial" id="passwordMatchText"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-industrial" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>Cancel
                    </button>
                    <button type="submit" class="btn-primary-industrial">
                        <i class="fas fa-save"></i>Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Photo preview
    const photoInput = document.getElementById('photo');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.profile-photo-display').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Character counter for reason textarea
    const reasonTextarea = document.getElementById('reason');
    const charCount = document.getElementById('charCount');
    if (reasonTextarea && charCount) {
        reasonTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            if (this.value.length < 50) {
                charCount.style.color = '#dc2626';
            } else {
                charCount.style.color = '#10b981';
            }
        });
    }

    // Password strength checker
    const newPasswordInput = document.getElementById('new_password');
    const passwordLengthSpan = document.getElementById('passwordLength');
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');

    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            passwordLengthSpan.textContent = password.length;

            // Calculate password strength
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z\d]/.test(password)) strength++;

            // Update strength bar
            const strengthPercent = (strength / 5) * 100;
            strengthBar.style.width = strengthPercent + '%';

            if (strength <= 1) {
                strengthBar.style.background = '#dc2626';
                strengthText.textContent = 'Weak';
                strengthText.style.color = '#dc2626';
            } else if (strength <= 3) {
                strengthBar.style.background = '#f59e0b';
                strengthText.textContent = 'Medium';
                strengthText.style.color = '#f59e0b';
            } else {
                strengthBar.style.background = '#10b981';
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#10b981';
            }
        });
    }

    // Password match checker
    const confirmPasswordInput = document.getElementById('new_password_confirmation');
    const matchText = document.getElementById('passwordMatchText');

    if (confirmPasswordInput && newPasswordInput) {
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        newPasswordInput.addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const password = newPasswordInput.value;
            const confirm = confirmPasswordInput.value;

            if (confirm.length > 0) {
                if (password === confirm) {
                    matchText.innerHTML = '<i class="fas fa-check-circle me-1" style="color: #10b981;"></i>Passwords match';
                    matchText.style.color = '#10b981';
                    confirmPasswordInput.classList.remove('is-invalid');
                    confirmPasswordInput.style.borderColor = '#10b981';
                } else {
                    matchText.innerHTML = '<i class="fas fa-times-circle me-1" style="color: #dc2626;"></i>Passwords do not match';
                    matchText.style.color = '#dc2626';
                    confirmPasswordInput.style.borderColor = '#dc2626';
                }
            } else {
                matchText.innerHTML = '';
                confirmPasswordInput.style.borderColor = '';
            }
        }
    }
});

// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
