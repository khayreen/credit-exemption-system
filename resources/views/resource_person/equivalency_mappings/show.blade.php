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
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
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

    .btn-back {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.25);
        color: white;
        transform: translateX(-3px);
    }

    /* Status Card */
    .status-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .status-card-header {
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status-card-header.pending {
        background: linear-gradient(135deg, var(--warning) 0%, #f97316 100%);
        color: white;
    }

    .status-card-header.added {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        color: white;
    }

    .status-card-header.rejected {
        background: linear-gradient(135deg, var(--danger) 0%, #ef4444 100%);
        color: white;
    }

    .status-card-header h5 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .program-badge {
        background: rgba(255,255,255,0.2);
        padding: 0.35rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-family: 'IBM Plex Mono', monospace;
    }

    .status-card-body {
        padding: 1.5rem;
    }

    .status-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .status-info-item p {
        margin: 0.35rem 0;
        color: var(--industrial-gray);
    }

    .status-info-item strong {
        color: var(--industrial-dark);
    }

    .alert-industrial {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        margin-top: 1rem;
    }

    .alert-industrial.rejection {
        background: rgba(220, 38, 38, 0.1);
        border-left: 4px solid var(--danger);
        color: var(--danger);
    }

    .alert-industrial.success {
        background: rgba(5, 150, 105, 0.1);
        border-left: 4px solid var(--success);
        color: var(--success);
    }

    /* Details Card */
    .details-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .details-card-header {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .details-card-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .details-card-body {
        padding: 1.5rem;
    }

    /* Course Card */
    .course-card {
        background: var(--industrial-light);
        border-radius: 12px;
        padding: 1.5rem;
        height: 100%;
    }

    .course-card h6 {
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .course-card h6.primary { color: var(--uitm-blue); }
    .course-card h6.info { color: var(--info); }

    .course-table {
        width: 100%;
    }

    .course-table th {
        width: 120px;
        font-weight: 500;
        color: var(--industrial-gray);
        padding: 0.5rem 0;
        vertical-align: top;
    }

    .course-table td {
        padding: 0.5rem 0;
        color: var(--industrial-dark);
    }

    .course-table td strong {
        font-family: 'IBM Plex Mono', monospace;
    }

    /* Arrow Separator */
    .arrow-separator {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .arrow-separator i {
        font-size: 2.5rem;
        color: #cbd5e1;
    }

    .match-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-family: 'IBM Plex Mono', monospace;
    }

    .match-badge.success {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .match-badge.warning {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
    }

    .notes-section {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .notes-section h6 {
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notes-section p {
        color: var(--industrial-gray);
        margin: 0;
    }

    /* Student Request Card */
    .student-request-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .student-request-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 1rem 1.25rem;
    }

    .student-request-header h6 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .student-request-body {
        padding: 1.25rem;
    }

    .student-request-body p {
        margin: 0.35rem 0;
        color: var(--industrial-gray);
    }

    .student-request-body strong {
        color: var(--industrial-dark);
    }

    .request-status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .request-status-badge.approved {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .request-status-badge.rejected {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger);
    }

    .request-status-badge.pending {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
    }

    /* Actions Card */
    .actions-card {
        background: white;
        border-radius: 16px;
        border: 2px solid var(--warning);
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .actions-card-header {
        background: var(--warning);
        color: white;
        padding: 1rem 1.25rem;
    }

    .actions-card-header h6 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .actions-card-body {
        padding: 1.25rem;
    }

    .actions-card-body p {
        color: var(--industrial-gray);
        margin-bottom: 1rem;
    }

    .btn-action-group {
        display: flex;
        gap: 0.75rem;
    }

    .btn-edit {
        background: var(--warning);
        color: white;
        padding: 0.65rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-edit:hover {
        background: #d97706;
        color: white;
        transform: translateY(-2px);
    }

    .btn-delete {
        background: var(--danger);
        color: white;
        padding: 0.65rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-delete:hover {
        background: #b91c1c;
        color: white;
        transform: translateY(-2px);
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .modal-header.warning {
        background: var(--warning);
        color: white;
    }

    .modal-header.danger {
        background: var(--danger);
        color: white;
    }

    .modal-header .btn-close-white {
        filter: brightness(0) invert(1);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .section-title {
        color: var(--industrial-gray);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .section-title.primary { color: var(--uitm-blue); }
    .section-title.info { color: var(--info); }

    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .btn-modal-cancel {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        border: none;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-modal-cancel:hover {
        background: #e2e8f0;
        color: var(--industrial-dark);
    }

    .btn-modal-update {
        background: var(--warning);
        color: white;
        border: none;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-modal-update:hover {
        background: #d97706;
        color: white;
    }

    .btn-modal-delete {
        background: var(--danger);
        color: white;
        border: none;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-modal-delete:hover {
        background: #b91c1c;
        color: white;
    }

    /* Toast */
    .toast-container {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 1050;
    }

    .toast-industrial {
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        overflow: hidden;
    }

    .toast-industrial .toast-header.success {
        background: var(--success);
        color: white;
    }

    .toast-industrial .toast-body {
        padding: 1rem;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }

        .status-info-grid {
            grid-template-columns: 1fr;
        }

        .arrow-separator {
            margin: 1.5rem 0;
        }

        .arrow-separator i {
            transform: rotate(90deg);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-paper-plane me-2"></i>Forwarded Mapping Details</h2>
            <p><i class="fas fa-exchange-alt me-2"></i>View your forwarded course mapping</p>
        </div>
        <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Status Card -->
    <div class="status-card">
        <div class="status-card-header {{ $mapping->status }}">
            <h5>
                <i class="fas fa-{{ $mapping->status === 'pending' ? 'clock' : ($mapping->status === 'added' ? 'check-circle' : 'times-circle') }}"></i>
                {{ $mapping->status_label }}
            </h5>
            <span class="program-badge">{{ $mapping->program_code }}</span>
        </div>
        <div class="status-card-body">
            <div class="status-info-grid">
                <div class="status-info-item">
                    <p><strong>Status:</strong> {{ $mapping->status_label }}</p>
                    <p><strong>Forwarded:</strong> {{ $mapping->created_at->format('d M Y, h:i A') }}</p>
                    <p><strong>Program:</strong> {{ $mapping->program_code }}</p>
                </div>
                <div class="status-info-item">
                    @if($mapping->status === 'added')
                        <p><strong>Added By:</strong> {{ $mapping->coordinator->name ?? 'N/A' }}</p>
                        <p><strong>Added On:</strong> {{ $mapping->added_at ? $mapping->added_at->format('d M Y, h:i A') : 'N/A' }}</p>
                    @elseif($mapping->status === 'rejected')
                        <p><strong>Rejected By:</strong> {{ $mapping->coordinator->name ?? 'N/A' }}</p>
                        <p><strong>Rejected On:</strong> {{ $mapping->updated_at->format('d M Y, h:i A') }}</p>
                    @else
                        <p class="text-muted"><i class="fas fa-clock me-1"></i>Awaiting Program Coordinator review</p>
                    @endif
                </div>
            </div>

            @if($mapping->status === 'rejected' && $mapping->rejection_reason)
                <div class="alert-industrial rejection">
                    <h6 class="mb-2"><i class="fas fa-exclamation-circle me-2"></i>Rejection Reason</h6>
                    <p class="mb-0">{{ $mapping->rejection_reason }}</p>
                </div>
            @endif

            @if($mapping->status === 'added')
                <div class="alert-industrial success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Success!</strong> This mapping has been added to the official equivalency list and is now available for students.
                </div>
            @endif
        </div>
    </div>

    <!-- Mapping Details -->
    <div class="details-card">
        <div class="details-card-header">
            <h5><i class="fas fa-exchange-alt"></i>Course Mapping Details</h5>
        </div>
        <div class="details-card-body">
            <div class="row">
                <!-- Diploma Course -->
                <div class="col-md-5">
                    <div class="course-card">
                        <h6 class="primary"><i class="fas fa-graduation-cap"></i>Diploma Course</h6>
                        <table class="course-table">
                            <tr>
                                <th>Course Code:</th>
                                <td><strong>{{ $mapping->diploma_course_code }}</strong></td>
                            </tr>
                            <tr>
                                <th>Course Name:</th>
                                <td>{{ $mapping->diploma_course_name }}</td>
                            </tr>
                            <tr>
                                <th>Credit Hours:</th>
                                <td>{{ $mapping->diploma_credit_hour }}</td>
                            </tr>
                            <tr>
                                <th>Institution:</th>
                                <td>{{ $mapping->diploma_institution }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Arrow -->
                <div class="col-md-2">
                    <div class="arrow-separator">
                        <i class="fas fa-arrow-right"></i>
                        <span class="match-badge {{ $mapping->match_percentage >= 80 ? 'success' : 'warning' }}">
                            {{ number_format($mapping->match_percentage, 0) }}% Match
                        </span>
                    </div>
                </div>

                <!-- Degree Course -->
                <div class="col-md-5">
                    <div class="course-card">
                        <h6 class="info"><i class="fas fa-university"></i>Degree Course</h6>
                        <table class="course-table">
                            <tr>
                                <th>Course Code:</th>
                                <td><strong>{{ $mapping->degree_course_code }}</strong></td>
                            </tr>
                            <tr>
                                <th>Course Name:</th>
                                <td>{{ $mapping->degree_course_name }}</td>
                            </tr>
                            <tr>
                                <th>Credit Hours:</th>
                                <td>{{ $mapping->degree_credit_hour }}</td>
                            </tr>
                            <tr>
                                <th>Institution:</th>
                                <td>Universiti Teknologi MARA (UiTM)</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            @if($mapping->notes)
                <div class="notes-section">
                    <h6><i class="fas fa-sticky-note"></i>Notes / Justification</h6>
                    <p>{{ $mapping->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Student Request Context (if applicable) -->
    @if($mapping->courseEquivalencyRequest)
        <div class="student-request-card">
            <div class="student-request-header">
                <h6><i class="fas fa-user-graduate"></i>Related Student Request</h6>
            </div>
            <div class="student-request-body">
                <p><strong>Student:</strong> {{ $mapping->courseEquivalencyRequest->student->user->name ?? 'N/A' }}</p>
                <p><strong>Request Date:</strong> {{ $mapping->courseEquivalencyRequest->created_at->format('d M Y') }}</p>
                <p>
                    <strong>Request Status:</strong>
                    @if($mapping->courseEquivalencyRequest->status === 'approved')
                        <span class="request-status-badge approved">Approved</span>
                    @elseif($mapping->courseEquivalencyRequest->status === 'rejected')
                        <span class="request-status-badge rejected">Rejected</span>
                    @else
                        <span class="request-status-badge pending">{{ ucfirst($mapping->courseEquivalencyRequest->status) }}</span>
                    @endif
                </p>
            </div>
        </div>
    @endif

    <!-- Actions -->
    @if($mapping->status === 'pending')
        <div class="actions-card">
            <div class="actions-card-header">
                <h6><i class="fas fa-edit"></i>Actions</h6>
            </div>
            <div class="actions-card-body">
                <p>This mapping is still pending review. You can edit or delete it before the Program Coordinator processes it.</p>
                <div class="btn-action-group">
                    <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="fas fa-edit"></i>Edit Mapping
                    </button>
                    <button type="button" class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash"></i>Delete Mapping
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Edit Modal -->
@if($mapping->status === 'pending')
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('resource_person.equivalency_mappings.update', $mapping) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header warning">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Course Mapping</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="section-title primary">Diploma Course</h6>
                            <div class="mb-3">
                                <label class="form-label">Course Code</label>
                                <input type="text" name="diploma_course_code" class="form-control" value="{{ $mapping->diploma_course_code }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Name</label>
                                <input type="text" name="diploma_course_name" class="form-control" value="{{ $mapping->diploma_course_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Credit Hours</label>
                                <input type="number" name="diploma_credit_hour" class="form-control" value="{{ $mapping->diploma_credit_hour }}" min="1" max="10" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Institution</label>
                                <input type="text" name="diploma_institution" class="form-control" value="{{ $mapping->diploma_institution }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="section-title info">Degree Course</h6>
                            <div class="mb-3">
                                <label class="form-label">Course Code</label>
                                <input type="text" name="degree_course_code" class="form-control" value="{{ $mapping->degree_course_code }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Name</label>
                                <input type="text" name="degree_course_name" class="form-control" value="{{ $mapping->degree_course_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Credit Hours</label>
                                <input type="number" name="degree_credit_hour" class="form-control" value="{{ $mapping->degree_credit_hour }}" min="1" max="10" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Match Percentage</label>
                        <input type="number" name="match_percentage" class="form-control" value="{{ $mapping->match_percentage }}" min="0" max="100" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ $mapping->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-update">
                        <i class="fas fa-save"></i>Update Mapping
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header danger">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Delete Mapping</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this course mapping?</p>
                <div class="alert-industrial rejection">
                    <strong>Warning:</strong> This action cannot be undone. The mapping will be permanently removed.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('resource_person.equivalency_mappings.destroy', $mapping) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modal-delete">
                        <i class="fas fa-trash"></i>Delete Mapping
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@if(session('success'))
    <div class="toast-container">
        <div class="toast toast-industrial show" role="alert">
            <div class="toast-header success">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif
@endsection
