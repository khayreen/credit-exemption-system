@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('resource_person.syllabi.index') }}">Degree Syllabi</a></li>
                <li class="breadcrumb-item active">Edit {{ $syllabus->course_code }}</li>
            </ol>
        </nav>
        <h2 class="mb-1"><i class="fas fa-edit me-2 text-primary"></i>Edit Degree Course Syllabus</h2>
        <p class="text-muted mb-0">Update syllabus information for {{ $syllabus->course_code }} - {{ $syllabus->course_name }}</p>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('resource_person.syllabi.update', $syllabus) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Course Information -->
                        <h5 class="mb-3"><i class="fas fa-book me-2 text-primary"></i>Course Information</h5>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="course_code" class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('course_code') is-invalid @enderror" id="course_code" name="course_code" value="{{ old('course_code', $syllabus->course_code) }}" placeholder="e.g., CS232" required>
                                @error('course_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('course_name') is-invalid @enderror" id="course_name" name="course_name" value="{{ old('course_name', $syllabus->course_name) }}" placeholder="e.g., Data Structures and Algorithms" required>
                                @error('course_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label for="credit_hours" class="form-label">Credits <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('credit_hours') is-invalid @enderror" id="credit_hours" name="credit_hours" value="{{ old('credit_hours', $syllabus->credit_hours) }}" min="1" max="10" step="0.5" required>
                                @error('credit_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="description" class="form-label">Course Description <span class="text-muted fw-normal">(Optional)</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Brief description of the course content...">{{ old('description', $syllabus->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $syllabus->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active (visible for comparison)</label>
                                </div>
                                <small class="text-muted">Inactive syllabi won't appear in recommendations</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Syllabus File -->
                        <h5 class="mb-3"><i class="fas fa-file-pdf me-2 text-danger"></i>Syllabus Document</h5>

                        <!-- Current File -->
                        <div class="mb-3">
                            <label class="form-label">Current Syllabus</label>
                            <div class="alert alert-secondary d-flex align-items-center">
                                <i class="fas fa-file-pdf fa-2x me-3 text-danger"></i>
                                <div class="flex-grow-1">
                                    <strong>{{ $syllabus->syllabus_file_original_name ?? 'syllabus.pdf' }}</strong>
                                    <br><small class="text-muted">Uploaded on {{ $syllabus->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                                <a href="{{ route('resource_person.syllabi.view_pdf', $syllabus) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="syllabus_file" class="form-label">Replace Syllabus (Optional)</label>
                            <input type="file" class="form-control @error('syllabus_file') is-invalid @enderror" id="syllabus_file" name="syllabus_file" accept=".pdf">
                            @error('syllabus_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Leave empty to keep the current file. PDF only, maximum 10MB.
                            </small>
                        </div>

                        <!-- New File Preview Area -->
                        <div id="file-preview" class="mb-4 d-none">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-file-pdf fa-2x me-3 text-danger"></i>
                                <div>
                                    <strong>New file: </strong><span id="file-name"></span>
                                    <br><small id="file-size" class="text-muted"></small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('resource_person.syllabi.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2 text-info"></i>Syllabus Details</h5>
                    <hr>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">ID:</td>
                            <td><code>{{ Str::limit($syllabus->id, 8) }}...</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Created:</td>
                            <td>{{ $syllabus->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Updated:</td>
                            <td>{{ $syllabus->updated_at->format('d M Y') }}</td>
                        </tr>
                        @if($syllabus->uploader)
                        <tr>
                            <td class="text-muted">Uploaded by:</td>
                            <td>{{ $syllabus->uploader->name }}</td>
                        </tr>
                        @endif
                        @if($syllabus->digital_signature)
                        <tr>
                            <td class="text-muted">File Hash:</td>
                            <td><code title="{{ $syllabus->digital_signature }}">{{ Str::limit($syllabus->digital_signature, 12) }}...</code></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-danger mt-4">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Permanently delete this syllabus. This action cannot be undone.</p>
                    <form action="{{ route('resource_person.syllabi.destroy', $syllabus) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this syllabus? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Delete Syllabus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('syllabus_file').addEventListener('change', function(e) {
    const preview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');

    if (this.files && this.files[0]) {
        const file = this.files[0];
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        preview.classList.remove('d-none');
    } else {
        preview.classList.add('d-none');
    }
});

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Auto-uppercase course code
document.getElementById('course_code').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>
@endpush
@endsection
