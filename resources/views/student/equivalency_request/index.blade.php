@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2"><i class="fas fa-list me-2"></i>My Course Equivalency Requests</h2>
                    <p class="text-muted">Track the status of your equivalency requests</p>
                </div>
                <a href="{{ route('student.equivalency.request.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>New Request
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Requests List -->
    <div class="row">
        <div class="col-12">
            @if($requests->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Diploma Course</th>
                                        <th>Institution</th>
                                        <th>Suggested Degree Course</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>
                                                <strong>{{ $request->diploma_course_code }}</strong><br>
                                                <small class="text-muted">{{ $request->diploma_course_name }}</small>
                                            </td>
                                            <td>{{ $request->diploma_institution }}</td>
                                            <td>
                                                <strong>{{ $request->suggested_degree_course_code }}</strong><br>
                                                <small class="text-muted">{{ $request->suggested_degree_course_name }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'pending' => 'bg-warning text-dark',
                                                        'under_review' => 'bg-info',
                                                        'syllabus_received' => 'bg-info',
                                                        'approved' => 'bg-success',
                                                        'rejected' => 'bg-secondary'
                                                    ][$request->status] ?? 'bg-secondary';

                                                    $statusLabel = match($request->status) {
                                                        'approved' => 'EQUIVALENT',
                                                        'rejected' => 'NOT EQUIVALENT',
                                                        'syllabus_received' => 'UNDER REVIEW',
                                                        default => strtoupper(str_replace('_', ' ', $request->status))
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                            <td>{{ $request->created_at->format('d M Y') }}</td>
                                            <td>
                                                <a href="{{ route('student.equivalency.request.show', $request->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5>No Equivalency Requests Yet</h5>
                        <p class="text-muted">You haven't submitted any course equivalency requests.</p>
                        <a href="{{ route('student.equivalency.request.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus-circle me-2"></i>Submit Your First Request
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
