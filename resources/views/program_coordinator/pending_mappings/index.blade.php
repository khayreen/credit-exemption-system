@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="fas fa-inbox me-2"></i>Pending Mappings from Resource Persons
            </h2>
            <p class="text-muted mb-0 mt-2">
                Review and process course mappings forwarded by Resource Persons
            </p>
        </div>
        <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    @if($pendingMappings->isEmpty())
        <!-- No Pending Mappings -->
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Pending Mappings</h4>
                <p class="text-muted mb-0">
                    There are currently no course mappings forwarded by Resource Persons awaiting your review.
                </p>
            </div>
        </div>
    @else
        <!-- Mappings by Program -->
        @foreach($pendingMappings as $programCode => $mappings)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>{{ $programCode }}
                    </h5>
                    <span class="badge bg-light text-primary fs-6">{{ $mappings->count() }} pending mapping(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
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
                                        <strong>{{ $mapping->diploma_course_code }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($mapping->diploma_course_name, 40) }}</small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-university me-1"></i>{{ $mapping->diploma_institution }}
                                        </small>
                                        <br>
                                        <small class="text-info">Credit: {{ $mapping->diploma_credit_hour }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $mapping->degree_course_code }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($mapping->degree_course_name, 40) }}</small>
                                        <br>
                                        <small class="text-info">Credit: {{ $mapping->degree_credit_hour }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge fs-6 bg-{{ $mapping->match_percentage >= 80 ? 'success' : 'warning' }}">
                                            {{ number_format($mapping->match_percentage, 0) }}%
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $mapping->resourcePerson->name ?? 'N/A' }}</small>
                                        <br>
                                        @if($mapping->notes)
                                            <button type="button"
                                                    class="btn btn-sm btn-link p-0 text-decoration-none"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#notesModal-{{ $mapping->id }}">
                                                <small><i class="fas fa-sticky-note me-1"></i>View notes</small>
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $mapping->created_at->format('d M Y') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $mapping->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-sm btn-success mb-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addModal-{{ $mapping->id }}">
                                            <i class="fas fa-plus me-1"></i>Add to List
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectModal-{{ $mapping->id }}">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </button>
                                    </td>
                                </tr>

                                <!-- Notes Modal -->
                                @if($mapping->notes)
                                <div class="modal fade" id="notesModal-{{ $mapping->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h6 class="modal-title">Resource Person Notes</h6>
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
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-plus-circle me-2"></i>Add Mapping to Equivalency List
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Mapping Summary -->
                                                    <div class="alert alert-info">
                                                        <h6 class="alert-heading">Mapping Details</h6>
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                <strong>{{ $mapping->diploma_course_code }}</strong> - {{ $mapping->diploma_course_name }}
                                                                <br><small>{{ $mapping->diploma_institution }} ({{ $mapping->diploma_credit_hour }} credits)</small>
                                                            </div>
                                                            <div class="col-md-2 text-center">
                                                                <i class="fas fa-arrow-right fa-2x text-muted"></i>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <strong>{{ $mapping->degree_course_code }}</strong> - {{ $mapping->degree_course_name }}
                                                                <br><small>{{ $mapping->degree_credit_hour }} credits | Match: {{ number_format($mapping->match_percentage, 0) }}%</small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Select Equivalency List -->
                                                    <div class="mb-3">
                                                        <label for="equivalency_list_id_{{ $mapping->id }}" class="form-label fw-bold">
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
                                                        <small class="text-muted">
                                                            Only draft equivalency lists for {{ $mapping->program_code }} are shown.
                                                        </small>
                                                    </div>

                                                    @if($draftLists->isEmpty())
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            No draft equivalency lists found for <strong>{{ $mapping->program_code }}</strong>.
                                                            <a href="{{ route('program_coordinator.equivalency_lists.create', ['program' => $mapping->program_code]) }}"
                                                               class="alert-link">
                                                                Create a new list first.
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit"
                                                            class="btn btn-success"
                                                            {{ $draftLists->isEmpty() ? 'disabled' : '' }}>
                                                        <i class="fas fa-plus-circle me-2"></i>Add to List
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
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-times-circle me-2"></i>Reject Mapping
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        You are about to reject the following course mapping forwarded by
                                                        <strong>{{ $mapping->resourcePerson->name ?? 'N/A' }}</strong>:
                                                    </p>
                                                    <div class="alert alert-light border">
                                                        <strong>{{ $mapping->diploma_course_code }}</strong> → <strong>{{ $mapping->degree_course_code }}</strong>
                                                        <br>
                                                        <small>Match: {{ number_format($mapping->match_percentage, 0) }}%</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="rejection_reason_{{ $mapping->id }}" class="form-label fw-bold">
                                                            Rejection Reason <span class="text-danger">*</span>
                                                        </label>
                                                        <textarea name="rejection_reason"
                                                                  id="rejection_reason_{{ $mapping->id }}"
                                                                  class="form-control"
                                                                  rows="3"
                                                                  placeholder="Explain why this mapping is being rejected..."
                                                                  required></textarea>
                                                        <small class="text-muted">This reason will be visible to the Resource Person.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-times-circle me-2"></i>Reject Mapping
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
            </div>
        @endforeach
    @endif
</div>

@if(session('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert">
            <div class="toast-header bg-danger text-white">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif
@endsection
