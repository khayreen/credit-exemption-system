@extends('layouts.app')

@section('content')
<h2 class="mb-4">Academic Advisor Dashboard</h2>

<!-- Stat Cards -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-orange"><i class="fas fa-inbox"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['pending_review'] }}</h5>
                    <p class="card-text text-muted">Applications Pending Review</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-green"><i class="fas fa-check-double"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['total_reviewed'] }}</h5>
                    <p class="card-text text-muted">Total Applications Reviewed</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-blue"><i class="fas fa-graduation-cap"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['exempted_courses'] }}</h5>
                    <p class="card-text text-muted">Courses Exempted by OCR</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-purple"><i class="fas fa-robot"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['total_courses'] }}</h5>
                    <p class="card-text text-muted">Total OCR Processed Courses</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Applications Table -->
<div class="card shadow-sm">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0">Pending Applications</h5>
    </div>
    <div class="card-body">
        @if($applications->isEmpty())
            <p class="text-center text-muted">There are no applications currently pending your review.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Submitted On</th>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                        <tr>
                            <td>{{ $app->created_at->format('d M Y, h:i A') }}</td>
                            <td>{{ $app->student->user->name }}</td>
                            <td>{{ $app->matric_no }}</td>
                            <td><span class="badge bg-warning text-dark">{{ $app->status }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('lecturer.application.show', $app) }}" class="btn btn-sm btn-primary">View Details</a>
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
