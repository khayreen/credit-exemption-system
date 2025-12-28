@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">All Equivalency Lists</h2>
        <p class="text-muted mb-0 mt-2">
            <i class="fas fa-clipboard-list me-1"></i>
            View all published and draft equivalency lists across all programs
        </p>
    </div>
    <div>
        <a href="{{ route('resource_person.course_equivalencies.manage') }}" class="btn btn-info me-2">
            <i class="fas fa-exchange-alt"></i> General Mappings
        </a>
        <a href="{{ route('resource_person.equivalency_mappings.create') }}" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Forward Mapping
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card stat-card border-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon icon-blue me-3">
                        <i class="fas fa-list-alt"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['total_lists'] }}</h3>
                        <p class="text-muted mb-0">Total Lists</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon icon-green me-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['published_lists'] }}</h3>
                        <p class="text-muted mb-0">Published</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon icon-orange me-3">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['draft_lists'] }}</h3>
                        <p class="text-muted mb-0">Drafts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon icon-purple me-3">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['pending_mappings'] }}</h3>
                        <p class="text-muted mb-0">Pending Mappings</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabbed Interface with Search and Accordion -->
<div class="card shadow-sm">
    <div class="card-body">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-3" id="equivalencyTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="internal-tab" data-bs-toggle="tab" data-bs-target="#internal" type="button" role="tab">
                    <i class="fas fa-building me-2"></i>Internal Lists (CS110)
                    <span class="badge bg-primary ms-2">{{ $internalLists->flatten()->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="external-tab" data-bs-toggle="tab" data-bs-target="#external" type="button" role="tab">
                    <i class="fas fa-university me-2"></i>External Lists
                    <span class="badge bg-info ms-2">{{ $externalLists->flatten()->count() }}</span>
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="equivalencyTabContent">

            <!-- INTERNAL LISTS TAB -->
            <div class="tab-pane fade show active" id="internal" role="tabpanel">
                <!-- Search Box -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="search_internal" class="form-control" placeholder="Search by program code or name...">
                        </div>
                    </div>
                </div>

                @if($internalLists->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No Internal Lists</h5>
                        <p class="text-muted mb-0">No internal equivalency lists available yet.</p>
                    </div>
                @else
                    <!-- Accordion for Programs -->
                    <div class="accordion" id="internalAccordion">
                        @foreach($internalLists as $programCode => $lists)
                            @php
                                $totalCourses = $lists->sum('total_equivalencies');
                                $accordionId = 'internal-' . str_replace(' ', '-', $programCode);
                            @endphp
                            <div class="accordion-item internal-item" data-program-code="{{ strtolower($programCode) }}" data-program-name="{{ strtolower($lists->first()->program_name ?? $programCode) }}">
                                <h2 class="accordion-header" id="heading-{{ $accordionId }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $accordionId }}">
                                        <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                                            <div>
                                                <i class="fas fa-graduation-cap me-2 text-primary"></i>
                                                <strong>{{ $programCode }}</strong>
                                                <span class="text-muted ms-2">{{ $lists->first()->program_name ?? '' }}</span>
                                            </div>
                                            <div>
                                                <span class="badge bg-info me-2">{{ $totalCourses }} courses</span>
                                                <span class="badge bg-secondary">{{ $lists->count() }} list(s)</span>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse-{{ $accordionId }}" class="accordion-collapse collapse" data-bs-parent="#internalAccordion">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Semester</th>
                                                        <th>Status</th>
                                                        <th>Mappings</th>
                                                        <th>Published By</th>
                                                        <th>Active</th>
                                                        <th class="text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($lists as $list)
                                                    <tr>
                                                        <td><strong>{{ $list->semester }}</strong></td>
                                                        <td>
                                                            @if($list->published_at)
                                                                <span class="badge bg-success">Published</span>
                                                                <br><small class="text-muted">{{ $list->published_at->format('d M Y') }}</small>
                                                            @elseif($list->status === 'draft')
                                                                <span class="badge bg-warning text-dark">Draft</span>
                                                            @elseif($list->status === 'archived')
                                                                <span class="badge bg-secondary">Archived</span>
                                                            @endif
                                                        </td>
                                                        <td><span class="badge bg-info">{{ $list->total_equivalencies }} courses</span></td>
                                                        <td><small>{{ $list->publisher->name ?? '-' }}</small></td>
                                                        <td>
                                                            @if($list->is_active)
                                                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="{{ route('program_coordinator.equivalency_lists.show', $list) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- EXTERNAL LISTS TAB -->
            <div class="tab-pane fade" id="external" role="tabpanel">
                <!-- Search Box -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="search_external" class="form-control" placeholder="Search by institution name or program code...">
                        </div>
                    </div>
                </div>

                @if($externalLists->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No External Lists</h5>
                        <p class="text-muted mb-0">No external equivalency lists available yet.</p>
                    </div>
                @else
                    <!-- Accordion for Institutions -->
                    <div class="accordion" id="externalAccordion">
                        @foreach($externalLists as $institution => $lists)
                            @php
                                $totalCourses = $lists->sum('total_equivalencies');
                                $accordionId = 'external-' . md5($institution);
                            @endphp
                            <div class="accordion-item external-item" data-institution="{{ strtolower($institution) }}">
                                <h2 class="accordion-header" id="heading-{{ $accordionId }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $accordionId }}">
                                        <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                                            <div>
                                                <i class="fas fa-university me-2 text-info"></i>
                                                <strong>{{ $institution }}</strong>
                                            </div>
                                            <div>
                                                <span class="badge bg-info me-2">{{ $totalCourses }} courses</span>
                                                <span class="badge bg-secondary">{{ $lists->count() }} list(s)</span>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse-{{ $accordionId }}" class="accordion-collapse collapse" data-bs-parent="#externalAccordion">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Program</th>
                                                        <th>Semester</th>
                                                        <th>Status</th>
                                                        <th>Mappings</th>
                                                        <th>Published By</th>
                                                        <th>Active</th>
                                                        <th class="text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($lists as $list)
                                                    <tr data-program-code="{{ strtolower($list->program_code) }}">
                                                        <td><span class="badge bg-primary">{{ $list->program_code }}</span></td>
                                                        <td><strong>{{ $list->semester }}</strong></td>
                                                        <td>
                                                            @if($list->published_at)
                                                                <span class="badge bg-success">Published</span>
                                                                <br><small class="text-muted">{{ $list->published_at->format('d M Y') }}</small>
                                                            @elseif($list->status === 'draft')
                                                                <span class="badge bg-warning text-dark">Draft</span>
                                                            @elseif($list->status === 'archived')
                                                                <span class="badge bg-secondary">Archived</span>
                                                            @endif
                                                        </td>
                                                        <td><span class="badge bg-info">{{ $list->total_equivalencies }} courses</span></td>
                                                        <td><small>{{ $list->publisher->name ?? '-' }}</small></td>
                                                        <td>
                                                            @if($list->is_active)
                                                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="{{ route('program_coordinator.equivalency_lists.show', $list) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
@if(session('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
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
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert">
            <div class="toast-header bg-danger text-white">
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

<!-- JavaScript for Live Search -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Internal Lists Search
    const searchInternal = document.getElementById('search_internal');
    if (searchInternal) {
        searchInternal.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.internal-item');

            items.forEach(item => {
                const programCode = item.dataset.programCode || '';
                const programName = item.dataset.programName || '';

                if (programCode.includes(searchTerm) || programName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // External Lists Search
    const searchExternal = document.getElementById('search_external');
    if (searchExternal) {
        searchExternal.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.external-item');

            items.forEach(item => {
                const institution = item.dataset.institution || '';

                // Also search within program codes in the table
                const rows = item.querySelectorAll('tbody tr');
                let hasMatch = institution.includes(searchTerm);

                if (!hasMatch) {
                    rows.forEach(row => {
                        const programCode = row.dataset.programCode || '';
                        if (programCode.includes(searchTerm)) {
                            hasMatch = true;
                        }
                    });
                }

                if (hasMatch) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script>

<style>
/* Accordion styling enhancements */
.accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    color: #212529;
}

.accordion-button:focus {
    box-shadow: none;
    border-color: rgba(0,0,0,.125);
}

.accordion-item {
    margin-bottom: 0.5rem;
    border-radius: 0.25rem !important;
    border: 1px solid #dee2e6;
}

.accordion-button {
    font-size: 0.95rem;
}

/* Tab styling */
.nav-tabs .nav-link {
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    font-weight: 600;
}

/* Search box styling */
.input-group-text {
    background-color: #f8f9fa;
}
</style>
@endsection
