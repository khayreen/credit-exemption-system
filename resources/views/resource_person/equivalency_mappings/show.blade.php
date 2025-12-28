@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Forwarded Mapping Details</h2>
                    <p class="text-muted mb-0 mt-2">
                        <i class="fas fa-paper-plane me-1"></i>
                        View your forwarded course mapping
                    </p>
                </div>
                <div>
                    <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Status Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-{{ $mapping->status_color }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-{{ $mapping->status === 'pending' ? 'clock' : ($mapping->status === 'added' ? 'check-circle' : 'times-circle') }} me-2"></i>
                            {{ $mapping->status_label }}
                        </h5>
                        <span class="badge bg-light text-dark fs-6">
                            {{ $mapping->program_code }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Status:</strong> {{ $mapping->status_label }}</p>
                            <p class="mb-2"><strong>Forwarded:</strong> {{ $mapping->created_at->format('d M Y, h:i A') }}</p>
                            <p class="mb-0"><strong>Program:</strong> {{ $mapping->program_code }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($mapping->status === 'added')
                                <p class="mb-2"><strong>Added By:</strong> {{ $mapping->coordinator->name ?? 'N/A' }}</p>
                                <p class="mb-0"><strong>Added On:</strong> {{ $mapping->added_at ? $mapping->added_at->format('d M Y, h:i A') : 'N/A' }}</p>
                            @elseif($mapping->status === 'rejected')
                                <p class="mb-2"><strong>Rejected By:</strong> {{ $mapping->coordinator->name ?? 'N/A' }}</p>
                                <p class="mb-0"><strong>Rejected On:</strong> {{ $mapping->updated_at->format('d M Y, h:i A') }}</p>
                            @else
                                <p class="text-muted mb-0"><i class="fas fa-clock me-1"></i>Awaiting Program Coordinator review</p>
                            @endif
                        </div>
                    </div>

                    @if($mapping->status === 'rejected' && $mapping->rejection_reason)
                        <hr>
                        <div class="alert alert-danger mb-0">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Rejection Reason</h6>
                            <p class="mb-0">{{ $mapping->rejection_reason }}</p>
                        </div>
                    @endif

                    @if($mapping->status === 'added')
                        <hr>
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Success!</strong> This mapping has been added to the official equivalency list and is now available for students.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mapping Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-exchange-alt me-2"></i>Course Mapping Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Diploma Course -->
                        <div class="col-md-5">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-graduation-cap me-2"></i>Diploma Course
                                    </h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <th width="120">Course Code:</th>
                                            <td><strong>{{ $mapping->diploma_course_code }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Course Name:</th>
                                            <td>{{ $mapping->diploma_course_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Credit Hours:</th>
                                            <td>{{ $mapping->diploma_credit_hour }}</td>
                                        </tr>
                                        <tr>
                                            <th>Institution:</th>
                                            <td>{{ $mapping->diploma_institution }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Arrow -->
                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <i class="fas fa-arrow-right fa-3x text-muted"></i>
                                <br>
                                <span class="badge bg-{{ $mapping->match_percentage >= 80 ? 'success' : 'warning' }} mt-2 fs-6">
                                    {{ number_format($mapping->match_percentage, 0) }}% Match
                                </span>
                            </div>
                        </div>

                        <!-- Degree Course -->
                        <div class="col-md-5">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="text-info mb-3">
                                        <i class="fas fa-university me-2"></i>Degree Course
                                    </h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <th width="120">Course Code:</th>
                                            <td><strong>{{ $mapping->degree_course_code }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Course Name:</th>
                                            <td>{{ $mapping->degree_course_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Credit Hours:</th>
                                            <td>{{ $mapping->degree_credit_hour }}</td>
                                        </tr>
                                        <tr>
                                            <th>Institution:</th>
                                            <td>Universiti Teknologi MARA (UiTM)</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($mapping->notes)
                        <hr>
                        <h6 class="mb-2"><i class="fas fa-sticky-note me-2"></i>Notes / Justification</h6>
                        <p class="text-muted mb-0">{{ $mapping->notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Student Request Context (if applicable) -->
            @if($mapping->courseEquivalencyRequest)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-user-graduate me-2"></i>Related Student Request
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Student:</strong> {{ $mapping->courseEquivalencyRequest->student->user->name ?? 'N/A' }}
                        </p>
                        <p class="mb-2">
                            <strong>Request Date:</strong> {{ $mapping->courseEquivalencyRequest->created_at->format('d M Y') }}
                        </p>
                        <p class="mb-0">
                            <strong>Request Status:</strong>
                            @if($mapping->courseEquivalencyRequest->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($mapping->courseEquivalencyRequest->status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ ucfirst($mapping->courseEquivalencyRequest->status) }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            @if($mapping->status === 'pending')
                <div class="card shadow-sm">
                    <div class="card-header bg-warning">
                        <h6 class="mb-0"><i class="fas fa-edit me-2"></i>Actions</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">This mapping is still pending review. You can edit or delete it before the Program Coordinator processes it.</p>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fas fa-edit me-2"></i>Edit Mapping
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-2"></i>Delete Mapping
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Modal -->
@if($mapping->status === 'pending')
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('resource_person.equivalency_mappings.update', $mapping) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit Course Mapping</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Diploma Course</h6>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Course Code</label>
                                <input type="text" name="diploma_course_code" class="form-control" value="{{ $mapping->diploma_course_code }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Course Name</label>
                                <input type="text" name="diploma_course_name" class="form-control" value="{{ $mapping->diploma_course_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Credit Hours</label>
                                <input type="number" name="diploma_credit_hour" class="form-control" value="{{ $mapping->diploma_credit_hour }}" min="1" max="10" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Institution</label>
                                <input type="text" name="diploma_institution" class="form-control" value="{{ $mapping->diploma_institution }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info mb-3">Degree Course</h6>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Course Code</label>
                                <input type="text" name="degree_course_code" class="form-control" value="{{ $mapping->degree_course_code }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Course Name</label>
                                <input type="text" name="degree_course_name" class="form-control" value="{{ $mapping->degree_course_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Credit Hours</label>
                                <input type="number" name="degree_credit_hour" class="form-control" value="{{ $mapping->degree_credit_hour }}" min="1" max="10" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Match Percentage</label>
                        <input type="number" name="match_percentage" class="form-control" value="{{ $mapping->match_percentage }}" min="0" max="100" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ $mapping->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Update Mapping
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Delete Mapping</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this course mapping?</p>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This action cannot be undone. The mapping will be permanently removed.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('resource_person.equivalency_mappings.destroy', $mapping) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Mapping
                    </button>
                </form>
            </div>
        </div>
    </div>
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
@endsection
