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
        --danger: #dc2626;
        --warning: #ea580c;
        --info: #0d9488;
    }

    body {
        font-family: 'IBM Plex Sans', sans-serif;
        background-color: var(--industrial-light);
    }

    .page-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        border-radius: 0 0 24px 24px;
        padding: 2rem 2.5rem;
        margin: -1.5rem -1.5rem 2rem -1.5rem;
        color: white;
    }

    .page-header h2 {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.35rem;
    }

    .page-header p {
        color: rgba(255,255,255,0.8);
        margin: 0;
    }

    .breadcrumb-industrial {
        background: transparent;
        padding: 0;
        margin-bottom: 1rem;
    }

    .breadcrumb-industrial a {
        color: rgba(255,255,255,0.7);
        text-decoration: none;
    }

    .breadcrumb-industrial a:hover {
        color: white;
    }

    .breadcrumb-industrial .active {
        color: rgba(255,255,255,0.5);
    }

    .breadcrumb-industrial .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255,255,255,0.5);
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 2rem;
    }

    .section-title {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.1rem;
    }

    .section-title i.primary { color: var(--uitm-blue); }
    .section-title i.danger { color: var(--danger); }

    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-text {
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

    .form-check-input:checked {
        background-color: var(--uitm-blue);
        border-color: var(--uitm-blue);
    }

    /* Current File Display */
    .current-file {
        background: var(--industrial-light);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .current-file-icon {
        font-size: 2.5rem;
        color: var(--danger);
    }

    .current-file-info {
        flex-grow: 1;
    }

    .current-file-info strong {
        color: var(--industrial-dark);
    }

    .current-file-info small {
        color: var(--industrial-gray);
    }

    .btn-view-pdf {
        border: 2px solid var(--danger);
        color: var(--danger);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-view-pdf:hover {
        background: var(--danger);
        color: white;
    }

    /* New File Preview */
    .file-preview {
        background: rgba(13, 148, 136, 0.1);
        border: 1px solid rgba(13, 148, 136, 0.2);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .file-preview-icon {
        font-size: 2.5rem;
        color: var(--danger);
    }

    .file-preview-info strong {
        color: var(--industrial-dark);
    }

    .file-preview-info small {
        color: var(--industrial-gray);
    }

    /* Buttons */
    .btn-cancel {
        border: 2px solid #e2e8f0;
        color: var(--industrial-gray);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-cancel:hover {
        background: var(--industrial-light);
        color: var(--industrial-dark);
    }

    .btn-save {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 58, 138, 0.3);
        color: white;
    }

    /* Info Sidebar */
    .info-card {
        background: var(--industrial-light);
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
    }

    .info-card h5 {
        color: var(--industrial-dark);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .info-card h5 i {
        color: var(--info);
    }

    .info-table {
        width: 100%;
    }

    .info-table td {
        padding: 0.5rem 0;
    }

    .info-table td:first-child {
        color: var(--industrial-gray);
        width: 100px;
    }

    .info-table code {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
    }

    /* Danger Zone */
    .danger-card {
        background: white;
        border-radius: 16px;
        border: 2px solid var(--danger);
        overflow: hidden;
        margin-top: 1.5rem;
    }

    .danger-card-header {
        background: var(--danger);
        color: white;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
    }

    .danger-card-body {
        padding: 1.25rem;
    }

    .danger-card-body p {
        color: var(--industrial-gray);
        margin-bottom: 1rem;
    }

    .btn-delete {
        background: transparent;
        border: 2px solid var(--danger);
        color: var(--danger);
        padding: 0.65rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-delete:hover {
        background: var(--danger);
        color: white;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <nav aria-label="breadcrumb" class="breadcrumb-industrial">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('resource_person.syllabi.index') }}">Degree Syllabi</a></li>
                <li class="breadcrumb-item active">Edit {{ $syllabus->course_code }}</li>
            </ol>
        </nav>
        <h2><i class="fas fa-edit me-2"></i>Edit Degree Course Syllabus</h2>
        <p><i class="fas fa-book-open me-2"></i>Update syllabus information for {{ $syllabus->course_code }} - {{ $syllabus->course_name }}</p>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="form-card">
                <form action="{{ route('resource_person.syllabi.update', $syllabus) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Course Information -->
                    <h5 class="section-title"><i class="fas fa-book primary"></i>Course Information</h5>

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
                            <small class="form-text">Inactive syllabi won't appear in recommendations</small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Syllabus File -->
                    <h5 class="section-title"><i class="fas fa-file-pdf danger"></i>Syllabus Document</h5>

                    <!-- Current File -->
                    <div class="mb-3">
                        <label class="form-label">Current Syllabus</label>
                        <div class="current-file">
                            <i class="fas fa-file-pdf current-file-icon"></i>
                            <div class="current-file-info">
                                <strong>{{ $syllabus->syllabus_file_original_name ?? 'syllabus.pdf' }}</strong>
                                <br><small>Uploaded on {{ $syllabus->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                            <a href="{{ route('resource_person.syllabi.view_pdf', $syllabus) }}" class="btn-view-pdf" target="_blank">
                                <i class="fas fa-eye"></i>View
                            </a>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="syllabus_file" class="form-label">Replace Syllabus (Optional)</label>
                        <input type="file" class="form-control @error('syllabus_file') is-invalid @enderror" id="syllabus_file" name="syllabus_file" accept=".pdf">
                        @error('syllabus_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Leave empty to keep the current file. PDF only, maximum 10MB.
                        </small>
                    </div>

                    <!-- New File Preview Area -->
                    <div id="file-preview" class="mb-4 d-none">
                        <div class="file-preview">
                            <i class="fas fa-file-pdf file-preview-icon"></i>
                            <div class="file-preview-info">
                                <strong>New file: </strong><span id="file-name"></span>
                                <br><small id="file-size"></small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('resource_person.syllabi.index') }}" class="btn-cancel">
                            <i class="fas fa-arrow-left"></i>Cancel
                        </a>
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="info-card">
                <h5><i class="fas fa-info-circle"></i>Syllabus Details</h5>
                <hr>
                <table class="info-table">
                    <tr>
                        <td>ID:</td>
                        <td><code>{{ Str::limit($syllabus->id, 8) }}...</code></td>
                    </tr>
                    <tr>
                        <td>Created:</td>
                        <td>{{ $syllabus->created_at->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Updated:</td>
                        <td>{{ $syllabus->updated_at->format('d M Y') }}</td>
                    </tr>
                    @if($syllabus->uploader)
                    <tr>
                        <td>Uploaded by:</td>
                        <td>{{ $syllabus->uploader->name }}</td>
                    </tr>
                    @endif
                    @if($syllabus->digital_signature)
                    <tr>
                        <td>File Hash:</td>
                        <td><code title="{{ $syllabus->digital_signature }}">{{ Str::limit($syllabus->digital_signature, 12) }}...</code></td>
                    </tr>
                    @endif
                </table>
            </div>

            <!-- Danger Zone -->
            <div class="danger-card">
                <div class="danger-card-header">
                    <i class="fas fa-exclamation-triangle"></i>Danger Zone
                </div>
                <div class="danger-card-body">
                    <p>Permanently delete this syllabus. This action cannot be undone.</p>
                    <form action="{{ route('resource_person.syllabi.destroy', $syllabus) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this syllabus? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">
                            <i class="fas fa-trash"></i>Delete Syllabus
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
