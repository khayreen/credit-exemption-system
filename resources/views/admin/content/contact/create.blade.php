@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Add Contact Setting</h2>
        <p class="text-muted mb-0">Create new contact information entry</p>
    </div>
    <a href="{{ route('admin.content.contact.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.content.contact.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Key <span class="text-danger">*</span></label>
                        <input type="text" name="key" class="form-control @error('key') is-invalid @enderror"
                               value="{{ old('key') }}" placeholder="e.g., support_email, admin_phone" required>
                        @error('key')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Use snake_case for the key (e.g., support_email)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Label <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
                               value="{{ old('label') }}" placeholder="e.g., Support Email" required>
                        @error('label')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Value <span class="text-danger">*</span></label>
                        <input type="text" name="value" class="form-control @error('value') is-invalid @enderror"
                               value="{{ old('value') }}" required>
                        @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="email" {{ old('type') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="phone" {{ old('type') == 'phone' ? 'selected' : '' }}>Phone</option>
                            <option value="url" {{ old('type') == 'url' ? 'selected' : '' }}>URL</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Save Contact
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Suggested Keys</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><code>support_email</code> - General support</li>
                    <li class="mb-2"><code>admin_email</code> - Admin contact</li>
                    <li class="mb-2"><code>hea_email</code> - HEA department</li>
                    <li class="mb-2"><code>helpdesk_phone</code> - Phone support</li>
                    <li class="mb-2"><code>office_hours</code> - Working hours</li>
                    <li><code>academic_calendar_url</code> - Calendar link</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
