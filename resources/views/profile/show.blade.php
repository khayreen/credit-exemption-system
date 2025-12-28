@extends('layouts.app')

@section('content')
<div class="profile-container">
    <!-- Header -->
    <div class="profile-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="profile-title">My Profile</h1>
                <p class="profile-subtitle">Manage your personal information and account settings</p>
            </div>
            <div class="col-lg-4 text-end">
                <div class="profile-illustration">
                    <i class="fas fa-user-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 4px solid #48bb78;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 4px solid #f56565;">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Pending Program Change Request Banner -->
    @if($pendingRequest)
    <div class="alert alert-warning mb-4" style="border-radius: 12px; border-left: 4px solid #ed8936; background: linear-gradient(135deg, #fffbeb, #fef3c7);">
        <div class="d-flex align-items-center">
            <div style="font-size: 2.5rem; color: #ed8936; margin-right: 1rem;">
                <i class="fas fa-clock"></i>
            </div>
            <div style="flex: 1;">
                <h5 style="color: #92400e; font-weight: 700; margin-bottom: 0.5rem;">
                    <i class="fas fa-hourglass-half me-2"></i>Program Change Request Pending
                </h5>
                <p style="color: #78350f; margin-bottom: 0.25rem;">
                    <strong>Requested Program:</strong> {{ $pendingRequest->requested_program_code }} - {{ $pendingRequest->requested_program_name }}
                </p>
                <p style="color: #78350f; margin-bottom: 0.25rem; font-size: 0.9rem;">
                    <i class="fas fa-file-pdf me-1"></i>Offer letter submitted for verification
                </p>
                <p style="color: #78350f; margin-bottom: 0; font-size: 0.9rem;">
                    <i class="fas fa-calendar me-1"></i>Submitted {{ $pendingRequest->created_at->diffForHumans() }} • Awaiting Academic Advisor review
                </p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Section 1: Personal Information -->
        <div class="profile-card">
            <div class="profile-card-header">
                <div class="icon-badge bg-primary">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h3>Personal Information</h3>
                    <p class="mb-0">Update your personal details and contact information</p>
                </div>
            </div>
            <div class="profile-card-body">
                <div class="row">
                    <!-- Profile Photo -->
                    <div class="col-md-3 text-center mb-4 mb-md-0">
                        <div class="photo-upload-section">
                            @if (Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile Photo" class="profile-photo-large">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=200&background=667eea&color=fff" alt="Profile Photo" class="profile-photo-large">
                            @endif
                            <div class="mt-3">
                                <label for="photo" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-camera me-1"></i>Change Photo
                                </label>
                                <input type="file" name="photo" id="photo" class="d-none" accept="image/*">
                                <p class="text-muted mt-2 small">JPG, PNG or GIF (MAX. 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="col-md-9">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fas fa-id-card me-1 text-primary"></i>Full Name
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone_number" class="form-label fw-semibold">
                                    <i class="fas fa-phone me-1 text-primary"></i>Phone Number
                                </label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                       id="phone_number" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($student)
                            <div class="col-12">
                                <label for="home_address" class="form-label fw-semibold">
                                    <i class="fas fa-home me-1 text-primary"></i>Home Address
                                </label>
                                <textarea class="form-control @error('home_address') is-invalid @enderror"
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

        <!-- Section 2: Academic Information (Read-Only with Request System) -->
        @if($student)
        <div class="profile-card">
            <div class="profile-card-header">
                <div class="icon-badge bg-success">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <h3>Academic Information</h3>
                    <p class="mb-0">Your enrolled program and academic details</p>
                </div>
            </div>
            <div class="profile-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold d-flex align-items-center">
                            <i class="fas fa-id-badge me-1 text-success"></i>Matric Number
                            <span class="lock-icon" data-bs-toggle="tooltip" title="Cannot be changed">
                                <i class="fas fa-lock"></i>
                            </span>
                        </label>
                        <input type="text" class="form-control" value="{{ $student->matric_no }}" readonly disabled>
                        <small class="text-muted">Contact Registry Office to update</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold d-flex align-items-center">
                            <i class="fas fa-university me-1 text-success"></i>Campus
                            <span class="lock-icon" data-bs-toggle="tooltip" title="Cannot be changed">
                                <i class="fas fa-lock"></i>
                            </span>
                        </label>
                        <input type="text" class="form-control" value="{{ $student->campus }}" readonly disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold d-flex align-items-center">
                            <i class="fas fa-building me-1 text-success"></i>Faculty
                            <span class="lock-icon" data-bs-toggle="tooltip" title="Cannot be changed">
                                <i class="fas fa-lock"></i>
                            </span>
                        </label>
                        <input type="text" class="form-control" value="{{ $student->faculty ? $student->faculty->name : 'Not set' }}" readonly disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold d-flex align-items-center">
                            <i class="fas fa-calendar-alt me-1 text-success"></i>Intake Semester
                            <span class="lock-icon" data-bs-toggle="tooltip" title="Cannot be changed">
                                <i class="fas fa-lock"></i>
                            </span>
                        </label>
                        <input type="text" class="form-control" value="{{ $student->intake_semester ?? 'Not set' }}" readonly disabled>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold d-flex align-items-center justify-content-between">
                            <span>
                                <i class="fas fa-award me-1 text-success"></i>Current Degree Program
                                <span class="lock-icon" data-bs-toggle="tooltip" title="Cannot be changed directly">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </span>
                            @if(!$pendingRequest)
                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#programChangeModal">
                                <i class="fas fa-exchange-alt me-1"></i>Request Program Change
                            </button>
                            @endif
                        </label>
                        <div class="program-display-box">
                            <div class="program-code">{{ $student->program_code }}</div>
                            <div class="program-name">{{ $student->program_name }}</div>
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
        <div class="profile-card">
            <div class="profile-card-header">
                <div class="icon-badge bg-info">
                    <i class="fas fa-school"></i>
                </div>
                <div>
                    <h3>Previous Education</h3>
                    <p class="mb-0">Your diploma and previous institution details</p>
                </div>
            </div>
            <div class="profile-card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-building me-1 text-info"></i>Institution Type
                        </label>
                        <input type="text" class="form-control" value="UiTM Diploma (CS110)" readonly disabled>
                        <small class="text-muted">Registered during application submission</small>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Section 4: Account Security -->
        <div class="profile-card">
            <div class="profile-card-header">
                <div class="icon-badge bg-warning">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h3>Account Security</h3>
                    <p class="mb-0">Manage your email and security settings</p>
                </div>
            </div>
            <div class="profile-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold d-flex align-items-center">
                            <i class="fas fa-envelope me-1 text-warning"></i>Email Address
                            <span class="lock-icon" data-bs-toggle="tooltip" title="Cannot be changed for security">
                                <i class="fas fa-lock"></i>
                            </span>
                        </label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly disabled>
                        <small class="text-muted">
                            <i class="fas fa-exclamation-triangle me-1"></i>Email cannot be changed for security reasons
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-key me-1 text-warning"></i>Password
                        </label>
                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="fas fa-lock me-1"></i>Change Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Button -->
        <div class="text-end mb-4">
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-times me-1"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>Save Changes
            </button>
        </div>
    </form>
</div>

<!-- Program Change Request Modal -->
@if($student && !$pendingRequest)
<div class="modal fade" id="programChangeModal" tabindex="-1" aria-labelledby="programChangeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="programChangeModalLabel">
                    <i class="fas fa-exchange-alt me-2"></i>Request Program Change
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.requestProgramChange') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="padding: 2rem;">
                    <!-- Warning Alert -->
                    <div class="alert alert-warning" style="border-radius: 12px; border-left: 4px solid #ed8936;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem; color: #ed8936; margin-right: 1rem; margin-top: 0.25rem;"></i>
                            <div>
                                <h6 style="color: #92400e; font-weight: 700;">Important Notice</h6>
                                <p class="mb-0" style="color: #78350f; font-size: 0.95rem;">
                                    Changing your program will affect your course equivalencies. Existing applications may need re-evaluation. <strong>You must upload your official degree offer letter</strong> for verification. Your Academic Advisor will verify the document before approving your request.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Current Program -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Current Program</label>
                        <div class="current-program-box">
                            <div class="program-code-highlight">{{ $student->program_code }}</div>
                            <div class="program-name-text">{{ $student->program_name }}</div>
                        </div>
                    </div>

                    <!-- Requested Program -->
                    <div class="mb-4">
                        <label for="requested_program_code" class="form-label fw-bold">
                            New Program <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('requested_program_code') is-invalid @enderror"
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
                        <label for="reason" class="form-label fw-bold">
                            Reason for Change <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('reason') is-invalid @enderror"
                                  id="reason" name="reason" rows="4"
                                  placeholder="Please provide a detailed reason for requesting this program change (minimum 50 characters)..."
                                  required minlength="50" maxlength="1000"></textarea>
                        <div class="form-text">
                            <span id="charCount">0</span> / 1000 characters (minimum 50 required)
                        </div>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Offer Letter (Required) -->
                    <div class="mb-3">
                        <label for="supporting_document" class="form-label fw-bold">
                            Degree Offer Letter <span class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control @error('supporting_document') is-invalid @enderror"
                               id="supporting_document" name="supporting_document"
                               accept=".pdf,.jpg,.jpeg,.png" required>
                        <div class="form-text">
                            <i class="fas fa-exclamation-circle me-1 text-danger"></i>
                            <strong>REQUIRED:</strong> Upload your official degree offer letter from UiTM. Academic Advisors will verify your claimed program from this document. PDF, JPG, or PNG (MAX. 5MB)
                        </div>
                        @error('supporting_document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 2px solid #e2e8f0; padding: 1.5rem;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #f093fb, #f5576c); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="fas fa-key me-2"></i>Change Password
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.changePassword') }}" method="POST" id="changePasswordForm">
                @csrf
                <div class="modal-body" style="padding: 2rem;">
                    <!-- Security Notice -->
                    <div class="alert alert-info" style="border-radius: 12px; border-left: 4px solid #4299e1;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-shield-alt" style="font-size: 1.25rem; color: #4299e1; margin-right: 0.75rem; margin-top: 0.15rem;"></i>
                            <div>
                                <p class="mb-0" style="color: #1e3a8a; font-size: 0.9rem;">
                                    For your security, you'll need to enter your current password to make changes. Choose a strong password with at least 8 characters.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Current Password -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-bold">
                            Current Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                   id="current_password" name="current_password" required
                                   placeholder="Enter your current password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye" id="current_password_icon"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-bold">
                            New Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                   id="new_password" name="new_password" required
                                   placeholder="Enter your new password" minlength="8">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye" id="new_password_icon"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            <span id="passwordLength">0</span> / 8 characters minimum
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="d-flex align-items-center">
                                <small class="me-2">Strength:</small>
                                <div class="progress flex-grow-1" style="height: 8px;">
                                    <div class="progress-bar" id="passwordStrengthBar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small class="ms-2" id="passwordStrengthText"></small>
                            </div>
                        </div>
                        @error('new_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label fw-bold">
                            Confirm New Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control"
                                   id="new_password_confirmation" name="new_password_confirmation" required
                                   placeholder="Re-enter your new password" minlength="8">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                            </button>
                        </div>
                        <div class="form-text" id="passwordMatchText"></div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 2px solid #e2e8f0; padding: 1.5rem;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Profile Container */
.profile-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Header */
.profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2.5rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.15);
}

.profile-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.profile-subtitle {
    font-size: 1.15rem;
    opacity: 0.9;
}

.profile-illustration {
    font-size: 5rem;
    opacity: 0.2;
    text-align: center;
}

/* Profile Cards */
.profile-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.profile-card-header {
    display: flex;
    align-items: center;
    padding: 1.75rem 2rem;
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    border-bottom: 2px solid #e2e8f0;
}

.icon-badge {
    width: 55px;
    height: 55px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    color: white;
    margin-right: 1.25rem;
    flex-shrink: 0;
}

.icon-badge.bg-primary { background: linear-gradient(135deg, #667eea, #764ba2); }
.icon-badge.bg-success { background: linear-gradient(135deg, #48bb78, #38a169); }
.icon-badge.bg-info { background: linear-gradient(135deg, #38b2ac, #319795); }
.icon-badge.bg-warning { background: linear-gradient(135deg, #ed8936, #dd6b20); }

.profile-card-header h3 {
    color: #2d3748;
    font-weight: 700;
    font-size: 1.4rem;
    margin-bottom: 0.25rem;
}

.profile-card-header p {
    color: #718096;
    font-size: 0.9rem;
}

.profile-card-body {
    padding: 2rem;
}

/* Photo Upload */
.photo-upload-section {
    position: relative;
}

.profile-photo-large {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #e2e8f0;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

/* Form Styling */
.form-label {
    color: #2d3748;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e2e8f0;
    padding: 0.65rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control:disabled, .form-control[readonly] {
    background-color: #f8fafc;
    border-color: #cbd5e0;
    color: #718096;
}

/* Lock Icon */
.lock-icon {
    margin-left: 0.5rem;
    font-size: 0.85rem;
    color: #a0aec0;
    cursor: help;
}

/* Program Display */
.program-display-box {
    background: linear-gradient(135deg, #f0f4ff, #e8eeff);
    border: 2px solid #667eea;
    border-radius: 12px;
    padding: 1.5rem;
}

.program-code {
    font-size: 1.75rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.program-name {
    font-size: 1.1rem;
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 1rem;
}

.program-help-text {
    color: #4a5568;
    font-size: 0.9rem;
    margin: 0;
    padding-top: 1rem;
    border-top: 1px solid #cbd5e0;
}

/* Security Info Box */
.security-info-box {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border: 2px solid #f59e0b;
    border-radius: 12px;
    padding: 1.25rem;
}

.security-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #ed8936, #dd6b20);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.security-info-box h6 {
    color: #92400e;
    font-weight: 700;
    font-size: 1.05rem;
}

/* Buttons */
.btn {
    border-radius: 10px;
    padding: 0.65rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

/* Modal Styling */
.current-program-box {
    background: linear-gradient(135deg, #fee, #fdd);
    border: 2px solid #f56565;
    border-radius: 12px;
    padding: 1.25rem;
    text-align: center;
}

.program-code-highlight {
    font-size: 1.5rem;
    font-weight: 700;
    color: #c53030;
    margin-bottom: 0.5rem;
}

.program-name-text {
    color: #742a2a;
    font-size: 1rem;
}

/* Character Counter */
#charCount {
    font-weight: 600;
    color: #667eea;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-header {
        padding: 1.5rem;
        text-align: center;
    }

    .profile-title {
        font-size: 1.75rem;
    }

    .profile-illustration {
        font-size: 3rem;
        margin-top: 1rem;
    }

    .profile-card-body {
        padding: 1.5rem;
    }

    .profile-photo-large {
        width: 150px;
        height: 150px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Photo preview
    const photoInput = document.getElementById('photo');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.profile-photo-large').src = e.target.result;
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
                charCount.style.color = '#f56565';
            } else {
                charCount.style.color = '#48bb78';
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
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Weak';
                strengthText.style.color = '#dc3545';
            } else if (strength <= 3) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Medium';
                strengthText.style.color = '#ffc107';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#28a745';
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
                    matchText.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>Passwords match';
                    matchText.style.color = '#28a745';
                    confirmPasswordInput.classList.remove('is-invalid');
                    confirmPasswordInput.classList.add('is-valid');
                } else {
                    matchText.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i>Passwords do not match';
                    matchText.style.color = '#dc3545';
                    confirmPasswordInput.classList.remove('is-valid');
                    confirmPasswordInput.classList.add('is-invalid');
                }
            } else {
                matchText.innerHTML = '';
                confirmPasswordInput.classList.remove('is-valid', 'is-invalid');
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
@endsection
