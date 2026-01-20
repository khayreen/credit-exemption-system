@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Edit Terms & Conditions</h2>
        <p class="text-muted mb-0">Version {{ $terms->version }}</p>
    </div>
    <a href="{{ route('admin.content.terms.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<form action="{{ route('admin.content.terms.update', $terms) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Content</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Terms & Conditions Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="20" required>{{ old('content', $terms->content) }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Version Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Version Number <span class="text-danger">*</span></label>
                        <input type="text" name="version" class="form-control @error('version') is-invalid @enderror"
                               value="{{ old('version', $terms->version) }}" required>
                        @error('version')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Effective Date <span class="text-danger">*</span></label>
                        <input type="date" name="effective_date" class="form-control @error('effective_date') is-invalid @enderror"
                               value="{{ old('effective_date', $terms->effective_date->format('Y-m-d')) }}" required>
                        @error('effective_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong>
                        @if($terms->is_current)
                        <span class="badge bg-success">Current Active Version</span>
                        @else
                        <span class="badge bg-secondary">Archived</span>
                        @endif
                    </div>

                    <hr>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Version
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
