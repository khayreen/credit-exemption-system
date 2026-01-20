@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">CS110 Internal Equivalency Lists</h2>
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-building me-1"></i>
                ONE continuous list per program - Edit anytime, submit for each semester
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
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Program Lists -->
    @foreach($programLists as $programCode => $data)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-{{ $data['status'] === 'draft' ? 'secondary' : ($data['status'] === 'submitted' ? 'info' : ($data['status'] === 'endorsed' ? 'success' : 'primary')) }} text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>
                        {{ $programCode }} - {{ $data['program_name'] }}
                    </h5>
                    <div>
                        @if($data['list'])
                            @if($data['list']->published_at)
                                <span class="badge bg-success">Published</span>
                                <br><small class="text-white">{{ $data['list']->published_at->format('d M Y') }}</small>
                            @else
                                <span class="badge bg-light text-dark">
                                    Status: {{ $data['list']->status_display }}
                                </span>
                            @endif
                        @else
                            <span class="badge bg-light text-dark">Not Created</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h3 class="mb-0">{{ $data['total_mappings'] }}</h3>
                                <small class="text-muted">Course Mappings</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h3 class="mb-0">{{ $data['last_semester'] ?? '-' }}</h3>
                                <small class="text-muted">Last Published</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <h3 class="mb-0">
                                    @if($data['last_published'])
                                        {{ $data['last_published']->format('d M Y') }}
                                    @else
                                        Never
                                    @endif
                                </h3>
                                <small class="text-muted">Last Publication Date</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                @if($data['list'] && $data['list']->target_semester)
                                    <h3 class="mb-0">{{ $data['list']->target_semester }}</h3>
                                    <small class="text-muted">Target Semester</small>
                                @else
                                    <h3 class="mb-0">-</h3>
                                    <small class="text-muted">No Target Set</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <div>
                        @if($data['list'])
                            @if($data['status'] === 'draft' || $data['status'] === 'rejected')
                                <span class="badge bg-secondary me-2">
                                    <i class="fas fa-pencil-alt"></i> Editable
                                </span>
                            @endif
                            @if($data['status'] === 'submitted')
                                <span class="badge bg-info">
                                    <i class="fas fa-hourglass-half"></i> Awaiting HEA Review
                                </span>
                            @endif
                            @if($data['status'] === 'under_review')
                                <span class="badge bg-primary">
                                    <i class="fas fa-search"></i> Under HEA Review
                                </span>
                            @endif
                            @if($data['status'] === 'endorsed')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Endorsed - Awaiting Publication
                                </span>
                            @endif
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('resource_person.equivalency_lists.edit', $programCode) }}" class="btn btn-success">
                            <i class="fas fa-edit"></i> Edit CS110 List
                        </a>
                    </div>
                </div>

                <!-- Course Mappings Table -->
                @if($data['list'] && $data['list']->courseEquivalencies->count() > 0)
                    <div class="mt-4">
                        <h6 class="mb-3"><i class="fas fa-list me-2"></i>Course Mappings ({{ $data['list']->courseEquivalencies->count() }})</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>CS110 Diploma Course</th>
                                        <th class="text-center">Credit</th>
                                        <th>{{ $programCode }} Degree Course</th>
                                        <th class="text-center">Credit</th>
                                        <th class="text-center">Match %</th>
                                        <th class="text-center">Eligible</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['list']->courseEquivalencies->sortBy('diploma_course_code') as $equiv)
                                    <tr>
                                        <td>
                                            <strong>{{ $equiv->diploma_course_code }}</strong><br>
                                            <small class="text-muted">{{ Str::limit($equiv->diploma_course_name, 40) }}</small>
                                        </td>
                                        <td class="text-center">{{ $equiv->diploma_credit_hour }}</td>
                                        <td>
                                            <strong>{{ $equiv->degree_course_code }}</strong><br>
                                            <small class="text-muted">{{ Str::limit($equiv->degree_course_name, 40) }}</small>
                                        </td>
                                        <td class="text-center">{{ $equiv->degree_credit_hour }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $equiv->match_percentage >= 80 ? 'success' : 'warning' }}">
                                                {{ number_format($equiv->match_percentage, 0) }}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($equiv->is_eligible)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($data['list'] && $data['list']->courseEquivalencyHistory->count() > 0)
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fas fa-history me-2"></i>
                        <strong>History:</strong> {{ $data['list']->courseEquivalencyHistory->count() }} archived course mappings
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    @if(empty($programLists))
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-graduation-cap fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Programs Assigned</h4>
                <p class="text-muted mb-0">You don't have any programs assigned yet. Contact HEA for program assignment.</p>
            </div>
        </div>
    @endif

    <!-- Info Box -->
    <div class="card border-primary mt-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>How CS110 Lists Work</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary">Continuous Editing</h6>
                    <ul class="small">
                        <li>ONE list per program (e.g., CDCS251)</li>
                        <li>Edit anytime to add/update/delete mappings</li>
                        <li>Changes are tracked in history</li>
                        <li>Always in DRAFT status when editable</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-success">Semester Publication</h6>
                    <ul class="small">
                        <li>Submit to HEA when ready for semester publication</li>
                        <li>Specify target semester (e.g., 2025/2026-1)</li>
                        <li>HEA reviews, endorses, and publishes PDF</li>
                        <li>After publication, reverts to DRAFT for next semester</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
