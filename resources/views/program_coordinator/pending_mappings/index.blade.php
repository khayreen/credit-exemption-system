@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-blue: #1e3a8a;
        --uitm-blue-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success: #059669;
        --danger: #dc2626;
        --warning: #ea580c;
        --info: #0d9488;
    }

    body { font-family: 'IBM Plex Sans', sans-serif; }
    .font-mono { font-family: 'IBM Plex Mono', monospace; }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.1) 100%);
        clip-path: polygon(100% 0, 0% 100%, 100% 100%);
    }

    .page-header h2 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header p {
        opacity: 0.9;
        margin: 0;
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .main-card-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        padding: 1.25rem 1.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .main-card-header h5 {
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .count-badge {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Custom Table */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table thead th {
        background: var(--industrial-light);
        color: var(--industrial-dark);
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .custom-table tbody tr:hover {
        background: rgba(30,58,138,0.02);
    }

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Course Info */
    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .course-name {
        color: var(--industrial-gray);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .course-meta {
        color: var(--industrial-gray);
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .course-credit {
        color: var(--info);
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Badges */
    .badge-success {
        background: var(--success);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-warning {
        background: var(--warning);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .match-badge {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.9rem;
        padding: 0.4rem 0.75rem;
    }

    /* Buttons */
    .btn-industrial {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }

    .btn-success-sm {
        background: var(--success);
        color: white;
        border: none;
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
    }

    .btn-success-sm:hover {
        background: #047857;
        color: white;
    }

    .btn-danger-outline-sm {
        background: transparent;
        color: var(--danger);
        border: 1px solid var(--danger);
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
    }

    .btn-danger-outline-sm:hover {
        background: var(--danger);
        color: white;
    }

    .btn-notes {
        background: transparent;
        color: var(--info);
        border: none;
        padding: 0;
        font-size: 0.8rem;
    }

    .btn-notes:hover {
        color: #0f766e;
        text-decoration: underline;
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        background: var(--industrial-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2.5rem;
        color: var(--industrial-gray);
    }

    .empty-state h4 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        color: var(--industrial-gray);
        max-width: 400px;
        margin: 0 auto;
    }

    /* Forwarded Info */
    .forwarded-by {
        font-size: 0.85rem;
        color: var(--industrial-dark);
    }

    .forwarded-date {
        font-size: 0.8rem;
        color: var(--industrial-gray);
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    .modal-header-success {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .modal-header-danger {
        background: linear-gradient(135deg, var(--danger) 0%, #b91c1c 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .modal-header-info {
        background: linear-gradient(135deg, var(--info) 0%, #0f766e 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    /* Mapping Summary Alert */
    .mapping-summary {
        background: rgba(13,148,136,0.08);
        border: 1px solid rgba(13,148,136,0.2);
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .mapping-summary h6 {
        font-weight: 700;
        color: var(--info);
        margin-bottom: 1rem;
    }

    .mapping-flow {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .mapping-flow-course {
        flex: 1;
    }

    .mapping-flow-course strong {
        color: var(--industrial-dark);
    }

    .mapping-flow-course small {
        display: block;
        color: var(--industrial-gray);
        margin-top: 0.25rem;
    }

    .mapping-flow-arrow {
        color: var(--industrial-gray);
        font-size: 1.5rem;
    }

    /* Reject Summary */
    .reject-summary {
        background: var(--industrial-light);
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .reject-summary strong {
        font-family: 'IBM Plex Mono', monospace;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30,58,138,0.1);
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--industrial-gray);
    }

    /* Warning Alert */
    .warning-alert {
        background: rgba(245,158,11,0.1);
        border: 1px solid rgba(245,158,11,0.3);
        border-left: 4px solid var(--uitm-amber);
        border-radius: 8px;
        padding: 1rem;
    }

    .warning-alert i {
        color: var(--uitm-amber);
    }

    .warning-alert a {
        color: var(--uitm-amber);
        font-weight: 600;
    }

    .warning-alert a:hover {
        color: #d97706;
    }

    /* Toast Notifications */
    .toast-container {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 1100;
    }

    .toast-custom {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        min-width: 320px;
    }

    .toast-header-success {
        background: var(--success);
        color: white;
        padding: 0.75rem 1rem;
    }

    .toast-header-error {
        background: var(--danger);
        color: white;
        padding: 0.75rem 1rem;
    }

    .toast-body {
        padding: 1rem;
        color: var(--industrial-dark);
    }

    /* Action Buttons Group */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .mapping-flow {
            flex-direction: column;
        }

        .mapping-flow-arrow {
            transform: rotate(90deg);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-inbox"></i> Pending Mappings from Resource Persons</h2>
                <p>Review and process course mappings forwarded by Resource Persons</p>
            </div>
            <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="btn btn-industrial btn-back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    @if($pendingMappings->isEmpty())
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h4>No Pending Mappings</h4>
            <p>There are currently no course mappings forwarded by Resource Persons awaiting your review.</p>
        </div>
    @else
        <!-- Mappings by Program -->
        @foreach($pendingMappings as $programCode => $mappings)
            <div class="main-card">
                <div class="main-card-header">
                    <h5><i class="fas fa-graduation-cap"></i> {{ $programCode }}</h5>
                    <span class="count-badge">{{ $mappings->count() }} pending mapping(s)</span>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th width="25%">Diploma Course</th>
                                <th width="25%">Degree Course</th>
                                <th width="8%" class="text-center">Match %</th>
                                <th width="15%">Forwarded By</th>
                                <th width="12%">Date</th>
                                <th width="15%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mappings as $mapping)
                            <tr>
                                <td>
                                    <div class="course-code">{{ $mapping->diploma_course_code }}</div>
                                    <div class="course-name">{{ Str::limit($mapping->diploma_course_name, 40) }}</div>
                                    <div class="course-meta">
                                        <i class="fas fa-university me-1"></i>{{ $mapping->diploma_institution }}
                                    </div>
                                    <div class="course-credit">
                                        <i class="fas fa-clock me-1"></i>{{ $mapping->diploma_credit_hour }} credits
                                    </div>
                                </td>
                                <td>
                                    <div class="course-code">{{ $mapping->degree_course_code }}</div>
                                    <div class="course-name">{{ Str::limit($mapping->degree_course_name, 40) }}</div>
                                    <div class="course-credit">
                                        <i class="fas fa-clock me-1"></i>{{ $mapping->degree_credit_hour }} credits
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="match-badge badge-{{ $mapping->match_percentage >= 80 ? 'success' : 'warning' }}">
                                        {{ number_format($mapping->match_percentage, 0) }}%
                                    </span>
                                </td>
                                <td>
                                    <div class="forwarded-by">{{ $mapping->resourcePerson->name ?? 'N/A' }}</div>
                                    @if($mapping->notes)
                                        <button type="button"
                                                class="btn btn-notes mt-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#notesModal-{{ $mapping->id }}">
                                            <i class="fas fa-sticky-note me-1"></i>View notes
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    <div class="forwarded-date">{{ $mapping->created_at->format('d M Y') }}</div>
                                    <div class="forwarded-date">{{ $mapping->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <button type="button"
                                                class="btn btn-industrial btn-success-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addModal-{{ $mapping->id }}">
                                            <i class="fas fa-plus"></i> Add to List
                                        </button>
                                        <button type="button"
                                                class="btn btn-industrial btn-danger-outline-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectModal-{{ $mapping->id }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Notes Modal -->
                            @if($mapping->notes)
                            <div class="modal fade" id="notesModal-{{ $mapping->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header-info">
                                            <h6 class="modal-title"><i class="fas fa-sticky-note"></i> Resource Person Notes</h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-0">{{ $mapping->notes }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Add to List Modal -->
                            <div class="modal fade" id="addModal-{{ $mapping->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route('program_coordinator.pending_mappings.add', $mapping) }}" method="POST">
                                            @csrf
                                            <div class="modal-header-success">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-plus-circle"></i> Add Mapping to Equivalency List
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Mapping Summary -->
                                                <div class="mapping-summary">
                                                    <h6>Mapping Details</h6>
                                                    <div class="mapping-flow">
                                                        <div class="mapping-flow-course">
                                                            <strong>{{ $mapping->diploma_course_code }}</strong> - {{ $mapping->diploma_course_name }}
                                                            <small>{{ $mapping->diploma_institution }} ({{ $mapping->diploma_credit_hour }} credits)</small>
                                                        </div>
                                                        <div class="mapping-flow-arrow">
                                                            <i class="fas fa-arrow-right"></i>
                                                        </div>
                                                        <div class="mapping-flow-course">
                                                            <strong>{{ $mapping->degree_course_code }}</strong> - {{ $mapping->degree_course_name }}
                                                            <small>{{ $mapping->degree_credit_hour }} credits | Match: {{ number_format($mapping->match_percentage, 0) }}%</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Select Equivalency List -->
                                                <div class="mb-3">
                                                    <label for="equivalency_list_id_{{ $mapping->id }}" class="form-label">
                                                        Select Equivalency List <span class="text-danger">*</span>
                                                    </label>
                                                    <select name="equivalency_list_id"
                                                            id="equivalency_list_id_{{ $mapping->id }}"
                                                            class="form-select"
                                                            required>
                                                        <option value="">-- Select a Draft List --</option>
                                                        @php
                                                            $draftLists = \App\Models\EquivalencyList::where('program_code', $mapping->program_code)
                                                                ->draft()
                                                                ->get();
                                                        @endphp
                                                        @forelse($draftLists as $list)
                                                            <option value="{{ $list->id }}">
                                                                {{ $list->semester }} |
                                                                {{ $list->category === 'internal' ? 'Internal (CS110)' : $list->source_institution }}
                                                                ({{ $list->total_mappings }} mappings)
                                                            </option>
                                                        @empty
                                                            <option disabled>No draft lists available for {{ $mapping->program_code }}</option>
                                                        @endforelse
                                                    </select>
                                                    <div class="form-text">
                                                        Only draft equivalency lists for {{ $mapping->program_code }} are shown.
                                                    </div>
                                                </div>

                                                @if($draftLists->isEmpty())
                                                    <div class="warning-alert">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        No draft equivalency lists found for <strong>{{ $mapping->program_code }}</strong>.
                                                        <a href="{{ route('program_coordinator.equivalency_lists.create', ['program' => $mapping->program_code]) }}">
                                                            Create a new list first.
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit"
                                                        class="btn btn-industrial btn-success-sm"
                                                        style="padding: 0.5rem 1rem;"
                                                        {{ $draftLists->isEmpty() ? 'disabled' : '' }}>
                                                    <i class="fas fa-plus-circle"></i> Add to List
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal-{{ $mapping->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('program_coordinator.pending_mappings.reject', $mapping) }}" method="POST">
                                            @csrf
                                            <div class="modal-header-danger">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-times-circle"></i> Reject Mapping
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>
                                                    You are about to reject the following course mapping forwarded by
                                                    <strong>{{ $mapping->resourcePerson->name ?? 'N/A' }}</strong>:
                                                </p>
                                                <div class="reject-summary">
                                                    <strong>{{ $mapping->diploma_course_code }}</strong>
                                                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                                    <strong>{{ $mapping->degree_course_code }}</strong>
                                                    <br>
                                                    <small class="text-muted">Match: {{ number_format($mapping->match_percentage, 0) }}%</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="rejection_reason_{{ $mapping->id }}" class="form-label">
                                                        Rejection Reason <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea name="rejection_reason"
                                                              id="rejection_reason_{{ $mapping->id }}"
                                                              class="form-control"
                                                              rows="3"
                                                              placeholder="Explain why this mapping is being rejected..."
                                                              required></textarea>
                                                    <div class="form-text">This reason will be visible to the Resource Person.</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Reject Mapping
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- Toast Notifications -->
@if(session('success'))
    <div class="toast-container">
        <div class="toast-custom show" role="alert">
            <div class="toast-header-success d-flex justify-content-between align-items-center">
                <span><i class="fas fa-check-circle me-2"></i><strong>Success</strong></span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="toast-container">
        <div class="toast-custom show" role="alert">
            <div class="toast-header-error d-flex justify-content-between align-items-center">
                <span><i class="fas fa-exclamation-circle me-2"></i><strong>Error</strong></span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    // Auto-hide toasts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('.toast-custom');
        toasts.forEach(toast => {
            setTimeout(() => {
                toast.classList.remove('show');
                toast.classList.add('fade');
            }, 5000);
        });
    });
</script>
@endpush
