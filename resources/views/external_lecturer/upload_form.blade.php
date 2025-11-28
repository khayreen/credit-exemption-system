@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Upload Course Syllabus</h4>
                    <a href="{{ route('external_lecturer.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
                <div class="card-body">
                    <p class="text-muted">Submit course syllabi to help UiTM evaluate credit exemption applications. Please ensure your syllabus is in PDF format and contains detailed course information.</p>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-upload me-2"></i>Syllabus Upload Form
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Please correct the following errors:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('external_lecturer.store_general') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Course Information -->
                        <h6 class="text-primary mb-3">Course Information</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" 
                                           class="form-control @error('course_code') is-invalid @enderror" 
                                           id="course_code" 
                                           name="course_code" 
                                           value="{{ old('course_code', request('course')) }}" 
                                           placeholder="Course Code" 
                                           required>
                                    <label for="course_code">Course Code *</label>
                                    @error('course_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" 
                                           class="form-control @error('credit_hours') is-invalid @enderror" 
                                           id="credit_hours" 
                                           name="credit_hours" 
                                           value="{{ old('credit_hours') }}" 
                                           placeholder="Credit Hours" 
                                           min="1" 
                                           max="6" 
                                           required>
                                    <label for="credit_hours">Credit Hours *</label>
                                    @error('credit_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" 
                                   class="form-control @error('course_name') is-invalid @enderror" 
                                   id="course_name" 
                                   name="course_name" 
                                   value="{{ old('course_name') }}" 
                                   placeholder="Course Name" 
                                   required>
                            <label for="course_name">Course Name *</label>
                            @error('course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" 
                                   class="form-control @error('institution_name') is-invalid @enderror" 
                                   id="institution_name" 
                                   name="institution_name" 
                                   value="{{ old('institution_name', Auth::user()->externalLecturer->institution_name ?? '') }}" 
                                   placeholder="Institution Name" 
                                   required>
                            <label for="institution_name">Institution Name *</label>
                            @error('institution_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-4">
                            <textarea class="form-control @error('course_description') is-invalid @enderror" 
                                      id="course_description" 
                                      name="course_description" 
                                      placeholder="Course Description" 
                                      style="height: 100px">{{ old('course_description') }}</textarea>
                            <label for="course_description">Course Description (Optional)</label>
                            @error('course_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <h6 class="text-primary mb-3">Syllabus Document</h6>
                        
                        <div class="mb-4">
                            <label for="syllabus_file" class="form-label">Upload Syllabus PDF *</label>
                            <input type="file" 
                                   class="form-control @error('syllabus_file') is-invalid @enderror" 
                                   id="syllabus_file" 
                                   name="syllabus_file" 
                                   accept=".pdf" 
                                   required>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Please upload a PDF file (max 5MB). Ensure the syllabus contains course objectives, topics covered, and assessment methods.
                            </div>
                            @error('syllabus_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submission Guidelines -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-lightbulb me-2"></i>Submission Guidelines</h6>
                            <ul class="mb-0">
                                <li>Ensure the syllabus is comprehensive and includes learning outcomes</li>
                                <li>Include course prerequisites and assessment breakdown</li>
                                <li>Provide detailed weekly topic coverage if available</li>
                                <li>The document should be officially formatted and institution-branded</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('external_lecturer.dashboard') }}" class="btn btn-outline-secondary me-md-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-cloud-upload-alt me-2"></i>Upload Syllabus
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Available Courses (if any) -->
            @if(isset($availableCourses) && $availableCourses->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-list me-2"></i>Courses Currently Requesting Syllabi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Student</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableCourses as $course)
                                <tr>
                                    <td><strong>{{ $course->course_code }}</strong></td>
                                    <td>{{ $course->course_name }}</td>
                                    <td>{{ $course->exemptionApplication->student_name ?? 'N/A' }}</td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary fill-course-btn"
                                                data-course-code="{{ $course->course_code }}"
                                                data-course-name="{{ $course->course_name }}"
                                                data-credit-hours="{{ $course->credit_hour }}">
                                            <i class="fas fa-fill me-1"></i>Fill Form
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fill form from available courses
    document.querySelectorAll('.fill-course-btn').forEach(button => {
        button.addEventListener('click', function() {
            const courseCode = this.dataset.courseCode;
            const courseName = this.dataset.courseName;
            const creditHours = this.dataset.creditHours;
            
            document.getElementById('course_code').value = courseCode;
            document.getElementById('course_name').value = courseName;
            document.getElementById('credit_hours').value = creditHours;
            
            // Scroll to form
            document.querySelector('.card').scrollIntoView({ behavior: 'smooth' });
        });
    });
    
    // File upload preview
    document.getElementById('syllabus_file').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const fileInfo = document.querySelector('.form-text');
            fileInfo.innerHTML = `<i class="fas fa-file-pdf me-1 text-danger"></i>Selected: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        }
    });
});
</script>
@endpush
@endsection