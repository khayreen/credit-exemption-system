@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
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

    body { font-family: 'IBM Plex Sans', sans-serif; }
    .font-mono { font-family: 'IBM Plex Mono', monospace; }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-header h2 i { color: var(--uitm-amber); }

    .page-header .subtitle {
        color: var(--industrial-gray);
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    /* Add Mapping Card */
    .add-mapping-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .add-mapping-header {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
        padding: 1rem 1.25rem;
        color: white;
    }

    .add-mapping-header h5 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .add-mapping-body {
        padding: 1.5rem;
    }

    /* Course Section */
    .course-section h6 {
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .course-section.diploma h6 { color: var(--uitm-blue); }
    .course-section.degree h6 { color: var(--info); }

    .arrow-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #cbd5e1;
    }

    /* Form Inputs */
    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.875rem;
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30,58,138,0.1);
    }

    /* Mappings Card */
    .mappings-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .mappings-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        padding: 1rem 1.25rem;
        color: white;
    }

    .mappings-header h5 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .mappings-table {
        margin: 0;
    }

    .mappings-table thead th {
        background: var(--industrial-light);
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.875rem 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .mappings-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .mappings-table tbody tr:hover {
        background: #fafbfc;
    }

    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-blue);
    }

    .course-name {
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

    .match-badge {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .match-badge.high { background: rgba(5,150,105,0.15); color: var(--success); }
    .match-badge.low { background: rgba(245,158,11,0.15); color: var(--warning); }

    /* Stats Footer */
    .stats-footer {
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: center;
        gap: 3rem;
        flex-wrap: wrap;
    }

    .stat-item span {
        font-weight: 500;
        color: var(--industrial-gray);
        font-size: 0.875rem;
    }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 24px;
        border-radius: 12px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: var(--industrial-gray);
        font-weight: 600;
    }

    .empty-state p {
        color: #94a3b8;
    }

    /* Buttons */
    .btn-industrial {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .btn-primary-industrial {
        background: var(--uitm-blue);
        color: white;
        border: none;
    }

    .btn-primary-industrial:hover {
        background: #1e40af;
        color: white;
    }

    .btn-success-industrial {
        background: var(--success);
        color: white;
        border: none;
    }

    .btn-success-industrial:hover {
        background: #047857;
        color: white;
        transform: translateY(-1px);
    }

    .btn-warning-industrial {
        background: var(--uitm-amber);
        color: white;
        border: none;
    }

    .btn-warning-industrial:hover {
        background: #d97706;
        color: white;
    }

    .btn-danger-industrial {
        background: var(--danger);
        color: white;
        border: none;
    }

    .btn-danger-industrial:hover {
        background: #b91c1c;
        color: white;
    }

    /* Modal Styling */
    .modal-industrial .modal-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        color: white;
        border: none;
        padding: 1.25rem 1.5rem;
    }

    .modal-industrial .modal-header.warning {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
    }

    .modal-industrial .modal-header.success {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
    }

    .modal-industrial .modal-title {
        font-weight: 600;
    }

    .modal-industrial .modal-body {
        padding: 1.5rem;
    }

    .modal-industrial .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }

    /* Section Title in Modal */
    .modal-section-title {
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .modal-section-title.diploma { color: var(--uitm-blue); }
    .modal-section-title.degree { color: var(--info); }

    /* Toast */
    .toast-industrial {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .toast-industrial.success .toast-header { background: var(--success); color: white; }
    .toast-industrial.error .toast-header { background: var(--danger); color: white; }

    /* Alert */
    .alert-warning-custom {
        background: rgba(245,158,11,0.08);
        border: 1px solid rgba(245,158,11,0.2);
        border-left: 4px solid var(--uitm-amber);
        border-radius: 8px;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
        }

        .arrow-divider {
            transform: rotate(90deg);
            margin: 1rem 0;
        }

        .stats-footer {
            flex-direction: column;
            gap: 0.75rem;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-edit"></i>Edit Equivalency List</h2>
            <p class="subtitle">
                <span class="font-mono fw-bold">{{ $list->program_code }}</span> |
                {{ $list->semester }} |
                {{ $list->category === 'external' ? $list->source_institution : 'UiTM CS110 (Internal)' }}
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('program_coordinator.equivalency_lists.show', $list) }}" class="btn btn-outline-secondary btn-industrial">
                <i class="fas fa-eye me-1"></i>View Details
            </a>
            <button type="button" class="btn btn-success-industrial btn-industrial" data-bs-toggle="modal" data-bs-target="#publishModal">
                <i class="fas fa-rocket me-1"></i>Publish List
            </button>
        </div>
    </div>

    <!-- Add New Mapping Card -->
    <div class="add-mapping-card">
        <div class="add-mapping-header">
            <h5><i class="fas fa-plus-circle"></i>Add New Course Mapping</h5>
        </div>
        <div class="add-mapping-body">
            <form action="{{ route('program_coordinator.equivalency_lists.addMapping', $list) }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Diploma Course Section -->
                    <div class="col-md-5">
                        <div class="course-section diploma">
                            <h6><i class="fas fa-graduation-cap"></i>Diploma Course Details</h6>

                            <div class="mb-3">
                                <label for="diploma_course_code" class="form-label">Course Code</label>
                                <input type="text"
                                       name="diploma_course_code"
                                       id="diploma_course_code"
                                       class="form-control @error('diploma_course_code') is-invalid @enderror"
                                       placeholder="e.g., DCS110"
                                       value="{{ old('diploma_course_code') }}"
                                       required>
                                @error('diploma_course_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="diploma_course_name" class="form-label">Course Name</label>
                                <input type="text"
                                       name="diploma_course_name"
                                       id="diploma_course_name"
                                       class="form-control @error('diploma_course_name') is-invalid @enderror"
                                       placeholder="e.g., Introduction to Computer Science"
                                       value="{{ old('diploma_course_name') }}"
                                       required>
                                @error('diploma_course_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="diploma_credit_hour" class="form-label">Credit Hours</label>
                                <input type="number"
                                       name="diploma_credit_hour"
                                       id="diploma_credit_hour"
                                       class="form-control font-mono @error('diploma_credit_hour') is-invalid @enderror"
                                       min="1"
                                       max="10"
                                       value="{{ old('diploma_credit_hour', 3) }}"
                                       required>
                                @error('diploma_credit_hour')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Arrow -->
                    <div class="col-md-1 arrow-divider">
                        <i class="fas fa-arrow-right"></i>
                    </div>

                    <!-- Degree Course Section -->
                    <div class="col-md-5">
                        <div class="course-section degree">
                            <h6><i class="fas fa-university"></i>Degree Course Details</h6>

                            <div class="mb-3">
                                <label for="degree_course_code" class="form-label">Course Code</label>
                                <input type="text"
                                       name="degree_course_code"
                                       id="degree_course_code"
                                       class="form-control @error('degree_course_code') is-invalid @enderror"
                                       placeholder="e.g., CS110"
                                       value="{{ old('degree_course_code') }}"
                                       required>
                                @error('degree_course_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="degree_course_name" class="form-label">Course Name</label>
                                <input type="text"
                                       name="degree_course_name"
                                       id="degree_course_name"
                                       class="form-control @error('degree_course_name') is-invalid @enderror"
                                       placeholder="e.g., Programming Fundamentals"
                                       value="{{ old('degree_course_name') }}"
                                       required>
                                @error('degree_course_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="degree_credit_hour" class="form-label">Credit Hours</label>
                                <input type="number"
                                       name="degree_credit_hour"
                                       id="degree_credit_hour"
                                       class="form-control font-mono @error('degree_credit_hour') is-invalid @enderror"
                                       min="1"
                                       max="10"
                                       value="{{ old('degree_credit_hour', 3) }}"
                                       required>
                                @error('degree_credit_hour')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Match Percentage -->
                    <div class="col-md-1 d-flex align-items-center">
                        <div class="w-100">
                            <label for="match_percentage" class="form-label small">Match %</label>
                            <input type="number"
                                   name="match_percentage"
                                   id="match_percentage"
                                   class="form-control font-mono @error('match_percentage') is-invalid @enderror"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                   value="{{ old('match_percentage', 85) }}"
                                   required>
                            @error('match_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success-industrial btn-industrial btn-lg">
                        <i class="fas fa-plus-circle me-2"></i>Add Course Mapping
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Existing Mappings -->
    <div class="mappings-card">
        <div class="mappings-header">
            <h5><i class="fas fa-list"></i>Existing Course Mappings ({{ $equivalencies->count() }})</h5>
        </div>
        @if($equivalencies->isEmpty())
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>No Course Mappings Yet</h5>
                <p>Use the form above to add your first course mapping.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mappings-table">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">#</th>
                            <th width="30%">Diploma Course</th>
                            <th width="30%">Degree Course</th>
                            <th class="text-center" width="8%">Match %</th>
                            <th class="text-center" width="10%">Credit Hours</th>
                            <th class="text-center" width="7%">Eligible</th>
                            <th class="text-center" width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equivalencies as $index => $eq)
                        <tr id="mapping-{{ $eq->id }}">
                            <td class="text-center text-muted font-mono">{{ $index + 1 }}</td>
                            <td>
                                <span class="course-code">{{ $eq->diploma_course_code }}</span>
                                <br>
                                <span class="course-name">{{ Str::limit($eq->diploma_course_name, 50) }}</span>
                            </td>
                            <td>
                                <span class="course-code">{{ $eq->degree_course_code }}</span>
                                <br>
                                <span class="course-name">{{ Str::limit($eq->degree_course_name, 50) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="match-badge {{ $eq->match_percentage >= 80 ? 'high' : 'low' }}">
                                    {{ number_format($eq->match_percentage, 0) }}%
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="font-mono text-muted">{{ $eq->diploma_credit_hour }} → {{ $eq->degree_credit_hour }}</span>
                            </td>
                            <td class="text-center">
                                @if($eq->is_eligible)
                                    <i class="fas fa-check-circle text-success fs-5"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger fs-5"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-sm btn-warning-industrial me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editMappingModal-{{ $eq->id }}"
                                        title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('program_coordinator.equivalency_lists.deleteMapping', [$list, $eq]) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this mapping?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger-industrial"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal for this mapping -->
                        <div class="modal fade modal-industrial" id="editMappingModal-{{ $eq->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('program_coordinator.equivalency_lists.updateMapping', [$list, $eq]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header warning">
                                            <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Course Mapping</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="modal-section-title diploma">Diploma Course</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Course Code</label>
                                                        <input type="text" name="diploma_course_code" class="form-control" value="{{ $eq->diploma_course_code }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Course Name</label>
                                                        <input type="text" name="diploma_course_name" class="form-control" value="{{ $eq->diploma_course_name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Credit Hours</label>
                                                        <input type="number" name="diploma_credit_hour" class="form-control font-mono" value="{{ $eq->diploma_credit_hour }}" min="1" max="10" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="modal-section-title degree">Degree Course</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Course Code</label>
                                                        <input type="text" name="degree_course_code" class="form-control" value="{{ $eq->degree_course_code }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Course Name</label>
                                                        <input type="text" name="degree_course_name" class="form-control" value="{{ $eq->degree_course_name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Credit Hours</label>
                                                        <input type="number" name="degree_credit_hour" class="form-control font-mono" value="{{ $eq->degree_credit_hour }}" min="1" max="10" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Match Percentage</label>
                                                <input type="number" name="match_percentage" class="form-control font-mono" value="{{ $eq->match_percentage }}" min="0" max="100" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-warning-industrial">
                                                <i class="fas fa-save me-2"></i>Update Mapping
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Statistics Footer -->
            <div class="stats-footer">
                <div class="stat-item">
                    <span>Total Mappings:</span>
                    <span class="stat-badge" style="background: var(--uitm-blue); color: white;">{{ $equivalencies->count() }}</span>
                </div>
                <div class="stat-item">
                    <span>Eligible (≥80%):</span>
                    <span class="stat-badge" style="background: var(--success); color: white;">{{ $equivalencies->where('is_eligible', true)->count() }}</span>
                </div>
                <div class="stat-item">
                    <span>Not Eligible (<80%):</span>
                    <span class="stat-badge" style="background: var(--uitm-amber); color: white;">{{ $equivalencies->where('is_eligible', false)->count() }}</span>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Publish Confirmation Modal -->
<div class="modal fade modal-industrial" id="publishModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header success">
                <h5 class="modal-title">
                    <i class="fas fa-rocket me-2"></i>Publish Equivalency List
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to publish this equivalency list?</p>
                <div class="alert alert-warning-custom">
                    <strong>Note:</strong> Once published:
                    <ul class="mb-0">
                        <li>The list will become visible to students</li>
                        <li>You cannot edit the course mappings</li>
                        <li>Any previous active list for this program/category will be deactivated</li>
                    </ul>
                </div>
                <p class="mb-0"><strong>Current mappings:</strong> <span class="font-mono">{{ $equivalencies->count() }}</span> course(s)</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('program_coordinator.equivalency_lists.publish', $list) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success-industrial">
                        <i class="fas fa-rocket me-2"></i>Publish Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div class="toast show toast-industrial success" role="alert">
        <div class="toast-header">
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

@if(session('error'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div class="toast show toast-industrial error" role="alert">
        <div class="toast-header">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
</div>
@endif
