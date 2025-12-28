@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Resource Person - Equivalency Lists</h2>
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-user-tie me-1"></i>
                View published equivalency lists and manage your forwarded mappings
            </p>
            @if(!empty($assignedPrograms))
                <p class="text-muted mb-0 mt-1">
                    <strong>Assigned Programs:</strong>
                    @foreach($assignedPrograms as $program)
                        <span class="badge bg-primary">{{ $program }}</span>
                    @endforeach
                </p>
            @endif
        </div>
        <div>
            <a href="{{ route('resource_person.equivalency_mappings.create') }}" class="btn btn-success">
                <i class="fas fa-paper-plane"></i> Forward New Mapping
            </a>
        </div>
    </div>

    <!-- Forwarded Mappings Status -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-orange me-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['pending_count'] }}</h3>
                            <p class="text-muted mb-0">Pending Review</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-green me-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['added_count'] }}</h3>
                            <p class="text-muted mb-0">Added to Lists</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-red me-3">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['rejected_count'] }}</h3>
                            <p class="text-muted mb-0">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-blue me-3">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['total_forwarded'] }}</h3>
                            <p class="text-muted mb-0">Total Forwarded</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Your Forwarded Mappings -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="fas fa-paper-plane me-2"></i>Your Forwarded Mappings
            </h5>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="mappingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                        Pending ({{ $pendingMappings->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="added-tab" data-bs-toggle="tab" data-bs-target="#added" type="button">
                        Added ({{ $addedMappings->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button">
                        Rejected ({{ $rejectedMappings->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="mappingsTabContent">
                <!-- Pending Tab -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    @if($pendingMappings->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No pending mappings</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Diploma Course</th>
                                        <th>Degree Course</th>
                                        <th>Program</th>
                                        <th class="text-center">Match %</th>
                                        <th>Forwarded</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingMappings as $mapping)
                                    <tr>
                                        <td>
                                            <strong>{{ $mapping->diploma_course_code }}</strong>
                                            <br><small class="text-muted">{{ Str::limit($mapping->diploma_course_name, 40) }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $mapping->degree_course_code }}</strong>
                                            <br><small class="text-muted">{{ Str::limit($mapping->degree_course_name, 40) }}</small>
                                        </td>
                                        <td><span class="badge bg-primary">{{ $mapping->program_code }}</span></td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $mapping->match_percentage >= 80 ? 'success' : 'warning' }}">
                                                {{ number_format($mapping->match_percentage, 0) }}%
                                            </span>
                                        </td>
                                        <td><small>{{ $mapping->created_at->diffForHumans() }}</small></td>
                                        <td class="text-center">
                                            <a href="{{ route('resource_person.equivalency_mappings.show', $mapping) }}" class="btn btn-sm btn-outline-primary">
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

                <!-- Added Tab -->
                <div class="tab-pane fade" id="added" role="tabpanel">
                    @if($addedMappings->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No mappings have been added to equivalency lists yet</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Diploma Course</th>
                                        <th>Degree Course</th>
                                        <th>Program</th>
                                        <th class="text-center">Match %</th>
                                        <th>Added By</th>
                                        <th>Added Date</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($addedMappings as $mapping)
                                    <tr>
                                        <td>
                                            <strong>{{ $mapping->diploma_course_code }}</strong>
                                            <br><small class="text-muted">{{ Str::limit($mapping->diploma_course_name, 40) }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $mapping->degree_course_code }}</strong>
                                            <br><small class="text-muted">{{ Str::limit($mapping->degree_course_name, 40) }}</small>
                                        </td>
                                        <td><span class="badge bg-primary">{{ $mapping->program_code }}</span></td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ number_format($mapping->match_percentage, 0) }}%</span>
                                        </td>
                                        <td><small>{{ $mapping->coordinator->name ?? 'N/A' }}</small></td>
                                        <td><small>{{ $mapping->added_at ? $mapping->added_at->format('d M Y') : '-' }}</small></td>
                                        <td class="text-center">
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Added
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Rejected Tab -->
                <div class="tab-pane fade" id="rejected" role="tabpanel">
                    @if($rejectedMappings->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No rejected mappings</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Diploma Course</th>
                                        <th>Degree Course</th>
                                        <th>Program</th>
                                        <th>Rejected By</th>
                                        <th>Reason</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rejectedMappings as $mapping)
                                    <tr>
                                        <td>
                                            <strong>{{ $mapping->diploma_course_code }}</strong>
                                            <br><small class="text-muted">{{ Str::limit($mapping->diploma_course_name, 40) }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $mapping->degree_course_code }}</strong>
                                            <br><small class="text-muted">{{ Str::limit($mapping->degree_course_name, 40) }}</small>
                                        </td>
                                        <td><span class="badge bg-primary">{{ $mapping->program_code }}</span></td>
                                        <td><small>{{ $mapping->coordinator->name ?? 'N/A' }}</small></td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm btn-link p-0 text-decoration-none"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reasonModal-{{ $mapping->id }}">
                                                <small><i class="fas fa-comment me-1"></i>View reason</small>
                                            </button>
                                        </td>
                                        <td><small>{{ $mapping->updated_at->format('d M Y') }}</small></td>
                                    </tr>

                                    <!-- Rejection Reason Modal -->
                                    <div class="modal fade" id="reasonModal-{{ $mapping->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h6 class="modal-title">Rejection Reason</h6>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-0">{{ $mapping->rejection_reason }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Published Equivalency Lists (Read-Only) -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Published Equivalency Lists (Read-Only)
            </h5>
        </div>
        <div class="card-body">
            <!-- Internal Lists -->
            @if(!$internalLists->isEmpty())
                <h6 class="text-primary mb-3">
                    <i class="fas fa-building me-2"></i>Internal Lists (CS110)
                </h6>
                <div class="table-responsive mb-4">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Program</th>
                                <th>Semester</th>
                                <th>Mappings</th>
                                <th>Published By</th>
                                <th>Published Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($internalLists as $list)
                            <tr>
                                <td><span class="badge bg-primary">{{ $list->program_code }}</span></td>
                                <td>{{ $list->semester }}</td>
                                <td><span class="badge bg-info">{{ $list->total_mappings }} courses</span></td>
                                <td><small>{{ $list->publisher->name ?? 'N/A' }}</small></td>
                                <td><small>{{ $list->published_at ? $list->published_at->format('d M Y') : '-' }}</small></td>
                                <td class="text-center">
                                    <a href="{{ route('resource_person.equivalency_lists.show', $list) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- External Lists -->
            @if(!$externalLists->isEmpty())
                <h6 class="text-info mb-3">
                    <i class="fas fa-university me-2"></i>External Lists (Other Institutions)
                </h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Program</th>
                                <th>Institution</th>
                                <th>Semester</th>
                                <th>Mappings</th>
                                <th>Published By</th>
                                <th>Published Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($externalLists as $list)
                            <tr>
                                <td><span class="badge bg-primary">{{ $list->program_code }}</span></td>
                                <td><small>{{ Str::limit($list->source_institution, 30) }}</small></td>
                                <td>{{ $list->semester }}</td>
                                <td><span class="badge bg-info">{{ $list->total_mappings }} courses</span></td>
                                <td><small>{{ $list->publisher->name ?? 'N/A' }}</small></td>
                                <td><small>{{ $list->published_at ? $list->published_at->format('d M Y') : '-' }}</small></td>
                                <td class="text-center">
                                    <a href="{{ route('resource_person.equivalency_lists.show', $list) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if($internalLists->isEmpty() && $externalLists->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Published Lists</h5>
                    <p class="text-muted mb-0">No published equivalency lists are available for your assigned programs.</p>
                </div>
            @endif
        </div>
    </div>
</div>

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
@endsection
