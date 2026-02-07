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
        background: linear-gradient(135deg, var(--info) 0%, #0f766e 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        color: white;
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
        background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.1) 100%);
        clip-path: polygon(100% 0, 0% 100%, 100% 100%);
    }

    .page-header h2 {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        opacity: 0.9;
        margin: 0;
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.orange {
        background: rgba(245,158,11,0.15);
        color: var(--uitm-amber);
    }

    .stat-icon.green {
        background: rgba(5,150,105,0.15);
        color: var(--success);
    }

    .stat-icon.red {
        background: rgba(220,38,38,0.15);
        color: var(--danger);
    }

    .stat-icon.teal {
        background: rgba(13,148,136,0.15);
        color: var(--info);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--industrial-dark);
        line-height: 1;
        font-family: 'IBM Plex Mono', monospace;
    }

    .stat-label {
        color: var(--industrial-gray);
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .filter-card-header {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .filter-card-header h6 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
    }

    .filter-card-body {
        padding: 1.25rem 1.5rem;
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .main-card-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        padding: 1.25rem 1.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .main-card-header h5 {
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .count-badge {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Custom Table */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table thead th {
        background: var(--industrial-light);
        color: var(--industrial-dark);
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .custom-table tbody tr:hover {
        background: rgba(30,58,138,0.02);
    }

    /* Course Info */
    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .course-name {
        color: var(--industrial-gray);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .course-meta {
        color: var(--industrial-gray);
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    /* Badges */
    .badge-primary {
        background: var(--uitm-blue);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-success {
        background: var(--success);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-warning {
        background: var(--uitm-amber);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-danger {
        background: var(--danger);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .match-badge {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.9rem;
        padding: 0.4rem 0.75rem;
    }

    /* Buttons */
    .btn-industrial {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
        color: white;
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

    .btn-outline-info {
        background: transparent;
        color: var(--info);
        border: 1px solid var(--info);
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
    }

    .btn-outline-info:hover {
        background: var(--info);
        color: white;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30,58,138,0.1);
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--industrial-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2rem;
        color: var(--industrial-gray);
    }

    .empty-state h5 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--industrial-gray);
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    .modal-header-warning {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .modal-header-success {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .modal-header-danger {
        background: linear-gradient(135deg, var(--danger) 0%, #b91c1c 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    /* Status Alert in Modal */
    .status-alert {
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .status-alert.warning {
        background: rgba(245,158,11,0.1);
        border: 1px solid rgba(245,158,11,0.3);
    }

    .status-alert.success {
        background: rgba(5,150,105,0.1);
        border: 1px solid rgba(5,150,105,0.3);
    }

    .status-alert.danger {
        background: rgba(220,38,38,0.1);
        border: 1px solid rgba(220,38,38,0.3);
    }

    /* Course Mapping Display in Modal */
    .course-mapping-display {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .course-mapping-item {
        flex: 1;
    }

    .course-mapping-item h6 {
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .course-mapping-item h6.primary { color: var(--uitm-blue); }
    .course-mapping-item h6.info { color: var(--info); }

    .course-mapping-arrow {
        color: var(--industrial-gray);
        font-size: 2rem;
    }

    .info-table {
        width: 100%;
    }

    .info-table th {
        color: var(--industrial-gray);
        font-weight: 600;
        padding: 0.35rem 0;
        width: 120px;
    }

    .info-table td {
        padding: 0.35rem 0;
        color: var(--industrial-dark);
    }

    /* Info Alert */
    .info-alert {
        background: rgba(13,148,136,0.08);
        border: 1px solid rgba(13,148,136,0.2);
        border-left: 4px solid var(--info);
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
    }

    .info-alert h6 {
        font-weight: 700;
        color: var(--info);
        margin-bottom: 0.5rem;
    }

    .info-alert p {
        color: var(--industrial-gray);
        margin: 0;
    }

    /* Danger Alert in Modal */
    .danger-alert {
        background: rgba(220,38,38,0.08);
        border: 1px solid rgba(220,38,38,0.2);
        border-radius: 8px;
        padding: 1rem;
    }

    .danger-alert h6 {
        font-weight: 700;
        color: var(--danger);
        margin-bottom: 0.5rem;
    }

    .danger-alert p {
        color: var(--industrial-gray);
        margin: 0;
    }

    /* Success Alert in Modal */
    .success-alert {
        background: rgba(5,150,105,0.08);
        border: 1px solid rgba(5,150,105,0.2);
        border-radius: 8px;
        padding: 1rem;
    }

    .success-alert h6 {
        font-weight: 700;
        color: var(--success);
        margin-bottom: 0.5rem;
    }

    .success-alert p {
        color: var(--industrial-gray);
        margin: 0;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .course-mapping-display {
            flex-direction: column;
        }

        .course-mapping-arrow {
            transform: rotate(90deg);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-paper-plane me-2"></i>Pending Course Mappings Monitor</h2>
                <p><i class="fas fa-eye me-2"></i>Monitor course mappings forwarded by Resource Persons to Program Coordinators (Read-Only)</p>
            </div>
            <a href="{{ route('hea.equivalency_lists.index') }}" class="btn btn-industrial btn-back">
                <i class="fas fa-arrow-left"></i> Back to Lists
            </a>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">Pending Review</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['added'] }}</div>
                <div class="stat-label">Added to Lists</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-times-circle"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['rejected'] }}</div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Forwarded</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <div class="filter-card-header">
            <h6><i class="fas fa-filter me-2"></i>Filter Mappings</h6>
        </div>
        <div class="filter-card-body">
            <form method="GET" action="{{ route('hea.pending_mappings.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="added" {{ $statusFilter === 'added' ? 'selected' : '' }}>Added</option>
                            <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Program</label>
                        <select name="program" class="form-select">
                            <option value="all" {{ $programFilter === 'all' ? 'selected' : '' }}>All Programs</option>
                            @foreach($programs as $code => $name)
                                <option value="{{ $code }}" {{ $programFilter === $code ? 'selected' : '' }}>
                                    {{ $code }} - {{ Str::limit($name, 30) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Resource Person</label>
                        <select name="resource_person" class="form-select">
                            <option value="all" {{ $resourcePersonFilter === 'all' ? 'selected' : '' }}>All Resource Persons</option>
                            @foreach($resourcePersons as $rp)
                                <option value="{{ $rp->id }}" {{ $resourcePersonFilter == $rp->id ? 'selected' : '' }}>
                                    {{ $rp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-industrial btn-primary-industrial w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Mappings Grouped by Program -->
    @if($mappings->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h5>No Pending Mappings</h5>
            <p>No course mappings match your current filters.</p>
        </div>
    @else
        @foreach($mappings->groupBy('program_code') as $programCode => $programMappings)
        <div class="main-card">
            <div class="main-card-header">
                <h5><i class="fas fa-graduation-cap"></i> {{ $programCode }} - {{ $programs[$programCode] ?? 'Unknown Program' }}</h5>
                <span class="count-badge">{{ $programMappings->count() }} mappings</span>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Diploma Course</th>
                            <th>Degree Course</th>
                            <th class="text-center" width="80">Match %</th>
                            <th>Resource Person</th>
                            <th>Status</th>
                            <th>Forwarded</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($programMappings as $mapping)
                        <tr>
                            <td>
                                <div class="course-code">{{ $mapping->diploma_course_code }}</div>
                                <div class="course-name">{{ Str::limit($mapping->diploma_course_name, 30) }}</div>
                                <div class="course-meta">{{ $mapping->diploma_credit_hour }} cr | {{ $mapping->diploma_institution }}</div>
                            </td>
                            <td>
                                <div class="course-code">{{ $mapping->degree_course_code }}</div>
                                <div class="course-name">{{ Str::limit($mapping->degree_course_name, 30) }}</div>
                                <div class="course-meta">{{ $mapping->degree_credit_hour }} cr</div>
                            </td>
                            <td class="text-center">
                                <span class="match-badge badge-{{ $mapping->match_percentage >= 80 ? 'success' : 'warning' }}">
                                    {{ number_format($mapping->match_percentage, 0) }}%
                                </span>
                            </td>
                            <td>
                                <small>{{ $mapping->resourcePerson->user->name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                @if($mapping->status === 'pending')
                                    <span class="badge-warning">Pending</span>
                                @elseif($mapping->status === 'added')
                                    <span class="badge-success">Added</span>
                                @elseif($mapping->status === 'rejected')
                                    <span class="badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $mapping->created_at->format('d M Y') }}</small>
                                <br>
                                <small class="text-muted">{{ $mapping->created_at->diffForHumans() }}</small>
                            </td>
                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-industrial btn-outline-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewModal-{{ $mapping->id }}"
                                        title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- View Modals for Each Mapping -->
        @foreach($programMappings as $mapping)
        <div class="modal fade" id="viewModal-{{ $mapping->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header-{{ $mapping->status === 'pending' ? 'warning' : ($mapping->status === 'added' ? 'success' : 'danger') }}">
                        <h5 class="modal-title">
                            <i class="fas fa-{{ $mapping->status === 'pending' ? 'clock' : ($mapping->status === 'added' ? 'check-circle' : 'times-circle') }}"></i>
                            Mapping Details - {{ $mapping->diploma_course_code }} → {{ $mapping->degree_course_code }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Status Information -->
                        <div class="status-alert {{ $mapping->status === 'pending' ? 'warning' : ($mapping->status === 'added' ? 'success' : 'danger') }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Status:</strong> {{ ucfirst($mapping->status) }}<br>
                                    <strong>Forwarded:</strong> {{ $mapping->created_at->format('d M Y, h:i A') }}
                                </div>
                                <div class="col-md-6">
                                    @if($mapping->status === 'added')
                                        <strong>Added By:</strong> {{ $mapping->coordinator->user->name ?? 'N/A' }}<br>
                                        <strong>Added On:</strong> {{ $mapping->added_at ? $mapping->added_at->format('d M Y, h:i A') : 'N/A' }}
                                    @elseif($mapping->status === 'rejected')
                                        <strong>Rejected By:</strong> {{ $mapping->coordinator->user->name ?? 'N/A' }}<br>
                                        <strong>Rejected On:</strong> {{ $mapping->updated_at->format('d M Y, h:i A') }}
                                    @else
                                        <span class="text-muted"><i class="fas fa-clock me-1"></i>Awaiting PC review</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Course Mapping Details -->
                        <div class="course-mapping-display">
                            <div class="course-mapping-item">
                                <h6 class="primary"><i class="fas fa-graduation-cap me-2"></i>Diploma Course</h6>
                                <table class="info-table">
                                    <tr>
                                        <th>Course Code:</th>
                                        <td><strong class="font-mono">{{ $mapping->diploma_course_code }}</strong></td>
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

                            <div class="course-mapping-arrow text-center">
                                <i class="fas fa-arrow-right"></i>
                                <br>
                                <span class="match-badge badge-{{ $mapping->match_percentage >= 80 ? 'success' : 'warning' }} mt-2" style="display: inline-block;">
                                    {{ number_format($mapping->match_percentage, 0) }}% Match
                                </span>
                            </div>

                            <div class="course-mapping-item">
                                <h6 class="info"><i class="fas fa-university me-2"></i>Degree Course</h6>
                                <table class="info-table">
                                    <tr>
                                        <th>Course Code:</th>
                                        <td><strong class="font-mono">{{ $mapping->degree_course_code }}</strong></td>
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

                        @if($mapping->notes)
                        <hr>
                        <h6 class="mb-2"><i class="fas fa-sticky-note me-2"></i>Resource Person Notes</h6>
                        <p class="text-muted">{{ $mapping->notes }}</p>
                        @endif

                        @if($mapping->status === 'rejected' && $mapping->rejection_reason)
                        <hr>
                        <div class="danger-alert">
                            <h6><i class="fas fa-exclamation-circle me-2"></i>Rejection Reason</h6>
                            <p>{{ $mapping->rejection_reason }}</p>
                        </div>
                        @endif

                        @if($mapping->status === 'added' && $mapping->equivalencyList)
                        <hr>
                        <div class="success-alert">
                            <h6><i class="fas fa-check-circle me-2"></i>Added to List</h6>
                            <p>
                                This mapping has been added to the equivalency list:
                                <strong>{{ $mapping->equivalencyList->semester }}</strong>
                                ({{ $mapping->equivalencyList->category === 'internal' ? 'Internal CS110' : $mapping->equivalencyList->source_institution }})
                            </p>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endforeach
    @endif

    <!-- Info Panel -->
    <div class="info-alert">
        <h6><i class="fas fa-info-circle me-2"></i>About Pending Mappings</h6>
        <p>
            This is a read-only monitoring interface for HEA personnel. Resource Persons forward course mappings to Program Coordinators for review.
            Program Coordinators can either add these mappings to their equivalency lists or reject them with a reason.
            HEA personnel can monitor this workflow but cannot take action on pending mappings.
        </p>
    </div>
</div>
@endsection
