@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-clipboard-check text-primary me-2"></i>CS110 Equivalency Lists - Pending HEA Endorsement</h2>
        <p class="text-muted mb-0">Review and endorse semester updates for CS110 Diploma → Degree program course equivalencies</p>
    </div>
    <div>
        <a href="{{ route('hea.equivalency_lists.published') }}" class="btn btn-outline-primary">
            <i class="fas fa-book me-1"></i> Published Lists
        </a>
        <a href="{{ route('hea.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Dashboard
        </a>
    </div>
</div>

<!-- Endorsement Queue Overview -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Endorsement Queue Overview</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="p-3 border rounded {{ $stats['total_pending'] > 0 ? 'border-info bg-light' : '' }}">
                    <h2 class="mb-0 text-info">{{ $stats['total_pending'] }}</h2>
                    <p class="text-muted mb-0 small"><i class="fas fa-list me-1"></i>Total Pending Lists</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded {{ $stats['submitted'] > 0 ? 'border-warning bg-light' : '' }}">
                    <h2 class="mb-0 text-warning">{{ $stats['submitted'] }}</h2>
                    <p class="text-muted mb-0 small"><i class="fas fa-hourglass-half me-1"></i>Awaiting Review</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded {{ $stats['under_review'] > 0 ? 'border-primary bg-light' : '' }}">
                    <h2 class="mb-0 text-primary">{{ $stats['under_review'] }}</h2>
                    <p class="text-muted mb-0 small"><i class="fas fa-eye me-1"></i>Under Review</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded {{ $stats['oldest_days'] > 30 ? 'border-danger bg-light' : '' }}">
                    <h2 class="mb-0 {{ $stats['oldest_days'] > 30 ? 'text-danger' : 'text-success' }}">
                        {{ $stats['oldest_days'] }}
                        @if($stats['oldest_days'] > 30)
                            <i class="fas fa-exclamation-triangle ms-1"></i>
                        @endif
                    </h2>
                    <p class="text-muted mb-0 small"><i class="fas fa-clock me-1"></i>Oldest (days)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('hea.equivalency_lists.pending') }}" class="row g-3">
            <div class="col-md-3">
                <label for="program" class="form-label small text-muted">Target Program</label>
                <select name="program" id="program" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="all" {{ $programFilter === 'all' ? 'selected' : '' }}>All Programs</option>
                    @foreach($programs as $code => $name)
                        <option value="{{ $code }}" {{ $programFilter === $code ? 'selected' : '' }}>
                            {{ $code }} - {{ Str::limit($name, 30) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="semester" class="form-label small text-muted">Semester</label>
                <select name="semester" id="semester" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="all" {{ $semesterFilter === 'all' ? 'selected' : '' }}>All Semesters</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester }}" {{ $semesterFilter === $semester ? 'selected' : '' }}>
                            {{ $semester }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label small text-muted">Status</label>
                <select name="status" id="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="submitted" {{ $statusFilter === 'submitted' ? 'selected' : '' }}>Submitted (Awaiting Review)</option>
                    <option value="under_review" {{ $statusFilter === 'under_review' ? 'selected' : '' }}>Under Review</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="sort" class="form-label small text-muted">Sort By</label>
                <select name="sort" id="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="oldest" {{ $sortBy === 'oldest' ? 'selected' : '' }}>Oldest First (Priority)</option>
                    <option value="newest" {{ $sortBy === 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="program" {{ $sortBy === 'program' ? 'selected' : '' }}>By Program Code</option>
                </select>
            </div>
        </form>
    </div>
</div>

@if($stats['total_pending'] === 0)
<!-- Empty State -->
<div class="card shadow-sm">
    <div class="card-body text-center py-5">
        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
        <h4>All Caught Up!</h4>
        <p class="text-muted mb-4">There are no CS110 equivalency lists waiting for endorsement.<br>All submitted lists have been reviewed and processed.</p>
        <a href="{{ route('hea.equivalency_lists.published') }}" class="btn btn-outline-success me-2">
            <i class="fas fa-book me-1"></i> View Published Lists
        </a>
        <a href="{{ route('hea.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-chart-line me-1"></i> View Statistics
        </a>
    </div>
</div>
@else

<!-- Lists Grouped by Degree Program -->
<div class="accordion" id="programAccordion">
    @foreach($programs as $programCode => $programName)
        @php
            $programLists = $listsByProgram->get($programCode, collect());
            $isExpanded = $programLists->count() > 0 && ($programFilter === $programCode || $programFilter === 'all');
        @endphp

        @if($programLists->count() > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white" id="heading{{ $programCode }}">
                <h5 class="mb-0">
                    <button class="btn btn-link text-decoration-none w-100 text-start d-flex justify-content-between align-items-center {{ $isExpanded ? '' : 'collapsed' }}"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $programCode }}"
                            aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $programCode }}">
                        <span>
                            <i class="fas fa-graduation-cap text-primary me-2"></i>
                            <strong>{{ $programCode }}</strong> - {{ $programName }}
                        </span>
                        <span class="badge bg-primary rounded-pill">{{ $programLists->count() }} pending</span>
                    </button>
                </h5>
            </div>

            <div id="collapse{{ $programCode }}"
                 class="collapse {{ $isExpanded ? 'show' : '' }}"
                 aria-labelledby="heading{{ $programCode }}"
                 data-bs-parent="#programAccordion">
                <div class="card-body p-0">
                    @foreach($programLists as $list)
                    @php
                        $daysWaiting = $list->submitted_at ? now()->diffInDays($list->submitted_at) : 0;
                        $urgencyClass = $daysWaiting > 30 ? 'danger' : ($daysWaiting > 15 ? 'warning' : 'success');
                        $urgencyIcon = $daysWaiting > 30 ? 'exclamation-triangle' : ($daysWaiting > 15 ? 'clock' : 'check-circle');
                    @endphp

                    <div class="border-bottom p-4 {{ $loop->last ? '' : 'border-bottom' }}">
                        <!-- List Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1">
                                    <span class="badge bg-{{ $urgencyClass }} me-2">
                                        <i class="fas fa-{{ $urgencyIcon }} me-1"></i>
                                        @if($list->status === 'submitted')
                                            NEW
                                        @else
                                            REVIEW
                                        @endif
                                    </span>
                                    {{ $list->semester }}
                                </h5>
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>Submitted by: <strong>{{ $list->creator->name ?? 'Unknown' }}</strong>
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-calendar me-1"></i>{{ $list->submitted_at?->format('d M Y') }}
                                    <span class="text-{{ $urgencyClass }}">
                                        ({{ $daysWaiting }} {{ Str::plural('day', $daysWaiting) }} ago)
                                    </span>
                                </small>
                                @if($list->reviewer)
                                <div class="mt-1">
                                    <small class="text-info">
                                        <i class="fas fa-eye me-1"></i>Currently reviewed by: <strong>{{ $list->reviewer->name }}</strong>
                                    </small>
                                </div>
                                @endif
                            </div>
                            <span class="badge bg-{{ $list->status === 'submitted' ? 'warning' : 'primary' }} fs-6">
                                {{ $list->status_display }}
                            </span>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="me-4">
                                        <i class="fas fa-list-ul text-muted me-1"></i>
                                        <strong>{{ $list->total_equivalencies }}</strong>
                                        <small class="text-muted">total mappings</small>
                                    </div>
                                    <div class="me-4">
                                        <i class="fas fa-check-circle text-success me-1"></i>
                                        <strong>{{ $list->eligible_count }}</strong>
                                        <small class="text-muted">eligible ({{ $list->total_equivalencies > 0 ? number_format(($list->eligible_count / $list->total_equivalencies) * 100, 1) : 0 }}%)</small>
                                    </div>
                                    <div>
                                        <i class="fas fa-times-circle text-danger me-1"></i>
                                        <strong>{{ $list->not_eligible_count }}</strong>
                                        <small class="text-muted">not eligible</small>
                                    </div>
                                </div>
                                <!-- Eligibility Progress Bar -->
                                @if($list->total_equivalencies > 0)
                                <div class="progress mt-2" style="height: 8px;">
                                    <div class="progress-bar bg-success"
                                         role="progressbar"
                                         style="width: {{ ($list->eligible_count / $list->total_equivalencies) * 100 }}%"
                                         aria-valuenow="{{ $list->eligible_count }}"
                                         aria-valuemin="0"
                                         aria-valuemax="{{ $list->total_equivalencies }}">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Changes from Previous Semester -->
                        @if($list->changes)
                        <div class="alert alert-info mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exchange-alt fa-2x text-info me-3"></i>
                                <div class="flex-grow-1">
                                    <strong><i class="fas fa-history me-1"></i>Changes from Previous Semester</strong>
                                    @if($list->previousList)
                                        <small class="text-muted">({{ $list->previousList->semester }})</small>
                                    @endif
                                    <div class="mt-2">
                                        @if($list->changes['added_count'] > 0)
                                        <span class="badge bg-success me-2">
                                            <i class="fas fa-plus me-1"></i>{{ $list->changes['added_count'] }} New
                                        </span>
                                        @endif
                                        @if($list->changes['modified_count'] > 0)
                                        <span class="badge bg-warning text-dark me-2">
                                            <i class="fas fa-edit me-1"></i>{{ $list->changes['modified_count'] }} Modified
                                        </span>
                                        @endif
                                        @if($list->changes['removed_count'] > 0)
                                        <span class="badge bg-danger me-2">
                                            <i class="fas fa-minus me-1"></i>{{ $list->changes['removed_count'] }} Removed
                                        </span>
                                        @endif
                                        @if($list->changes['added_count'] === 0 && $list->changes['modified_count'] === 0 && $list->changes['removed_count'] === 0)
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-equals me-1"></i>No changes from previous semester
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-secondary mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>First Submission:</strong> No previous semester list available for comparison.
                        </div>
                        @endif

                        <!-- Submission Notes -->
                        @if($list->submission_notes)
                        <div class="alert alert-light border mb-3">
                            <strong><i class="fas fa-comment-dots me-1"></i>Submission Notes:</strong>
                            <div class="mt-1">{{ $list->submission_notes }}</div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('hea.equivalency_lists.review', $list) }}"
                               class="btn btn-primary">
                                <i class="fas fa-clipboard-check me-1"></i> Review Details
                            </a>
                            <button type="button"
                                    class="btn btn-success"
                                    data-bs-toggle="modal"
                                    data-bs-target="#quickEndorseModal{{ $list->id }}">
                                <i class="fas fa-check me-1"></i> Quick Endorse
                            </button>
                            <button type="button"
                                    class="btn btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#quickRejectModal{{ $list->id }}">
                                <i class="fas fa-times me-1"></i> Reject
                            </button>
                        </div>
                    </div>

                    <!-- Quick Endorse Modal -->
                    <div class="modal fade" id="quickEndorseModal{{ $list->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('hea.equivalency_lists.endorse', $list) }}" method="POST">
                                    @csrf
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-check-circle me-1"></i> Quick Endorse & Publish
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-success">
                                            <i class="fas fa-info-circle me-1"></i>
                                            You are about to endorse and publish this list.
                                        </div>
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <td class="text-muted">Program:</td>
                                                <td><strong>{{ $list->program_code }}</strong></td>
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
                                                <td class="text-muted">Eligible:</td>
                                                <td>{{ $list->eligible_count }} ({{ $list->total_equivalencies > 0 ? number_format(($list->eligible_count / $list->total_equivalencies) * 100, 1) : 0 }}%)</td>
                                            </tr>
                                        </table>
                                        <div class="mb-3">
                                            <label class="form-label">Endorsement Notes (Optional)</label>
                                            <textarea name="endorsement_notes" class="form-control" rows="2"></textarea>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="confirm" value="1" required>
                                            <label class="form-check-label">
                                                I confirm I have reviewed this list and endorse it for publication.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check me-1"></i> Endorse & Publish
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Reject Modal -->
                    <div class="modal fade" id="quickRejectModal{{ $list->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('hea.equivalency_lists.reject', $list) }}" method="POST">
                                    @csrf
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-times-circle me-1"></i> Reject List
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            This list will be returned to the Resource Person for revision.
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                            <textarea name="review_notes" class="form-control" rows="4" required minlength="10" placeholder="Please explain what needs to be corrected..."></textarea>
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
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>

@endif

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

@endsection
