@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-clipboard-list me-2"></i>Equivalency List Details</h2>
    <div>
        @if(in_array($list->status, ['submitted', 'under_review']))
            <a href="{{ route('hea.equivalency_lists.review', $list) }}" class="btn btn-primary">
                <i class="fas fa-clipboard-check me-1"></i> Review
            </a>
        @endif
        <a href="{{ route('hea.equivalency_lists.pdf', $list) }}" class="btn btn-outline-secondary" target="_blank">
            <i class="fas fa-file-pdf me-1"></i> Download PDF
        </a>
        <a href="{{ route('hea.equivalency_lists.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- List Information Card -->
<div class="card shadow-sm mb-4">
    <div class="card-header {{ $list->isInternal() ? 'bg-primary' : 'bg-success' }} text-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">
                    @if($list->isInternal())
                        CS110 (UiTM DIPLOMA) LIST
                    @else
                        EXTERNAL LIST ({{ $list->source_institution }})
                    @endif
                </h5>
                <small>
                    {{ $list->program_code }} - {{ $list->program_name }}
                </small>
            </div>
            <div class="text-end">
                @if($list->published_at)
                    <span class="badge bg-success fs-6">Published</span>
                @else
                    <span class="badge bg-{{ $list->status_badge_class }} fs-6">{{ $list->status_display }}</span>
                @endif
                @if($list->is_active)
                    <br><span class="badge bg-warning text-dark mt-1">ACTIVE LIST</span>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-muted mb-3">List Information</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" width="150">Category:</td>
                        <td>
                            @if($list->isInternal())
                                <span class="badge bg-primary">Internal (CS110)</span>
                            @else
                                <span class="badge bg-success">External</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Source:</td>
                        <td>{{ $list->source_display }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Target Program:</td>
                        <td><strong>{{ $list->program_code }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Semester:</td>
                        <td>{{ $list->semester }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Academic Year:</td>
                        <td>{{ $list->academic_year }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-3">Workflow Information</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" width="150">Created By:</td>
                        <td>{{ $list->creator->name ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created:</td>
                        <td>{{ $list->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @if($list->submitted_at)
                    <tr>
                        <td class="text-muted">Submitted:</td>
                        <td>{{ $list->submitted_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @endif
                    @if($list->endorsed_at)
                    <tr>
                        <td class="text-muted">Endorsed By:</td>
                        <td>{{ $list->endorser->name ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Endorsed:</td>
                        <td>{{ $list->endorsed_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @endif
                    @if($list->published_at)
                    <tr>
                        <td class="text-muted">Published:</td>
                        <td>{{ $list->published_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        @if($list->submission_notes)
        <div class="alert alert-info mt-3 mb-0">
            <strong><i class="fas fa-comment me-1"></i> Submission Notes:</strong><br>
            {{ $list->submission_notes }}
        </div>
        @endif

        @if($list->endorsement_notes)
        <div class="alert alert-success mt-3 mb-0">
            <strong><i class="fas fa-check-circle me-1"></i> Endorsement Notes:</strong><br>
            {{ $list->endorsement_notes }}
        </div>
        @endif

        @if($list->review_notes && $list->status === 'rejected')
        <div class="alert alert-danger mt-3 mb-0">
            <strong><i class="fas fa-times-circle me-1"></i> Rejection Reason:</strong><br>
            {{ $list->review_notes }}
        </div>
        @endif
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center border-primary">
            <div class="card-body py-3">
                <h3 class="mb-0 text-primary">{{ $list->total_equivalencies }}</h3>
                <small class="text-muted">Total Mappings</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-success">
            <div class="card-body py-3">
                <h3 class="mb-0 text-success">{{ $list->eligible_count }}</h3>
                <small class="text-muted">Eligible (>=80%)</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-warning">
            <div class="card-body py-3">
                <h3 class="mb-0 text-warning">{{ $list->not_eligible_count }}</h3>
                <small class="text-muted">Not Eligible (<80%)</small>
            </div>
        </div>
    </div>
</div>

<!-- Equivalency Mappings Table -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-list me-1"></i> Course Equivalency Mappings</h6>
        <span class="badge bg-secondary">{{ $list->courseEquivalencies->count() }} mappings</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Diploma Course</th>
                        <th>Degree Course</th>
                        <th width="80" class="text-center">Match %</th>
                        <th width="100" class="text-center">Eligible</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list->courseEquivalencies->sortBy('diploma_course_code') as $index => $eq)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $eq->diploma_course_code }}</div>
                            <small class="text-muted">{{ $eq->diploma_course_name }} ({{ $eq->diploma_credit_hour }} cr)</small>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $eq->degree_course_code }}</div>
                            <small class="text-muted">{{ $eq->degree_course_name }} ({{ $eq->degree_credit_hour }} cr)</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $eq->match_percentage >= 80 ? 'success' : 'warning' }}">
                                {{ number_format($eq->match_percentage, 0) }}%
                            </span>
                        </td>
                        <td class="text-center">
                            @if($eq->is_eligible)
                                <i class="fas fa-check-circle text-success fa-lg" title="Eligible"></i>
                            @else
                                <i class="fas fa-times-circle text-danger fa-lg" title="Not Eligible"></i>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No course mappings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light">
        <small class="text-muted">
            <i class="fas fa-check-circle text-success"></i> Eligible = Match >=80% (student must also have grade C or above)
            <span class="mx-2">|</span>
            <i class="fas fa-times-circle text-danger"></i> Not Eligible = Match <80%
        </small>
    </div>
</div>

<!-- Activity Log -->
@if($activityLog && $activityLog->count() > 0)
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="fas fa-history me-1"></i> Activity Log</h6>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @foreach($activityLog as $log)
            <div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>{{ $log->action }}</strong>
                        <small class="text-muted">by {{ $log->user->name ?? 'System' }}</small>
                    </div>
                    <small class="text-muted">{{ $log->created_at->format('d M Y, H:i') }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
