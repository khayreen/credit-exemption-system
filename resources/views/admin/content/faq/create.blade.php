@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Add FAQ Item</h2>
        <p class="text-muted mb-0">Create a new frequently asked question</p>
    </div>
    <a href="{{ route('admin.content.faq.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.content.faq.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Question <span class="text-danger">*</span></label>
                        <textarea name="question" class="form-control @error('question') is-invalid @enderror" rows="2" required>{{ old('question') }}</textarea>
                        @error('question')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Answer <span class="text-danger">*</span></label>
                        <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" rows="6" required>{{ old('answer') }}</textarea>
                        @error('answer')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
                               value="{{ old('category') }}" list="categoryList" placeholder="e.g., General, Application">
                        <datalist id="categoryList">
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                        @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                        <small class="text-muted">Lower numbers appear first</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Save FAQ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
