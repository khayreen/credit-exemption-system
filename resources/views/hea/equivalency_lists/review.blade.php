@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-clipboard-check text-primary me-2"></i>Review Equivalency List</h2>
    <div>
        <a href="{{ route('hea.equivalency_lists.pending') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Pending
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
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
                    @if($list->isInternal())
                        Source: CS110 - Diploma in Computer Science (UiTM)
                    @else
                        Source: {{ $list->source_institution }} (Various Diploma Programs)
                    @endif
                </small>
            </div>
            <span class="badge bg-{{ $list->status_badge_class }} fs-6">{{ $list->status_display }}</span>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" width="150">Target Program:</td>
                        <td><strong>{{ $list->program_code }}</strong> - {{ $list->program_name }}</td>
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
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" width="150">Submitted By:</td>
                        <td>{{ $list->creator->name ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Submitted On:</td>
                        <td>{{ $list->submitted_at?->format('d M Y, H:i') }}</td>
                    </tr>
                    @if($list->reviewer)
                    <tr>
                        <td class="text-muted">Reviewing:</td>
                        <td>{{ $list->reviewer->name }}</td>
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

        @if($list->isInternal())
        <div class="alert alert-primary mt-3 mb-0">
            <i class="fas fa-info-circle me-1"></i>
            <strong>Internal List:</strong> This list maps courses from UiTM's own CS110 Diploma in Computer Science program to the {{ $list->program_code }} degree program. CS110 students typically qualify for the most credit exemptions due to high curriculum similarity.
        </div>
        @else
        <div class="alert alert-success mt-3 mb-0">
            <i class="fas fa-info-circle me-1"></i>
            <strong>External List:</strong> This list maps diploma courses from {{ $list->source_institution }} to the UiTM {{ $list->program_code }} degree program (external credit transfer).
        </div>
        @endif
    </div>
</div>

<!-- Statistics Card -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center border-primary">
            <div class="card-body py-3">
                <h3 class="mb-0 text-primary">{{ $list->total_equivalencies }}</h3>
                <small class="text-muted">Total Mappings</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-success">
            <div class="card-body py-3">
                <h3 class="mb-0 text-success">{{ $list->eligible_count }}</h3>
                <small class="text-muted">Eligible (>=80%)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-warning">
            <div class="card-body py-3">
                <h3 class="mb-0 text-warning">{{ $list->not_eligible_count }}</h3>
                <small class="text-muted">Not Eligible (<80%)</small>
            </div>
        </div>
    </div>
    @if($changes)
    <div class="col-md-3">
        <div class="card text-center border-info">
            <div class="card-body py-3">
                <h3 class="mb-0 text-info">{{ $changes['added_count'] + $changes['modified_count'] + $changes['removed_count'] }}</h3>
                <small class="text-muted">Changes from Previous</small>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Changes from Previous Semester (if exists) -->
@if($changes && ($changes['added_count'] > 0 || $changes['modified_count'] > 0 || $changes['removed_count'] > 0))
<div class="card shadow-sm mb-4">
    <div class="card-header bg-info text-white">
        <h6 class="mb-0"><i class="fas fa-exchange-alt me-1"></i> Changes from Previous Semester ({{ $previousList->semester }})</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2">+{{ $changes['added_count'] }}</span>
                    <span>New mappings added</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-warning text-dark me-2">~{{ $changes['modified_count'] }}</span>
                    <span>Match % updated</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-danger me-2">-{{ $changes['removed_count'] }}</span>
                    <span>Mappings removed</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

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
                        <th>{{ $list->isInternal() ? 'CS110' : $list->source_institution }} Diploma Course</th>
                        <th>{{ $list->program_code }} Degree Course</th>
                        <th width="80" class="text-center">Match %</th>
                        <th width="100" class="text-center">Eligible</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list->courseEquivalencies as $index => $eq)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $eq->diploma_course_code }}</div>
                            <small class="text-muted">{{ $eq->diploma_course_name }} ({{ $eq->diploma_credit_hour }} cr)</small>
                            @if($changes && in_array($eq, $changes['added']))
                                <span class="badge bg-success ms-1">NEW</span>
                            @endif
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
<div class="card shadow-sm mb-4">
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

<!-- Decision Section -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="fas fa-gavel me-1"></i> HEA Decision</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Endorse & Publish -->
            <div class="col-md-6">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-check-circle me-1"></i> Endorse & Publish</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">
                            Endorsing this list will:
                            <ul class="small text-muted">
                                <li>Mark the list as officially endorsed by HEA</li>
                                <li>Publish it for student reference</li>
                                <li>Archive any previous active list for this program/source</li>
                            </ul>
                        </p>
                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#endorseModal">
                            <i class="fas fa-check me-1"></i> Endorse & Publish
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reject & Return -->
            <div class="col-md-6">
                <div class="card border-danger h-100">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="fas fa-times-circle me-1"></i> Reject & Request Changes</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">
                            Rejecting this list will:
                            <ul class="small text-muted">
                                <li>Return the list to the Resource Person</li>
                                <li>Require them to make revisions</li>
                                <li>Allow resubmission after corrections</li>
                            </ul>
                        </p>
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times me-1"></i> Reject & Request Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Endorse Modal -->
<div class="modal fade" id="endorseModal" tabindex="-1" aria-labelledby="endorseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hea.equivalency_lists.endorse', $list) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="endorseModalLabel">
                        <i class="fas fa-check-circle me-1"></i> Endorse & Publish Equivalency List
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        You are about to endorse and publish this equivalency list.
                    </div>

                    <table class="table table-sm table-bordered">
                        <tr>
                            <td class="text-muted">Category:</td>
                            <td>{{ $list->isInternal() ? 'CS110 (UiTM Diploma)' : 'External ('.$list->source_institution.')' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Target Program:</td>
                            <td>{{ $list->program_code }} - {{ $list->program_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Semester:</td>
                            <td>{{ $list->semester }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Mappings:</td>
                            <td>{{ $list->total_equivalencies }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Eligible Courses:</td>
                            <td>{{ $list->eligible_count }}</td>
                        </tr>
                    </table>

                    <div class="mb-3">
                        <label for="endorsement_notes" class="form-label">Endorsement Notes (Optional)</label>
                        <textarea name="endorsement_notes" id="endorsement_notes" class="form-control" rows="3" placeholder="Add any notes about this endorsement..."></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="confirm" id="confirmEndorse" value="1" required>
                        <label class="form-check-label" for="confirmEndorse">
                            I confirm that I have reviewed all equivalency mappings and endorse this list for publication.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Endorse & Publish Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hea.equivalency_lists.reject', $list) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-times-circle me-1"></i> Reject & Request Changes
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        This list will be returned to the Resource Person for revision.
                    </div>

                    <table class="table table-sm table-bordered">
                        <tr>
                            <td class="text-muted">Category:</td>
                            <td>{{ $list->isInternal() ? 'CS110 (UiTM Diploma)' : 'External ('.$list->source_institution.')' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Target Program:</td>
                            <td>{{ $list->program_code }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Submitted By:</td>
                            <td>{{ $list->creator->name ?? 'Unknown' }}</td>
                        </tr>
                    </table>

                    <div class="mb-3">
                        <label for="review_notes" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="review_notes" id="review_notes" class="form-control" rows="4" required minlength="10" placeholder="Please explain what needs to be corrected or improved..."></textarea>
                        <small class="text-muted">Minimum 10 characters. This will be visible to the Resource Person.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i> Reject & Return
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
