@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">CS110 Equivalency List Details</h2>
            <p class="text-muted mb-0 mt-2">
                <span class="badge bg-primary">{{ $list->program_code }}</span>
                <span class="badge bg-info">{{ $list->semester }}</span>
                @if($list->published_at)
                    <span class="badge bg-success">Published</span>
                @else
                    <span class="badge bg-{{ $list->status_badge_class }}">{{ $list->status_display }}</span>
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Lists
            </a>
            @if($list->canBeEdited())
                <a href="{{ route('resource_person.equivalency_lists.edit', $list->program_code) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit List
                </a>
            @endif
        </div>
    </div>

    <!-- List Information Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>List Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p class="mb-2"><strong>Program:</strong> {{ $list->program_name }}</p>
                    <p class="mb-2"><strong>Program Code:</strong> <span class="badge bg-primary">{{ $list->program_code }}</span></p>
                    <p class="mb-2"><strong>Category:</strong> <span class="badge bg-info">Internal (CS110)</span></p>
                </div>
                <div class="col-md-4">
                    <p class="mb-2"><strong>Semester:</strong> {{ $list->semester }}</p>
                    <p class="mb-2"><strong>Academic Year:</strong> {{ $list->academic_year }}</p>
                    <p class="mb-2"><strong>Status:</strong>
                        @if($list->published_at)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-{{ $list->status_badge_class }}">{{ $list->status_display }}</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-4">
                    <p class="mb-2"><strong>Created By:</strong> {{ $list->creator->name }}</p>
                    <p class="mb-2"><strong>Created Date:</strong> {{ $list->created_at->format('d M Y') }}</p>
                    @if($list->submitted_at)
                        <p class="mb-2"><strong>Submitted:</strong> {{ $list->submitted_at->format('d M Y') }}</p>
                    @endif
                </div>
            </div>

            @if($list->submission_notes)
                <hr>
                <div class="alert alert-info mb-0">
                    <strong><i class="fas fa-comment me-2"></i>Submission Notes:</strong><br>
                    {{ $list->submission_notes }}
                </div>
            @endif

            @if($list->isEndorsed() || $list->isPublished())
                <hr>
                <div class="alert alert-success mb-0">
                    <strong><i class="fas fa-check-circle me-2"></i>Endorsed by HEA:</strong> {{ $list->endorser->name ?? 'N/A' }}<br>
                    <strong>Endorsed Date:</strong> {{ $list->endorsed_at ? $list->endorsed_at->format('d M Y, h:i A') : 'N/A' }}
                    @if($list->endorsement_notes)
                        <br><strong>Notes:</strong> {{ $list->endorsement_notes }}
                    @endif
                </div>
            @endif

            @if($list->isPublished())
                <hr>
                <div class="alert alert-success mb-0">
                    <strong><i class="fas fa-book me-2"></i>Published:</strong> {{ $list->publisher->name ?? 'N/A' }}<br>
                    <strong>Published Date:</strong> {{ $list->published_at ? $list->published_at->format('d M Y, h:i A') : 'N/A' }}<br>
                    <strong>Active:</strong> {{ $list->is_active ? 'Yes' : 'No' }}
                </div>
            @endif

            @if($list->isRejected())
                <hr>
                <div class="alert alert-danger mb-0">
                    <strong><i class="fas fa-times-circle me-2"></i>Rejected by HEA:</strong> {{ $list->reviewer->name ?? 'N/A' }}<br>
                    <strong>Rejection Date:</strong> {{ $list->reviewed_at ? $list->reviewed_at->format('d M Y, h:i A') : 'N/A' }}<br>
                    <strong>Reason:</strong> {{ $list->review_notes }}
                </div>
            @endif
        </div>
    </div>

    <!-- Course Mappings Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Course Mappings ({{ $list->total_equivalencies }})</h5>
        </div>
        <div class="card-body">
            @if($list->courseEquivalencies->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Course Mappings</h5>
                    <p class="text-muted">This list doesn't have any course mappings yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>CS110 Diploma Course</th>
                                <th>Credit</th>
                                <th>{{ $list->program_code }} Degree Course</th>
                                <th>Credit</th>
                                <th class="text-center">Match %</th>
                                <th class="text-center">Eligible</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list->courseEquivalencies->sortBy('diploma_course_code') as $equiv)
                            <tr>
                                <td>
                                    <strong>{{ $equiv->diploma_course_code }}</strong><br>
                                    <small class="text-muted">{{ $equiv->diploma_course_name }}</small>
                                </td>
                                <td>{{ $equiv->diploma_credit_hour }}</td>
                                <td>
                                    <strong>{{ $equiv->degree_course_code }}</strong><br>
                                    <small class="text-muted">{{ $equiv->degree_course_name }}</small>
                                </td>
                                <td>{{ $equiv->degree_credit_hour }}</td>
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

                <!-- Statistics -->
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h3 class="mb-0">{{ $list->total_equivalencies }}</h3>
                                <small class="text-muted">Total Mappings</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h3 class="mb-0">{{ $list->eligible_count }}</h3>
                                <small class="text-muted">Eligible Courses</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-secondary">
                            <div class="card-body text-center">
                                <h3 class="mb-0">{{ $list->not_eligible_count }}</h3>
                                <small class="text-muted">Not Eligible</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
