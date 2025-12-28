@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Create New Equivalency List
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('program_coordinator.equivalency_lists.store') }}" method="POST">
                        @csrf

                        <!-- Category Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">List Category <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-check-lg">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="category"
                                               id="categoryInternal"
                                               value="internal"
                                               {{ old('category', $category) === 'internal' ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="categoryInternal">
                                            <i class="fas fa-building text-primary me-2"></i>
                                            <strong>Internal (CS110)</strong>
                                            <p class="text-muted mb-0 ms-4">
                                                <small>For UiTM Diploma CS110 students transferring to degree programs</small>
                                            </p>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-lg">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="category"
                                               id="categoryExternal"
                                               value="external"
                                               {{ old('category', $category) === 'external' ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="categoryExternal">
                                            <i class="fas fa-university text-info me-2"></i>
                                            <strong>External Institution</strong>
                                            <p class="text-muted mb-0 ms-4">
                                                <small>For diploma students from other institutions (Politeknik, MMU, etc.)</small>
                                            </p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('category')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Program Selection -->
                        <div class="mb-4">
                            <label for="program_code" class="form-label fw-bold">
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
                            <label for="source_institution" class="form-label fw-bold">
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

                        <hr class="my-4">

                        <!-- Academic Period -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="academic_year" class="form-label fw-bold">
                                    Academic Year <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="academic_year"
                                       id="academic_year"
                                       class="form-control @error('academic_year') is-invalid @enderror"
                                       placeholder="e.g., 2024/2025"
                                       value="{{ old('academic_year') }}"
                                       required>
                                <small class="text-muted">Format: YYYY/YYYY (e.g., 2024/2025)</small>
                                @error('academic_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="semester" class="form-label fw-bold">
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

                        <hr class="my-4">

                        <!-- Copy from Previous Semester Option -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="copy_from_previous"
                                       id="copy_from_previous"
                                       value="1"
                                       {{ old('copy_from_previous') ? 'checked' : '' }}>
                                <label class="form-check-label" for="copy_from_previous">
                                    <i class="fas fa-copy me-2 text-info"></i>
                                    <strong>Copy course mappings from previous semester</strong>
                                    <p class="text-muted mb-0 ms-4">
                                        <small>If available, all course equivalencies from the previous semester will be copied to this list.</small>
                                    </p>
                                </label>
                            </div>
                        </div>

                        <!-- Error Messages -->
                        @if($errors->any() && !$errors->has('program_code') && !$errors->has('category') && !$errors->has('source_institution') && !$errors->has('academic_year') && !$errors->has('semester'))
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-plus-circle me-2"></i>Create Equivalency List
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Information</h6>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">What happens next?</h6>
                    <ol class="mb-0">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryRadios = document.querySelectorAll('input[name="category"]');
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

        categoryRadios.forEach(radio => {
            radio.addEventListener('change', toggleInstitutionField);
        });

        // Initialize on page load
        toggleInstitutionField();
    });
</script>
@endpush
@endsection
