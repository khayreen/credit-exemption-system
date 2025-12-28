@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2"><i class="fas fa-file-signature me-2"></i>Course Equivalency Request</h2>
                    <p class="text-muted">Request equivalency review for a diploma course</p>
                </div>
                <a href="{{ route('student.equivalency.request.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-list me-2"></i>My Requests
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Please correct the following errors:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Information Alert -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>About Course Equivalency Requests</h5>
                <p class="mb-0">Use this form to request equivalency review for a diploma course that you believe is similar to a degree course in your current program.</p>
            </div>
        </div>
    </div>

    <!-- Request Form -->
    <form id="equivalencyRequestForm" action="{{ route('student.equivalency.request.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <!-- Diploma Course Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Diploma Course Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Diploma Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_course_code" class="form-control @error('diploma_course_code') is-invalid @enderror" value="{{ old('diploma_course_code') }}" placeholder="e.g., CSC159" maxlength="20" required>
                                @error('diploma_course_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Diploma Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_course_name" class="form-control @error('diploma_course_name') is-invalid @enderror" value="{{ old('diploma_course_name') }}" placeholder="e.g., COMPUTER ORGANIZATION" maxlength="255" required>
                                @error('diploma_course_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Institution/University <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_institution" class="form-control @error('diploma_institution') is-invalid @enderror" value="{{ old('diploma_institution') }}" placeholder="e.g., UiTM Shah Alam" maxlength="255" required>
                                @error('diploma_institution')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Diploma Program <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_program" class="form-control @error('diploma_program') is-invalid @enderror" value="{{ old('diploma_program') }}" placeholder="e.g., Diploma in Computer Science" maxlength="255" required>
                                @error('diploma_program')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" name="diploma_credit_hours" class="form-control @error('diploma_credit_hours') is-invalid @enderror" value="{{ old('diploma_credit_hours', '3') }}" min="0.5" max="10" step="0.5" required>
                                @error('diploma_credit_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suggested Degree Course -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Suggested Degree Course</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Specify the degree course in your current program that you believe matches your diploma course. This will help the resource person review your request more efficiently.</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Degree Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="suggested_degree_course_code" class="form-control @error('suggested_degree_course_code') is-invalid @enderror" value="{{ old('suggested_degree_course_code') }}" placeholder="e.g., CS132" maxlength="20" required>
                                @error('suggested_degree_course_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Degree Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="suggested_degree_course_name" class="form-control @error('suggested_degree_course_name') is-invalid @enderror" value="{{ old('suggested_degree_course_name') }}" placeholder="e.g., COMPUTER ARCHITECTURE" maxlength="255" required>
                                @error('suggested_degree_course_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- External Lecturer Contact Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>External Lecturer Contact <span class="text-danger">*</span></h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-3">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Important: Official Syllabus Verification</h6>
                            <p class="mb-2">To ensure authenticity and prevent document manipulation, we will request the official course syllabus directly from your lecturer. Provide your lecturer's official institutional email address</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lecturer's Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="external_lecturer_name" class="form-control @error('external_lecturer_name') is-invalid @enderror"
                                       value="{{ old('external_lecturer_name') }}"
                                       placeholder="e.g., Dr. Ahmad bin Abdullah"
                                       maxlength="255" required>
                                <small class="text-muted">Full name of the lecturer who taught this course</small>
                                @error('external_lecturer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lecturer's Official Email <span class="text-danger">*</span></label>
                                <input type="email" name="external_lecturer_email" class="form-control @error('external_lecturer_email') is-invalid @enderror"
                                       value="{{ old('external_lecturer_email') }}"
                                       placeholder="e.g., ahmad.abdullah@university.edu.my"
                                       maxlength="255" required>
                                <small class="text-muted">Must be official institutional email address</small>
                                @error('external_lecturer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-between mb-4">
                    <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <span id="submitBtnText">
                            <i class="fas fa-paper-plane me-2"></i>Submit Request
                        </span>
                        <span id="submitBtnLoading" class="d-none">
                            <i class="fas fa-spinner fa-spin me-2"></i>Submitting...
                        </span>
                    </button>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <!-- Current Program Info -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Your Current Program</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Program:</strong><br>{{ $student->program_name ?? 'Not Set' }}</p>
                        <p class="mb-2"><strong>Campus:</strong><br>{{ $student->campus ?? 'Not Set' }}</p>
                        <p class="mb-0"><strong>Student ID:</strong><br>{{ $student->matric_no ?? Auth::user()->name }}</p>

                        <input type="hidden" name="current_program_code" value="{{ $student->program_code ?? 'CS251' }}">
                        <input type="hidden" name="current_program_name" value="{{ $student->program_name ?? 'Not Set' }}">
                    </div>
                </div>

                <!-- Guidelines -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Guidelines</h6>
                    </div>
                    <div class="card-body">
                        <ul class="small mb-0 ps-3">
                            <li class="mb-2">Provide accurate course information</li>
                            <li class="mb-2">Write a clear, detailed justification</li>
                            <li class="mb-2">Resource person will review your request</li>
                            <li class="mb-0">You will be notified of the decision</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('equivalencyRequestForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitBtnLoading = document.getElementById('submitBtnLoading');

    form.addEventListener('submit', function(e) {
        // Check if form is valid
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            form.classList.add('was-validated');

            // Scroll to first error
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

        // Form will submit naturally
        console.log('Form submitting...');
    });
});
</script>
@endpush

@endsection
