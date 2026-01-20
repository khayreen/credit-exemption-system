@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Contact Settings</h2>
        <p class="text-muted mb-0">Manage contact information displayed across the system</p>
    </div>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <a href="{{ route('admin.content.contact.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Contact
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
                        <th>Key</th>
                        <th>Label</th>
                        <th>Value</th>
                        <th>Type</th>
                        <th>Last Updated</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr>
                        <td><code>{{ $contact->key }}</code></td>
                        <td>{{ $contact->label }}</td>
                        <td>
                            @if($contact->type === 'email')
                            <a href="mailto:{{ $contact->value }}">{{ $contact->value }}</a>
                            @elseif($contact->type === 'url')
                            <a href="{{ $contact->value }}" target="_blank">{{ Str::limit($contact->value, 40) }}</a>
                            @elseif($contact->type === 'phone')
                            <a href="tel:{{ $contact->value }}">{{ $contact->value }}</a>
                            @else
                            {{ $contact->value }}
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ ucfirst($contact->type) }}</span></td>
                        <td><small>{{ $contact->updated_at->format('M d, Y H:i') }}</small></td>
                        <td>
                            <a href="{{ route('admin.content.contact.edit', $contact) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.content.contact.destroy', $contact) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this contact setting?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No contact settings found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-code me-2"></i>Usage in Templates</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Use the following code in your Blade templates to display contact information:</p>
        <pre class="bg-light p-3 rounded"><code>{{ "{{ App\\Models\\ContactSetting::getValue('support_email', 'default@example.com') }}" }}</code></pre>
    </div>
</div>
@endsection
