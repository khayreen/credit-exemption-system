@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-file-pdf me-2 text-danger"></i>Degree Course Syllabi</h2>
            <p class="text-muted mb-0">Manage UiTM degree course syllabi for comparison</p>
        </div>
        <a href="{{ route('resource_person.syllabi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Upload New Syllabus
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-file-pdf fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        <p class="text-muted mb-0">Total Syllabi</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['active'] }}</h3>
                        <p class="text-muted mb-0">Active Syllabi</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="fas fa-graduation-cap fa-2x text-info"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['programs'] }}</h3>
                        <p class="text-muted mb-0">Programs Covered</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('resource_person.syllabi.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Course code or name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Program</label>
                    <select name="program_code" class="form-select">
                        <option value="">All Programs</option>
                        @foreach($programCodes as $code)
                            <option value="{{ $code }}" {{ request('program_code') == $code ? 'selected' : '' }}>{{ $code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Syllabi Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($syllabi->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No syllabi found</h5>
                    <p class="text-muted mb-3">Upload your first degree course syllabus to get started.</p>
                    <a href="{{ route('resource_person.syllabi.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Upload Syllabus
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">Course Code</th>
                                <th class="py-3">Course Name</th>
                                <th class="py-3 text-center">Credits</th>
                                <th class="py-3">Program</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3">Uploaded</th>
                                <th class="py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($syllabi as $syllabus)
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-primary">{{ $syllabus->course_code }}</span>
                                    </td>
                                    <td class="py-3">
                                        <strong>{{ $syllabus->course_name }}</strong>
                                        @if($syllabus->description)
                                            <br><small class="text-muted">{{ Str::limit($syllabus->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">{{ $syllabus->credit_hours }}</td>
                                    <td class="py-3">
                                        <span class="badge bg-secondary">{{ $syllabus->program_code }}</span>
                                    </td>
                                    <td class="py-3 text-center">
                                        @if($syllabus->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <small>{{ $syllabus->created_at->format('d M Y') }}</small>
                                        @if($syllabus->uploader)
                                            <br><small class="text-muted">by {{ $syllabus->uploader->name }}</small>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('resource_person.syllabi.view_pdf', $syllabus) }}" class="btn btn-outline-danger" target="_blank" title="View PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <a href="{{ route('resource_person.syllabi.edit', $syllabus) }}" class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" title="Delete" onclick="confirmDelete('{{ $syllabus->id }}', '{{ $syllabus->course_code }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $syllabus->id }}" action="{{ route('resource_person.syllabi.destroy', $syllabus) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($syllabi->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $syllabi->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, courseCode) {
    if (confirm(`Are you sure you want to delete the syllabus for ${courseCode}? This action cannot be undone.`)) {
        document.getElementById(`delete-form-${id}`).submit();
    }
}
</script>
@endpush
@endsection
