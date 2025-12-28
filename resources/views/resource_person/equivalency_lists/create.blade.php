@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Create New Equivalency List</h2>
        <p class="text-muted mb-0 mt-2">
            @if($step == 1)
                Step 1: Select diploma source type
            @else
                Step 2: Configure list details
            @endif
        </p>
    </div>
    <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Lists
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($step == 1)
<!-- Step 1: Category Selection -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Select Diploma Source Type</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4">What type of diploma courses will this list map to your degree program?</p>

        <div class="row">
            <!-- Internal (CS110) Option -->
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-primary category-card" id="internal-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span style="font-size: 4em;">&#127968;</span>
                        </div>
                        <h4 class="card-title">CS110 (UiTM Diploma)</h4>
                        <p class="card-text text-muted">
                            Map courses from UiTM's <strong>Diploma in Computer Science (CS110)</strong> to your degree program.
                        </p>
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-star text-warning me-1"></i>
                            <strong>Highest Similarity</strong><br>
                            <small>Students from CS110 qualify for the most credit exemptions.</small>
                        </div>
                        <a href="{{ route('resource_person.equivalency_lists.create', ['step' => 2, 'category' => 'internal']) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-check me-1"></i> Select CS110
                        </a>
                    </div>
                </div>
            </div>

            <!-- External Option -->
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-success category-card" id="external-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span style="font-size: 4em;">&#127760;</span>
                        </div>
                        <h4 class="card-title">External Institution</h4>
                        <p class="card-text text-muted">
                            Map courses from external institutions like <strong>Politeknik, UTM, MMU, GMI</strong>, etc. to your degree program.
                        </p>
                        <div class="alert alert-secondary mb-3">
                            <i class="fas fa-university me-1"></i>
                            <strong>Credit Transfer</strong><br>
                            <small>For students from other Malaysian institutions.</small>
                        </div>
                        <a href="{{ route('resource_person.equivalency_lists.create', ['step' => 2, 'category' => 'external']) }}" class="btn btn-success btn-lg">
                            <i class="fas fa-check me-1"></i> Select External
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@else
<!-- Step 2: Configure List Details -->
<div class="card shadow-sm">
    <div class="card-header {{ $category === 'internal' ? 'bg-primary' : 'bg-success' }} text-white">
        <div class="d-flex align-items-center">
            <span class="me-2" style="font-size: 1.5em;">{{ $category === 'internal' ? '&#127968;' : '&#127760;' }}</span>
            <div>
                <h5 class="mb-0">
                    {{ $category === 'internal' ? 'CS110 (UiTM Diploma) List' : 'External Institution List' }}
                </h5>
                <small class="opacity-75">Configure your new equivalency list</small>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('resource_person.equivalency_lists.store') }}" method="POST">
            @csrf
            <input type="hidden" name="category" value="{{ $category }}">

            @if($category === 'internal')
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Source:</strong> CS110 - Diploma in Computer Science (UiTM)
                </div>
            @endif

            <div class="row">
                <!-- Target Program -->
                <div class="col-md-6 mb-3">
                    <label for="program_code" class="form-label">Target Degree Program <span class="text-danger">*</span></label>
                    <select name="program_code" id="program_code" class="form-select @error('program_code') is-invalid @enderror" required>
                        <option value="">-- Select Program --</option>
                        @foreach($programs as $code => $name)
                            <option value="{{ $code }}" {{ old('program_code') == $code ? 'selected' : '' }}>
                                {{ $code }} - {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('program_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Only programs assigned to you are shown.</small>
                </div>

                @if($category === 'external')
                <!-- Source Institution (External only) -->
                <div class="col-md-6 mb-3">
                    <label for="source_institution" class="form-label">Source Institution <span class="text-danger">*</span></label>
                    <select name="source_institution" id="source_institution" class="form-select @error('source_institution') is-invalid @enderror" required>
                        <option value="">-- Select Institution --</option>
                        @foreach($institutions as $code => $name)
                            <option value="{{ $code }}" {{ old('source_institution') == $code ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('source_institution')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endif
            </div>

            <div class="row">
                <!-- Academic Year -->
                <div class="col-md-6 mb-3">
                    <label for="academic_year" class="form-label">Academic Year <span class="text-danger">*</span></label>
                    <select name="academic_year" id="academic_year" class="form-select @error('academic_year') is-invalid @enderror" required>
                        <option value="">-- Select Year --</option>
                        @php
                            $currentYear = date('Y');
                            $currentMonth = date('n');
                            // Academic year starts in September
                            $academicYear = $currentMonth >= 9 ? $currentYear : $currentYear - 1;
                        @endphp
                        @for($i = 0; $i < 3; $i++)
                            @php $year = ($academicYear - $i) . '/' . ($academicYear - $i + 1); @endphp
                            <option value="{{ $year }}" {{ old('academic_year', $year) == $year && $i == 0 ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                    @error('academic_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Semester -->
                <div class="col-md-6 mb-3">
                    <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                    <select name="semester" id="semester" class="form-select @error('semester') is-invalid @enderror" required>
                        <option value="">-- Select Semester --</option>
                        <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1 (Sep - Feb)</option>
                        <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2 (Mar - Aug)</option>
                    </select>
                    @error('semester')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Copy from Previous -->
            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" name="copy_from_previous" id="copy_from_previous" class="form-check-input" value="1" {{ old('copy_from_previous') ? 'checked' : '' }}>
                    <label for="copy_from_previous" class="form-check-label">
                        Copy equivalencies from previous semester's published list
                    </label>
                </div>
                <small class="text-muted">If a published list exists for the same program and source, its mappings will be copied to this new list.</small>
            </div>

            <hr>

            <div class="d-flex justify-content-between">
                <a href="{{ route('resource_person.equivalency_lists.create') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Category Selection
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-1"></i> Create List
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<style>
.category-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}
.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>
@endsection
