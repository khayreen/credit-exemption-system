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
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
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

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
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

    .stat-icon.blue {
        background: rgba(30,58,138,0.15);
        color: var(--uitm-blue);
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
        padding: 1.25rem 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .main-card-header.primary {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
    }

    .main-card-header.teal {
        background: linear-gradient(135deg, var(--info) 0%, #0f766e 100%);
    }

    .main-card-header h5 {
        font-weight: 700;
        margin: 0;
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

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Badges */
    .badge-primary {
        background: var(--uitm-blue);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        font-family: 'IBM Plex Mono', monospace;
    }

    .badge-info {
        background: var(--info);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        font-family: 'IBM Plex Mono', monospace;
    }

    /* Empty State */
    .empty-state {
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

    .btn-outline-primary {
        background: transparent;
        color: var(--uitm-blue);
        border: 1px solid var(--uitm-blue);
    }

    .btn-outline-primary:hover {
        background: var(--uitm-blue);
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

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
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
                <h2><i class="fas fa-file-alt me-2"></i>Draft Equivalency Lists</h2>
                <p><i class="fas fa-clipboard-list me-2"></i>Monitor draft lists being prepared by Program Coordinators</p>
            </div>
            <a href="{{ route('hea.equivalency_lists.index') }}" class="btn btn-industrial btn-back">
                <i class="fas fa-arrow-left"></i> Back to All Lists
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <div class="filter-card-header">
            <h6><i class="fas fa-filter me-2"></i>Filter Drafts</h6>
        </div>
        <div class="filter-card-body">
            <form method="GET" action="{{ route('hea.equivalency_lists.drafts') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Program</label>
                        <select name="program" class="form-select">
                            <option value="all" {{ $programFilter === 'all' ? 'selected' : '' }}>All Programs</option>
                            @foreach($programs as $code => $name)
                                <option value="{{ $code }}" {{ $programFilter === $code ? 'selected' : '' }}>
                                    {{ $code }} - {{ Str::limit($name, 40) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="all" {{ $categoryFilter === 'all' ? 'selected' : '' }}>All Categories</option>
                            <option value="internal" {{ $categoryFilter === 'internal' ? 'selected' : '' }}>Internal (CS110)</option>
                            <option value="external" {{ $categoryFilter === 'external' ? 'selected' : '' }}>External</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-industrial btn-primary-industrial w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <div class="stat-value">{{ $draftCounts['total'] }}</div>
                <div class="stat-label">Total Drafts</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <div class="stat-value">{{ $draftCounts['internal'] }}</div>
                <div class="stat-label">Internal (CS110)</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal">
                <i class="fas fa-university"></i>
            </div>
            <div>
                <div class="stat-value">{{ $draftCounts['external'] }}</div>
                <div class="stat-label">External Institutions</div>
            </div>
        </div>
    </div>

    <!-- Internal Drafts -->
    <div class="main-card">
        <div class="main-card-header primary">
            <i class="fas fa-building"></i>
            <h5>Internal (CS110) Draft Lists</h5>
        </div>
        @if($internalLists->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h5>No Internal Drafts</h5>
                <p>No draft lists for CS110 internal transfers.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Semester</th>
                            <th class="text-center">Mappings</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th>Last Updated</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($internalLists as $list)
                        <tr>
                            <td>
                                <span class="badge-primary">{{ $list->program_code }}</span>
                                <br>
                                <small class="text-muted">{{ Str::limit($list->program_name, 35) }}</small>
                            </td>
                            <td>{{ $list->semester }}</td>
                            <td class="text-center">
                                <span class="badge-info">{{ $list->courseEquivalencies->count() }}</span>
                            </td>
                            <td>
                                <small>{{ $list->creator->name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <small>{{ $list->created_at->format('d M Y') }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $list->updated_at->diffForHumans() }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('hea.equivalency_lists.show', $list) }}"
                                   class="btn btn-industrial btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- External Drafts -->
    <div class="main-card">
        <div class="main-card-header teal">
            <i class="fas fa-university"></i>
            <h5>External Institution Draft Lists</h5>
        </div>
        @if($externalLists->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h5>No External Drafts</h5>
                <p>No draft lists for external institutions.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Institution</th>
                            <th>Semester</th>
                            <th class="text-center">Mappings</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th>Last Updated</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($externalLists as $list)
                        <tr>
                            <td>
                                <span class="badge-primary">{{ $list->program_code }}</span>
                            </td>
                            <td>
                                <small>{{ Str::limit($list->source_institution, 30) }}</small>
                            </td>
                            <td>{{ $list->semester }}</td>
                            <td class="text-center">
                                <span class="badge-info">{{ $list->courseEquivalencies->count() }}</span>
                            </td>
                            <td>
                                <small>{{ $list->creator->name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <small>{{ $list->created_at->format('d M Y') }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $list->updated_at->diffForHumans() }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('hea.equivalency_lists.show', $list) }}"
                                   class="btn btn-industrial btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Info Panel -->
    <div class="info-alert">
        <h6><i class="fas fa-info-circle me-2"></i>About Draft Lists</h6>
        <p>
            Draft lists are being prepared by Program Coordinators. These lists are not yet published and are not visible to students.
            Program Coordinators can edit, add mappings, and publish these lists directly without requiring HEA approval.
        </p>
    </div>
</div>
@endsection
