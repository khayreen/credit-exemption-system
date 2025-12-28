@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Pending Course Mappings Monitor</h2>
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-paper-plane me-1"></i>
                Monitor course mappings forwarded by Resource Persons to Program Coordinators (Read-Only)
            </p>
        </div>
        <a href="{{ route('hea.equivalency_lists.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Lists
        </a>
    </div>

    <!-- Statistics Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-orange me-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['pending'] }}</h3>
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
                            <h3 class="mb-0">{{ $stats['added'] }}</h3>
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
                            <h3 class="mb-0">{{ $stats['rejected'] }}</h3>
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
                        <div class="stat-icon icon-purple me-3">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            <p class="text-muted mb-0">Total Forwarded</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Mappings</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('hea.pending_mappings.index') }}">
                <div class="row">
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
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Mappings Grouped by Program -->
    @if($mappings->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No Pending Mappings</h5>
                <p class="text-muted mb-0">No course mappings match your current filters.</p>
            </div>
        </div>
    @else
        @foreach($mappings->groupBy('program_code') as $programCode => $programMappings)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>
                    {{ $programCode }} - {{ $programs[$programCode] ?? 'Unknown Program' }}
                    <span class="badge bg-light text-dark ms-2">{{ $programMappings->count() }} mappings</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Diploma Course</th>
                                <th>Degree Course</th>
                                <th width="80" class="text-center">Match %</th>
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
                                    <strong>{{ $mapping->diploma_course_code }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($mapping->diploma_course_name, 30) }}</small>
                                    <br>
                                    <small class="text-muted">{{ $mapping->diploma_credit_hour }} cr | {{ $mapping->diploma_institution }}</small>
                                </td>
                                <td>
                                    <strong>{{ $mapping->degree_course_code }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($mapping->degree_course_name, 30) }}</small>
                                    <br>
                                    <small class="text-muted">{{ $mapping->degree_credit_hour }} cr</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $mapping->match_percentage >= 80 ? 'success' : 'warning' }}">
                                        {{ number_format($mapping->match_percentage, 0) }}%
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $mapping->resourcePerson->user->name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @if($mapping->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($mapping->status === 'added')
                                        <span class="badge bg-success">Added</span>
                                    @elseif($mapping->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $mapping->created_at->format('d M Y') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $mapping->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-info"
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
        </div>

        <!-- View Modals for Each Mapping -->
        @foreach($programMappings as $mapping)
        <div class="modal fade" id="viewModal-{{ $mapping->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-{{ $mapping->status === 'pending' ? 'warning' : ($mapping->status === 'added' ? 'success' : 'danger') }} text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-{{ $mapping->status === 'pending' ? 'clock' : ($mapping->status === 'added' ? 'check-circle' : 'times-circle') }} me-2"></i>
                            Mapping Details - {{ $mapping->diploma_course_code }} → {{ $mapping->degree_course_code }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Status Information -->
                        <div class="alert alert-{{ $mapping->status === 'pending' ? 'warning' : ($mapping->status === 'added' ? 'success' : 'danger') }}">
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
                        <div class="row">
                            <div class="col-md-5">
                                <h6 class="text-primary mb-3"><i class="fas fa-graduation-cap me-2"></i>Diploma Course</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="120">Course Code:</th>
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

                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="fas fa-arrow-right fa-3x text-muted"></i>
                                    <br>
                                    <span class="badge bg-{{ $mapping->match_percentage >= 80 ? 'success' : 'warning' }} mt-2 fs-6">
                                        {{ number_format($mapping->match_percentage, 0) }}% Match
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <h6 class="text-info mb-3"><i class="fas fa-university me-2"></i>Degree Course</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="120">Course Code:</th>
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

                        @if($mapping->notes)
                        <hr>
                        <h6 class="mb-2"><i class="fas fa-sticky-note me-2"></i>Resource Person Notes</h6>
                        <p class="text-muted">{{ $mapping->notes }}</p>
                        @endif

                        @if($mapping->status === 'rejected' && $mapping->rejection_reason)
                        <hr>
                        <div class="alert alert-danger mb-0">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Rejection Reason</h6>
                            <p class="mb-0">{{ $mapping->rejection_reason }}</p>
                        </div>
                        @endif

                        @if($mapping->status === 'added' && $mapping->equivalencyList)
                        <hr>
                        <div class="alert alert-success mb-0">
                            <h6 class="alert-heading"><i class="fas fa-check-circle me-2"></i>Added to List</h6>
                            <p class="mb-0">
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
    <div class="alert alert-info mt-4">
        <h6 class="alert-heading">
            <i class="fas fa-info-circle me-2"></i>About Pending Mappings
        </h6>
        <p class="mb-0">
            This is a read-only monitoring interface for HEA personnel. Resource Persons forward course mappings to Program Coordinators for review.
            Program Coordinators can either add these mappings to their equivalency lists or reject them with a reason.
            HEA personnel can monitor this workflow but cannot take action on pending mappings.
        </p>
    </div>
</div>
@endsection
