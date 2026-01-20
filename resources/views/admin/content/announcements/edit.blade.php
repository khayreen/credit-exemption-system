@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Edit Announcement</h2>
        <p class="text-muted mb-0">{{ $announcement->title }}</p>
    </div>
    <a href="{{ route('admin.content.announcements.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<form action="{{ route('admin.content.announcements.update', $announcement) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Announcement Content</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $announcement->title) }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content', $announcement->content) }}</textarea>
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
                    <h5 class="mb-0">Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="info" {{ old('type', $announcement->type) == 'info' ? 'selected' : '' }}>Info (Blue)</option>
                            <option value="success" {{ old('type', $announcement->type) == 'success' ? 'selected' : '' }}>Success (Green)</option>
                            <option value="warning" {{ old('type', $announcement->type) == 'warning' ? 'selected' : '' }}>Warning (Yellow)</option>
                            <option value="danger" {{ old('type', $announcement->type) == 'danger' ? 'selected' : '' }}>Danger (Red)</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Target Roles</label>
                        @foreach($roles as $value => $label)
                        <div class="form-check">
                            <input type="checkbox" name="target_roles[]" value="{{ $value }}"
                                   class="form-check-input" id="role_{{ $value }}"
                                   {{ in_array($value, old('target_roles', $announcement->target_roles ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_{{ $value }}">{{ $label }}</label>
                        </div>
                        @endforeach
                        <small class="text-muted">Leave unchecked to show to all users</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" name="starts_at" class="form-control"
                               value="{{ old('starts_at', $announcement->starts_at?->format('Y-m-d\TH:i')) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" name="ends_at" class="form-control"
                               value="{{ old('ends_at', $announcement->ends_at?->format('Y-m-d\TH:i')) }}">
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_dismissible" value="1" class="form-check-input" id="isDismissible"
                                   {{ old('is_dismissible', $announcement->is_dismissible) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isDismissible">Allow users to dismiss</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive"
                                   {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Announcement
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
