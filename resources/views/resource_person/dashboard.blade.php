@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Resource Person Dashboard</h2>
        @if(!empty($stats['assigned_programs']))
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-graduation-cap me-1"></i>
                <strong>Assigned Programs:</strong>
                @foreach($stats['assigned_programs'] as $index => $program)
                    <span class="badge bg-primary">{{ $program }}</span>{{ $index < count($stats['assigned_programs']) - 1 ? ', ' : '' }}
                @endforeach
            </p>
        @endif
    </div>
    <a href="{{ route('resource_person.course_equivalencies.manage') }}" class="btn btn-success">
        <i class="fas fa-exchange-alt"></i> Manage Course Equivalencies
    </a>
</div>

<!-- Stat Cards -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-orange"><i class="fas fa-book-open"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['subjects_for_review'] }}</h5>
                    <p class="card-text text-muted">Subjects for Review</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-blue"><i class="fas fa-paper-plane"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['syllabus_requests'] }}</h5>
                    <p class="card-text text-muted">Syllabus Requests Sent</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Subjects Table -->
<div class="card shadow-sm">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0">Subjects Pending Your Expertise</h5>
    </div>
    <div class="card-body">
        @if($subjects->isEmpty())
            <p class="text-center text-muted">There are no subjects currently pending your review.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Diploma Course Details</th>
                            <th>Student Name</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                        <tr>
                            <td>
                                <strong>{{ $subject->course_code }}</strong> - {{ $subject->course_name }}<br>
                                <small class="text-muted">Institution: {{ $subject->exemptionApplication->previous_institution }}</small>
                            </td>
                            <td>
                                {{ $subject->exemptionApplication->student->user->name }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('resource_person.subject.review', $subject) }}" class="btn btn-sm btn-primary">Review Subject</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
