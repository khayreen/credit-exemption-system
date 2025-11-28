@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Dashboard Header -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('External Lecturer Dashboard') }}</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-upload me-2"></i>Upload Syllabus
                    </button>
                </div>
                <div class="card-body">
                    <p class="mb-3">Welcome, <strong>{{ Auth::user()->name }}</strong>.</p>
                    <p class="text-muted">Submit course syllabi to help UiTM evaluate credit exemption applications.</p>
                    
                    <!-- Stats Cards -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-warning">{{ $stats['pending_requests'] }}</h5>
                                    <p class="card-text">Pending Requests</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-success">{{ $stats['completed_submissions'] }}</h5>
                                    <p class="card-text">Completed Submissions</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Syllabus Requests -->
            @if($pendingRequests->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock text-warning me-2"></i>Pending Syllabus Requests
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Student</th>
                                    <th>Requested Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingRequests as $request)
                                <tr>
                                    <td><strong>{{ $request->course_code }}</strong></td>
                                    <td>{{ $request->course_name }}</td>
                                    <td>{{ $request->exemptionApplication->student_name ?? 'N/A' }}</td>
                                    <td>{{ $request->updated_at->format('M d, Y') }}</td>
                                    <td>
                                            <button type="button" 
                                                class="btn btn-sm btn-outline-primary fill-course-btn"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#uploadModal"
                                                data-course-code="{{ $request->course_code }}"
                                                data-course-name="{{ $request->course_name }}"
                                                data-credit-hours="{{ $request->credit_hour }}">
                                            Submit Syllabus
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

            <!-- Recent Submissions -->
            @if($completedSubmissions->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle text-success me-2"></i>Recent Submissions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Student</th>
                                    <th>Submitted Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($completedSubmissions as $submission)
                                <tr>
                                    <td><strong>{{ $submission->course_code }}</strong></td>
                                    <td>{{ $submission->course_name }}</td>
                                    <td>{{ $submission->exemptionApplication->student_name ?? 'General Submission' }}</td>
                                    <td>{{ $submission->updated_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $submission->status }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- No Data Message -->
            @if($pendingRequests->count() == 0 && $completedSubmissions->count() == 0)
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No syllabus requests yet</h5>
                    <p class="text-muted">You can proactively upload course syllabi using the button above.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-upload me-2"></i>Upload Syllabus
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Upload Syllabus Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">
                    <i class="fas fa-upload me-2"></i>Upload Course Syllabus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('external_lecturer.store_general') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
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

                    <!-- Course Information -->
                    <h6 class="text-primary mb-3">Course Information</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" 
                                       class="form-control @error('course_code') is-invalid @enderror" 
                                       id="modal_course_code" 
                                       name="course_code" 
                                       value="{{ old('course_code') }}" 
                                       placeholder="Course Code" 
                                       required>
                                <label for="modal_course_code">Course Code *</label>
                                @error('course_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" 
                                       class="form-control @error('credit_hours') is-invalid @enderror" 
                                       id="modal_credit_hours" 
                                       name="credit_hours" 
                                       value="{{ old('credit_hours') }}" 
                                       placeholder="Credit Hours" 
                                       min="1" 
                                       max="6" 
                                       required>
                                <label for="modal_credit_hours">Credit Hours *</label>
                                @error('credit_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" 
                               class="form-control @error('course_name') is-invalid @enderror" 
                               id="modal_course_name" 
                               name="course_name" 
                               value="{{ old('course_name') }}" 
                               placeholder="Course Name" 
                               required>
                        <label for="modal_course_name">Course Name *</label>
                        @error('course_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" 
                               class="form-control @error('institution_name') is-invalid @enderror" 
                               id="modal_institution_name" 
                               name="institution_name" 
                               value="{{ old('institution_name', Auth::user()->externalLecturer->institution_name ?? '') }}" 
                               placeholder="Institution Name" 
                               required>
                        <label for="modal_institution_name">Institution Name *</label>
                        @error('institution_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control @error('course_description') is-invalid @enderror" 
                                  id="modal_course_description" 
                                  name="course_description" 
                                  placeholder="Course Description" 
                                  style="height: 80px">{{ old('course_description') }}</textarea>
                        <label for="modal_course_description">Course Description (Optional)</label>
                        @error('course_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <h6 class="text-primary mb-3">Syllabus Document</h6>
                    
                    <div class="mb-3">
                        <label for="modal_syllabus_file" class="form-label">Upload Syllabus PDF *</label>
                        <input type="file" 
                               class="form-control @error('syllabus_file') is-invalid @enderror" 
                               id="modal_syllabus_file" 
                               name="syllabus_file" 
                               accept=".pdf" 
                               required>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Please upload a PDF file (max 5MB). Ensure the syllabus contains course objectives and assessment methods.
                        </div>
                        @error('syllabus_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submission Guidelines -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Submission Guidelines</h6>
                        <ul class="mb-0 small">
                            <li>Ensure the syllabus includes learning outcomes and course prerequisites</li>
                            <li>Include assessment breakdown and weekly topic coverage</li>
                            <li>Document should be officially formatted and institution-branded</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Upload Syllabus
                    </button>
                </div>
            </form>
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
            
            document.getElementById('modal_course_code').value = courseCode;
            document.getElementById('modal_course_name').value = courseName;
            document.getElementById('modal_credit_hours').value = creditHours;
        });
    });
    
    // File upload preview
    document.getElementById('modal_syllabus_file').addEventListener('change', function() {
        const file = this.files[0];
        const fileInfo = this.nextElementSibling;
        if (file) {
            fileInfo.innerHTML = `<i class="fas fa-file-pdf me-1 text-danger"></i>Selected: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        }
    });
    
    // Clear form when modal is hidden
    document.getElementById('uploadModal').addEventListener('hidden.bs.modal', function() {
        this.querySelector('form').reset();
        document.getElementById('modal_syllabus_file').nextElementSibling.innerHTML = '<i class="fas fa-info-circle me-1"></i>Please upload a PDF file (max 5MB). Ensure the syllabus contains course objectives and assessment methods.';
    });
});
</script>
@endpush
@endsection
