@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Equivalency Lists</h2>
        <p class="text-muted mb-0 mt-2">
            <i class="fas fa-clipboard-list me-1"></i>
            View all equivalency lists grouped by program and institution
        </p>
    </div>
    <div>
        <a href="{{ route('hea.equivalency_lists.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-table"></i> Table View
        </a>
        <a href="{{ route('hea.pending_mappings.index') }}" class="btn btn-info ms-2">
            <i class="fas fa-inbox"></i> Pending Mappings
            @if($stats['pending_mappings'] > 0)
                <span class="badge bg-danger ms-1">{{ $stats['pending_mappings'] }}</span>
            @endif
        </a>
    </div>
</div>

<!-- Endorser Filter -->
<div class="card mb-4 border-primary">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('hea.equivalency_lists.grouped') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="endorser" class="form-label mb-1"><i class="fas fa-filter me-1"></i>Filter by Endorser</label>
                <select name="endorser" id="endorser" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ $endorserFilter === 'all' ? 'selected' : '' }}>All Lists</option>
                    <option value="me" {{ $endorserFilter === 'me' ? 'selected' : '' }}>Lists I Endorsed</option>
                    <option value="endorsed" {{ $endorserFilter === 'endorsed' ? 'selected' : '' }}>All Endorsed Lists</option>
                </select>
            </div>
            <div class="col-md-8 text-end">
                @if($endorserFilter !== 'all')
                    <a href="{{ route('hea.equivalency_lists.grouped') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear Filter
                    </a>
                @endif
            </div>
        </form>
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
        <a href="#published" class="text-decoration-none" onclick="event.preventDefault(); document.getElementById('published-tab').click();" style="cursor: pointer;">
            <div class="card stat-card border-success hover-card">
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
        </a>
    </div>
    <div class="col-md-3 mb-4">
        <a href="{{ route('hea.equivalency_lists.grouped', ['endorser' => 'me']) }}" class="text-decoration-none">
            <div class="card stat-card border-info {{ $endorserFilter === 'me' ? 'bg-light' : '' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-purple me-3">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['endorsed_by_me'] }}</h3>
                            <p class="text-muted mb-0">Endorsed by Me</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon icon-orange me-3">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['pending_mappings'] }}</h3>
                        <p class="text-muted mb-0">Pending from RP</p>
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
                <button class="nav-link active" id="published-tab" data-bs-toggle="tab" data-bs-target="#published" type="button" role="tab">
                    <i class="fas fa-check-circle me-2"></i>Published Lists
                    <span class="badge bg-success ms-2">{{ $stats['published_lists'] }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="internal-tab" data-bs-toggle="tab" data-bs-target="#internal" type="button" role="tab">
                    <i class="fas fa-building me-2"></i>Internal Lists (CS110)
                    <span class="badge bg-secondary ms-2">{{ $stats['wip_internal'] }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="external-tab" data-bs-toggle="tab" data-bs-target="#external" type="button" role="tab">
                    <i class="fas fa-university me-2"></i>External Lists
                    <span class="badge bg-secondary ms-2">{{ $stats['wip_external'] }}</span>
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="equivalencyTabContent">

            <!-- INTERNAL LISTS TAB -->
            <div class="tab-pane fade" id="internal" role="tabpanel">
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
                        <p class="text-muted mb-0">No internal equivalency lists have been created yet.</p>
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
                                                        <th>Endorsed By</th>
                                                        <th>Active</th>
                                                        <th class="text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($lists as $list)
                                                    @php
                                                        // For legacy lists: if endorsed_by is null but published, use publisher as endorser
                                                        $effectiveEndorser = $list->endorser ?? $list->publisher;
                                                        $effectiveEndorserId = $list->endorsed_by_user_id ?? $list->published_by_user_id;
                                                        $isEndorsedByMe = $effectiveEndorserId === auth()->id();
                                                    @endphp
                                                    <tr class="{{ $isEndorsedByMe ? 'table-success' : '' }}">
                                                        <td>
                                                            @if($list->published_at)
                                                                <i class="fas fa-check-circle text-success me-1" title="Published on {{ $list->published_at->format('d M Y') }}"></i>
                                                            @endif
                                                            <strong>{{ $list->semester }}</strong>
                                                            @if($isEndorsedByMe)
                                                                <span class="badge bg-success ms-1" title="Endorsed by you">
                                                                    <i class="fas fa-user-check"></i>
                                                                </span>
                                                            @endif
                                                        </td>
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
                                                        <td>
                                                            @if($effectiveEndorser)
                                                                <small>{{ $effectiveEndorser->name }}</small>
                                                                @if($list->endorsed_at ?? $list->published_at)
                                                                    <br><small class="text-muted">{{ ($list->endorsed_at ?? $list->published_at)->format('d M Y') }}</small>
                                                                @endif
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($list->is_active)
                                                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="{{ route('hea.equivalency_lists.show', $list) }}" class="btn btn-sm btn-outline-primary btn-action-icon" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if(!$list->published_at && !$list->endorsed_at)
                                                                <form action="{{ route('hea.equivalency_lists.destroy', $list) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Are you sure you want to delete this draft list for {{ $list->semester }}? This action cannot be undone.');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action-icon" title="Delete Draft">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
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
                        <p class="text-muted mb-0">No external equivalency lists have been created yet.</p>
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
                                                        <th>Endorsed By</th>
                                                        <th>Active</th>
                                                        <th class="text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($lists as $list)
                                                    @php
                                                        // For legacy lists: if endorsed_by is null but published, use publisher as endorser
                                                        $effectiveEndorser = $list->endorser ?? $list->publisher;
                                                        $effectiveEndorserId = $list->endorsed_by_user_id ?? $list->published_by_user_id;
                                                        $isEndorsedByMe = $effectiveEndorserId === auth()->id();
                                                    @endphp
                                                    <tr data-program-code="{{ strtolower($list->program_code) }}" class="{{ $isEndorsedByMe ? 'table-success' : '' }}">
                                                        <td><span class="badge bg-primary">{{ $list->program_code }}</span></td>
                                                        <td>
                                                            @if($list->published_at)
                                                                <i class="fas fa-check-circle text-success me-1" title="Published on {{ $list->published_at->format('d M Y') }}"></i>
                                                            @endif
                                                            <strong>{{ $list->semester }}</strong>
                                                            @if($isEndorsedByMe)
                                                                <span class="badge bg-success ms-1" title="Endorsed by you">
                                                                    <i class="fas fa-user-check"></i>
                                                                </span>
                                                            @endif
                                                        </td>
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
                                                        <td>
                                                            @if($effectiveEndorser)
                                                                <small>{{ $effectiveEndorser->name }}</small>
                                                                @if($list->endorsed_at ?? $list->published_at)
                                                                    <br><small class="text-muted">{{ ($list->endorsed_at ?? $list->published_at)->format('d M Y') }}</small>
                                                                @endif
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($list->is_active)
                                                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="{{ route('hea.equivalency_lists.show', $list) }}" class="btn btn-sm btn-outline-primary btn-action-icon" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if(!$list->published_at && !$list->endorsed_at)
                                                                <form action="{{ route('hea.equivalency_lists.destroy', $list) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Are you sure you want to delete this draft list for {{ $list->semester }}? This action cannot be undone.');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action-icon" title="Delete Draft">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
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

            <!-- PUBLISHED LISTS TAB -->
            <div class="tab-pane fade show active" id="published" role="tabpanel">
                <!-- Search Box -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="search_published" class="form-control" placeholder="Search by program code or name...">
                        </div>
                    </div>
                </div>

                @if($publishedLists->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No Published Lists</h5>
                        <p class="text-muted mb-0">No equivalency lists have been published yet.</p>
                    </div>
                @else
                    <!-- Accordion for Programs -->
                    <div class="accordion" id="publishedAccordion">
                        @foreach($publishedLists as $programCode => $lists)
                            @php
                                $totalCourses = $lists->sum('total_equivalencies');
                                $accordionId = 'published-' . str_replace(' ', '-', $programCode);
                                $firstList = $lists->first();
                            @endphp
                            <div class="accordion-item published-item" data-program-code="{{ strtolower($programCode) }}" data-program-name="{{ strtolower($firstList->program_name ?? $programCode) }}">
                                <h2 class="accordion-header" id="heading-{{ $accordionId }}">
                                    <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $accordionId }}">
                                        <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                                            <div>
                                                <i class="fas fa-check-circle me-2 text-success"></i>
                                                <strong>{{ $programCode }}</strong>
                                                <span class="text-muted ms-2">{{ $firstList->program_name ?? '' }}</span>
                                            </div>
                                            <div>
                                                <span class="badge bg-success me-2">{{ $totalCourses }} courses</span>
                                                <span class="badge bg-primary">{{ $lists->count() }} semester(s)</span>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse-{{ $accordionId }}" class="accordion-collapse collapse" data-bs-parent="#publishedAccordion">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-success">
                                                    <tr>
                                                        <th width="14%">Semester</th>
                                                        <th width="11%">Status</th>
                                                        <th width="10%">Mappings</th>
                                                        <th width="13%">Endorsed By</th>
                                                        <th width="12%">Published By</th>
                                                        <th width="15%">Published Date</th>
                                                        <th width="10%" class="text-center">Active</th>
                                                        <th width="15%" class="text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($lists as $list)
                                                    @php
                                                        // For legacy lists: if endorsed_by is null but published, use publisher as endorser
                                                        $effectiveEndorser = $list->endorser ?? $list->publisher;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <i class="fas fa-check-circle text-success me-1" title="Published"></i>
                                                            <strong>{{ $list->semester }}</strong>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success">Published</span>
                                                        </td>
                                                        <td><span class="badge bg-info">{{ $list->total_equivalencies }} courses</span></td>
                                                        <td>
                                                            @if($effectiveEndorser)
                                                                <small>{{ $effectiveEndorser->name }}</small>
                                                                @if($list->endorsed_at ?? $list->published_at)
                                                                    <br><small class="text-muted">{{ ($list->endorsed_at ?? $list->published_at)->format('d M Y') }}</small>
                                                                @endif
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td><small>{{ $list->publisher->name ?? '-' }}</small></td>
                                                        <td><small>{{ $list->published_at->format('d M Y, H:i') }}</small></td>
                                                        <td class="text-center">
                                                            @if($list->is_active)
                                                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>
                                                            @else
                                                                <span class="text-muted">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="{{ route('hea.equivalency_lists.show', $list) }}" class="btn btn-sm btn-outline-primary btn-action-icon" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('hea.equivalency_lists.pdf', $list) }}" class="btn btn-sm btn-outline-secondary btn-action-icon ms-1" target="_blank" title="Download PDF">
                                                                <i class="fas fa-file-pdf"></i>
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

    // Published Lists Search
    const searchPublished = document.getElementById('search_published');
    if (searchPublished) {
        searchPublished.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.published-item');

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

/* Statistics card hover effect */
.stat-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

a .stat-card:hover, .hover-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    cursor: pointer;
}

/* Highlight endorsed rows */
.table-success {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

/* Action buttons - make all buttons square and same size */
.btn-action-icon {
    width: 34px;
    height: 34px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
