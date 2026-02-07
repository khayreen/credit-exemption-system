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

    .btn-upload {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
        color: white;
    }

    /* Alert Styles */
    .alert-industrial {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-industrial.alert-success {
        background: rgba(5, 150, 105, 0.1);
        border-left: 4px solid var(--success);
        color: var(--success);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.primary {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .stat-icon.success {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .stat-icon.info {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info);
    }

    .stat-content h3 {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
    }

    .stat-content p {
        color: var(--industrial-gray);
        margin: 0;
        font-size: 0.9rem;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
        color: white;
    }

    /* Syllabi Table Card */
    .syllabi-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: var(--industrial-gray);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
        margin-bottom: 1.5rem;
    }

    /* Syllabi Table */
    .syllabi-table {
        width: 100%;
        margin: 0;
    }

    .syllabi-table thead th {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
        border-bottom: 2px solid var(--uitm-blue);
    }

    .syllabi-table tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .syllabi-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.02);
    }

    .course-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
    }

    .course-name {
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .course-description {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .program-badge {
        display: inline-block;
        background: var(--industrial-light);
        color: var(--industrial-gray);
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .status-badge.active {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .status-badge.inactive {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
    }

    .upload-info {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .upload-info small {
        color: #94a3b8;
    }

    /* Action Buttons */
    .action-btn-group {
        display: flex;
        gap: 0.35rem;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid;
        transition: all 0.2s ease;
        background: transparent;
    }

    .action-btn.view {
        border-color: var(--danger);
        color: var(--danger);
    }

    .action-btn.view:hover {
        background: var(--danger);
        color: white;
    }

    .action-btn.edit {
        border-color: var(--uitm-blue);
        color: var(--uitm-blue);
    }

    .action-btn.edit:hover {
        background: var(--uitm-blue);
        color: white;
    }

    .action-btn.delete {
        border-color: var(--danger);
        color: var(--danger);
    }

    .action-btn.delete:hover {
        background: var(--danger);
        color: white;
    }

    /* Pagination Footer */
    .pagination-footer {
        background: white;
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-file-pdf me-2"></i>Degree Course Syllabi</h2>
            <p><i class="fas fa-book-open me-2"></i>Manage UiTM degree course syllabi for comparison</p>
        </div>
        <a href="{{ route('resource_person.syllabi.create') }}" class="btn-upload">
            <i class="fas fa-plus"></i> Upload New Syllabus
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-industrial alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Syllabi</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['active'] }}</h3>
                <p>Active Syllabi</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['programs'] }}</h3>
                <p>Programs Covered</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form action="{{ route('resource_person.syllabi.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Course code or name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Program</label>
                <select name="program_code" class="form-select">
                    <option value="">All Programs</option>
                    @foreach($programCodes as $code)
                        <option value="{{ $code }}" {{ request('program_code') == $code ? 'selected' : '' }}>{{ $code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn-filter w-100">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Syllabi Table -->
    <div class="syllabi-card">
        @if($syllabi->isEmpty())
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h5>No syllabi found</h5>
                <p>Upload your first degree course syllabus to get started.</p>
                <a href="{{ route('resource_person.syllabi.create') }}" class="btn-upload">
                    <i class="fas fa-plus me-2"></i>Upload Syllabus
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="syllabi-table">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th class="text-center">Credits</th>
                            <th>Program</th>
                            <th class="text-center">Status</th>
                            <th>Uploaded</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($syllabi as $syllabus)
                            <tr>
                                <td>
                                    <span class="course-badge">{{ $syllabus->course_code }}</span>
                                </td>
                                <td>
                                    <span class="course-name">{{ $syllabus->course_name }}</span>
                                    @if($syllabus->description)
                                        <div class="course-description">{{ Str::limit($syllabus->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span style="font-family: 'IBM Plex Mono', monospace; font-weight: 600;">{{ $syllabus->credit_hours }}</span>
                                </td>
                                <td>
                                    <span class="program-badge">{{ $syllabus->program_code }}</span>
                                </td>
                                <td class="text-center">
                                    @if($syllabus->is_active)
                                        <span class="status-badge active">Active</span>
                                    @else
                                        <span class="status-badge inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="upload-info">
                                        {{ $syllabus->created_at->format('d M Y') }}
                                        @if($syllabus->uploader)
                                            <br><small>by {{ $syllabus->uploader->name }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="action-btn-group justify-content-center">
                                        <a href="{{ route('resource_person.syllabi.view_pdf', $syllabus) }}" class="action-btn view" target="_blank" title="View PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <a href="{{ route('resource_person.syllabi.edit', $syllabus) }}" class="action-btn edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="action-btn delete" title="Delete" onclick="confirmDelete('{{ $syllabus->id }}', '{{ $syllabus->course_code }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $syllabus->id }}" action="{{ route('resource_person.syllabi.destroy', $syllabus) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($syllabi->hasPages())
                <div class="pagination-footer">
                    {{ $syllabi->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, courseCode) {
    if (confirm(`Are you sure you want to delete the syllabus for ${courseCode}? This action cannot be undone.`)) {
        document.getElementById(`delete-form-${id}`).submit();
    }
}
</script>
@endpush
@endsection
