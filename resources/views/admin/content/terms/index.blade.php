@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Terms & Conditions</h2>
        <p class="text-muted mb-0">Manage terms and conditions versions</p>
    </div>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <a href="{{ route('admin.content.terms.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Version
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Current Version -->
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Current Active Version</h5>
    </div>
    <div class="card-body">
        @if($currentTerms)
        <div class="row">
            <div class="col-md-3">
                <strong>Version:</strong> {{ $currentTerms->version }}
            </div>
            <div class="col-md-3">
                <strong>Effective Date:</strong> {{ $currentTerms->effective_date->format('M d, Y') }}
            </div>
            <div class="col-md-3">
                <strong>Last Updated:</strong> {{ $currentTerms->updated_at->format('M d, Y H:i') }}
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ route('admin.content.terms.edit', $currentTerms) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
            </div>
        </div>
        <hr>
        <div class="terms-preview" style="max-height: 300px; overflow-y: auto;">
            {!! nl2br(e(Str::limit($currentTerms->content, 1000))) !!}
            @if(strlen($currentTerms->content) > 1000)
            <p class="text-muted mt-2"><em>... content truncated for preview</em></p>
            @endif
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
            <p class="text-muted">No active Terms & Conditions version. Create one to get started.</p>
            <a href="{{ route('admin.content.terms.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create First Version
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Version History -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Version History</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Version</th>
                        <th>Effective Date</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($termsHistory as $terms)
                    <tr>
                        <td><strong>{{ $terms->version }}</strong></td>
                        <td>{{ $terms->effective_date->format('M d, Y') }}</td>
                        <td>
                            @if($terms->is_current)
                            <span class="badge bg-success">Current</span>
                            @else
                            <span class="badge bg-secondary">Archived</span>
                            @endif
                        </td>
                        <td>{{ $terms->creator->name ?? 'System' }}</td>
                        <td><small>{{ $terms->created_at->format('M d, Y H:i') }}</small></td>
                        <td>
                            <a href="{{ route('admin.content.terms.edit', $terms) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!$terms->is_current)
                            <form action="{{ route('admin.content.terms.set-current', $terms) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Set this as the current version?')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.content.terms.destroy', $terms) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this version?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No version history</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
