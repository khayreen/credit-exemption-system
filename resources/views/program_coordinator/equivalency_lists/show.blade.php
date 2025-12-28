@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                @if($list->category === 'internal')
                    <i class="fas fa-building text-primary me-2"></i>
                @else
                    <i class="fas fa-university text-info me-2"></i>
                @endif
                {{ $list->program_code }} Equivalency List
            </h2>
            <p class="text-muted mb-0">
                {{ $list->semester }}
                @if($list->category === 'external')
                    | {{ $list->source_institution }}
                @else
                    | UiTM CS110 (Internal)
                @endif
            </p>
        </div>
        <div>
            <a href="{{ Auth::user()->role == 'academic_advisor' ? route('academic_advisor.equivalency_lists.index') : route('program_coordinator.equivalency_lists.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            @if(!isset($isReadOnly) && $list->status === 'draft')
                <a href="{{ route('program_coordinator.equivalency_lists.edit', $list) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Edit List
                </a>
                <form action="{{ route('program_coordinator.equivalency_lists.publish', $list) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to publish this list? Once published, you cannot edit the mappings.');">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-rocket"></i> Publish List
                    </button>
                </form>
            @elseif($list->status === 'published' && $list->is_active)
                <span class="badge bg-success fs-6 px-3 py-2">
                    <i class="fas fa-check-circle"></i> PUBLISHED & ACTIVE
                </span>
            @elseif($list->status === 'published')
                <span class="badge bg-info fs-6 px-3 py-2">
                    <i class="fas fa-check-circle"></i> PUBLISHED
                </span>
            @elseif($list->status === 'archived')
                <span class="badge bg-secondary fs-6 px-3 py-2">
                    <i class="fas fa-archive"></i> ARCHIVED
                </span>
            @endif
        </div>
    </div>

    <!-- List Information Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>List Information</h5>
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
                            <td>
                                @if($list->status === 'draft')
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @elseif($list->status === 'published')
                                    <span class="badge bg-success">Published</span>
                                @elseif($list->status === 'archived')
                                    <span class="badge bg-secondary">Archived</span>
                                @endif
                            </td>
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
                        <tr>
                            <th>Active List:</th>
                            <td>
                                @if($list->is_active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Yes
                                    </span>
                                @else
                                    <span class="text-muted">No</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Mappings -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-exchange-alt me-2"></i>Course Equivalency Mappings
            </h5>
            @if(!isset($isReadOnly) && $list->status === 'draft')
                <a href="{{ route('program_coordinator.equivalency_lists.edit', $list) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i> Add Mappings
                </a>
            @endif
        </div>
        <div class="card-body p-0">
            @if($list->courseEquivalencies->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Course Mappings</h5>
                    <p class="text-muted mb-0">This equivalency list does not have any course mappings yet.</p>
                    @if(!isset($isReadOnly) && $list->status === 'draft')
                        <a href="{{ route('program_coordinator.equivalency_lists.edit', $list) }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-2"></i>Add Course Mappings
                        </a>
                    @endif
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

    @if($list->status === 'draft')
        <!-- Publishing Information -->
        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">
                <i class="fas fa-info-circle me-2"></i>Ready to Publish?
            </h5>
            <p class="mb-0">
                Once you have added all course mappings, you can <strong>publish this list directly</strong>.
                No HEA approval is required. Published lists will become visible to students immediately.
            </p>
            <hr>
            <p class="mb-0">
                <strong>Note:</strong> After publishing, you cannot edit the course mappings. Make sure all mappings are correct before publishing.
            </p>
        </div>
    @endif
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
