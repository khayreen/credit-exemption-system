@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="fas fa-clipboard-list me-2"></i>Published Equivalency Lists
            </h2>
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-info-circle me-1"></i>
                View published course equivalency lists organized by degree program
            </p>
        </div>
        <div>
            <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="btn btn-info">
                <i class="fas fa-search me-2"></i>Search All Course Mappings
            </a>
            <a href="{{ route('program_coordinator.equivalency_lists.create') }}" class="btn btn-success ms-2">
                <i class="fas fa-plus-circle me-2"></i>Create New List
            </a>
            <a href="{{ route('program_coordinator.pending_mappings.index') }}" class="btn btn-primary ms-2">
                <i class="fas fa-inbox me-2"></i>Pending Mappings
                @if($stats['pending_mappings'] > 0)
                    <span class="badge bg-danger ms-1">{{ $stats['pending_mappings'] }}</span>
                @endif
            </a>
        </div>
    </div>

    <!-- Program Tabs -->
    <ul class="nav nav-tabs mb-4" id="programTabs" role="tablist">
                @foreach($programs as $index => $programCode)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $latestPublishedProgram === $programCode ? 'active' : '' }}"
                            id="{{ strtolower($programCode) }}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#{{ strtolower($programCode) }}"
                            type="button"
                            role="tab">
                        <strong>{{ $programCode }}</strong>
                    </button>
                </li>
                @endforeach
            </ul>

            <div class="tab-content" id="programTabsContent">
                @foreach($programs as $index => $programCode)
                <div class="tab-pane fade {{ $latestPublishedProgram === $programCode ? 'show active' : '' }}"
                     id="{{ strtolower($programCode) }}"
                     role="tabpanel">

                    <!-- Program Name -->
                    <h4 class="mb-3 text-primary">
                        <i class="fas fa-graduation-cap me-2"></i>{{ $programData[$programCode]['name'] }}
                    </h4>

                    @if($programData[$programCode]['current'] || $programData[$programCode]['history']->isNotEmpty())

                        <!-- Current Active List -->
                        @if($programData[$programCode]['current'])
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-star text-warning me-2"></i>Current Active List
                                </h5>
                                @php $list = $programData[$programCode]['current']; @endphp
                                <a href="{{ route('program_coordinator.equivalency_lists.pdf', $list) }}"
                                   class="btn btn-danger"
                                   target="_blank">
                                    <i class="fas fa-file-pdf me-2"></i>View as PDF
                                </a>
                            </div>

                            <!-- List Information Card -->
                            <div class="card shadow-sm mb-3">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="fas fa-info-circle me-2"></i>List Information
                                        </h6>
                                        @if($list->is_active)
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="fas fa-check-circle"></i> PUBLISHED & ACTIVE
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <th width="150">Program Code:</th>
                                                    <td><span class="badge bg-primary">{{ $list->program_code }}</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Program Name:</th>
                                                    <td>{{ $list->program_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Category:</th>
                                                    <td>
                                                        @if($list->category === 'internal')
                                                            <span class="badge bg-primary">Internal (CS110)</span>
                                                        @else
                                                            <span class="badge bg-info">External Institution</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($list->category === 'external')
                                                    <tr>
                                                        <th>Source Institution:</th>
                                                        <td>{{ $list->source_institution }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <th>Semester:</th>
                                                    <td>{{ $list->semester }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <th width="150">Status:</th>
                                                    <td><span class="badge bg-success">Published</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Total Mappings:</th>
                                                    <td><strong>{{ $list->total_mappings }}</strong> course(s)</td>
                                                </tr>
                                                <tr>
                                                    <th>Created By:</th>
                                                    <td>{{ $list->creator->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Created At:</th>
                                                    <td>{{ $list->created_at->format('d M Y, h:i A') }}</td>
                                                </tr>
                                                @if($list->published_at)
                                                    <tr>
                                                        <th>Published By:</th>
                                                        <td>{{ $list->publisher->name ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Published At:</th>
                                                        <td>{{ $list->published_at->format('d M Y, h:i A') }}</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Course Mappings -->
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-exchange-alt me-2"></i>Course Equivalency Mappings
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    @if($list->courseEquivalencies->isEmpty())
                                        <div class="text-center py-5">
                                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                            <h5 class="text-muted">No Course Mappings</h5>
                                            <p class="text-muted mb-0">This equivalency list does not have any course mappings yet.</p>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="5%" class="text-center">#</th>
                                                        <th width="35%">Diploma Course</th>
                                                        <th width="35%">Degree Course</th>
                                                        <th width="10%" class="text-center">Match %</th>
                                                        <th width="10%" class="text-center">Credit Hours</th>
                                                        <th width="5%" class="text-center">Eligible</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($list->courseEquivalencies->sortBy('diploma_course_code') as $index => $eq)
                                                    <tr>
                                                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                                                        <td>
                                                            <strong>{{ $eq->diploma_course_code }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $eq->diploma_course_name }}</small>
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-university me-1"></i>{{ $eq->diploma_institution }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <strong>{{ $eq->degree_course_code }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $eq->degree_course_name }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge fs-6 bg-{{ $eq->match_percentage >= 80 ? 'success' : 'warning' }}">
                                                                {{ number_format($eq->match_percentage, 0) }}%
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <small class="text-muted">
                                                                {{ $eq->diploma_credit_hour }} → {{ $eq->degree_credit_hour }}
                                                            </small>
                                                        </td>
                                                        <td class="text-center">
                                                            @if($eq->is_eligible)
                                                                <i class="fas fa-check-circle text-success fs-5"></i>
                                                            @else
                                                                <i class="fas fa-times-circle text-danger fs-5"></i>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Statistics Footer -->
                                        <div class="card-footer bg-light">
                                            <div class="row text-center">
                                                <div class="col-md-4">
                                                    <strong>Total Mappings:</strong>
                                                    <span class="badge bg-primary ms-2">{{ $list->courseEquivalencies->count() }}</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Eligible (≥80%):</strong>
                                                    <span class="badge bg-success ms-2">{{ $list->courseEquivalencies->where('is_eligible', true)->count() }}</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Not Eligible (<80%):</strong>
                                                    <span class="badge bg-warning text-dark ms-2">{{ $list->courseEquivalencies->where('is_eligible', false)->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- History -->
                        @if($programData[$programCode]['history']->isNotEmpty())
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-history me-2"></i>History (Previous Published Lists)
                            </h5>
                            @foreach($programData[$programCode]['history'] as $list)
                            <div class="card mb-3 border-secondary">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h6 class="mb-2">
                                                <i class="fas fa-calendar-alt me-2 text-muted"></i>{{ $list->semester }}
                                            </h6>
                                            <p class="mb-1">
                                                <span class="badge bg-{{ $list->category === 'internal' ? 'primary' : 'info' }} bg-opacity-75">
                                                    {{ $list->category === 'internal' ? 'UiTM CS110' : 'External - ' . $list->source_institution }}
                                                </span>
                                                <span class="badge bg-secondary ms-2">
                                                    <i class="fas fa-book me-1"></i>{{ $list->courseEquivalencies->count() }} courses
                                                </span>
                                            </p>
                                            <p class="text-muted mb-0">
                                                <small>
                                                    <i class="fas fa-user me-1"></i>Published by {{ $list->publisher->name ?? 'N/A' }}
                                                    on {{ $list->published_at ? $list->published_at->format('d M Y') : 'N/A' }}
                                                </small>
                                            </p>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <span class="badge bg-secondary fs-6 px-3 py-2 me-2">
                                                <i class="fas fa-archive"></i> ARCHIVED
                                            </span>
                                            <a href="{{ route('program_coordinator.equivalency_lists.show', $list) }}"
                                               class="btn btn-outline-secondary">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Published Lists Yet</h5>
                            <p class="text-muted">No equivalency lists have been published for this program yet.</p>
                            <a href="{{ route('program_coordinator.equivalency_lists.create') }}?program_code={{ $programCode }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus-circle me-2"></i>Create First List for {{ $programCode }}
                            </a>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
</div>

<style>
.nav-tabs .nav-link {
    color: #495057;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    font-weight: 600;
    border-bottom: 3px solid #0d6efd;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
@endsection
