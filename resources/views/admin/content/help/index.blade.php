@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Help Articles</h2>
        <p class="text-muted mb-0">Manage help and support content</p>
    </div>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <a href="{{ route('admin.content.help.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Article
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">Order</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $article->sort_order }}</span></td>
                        <td>
                            <strong>{{ $article->title }}</strong>
                            <br><small class="text-muted">{{ Str::limit(strip_tags($article->content), 60) }}</small>
                        </td>
                        <td>
                            @if($article->category)
                            <span class="badge bg-info">{{ $article->category }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($article->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td><small>{{ $article->created_at->format('M d, Y') }}</small></td>
                        <td>
                            <a href="{{ route('admin.content.help.edit', $article) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.content.help.destroy', $article) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this article?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No help articles found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($articles->hasPages())
    <div class="card-footer">
        {{ $articles->links() }}
    </div>
    @endif
</div>
@endsection
