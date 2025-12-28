@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Draft Equivalency Lists</h2>
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-file-alt me-1"></i>
                Monitor draft lists being prepared by Program Coordinators
            </p>
        </div>
        <a href="{{ route('hea.equivalency_lists.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to All Lists
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Drafts</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('hea.equivalency_lists.drafts') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Program</label>
                        <select name="program" class="form-select">
                            <option value="all" {{ $programFilter === 'all' ? 'selected' : '' }}>All Programs</option>
                            @foreach($programs as $code => $name)
                                <option value="{{ $code }}" {{ $programFilter === $code ? 'selected' : '' }}>
                                    {{ $code }} - {{ Str::limit($name, 40) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="all" {{ $categoryFilter === 'all' ? 'selected' : '' }}>All Categories</option>
                            <option value="internal" {{ $categoryFilter === 'internal' ? 'selected' : '' }}>Internal (CS110)</option>
                            <option value="external" {{ $categoryFilter === 'external' ? 'selected' : '' }}>External</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-orange me-3">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $draftCounts['total'] }}</h3>
                            <p class="text-muted mb-0">Total Drafts</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-blue me-3">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $draftCounts['internal'] }}</h3>
                            <p class="text-muted mb-0">Internal (CS110)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-purple me-3">
                            <i class="fas fa-university"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $draftCounts['external'] }}</h3>
                            <p class="text-muted mb-0">External Institutions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Internal Drafts -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-building me-2"></i>Internal (CS110) Draft Lists
            </h5>
        </div>
        <div class="card-body p-0">
            @if($internalLists->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Internal Drafts</h5>
                    <p class="text-muted mb-0">No draft lists for CS110 internal transfers.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Program</th>
                                <th>Semester</th>
                                <th>Mappings</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th>Last Updated</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($internalLists as $list)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $list->program_code }}</span>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($list->program_name, 35) }}</small>
                                </td>
                                <td>{{ $list->semester }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $list->courseEquivalencies->count() }}</span>
                                </td>
                                <td>
                                    <small>{{ $list->creator->name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <small>{{ $list->created_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <small>{{ $list->updated_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('hea.equivalency_lists.show', $list) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Details">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- External Drafts -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="fas fa-university me-2"></i>External Institution Draft Lists
            </h5>
        </div>
        <div class="card-body p-0">
            @if($externalLists->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No External Drafts</h5>
                    <p class="text-muted mb-0">No draft lists for external institutions.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Program</th>
                                <th>Institution</th>
                                <th>Semester</th>
                                <th>Mappings</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th>Last Updated</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($externalLists as $list)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $list->program_code }}</span>
                                </td>
                                <td>
                                    <small>{{ Str::limit($list->source_institution, 30) }}</small>
                                </td>
                                <td>{{ $list->semester }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $list->courseEquivalencies->count() }}</span>
                                </td>
                                <td>
                                    <small>{{ $list->creator->name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <small>{{ $list->created_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <small>{{ $list->updated_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('hea.equivalency_lists.show', $list) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Details">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Info Panel -->
    <div class="alert alert-info mt-4">
        <h6 class="alert-heading">
            <i class="fas fa-info-circle me-2"></i>About Draft Lists
        </h6>
        <p class="mb-0">
            Draft lists are being prepared by Program Coordinators. These lists are not yet published and are not visible to students.
            Program Coordinators can edit, add mappings, and publish these lists directly without requiring HEA approval.
        </p>
    </div>
</div>
@endsection
