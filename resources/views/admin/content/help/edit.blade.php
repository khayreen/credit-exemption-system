@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Edit Help Article</h2>
        <p class="text-muted mb-0">{{ $article->title }}</p>
    </div>
    <a href="{{ route('admin.content.help.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<form action="{{ route('admin.content.help.update', $article) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $article->title) }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="15" required>{{ old('content', $article->content) }}</textarea>
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
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control"
                               value="{{ old('category', $article->category) }}" list="categoryList">
                        <datalist id="categoryList">
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', $article->sort_order) }}" min="0">
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive"
                                   {{ old('is_active', $article->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Article
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
