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
            <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Lists
            </a>
        </div>
    </div>

    <!-- Read-Only Notice -->
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Read-Only View:</strong> This is a published equivalency list. Resource Persons can view but cannot edit published lists. To propose new mappings, use the "Forward New Mapping" feature.
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
                                <span class="badge bg-success">Published</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Total Mappings:</th>
                            <td><strong>{{ $list->courseEquivalencies->count() }}</strong> course(s)</td>
                        </tr>
                        <tr>
                            <th>Published By:</th>
                            <td>{{ $list->publisher->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Published At:</th>
                            <td>{{ $list->published_at ? $list->published_at->format('d M Y, h:i A') : 'N/A' }}</td>
                        </tr>
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

    <!-- Course Mappings (Read-Only) -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-exchange-alt me-2"></i>Course Equivalency Mappings
            </h5>
        </div>
        <div class="card-body p-0">
            @if($list->courseEquivalencies->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Course Mappings</h5>
                    <p class="text-muted mb-0">This equivalency list does not have any course mappings.</p>
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
                                        <i class="fas fa-check-circle text-success fs-5" title="Eligible for exemption"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger fs-5" title="Not eligible"></i>
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

    <!-- Action Panel -->
    <div class="card mt-4">
        <div class="card-body">
            <h6 class="card-title">
                <i class="fas fa-lightbulb me-2"></i>Want to propose a new course mapping?
            </h6>
            <p class="mb-3">
                If you've evaluated a syllabus and identified a new course equivalency for this program, you can forward it to the Program Coordinator for review.
            </p>
            <a href="{{ route('resource_person.equivalency_mappings.create', ['program' => $list->program_code]) }}" class="btn btn-success">
                <i class="fas fa-paper-plane me-2"></i>Forward New Mapping
            </a>
        </div>
    </div>
</div>
@endsection
