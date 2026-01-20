@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Create Terms & Conditions Version</h2>
        <p class="text-muted mb-0">Add a new version of terms and conditions</p>
    </div>
    <a href="{{ route('admin.content.terms.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<form action="{{ route('admin.content.terms.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Content</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Terms & Conditions Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="20" required>{{ old('content', $currentTerms->content ?? '') }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">You can copy from the existing version and modify as needed.</small>
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
                               value="{{ old('version', $nextVersion) }}" required>
                        @error('version')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">e.g., 1.0, 1.1, 2.0</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Effective Date <span class="text-danger">*</span></label>
                        <input type="date" name="effective_date" class="form-control @error('effective_date') is-invalid @enderror"
                               value="{{ old('effective_date', now()->format('Y-m-d')) }}" required>
                        @error('effective_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="set_as_current" value="1" class="form-check-input" id="setAsCurrent" checked>
                            <label class="form-check-label" for="setAsCurrent">
                                Set as current active version
                            </label>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Create Version
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
