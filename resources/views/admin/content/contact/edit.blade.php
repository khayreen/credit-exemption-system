@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Edit Contact Setting</h2>
        <p class="text-muted mb-0">{{ $contact->label }}</p>
    </div>
    <a href="{{ route('admin.content.contact.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.content.contact.update', $contact) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Key</label>
                        <input type="text" class="form-control" value="{{ $contact->key }}" disabled readonly>
                        <small class="text-muted">Key cannot be changed after creation</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Label <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
                               value="{{ old('label', $contact->label) }}" required>
                        @error('label')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Value <span class="text-danger">*</span></label>
                        <input type="text" name="value" class="form-control @error('value') is-invalid @enderror"
                               value="{{ old('value', $contact->value) }}" required>
                        @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="text" {{ old('type', $contact->type) == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="email" {{ old('type', $contact->type) == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="phone" {{ old('type', $contact->type) == 'phone' ? 'selected' : '' }}>Phone</option>
                            <option value="url" {{ old('type', $contact->type) == 'url' ? 'selected' : '' }}>URL</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Contact
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
