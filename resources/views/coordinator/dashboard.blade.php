@extends('layouts.app')

@section('content')
<h2 class="mb-4">Coordinator Dashboard</h2>

<!-- Stat Cards -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-blue"><i class="fas fa-tasks"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['pending_equivalency'] }}</h5>
                    <p class="card-text text-muted">Applications Pending Equivalency</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-orange"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['pending_resource_person'] }}</h5>
                    <p class="card-text text-muted">Pending Resource Person</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('coordinator.course_equivalencies.view') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-eye"></i> View Course Equivalencies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Applications Table -->
<div class="card shadow-sm">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0">Applications Awaiting Your Review</h5>
    </div>
    <div class="card-body">
        @if($applications->isEmpty())
            <p class="text-center text-muted">There are no applications currently pending your review.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Reviewed On</th>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                        <tr>
                            <td>{{ $app->updated_at->format('d M Y, h:i A') }}</td>
                            <td>{{ $app->student->user->name }}</td>
                            <td>{{ $app->matric_no }}</td>
                            <td><span class="badge bg-primary">{{ $app->status }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('coordinator.application.show', $app) }}" class="btn btn-sm btn-primary">Manage Equivalencies</a>
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
