@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">FAQ Management</h2>
        <p class="text-muted mb-0">Manage frequently asked questions</p>
    </div>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <a href="{{ route('admin.content.faq.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add FAQ
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
                        <th>Question</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqItems as $faq)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $faq->sort_order }}</span></td>
                        <td>
                            <strong>{{ Str::limit($faq->question, 60) }}</strong>
                            <br><small class="text-muted">{{ Str::limit($faq->answer, 80) }}</small>
                        </td>
                        <td>
                            @if($faq->category)
                            <span class="badge bg-info">{{ $faq->category }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($faq->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.content.faq.edit', $faq) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.content.faq.destroy', $faq) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this FAQ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No FAQ items found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($faqItems->hasPages())
    <div class="card-footer">
        {{ $faqItems->links() }}
    </div>
    @endif
</div>
@endsection
