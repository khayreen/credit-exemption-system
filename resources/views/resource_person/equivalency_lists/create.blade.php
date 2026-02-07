@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
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

    body {
        font-family: 'IBM Plex Sans', sans-serif;
        background-color: var(--industrial-light);
    }

    .page-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        border-radius: 0 0 24px 24px;
        padding: 2rem 2.5rem;
        margin: -1.5rem -1.5rem 2rem -1.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h2 {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.35rem;
    }

    .page-header p {
        color: rgba(255,255,255,0.8);
        margin: 0;
    }

    .step-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.15);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
    }

    .step-number {
        width: 28px;
        height: 28px;
        background: white;
        color: var(--uitm-blue);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-family: 'IBM Plex Mono', monospace;
    }

    .btn-back {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.25);
        color: white;
        transform: translateX(-3px);
    }

    /* Alert Styles */
    .alert-industrial {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
    }

    .alert-industrial.alert-danger {
        background: rgba(220, 38, 38, 0.1);
        border-left: 4px solid var(--danger);
        color: var(--danger);
    }

    /* Category Selection Cards */
    .category-selection-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .category-selection-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .category-selection-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .category-card {
        background: white;
        border-radius: 16px;
        border: 2px solid #e2e8f0;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
    }

    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    }

    .category-card.internal {
        border-color: var(--uitm-blue);
    }

    .category-card.internal:hover {
        border-color: var(--uitm-blue);
        box-shadow: 0 12px 40px rgba(30, 58, 138, 0.2);
    }

    .category-card.external {
        border-color: var(--success);
    }

    .category-card.external:hover {
        border-color: var(--success);
        box-shadow: 0 12px 40px rgba(5, 150, 105, 0.2);
    }

    .category-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .category-card h4 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin-bottom: 0.75rem;
    }

    .category-card p {
        color: var(--industrial-gray);
        margin-bottom: 1rem;
    }

    .highlight-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        margin-bottom: 1.25rem;
    }

    .highlight-badge.internal {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .highlight-badge.external {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .btn-category {
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-category.internal {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        border: none;
    }

    .btn-category.external {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        color: white;
        border: none;
    }

    .btn-category:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        color: white;
    }

    /* Configuration Form Card */
    .config-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .config-header {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .config-header.internal {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
    }

    .config-header.external {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        color: white;
    }

    .config-header-icon {
        font-size: 2rem;
    }

    .config-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .config-header small {
        opacity: 0.85;
    }

    .config-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-text {
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

    .info-banner {
        background: rgba(13, 148, 136, 0.1);
        border: 1px solid rgba(13, 148, 136, 0.2);
        border-left: 4px solid var(--info);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        color: var(--info);
        margin-bottom: 1.5rem;
    }

    .form-check-input:checked {
        background-color: var(--uitm-blue);
        border-color: var(--uitm-blue);
    }

    .btn-create {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 58, 138, 0.3);
        color: white;
    }

    .btn-outline-back {
        border: 2px solid #e2e8f0;
        color: var(--industrial-gray);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-outline-back:hover {
        background: var(--industrial-light);
        color: var(--industrial-dark);
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-plus-circle me-2"></i>Create New Equivalency List</h2>
            <p>
                @if($step == 1)
                    <span class="step-indicator">
                        <span class="step-number">1</span>
                        Select diploma source type
                    </span>
                @else
                    <span class="step-indicator">
                        <span class="step-number">2</span>
                        Configure list details
                    </span>
                @endif
            </p>
        </div>
        <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>Back to Lists
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-industrial alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($step == 1)
    <!-- Step 1: Category Selection -->
    <div class="category-selection-card">
        <div class="category-selection-header">
            <h5><i class="fas fa-list-alt me-2"></i>Select Diploma Source Type</h5>
        </div>
        <div class="p-4">
            <p class="text-muted mb-4">What type of diploma courses will this list map to your degree program?</p>

            <div class="row g-4">
                <!-- Internal (CS110) Option -->
                <div class="col-md-6">
                    <div class="category-card internal">
                        <div class="category-icon">&#127968;</div>
                        <h4>CS110 (UiTM Diploma)</h4>
                        <p>Map courses from UiTM's <strong>Diploma in Computer Science (CS110)</strong> to your degree program.</p>
                        <div class="highlight-badge internal">
                            <i class="fas fa-star text-warning"></i>
                            <div>
                                <strong>Highest Similarity</strong><br>
                                <small>Students from CS110 qualify for the most credit exemptions.</small>
                            </div>
                        </div>
                        <a href="{{ route('resource_person.equivalency_lists.create', ['step' => 2, 'category' => 'internal']) }}" class="btn btn-category internal">
                            <i class="fas fa-check"></i> Select CS110
                        </a>
                    </div>
                </div>

                <!-- External Option -->
                <div class="col-md-6">
                    <div class="category-card external">
                        <div class="category-icon">&#127760;</div>
                        <h4>External Institution</h4>
                        <p>Map courses from external institutions like <strong>Politeknik, UTM, MMU, GMI</strong>, etc. to your degree program.</p>
                        <div class="highlight-badge external">
                            <i class="fas fa-university"></i>
                            <div>
                                <strong>Credit Transfer</strong><br>
                                <small>For students from other Malaysian institutions.</small>
                            </div>
                        </div>
                        <a href="{{ route('resource_person.equivalency_lists.create', ['step' => 2, 'category' => 'external']) }}" class="btn btn-category external">
                            <i class="fas fa-check"></i> Select External
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Step 2: Configure List Details -->
    <div class="config-card">
        <div class="config-header {{ $category === 'internal' ? 'internal' : 'external' }}">
            <span class="config-header-icon">{{ $category === 'internal' ? '&#127968;' : '&#127760;' }}</span>
            <div>
                <h5>{{ $category === 'internal' ? 'CS110 (UiTM Diploma) List' : 'External Institution List' }}</h5>
                <small>Configure your new equivalency list</small>
            </div>
        </div>
        <div class="config-body">
            <form action="{{ route('resource_person.equivalency_lists.store') }}" method="POST">
                @csrf
                <input type="hidden" name="category" value="{{ $category }}">

                @if($category === 'internal')
                    <div class="info-banner">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Source:</strong> CS110 - Diploma in Computer Science (UiTM)
                    </div>
                @endif

                <div class="row">
                    <!-- Target Program -->
                    <div class="col-md-6 mb-4">
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
                        <small class="form-text">Only programs assigned to you are shown.</small>
                    </div>

                    @if($category === 'external')
                    <!-- Source Institution (External only) -->
                    <div class="col-md-6 mb-4">
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
                    <div class="col-md-6 mb-4">
                        <label for="academic_year" class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <select name="academic_year" id="academic_year" class="form-select @error('academic_year') is-invalid @enderror" required>
                            <option value="">-- Select Year --</option>
                            @php
                                $currentYear = date('Y');
                                $currentMonth = date('n');
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
                    <div class="col-md-6 mb-4">
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
                    <small class="form-text">If a published list exists for the same program and source, its mappings will be copied to this new list.</small>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <a href="{{ route('resource_person.equivalency_lists.create') }}" class="btn-outline-back">
                        <i class="fas fa-arrow-left me-2"></i>Back to Category Selection
                    </a>
                    <button type="submit" class="btn-create">
                        <i class="fas fa-plus me-2"></i>Create List
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
