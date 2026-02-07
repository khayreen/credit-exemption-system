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

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .main-card-header {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
        padding: 1.5rem;
        color: white;
    }

    .main-card-header h4 {
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .main-card-body {
        padding: 2rem;
    }

    /* Section Label */
    .section-label {
        font-weight: 700;
        color: var(--industrial-dark);
        margin-bottom: 1rem;
    }

    .section-label .required {
        color: var(--danger);
    }

    /* Category Cards */
    .category-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .category-card {
        position: relative;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
    }

    .category-card:hover {
        border-color: var(--uitm-blue-light);
        background: rgba(59,130,246,0.02);
    }

    .category-card.selected {
        border-color: var(--uitm-blue);
        background: rgba(30,58,138,0.05);
    }

    .category-card input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .category-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .category-card.internal .category-card-icon {
        background: rgba(30,58,138,0.15);
        color: var(--uitm-blue);
    }

    .category-card.external .category-card-icon {
        background: rgba(13,148,136,0.15);
        color: var(--info);
    }

    .category-card h6 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .category-card p {
        font-size: 0.85rem;
        color: var(--industrial-gray);
        margin: 0;
    }

    .category-check {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--success);
        color: white;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .category-card.selected .category-check {
        display: flex;
    }

    /* Divider */
    .section-divider {
        border-top: 1px solid #e2e8f0;
        margin: 2rem 0;
    }

    /* Form Inputs */
    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
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

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: var(--danger);
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--industrial-gray);
    }

    /* Copy Option */
    .copy-option {
        background: rgba(13,148,136,0.05);
        border: 1px solid rgba(13,148,136,0.2);
        border-radius: 10px;
        padding: 1.25rem;
    }

    .copy-option .form-check-input:checked {
        background-color: var(--info);
        border-color: var(--info);
    }

    .copy-option h6 {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.25rem;
    }

    .copy-option p {
        font-size: 0.85rem;
        color: var(--industrial-gray);
        margin: 0;
    }

    /* Info Card */
    .info-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-top: 1.5rem;
    }

    .info-card-header {
        background: var(--info);
        color: white;
        padding: 1rem 1.25rem;
    }

    .info-card-header h6 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-body {
        padding: 1.25rem;
    }

    .info-card-body h6 {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.75rem;
    }

    .info-card-body ol {
        margin: 0;
        padding-left: 1.25rem;
        color: var(--industrial-gray);
        font-size: 0.9rem;
    }

    .info-card-body ol li {
        margin-bottom: 0.5rem;
    }

    /* Buttons */
    .btn-industrial {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .btn-success-industrial {
        background: var(--success);
        color: white;
        border: none;
    }

    .btn-success-industrial:hover {
        background: #047857;
        color: white;
        transform: translateY(-1px);
    }

    .btn-lg {
        padding: 0.875rem 2rem;
        font-size: 1rem;
    }

    /* Error Alert */
    .alert-danger-custom {
        background: rgba(220,38,38,0.08);
        border: 1px solid rgba(220,38,38,0.2);
        border-left: 4px solid var(--danger);
        border-radius: 8px;
        padding: 1rem 1.25rem;
    }

    @media (max-width: 768px) {
        .category-options {
            grid-template-columns: 1fr;
        }

        .main-card-body {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="main-card">
                <div class="main-card-header">
                    <h4><i class="fas fa-plus-circle"></i>Create New Equivalency List</h4>
                </div>
                <div class="main-card-body">
                    <form action="{{ route('program_coordinator.equivalency_lists.store') }}" method="POST">
                        @csrf

                        <!-- Category Selection -->
                        <div class="mb-4">
                            <label class="section-label">List Category <span class="required">*</span></label>
                            <div class="category-options">
                                <label class="category-card internal {{ old('category', $category) === 'internal' ? 'selected' : '' }}">
                                    <input type="radio" name="category" value="internal"
                                           {{ old('category', $category) === 'internal' ? 'checked' : '' }} required>
                                    <div class="category-check"><i class="fas fa-check"></i></div>
                                    <div class="category-card-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <h6>Internal (CS110)</h6>
                                    <p>For UiTM Diploma CS110 students transferring to degree programs</p>
                                </label>
                                <label class="category-card external {{ old('category', $category) === 'external' ? 'selected' : '' }}">
                                    <input type="radio" name="category" value="external"
                                           {{ old('category', $category) === 'external' ? 'checked' : '' }} required>
                                    <div class="category-check"><i class="fas fa-check"></i></div>
                                    <div class="category-card-icon">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <h6>External Institution</h6>
                                    <p>For diploma students from other institutions (Politeknik, MMU, etc.)</p>
                                </label>
                            </div>
                            @error('category')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="section-divider"></div>

                        <!-- Program Selection -->
                        <div class="mb-4">
                            <label for="program_code" class="form-label">
                                Degree Program <span class="text-danger">*</span>
                            </label>
                            <select name="program_code" id="program_code" class="form-select @error('program_code') is-invalid @enderror" required>
                                <option value="">-- Select Degree Program --</option>
                                @foreach($programs as $code => $name)
                                    <option value="{{ $code }}" {{ old('program_code') == $code ? 'selected' : '' }}>
                                        {{ $code }} - {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('program_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Source Institution (only for external) -->
                        <div class="mb-4" id="institutionField" style="display: none;">
                            <label for="source_institution" class="form-label">
                                Source Institution <span class="text-danger">*</span>
                            </label>
                            <select name="source_institution" id="source_institution" class="form-select @error('source_institution') is-invalid @enderror">
                                <option value="">-- Select Source Institution --</option>
                                @foreach($institutions as $key => $name)
                                    <option value="{{ $name }}" {{ old('source_institution') == $name ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('source_institution')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="section-divider"></div>

                        <!-- Academic Period -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="academic_year" class="form-label">
                                    Academic Year <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="academic_year"
                                       id="academic_year"
                                       class="form-control @error('academic_year') is-invalid @enderror"
                                       placeholder="e.g., 2024/2025"
                                       value="{{ old('academic_year') }}"
                                       required>
                                <div class="form-text">Format: YYYY/YYYY (e.g., 2024/2025)</div>
                                @error('academic_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="semester" class="form-label">
                                    Semester <span class="text-danger">*</span>
                                </label>
                                <select name="semester" id="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                    <option value="">-- Select Semester --</option>
                                    <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                                    <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                                </select>
                                @error('semester')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        <!-- Copy from Previous Semester Option -->
                        <div class="copy-option mb-4">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="copy_from_previous"
                                       id="copy_from_previous"
                                       value="1"
                                       {{ old('copy_from_previous') ? 'checked' : '' }}>
                                <label class="form-check-label" for="copy_from_previous">
                                    <h6><i class="fas fa-copy me-2" style="color: var(--info);"></i>Copy course mappings from previous semester</h6>
                                    <p>If available, all course equivalencies from the previous semester will be copied to this list.</p>
                                </label>
                            </div>
                        </div>

                        <!-- Error Messages -->
                        @if($errors->any() && !$errors->has('program_code') && !$errors->has('category') && !$errors->has('source_institution') && !$errors->has('academic_year') && !$errors->has('semester'))
                            <div class="alert-danger-custom mb-4">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="btn btn-outline-secondary btn-industrial">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success-industrial btn-industrial btn-lg">
                                <i class="fas fa-plus-circle me-2"></i>Create Equivalency List
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="info-card">
                <div class="info-card-header">
                    <h6><i class="fas fa-info-circle"></i>Information</h6>
                </div>
                <div class="info-card-body">
                    <h6>What happens next?</h6>
                    <ol>
                        <li>The equivalency list will be created with <strong>"Draft"</strong> status</li>
                        <li>You will be redirected to the edit page to add course mappings</li>
                        <li>You can add course mappings manually or from Resource Person forwarded mappings</li>
                        <li>Once ready, you can <strong>publish the list directly</strong> (no HEA approval needed)</li>
                        <li>Published lists become active and visible to students</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryCards = document.querySelectorAll('.category-card');
        const institutionField = document.getElementById('institutionField');
        const institutionSelect = document.getElementById('source_institution');

        function toggleInstitutionField() {
            const selectedCategory = document.querySelector('input[name="category"]:checked');
            if (selectedCategory && selectedCategory.value === 'external') {
                institutionField.style.display = 'block';
                institutionSelect.setAttribute('required', 'required');
            } else {
                institutionField.style.display = 'none';
                institutionSelect.removeAttribute('required');
                institutionSelect.value = '';
            }
        }

        categoryCards.forEach(card => {
            card.addEventListener('click', function() {
                categoryCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input[type="radio"]').checked = true;
                toggleInstitutionField();
            });
        });

        // Initialize on page load
        toggleInstitutionField();
    });
</script>
@endpush
