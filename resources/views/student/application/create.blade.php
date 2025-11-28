@extends('layouts.app')

@push('styles')
<style>
    /* Breadcrumb Styling */
    .application-breadcrumb {
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .application-breadcrumb .breadcrumb {
        margin-bottom: 0;
        background-color: transparent;
        padding: 0;
    }

    .application-breadcrumb .breadcrumb-item {
        font-size: 0.95rem;
        font-weight: 500;
    }

    .application-breadcrumb .breadcrumb-item.active {
        color: #667eea;
    }

    /* Multi-Step Progress Dots */
    .step-progress {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 35px;
        padding: 20px 0;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        flex: 1;
        max-width: 200px;
    }

    .step-dot {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: #e9ecef;
        border: 3px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.1rem;
        color: #6c757d;
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
    }

    .step-item.active .step-dot {
        background-color: #667eea;
        border-color: #667eea;
        color: white;
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
        transform: scale(1.1);
    }

    .step-item.completed .step-dot {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }

    .step-label {
        margin-top: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #6c757d;
        text-align: center;
    }

    .step-item.active .step-label {
        color: #667eea;
    }

    .step-item.completed .step-label {
        color: #28a745;
    }

    .step-line {
        position: absolute;
        top: 22px;
        left: 50%;
        width: 100%;
        height: 3px;
        background-color: #e9ecef;
        z-index: 1;
    }

    .step-item.completed .step-line {
        background-color: #28a745;
    }

    .step-item:last-child .step-line {
        display: none;
    }

    /* Form Step Content */
    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }

    /* Navigation Buttons */
    .step-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #e9ecef;
    }

    .btn-step {
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-previous {
        background-color: #6c757d;
        color: white;
        border: none;
    }

    .btn-previous:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .btn-next, .btn-submit {
        background-color: #667eea;
        color: white;
        border: none;
    }

    .btn-next:hover, .btn-submit:hover {
        background-color: #5568d3;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
    }

    /* Semester Guide Popover Styling */
    .semester-guide-popover {
        max-width: 350px;
    }

    .semester-guide-popover .popover-header {
        background-color: #667eea;
        color: white;
        font-weight: 600;
        border-bottom: none;
    }

    .semester-guide-popover .popover-body {
        padding: 12px 15px;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .semester-guide-popover .popover-body ul {
        list-style-type: disc;
    }

    .semester-guide-popover .popover-body li {
        margin-bottom: 6px;
    }

    /* Simple Searchable Dropdown Styling */
    .searchable-dropdown-wrapper {
        position: relative;
        width: 100%;
    }

    .searchable-dropdown-input {
        width: 100%;
        padding: 0.375rem 2rem 0.375rem 0.75rem;
        font-size: 1rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        background-color: white;
        cursor: pointer;
    }

    .searchable-dropdown-input:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .searchable-dropdown-arrow {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #6c757d;
    }

    .searchable-dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1050;
        background: white;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        margin-top: 2px;
        max-height: 250px;
        overflow-y: auto;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: none;
    }

    .searchable-dropdown-menu.show {
        display: block;
    }

    .searchable-dropdown-option {
        padding: 8px 12px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .searchable-dropdown-option:hover {
        background-color: #f8f9fa;
    }

    .searchable-dropdown-option.selected {
        background-color: #667eea;
        color: white;
    }

    .searchable-dropdown-option.no-results {
        padding: 12px;
        text-align: center;
        color: #6c757d;
        cursor: default;
    }

    .searchable-dropdown-option mark {
        background-color: #fff59d;
        padding: 0 2px;
        font-weight: 600;
    }

    /* Hide original select */
    .searchable-select {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <div class="application-breadcrumb">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page" id="breadcrumb-step">Student Details</li>
            </ol>
        </nav>
    </div>

    <h2 class="mb-4">Credit Exemption Application</h2>

    {{-- Display validation errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6><strong><i class="fas fa-exclamation-triangle"></i> Please correct the following errors:</strong></h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Display warning messages --}}
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Multi-Step Progress Indicator --}}
    <div class="step-progress">
        <div class="step-item active" data-step="1">
            <div class="step-dot">1</div>
            <div class="step-label">Student Details</div>
            <div class="step-line"></div>
        </div>
        <div class="step-item" data-step="2">
            <div class="step-dot">2</div>
            <div class="step-label">Upload Transcript</div>
        </div>
    </div>

    <form method="POST" action="{{ route('student.application.store') }}" enctype="multipart/form-data" id="multiStepForm">
        @csrf

        {{-- STEP 1: Student Details --}}
        <div class="form-step active" id="step-1">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="fas fa-user-edit"></i>  Fill in Your Details</h5>
                </div>
                <div class="card-body p-4">
                        <h6>Student Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Student ID <span class="text-danger">*</span></label>
                                <input type="text" name="student_id" class="form-control @error('student_id') is-invalid @enderror" value="{{ old('student_id') }}" required>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">IC Number<span class="text-danger">*</span></label>
                                <input type="text" name="ic_number" class="form-control @error('ic_number') is-invalid @enderror" value="{{ old('ic_number') }}" placeholder="000000-00-0000" required>
                                @error('ic_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Home Address <span class="text-danger">*</span></label>
                                <textarea name="home_address" class="form-control @error('home_address') is-invalid @enderror" rows="2" required>{{ old('home_address') }}</textarea>
                                @error('home_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <h6>Current UiTM Details</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Campus <span class="text-danger">*</span></label>
                                <select name="campus" id="campus_select" class="form-select searchable-select" required>
                                    <option value="" disabled selected>Select Your Campus</option>
                                    @foreach($campuses as $campus)
                                        <option value="{{ $campus->name }}" {{ old('campus') == $campus->name ? 'selected' : '' }}>{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Programme Code (FSKM) <span class="text-danger">*</span></label>
                                <select name="program_name" id="program_select" class="form-select searchable-select" required onchange="updateProgramCodeAndGroups(this)">
                                    <option value="" disabled selected>Select Your Program</option>
                                    <option value="BACHELOR OF COMPUTER SCIENCE (HONS.)" data-code="CS230" {{ old('program_code') == 'CS230' ? 'selected' : '' }}>CS230 - BACHELOR OF COMPUTER SCIENCE (HONS.)</option>
                                    <option value="BACHELOR OF INFORMATION TECHNOLOGY (HONS.)" data-code="CS240" {{ old('program_code') == 'CS240' ? 'selected' : '' }}>CS240 - BACHELOR OF INFORMATION TECHNOLOGY (HONS.)</option>
                                    <option value="BACHELOR OF COMPUTER SCIENCE (HONS.) NETCENTRIC COMPUTING" data-code="CS251" {{ old('program_code') == 'CS251' ? 'selected' : '' }}>CS251 - BACHELOR OF COMPUTER SCIENCE (HONS.) NETCENTRIC COMPUTING</option>
                                    <option value="BACHELOR OF COMPUTER SCIENCE (HONS.) MULTIMEDIA COMPUTING" data-code="CS253" {{ old('program_code') == 'CS253' ? 'selected' : '' }}>CS253 - BACHELOR OF COMPUTER SCIENCE (HONS.) MULTIMEDIA COMPUTING</option>
                                    <option value="BACHELOR OF COMPUTER SCIENCE (HONS.) COMPUTER NETWORKS" data-code="CS255" {{ old('program_code') == 'CS255' ? 'selected' : '' }}>CS255 - BACHELOR OF COMPUTER SCIENCE (HONS.) COMPUTER NETWORKS</option>
                                    <option value="BACHELOR OF INFORMATION SYSTEMS (HONS.) INTELLIGENT SYSTEMS ENGINEERING" data-code="CS259" {{ old('program_code') == 'CS259' ? 'selected' : '' }}>CS259 - BACHELOR OF INFORMATION SYSTEMS (HONS.) INTELLIGENT SYSTEMS ENGINEERING</option>
                                    <option value="BACHELOR OF INFORMATION SYSTEMS (HONS.) BUSINESS COMPUTING" data-code="CS264" {{ old('program_code') == 'CS264' ? 'selected' : '' }}>CS264 - BACHELOR OF INFORMATION SYSTEMS (HONS.) BUSINESS COMPUTING</option>
                                    <option value="BACHELOR OF INFORMATION SYSTEMS (HONS.) INFORMATION SYSTEMS ENGINEERING" data-code="CS266" {{ old('program_code') == 'CS266' ? 'selected' : '' }}>CS266 - BACHELOR OF INFORMATION SYSTEMS (HONS.) INFORMATION SYSTEMS ENGINEERING</option>
                                    <option value="BACHELOR OF COMPUTER SCIENCE (HONS.) MOBILE COMPUTING" data-code="CS270" {{ old('program_code') == 'CS270' ? 'selected' : '' }}>CS270 - BACHELOR OF COMPUTER SCIENCE (HONS.) MOBILE COMPUTING</option>
                                </select>
                                <input type="hidden" name="program_code" id="program_code" value="{{ old('program_code', '') }}">
                                <input type="hidden" name="faculty" value="FAKULTI SAINS KOMPUTER DAN MATEMATIK">
                                <input type="hidden" name="faculty_id" value="">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Group <span class="text-danger">*</span></label>
                                <select name="student_group" id="group_select" class="form-select" required>
                                    <option value="" disabled selected>Select Your Program First</option>
                                </select>
                                @error('student_group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Current Semester <span class="text-danger">*</span>
                                    <i class="fas fa-info-circle text-primary"
                                       style="cursor: pointer; margin-left: 5px;"
                                       data-bs-toggle="popover"
                                       data-bs-placement="right"
                                       data-bs-trigger="hover focus"
                                       data-bs-html="true"
                                       data-bs-title="<strong>Semester Selection Guide</strong>"
                                       data-bs-content="<div style='text-align: left;'><p style='margin-bottom: 8px;'><strong>If you are a Diploma graduate from:</strong></p><ul style='margin-bottom: 0; padding-left: 20px;'><li>the same faculty — choose <strong>3</strong></li><li>another UiTM faculty — choose <strong>1 or 2</strong></li><li>another institution (IPT) — choose <strong>1</strong></li></ul></div>"></i>
                                </label>
                                <input type="number" name="current_semester" class="form-control @error('current_semester') is-invalid @enderror" value="{{ old('current_semester') }}" min="1" max="20" placeholder="Enter your semester" required>
                                @error('current_semester')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <h6>Previous Institution (IPT)</h6>
                        
                        <!-- Institution Type Selection -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="institution_type" id="uitm_previous" value="uitm" onchange="toggleInstitutionFields()">
                                    <label class="form-check-label" for="uitm_previous">
                                        UiTM (Previous Campus/Program)
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="institution_type" id="non_uitm_previous" value="non_uitm" onchange="toggleInstitutionFields()">
                                    <label class="form-check-label" for="non_uitm_previous">
                                        Non-UiTM Institution
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- UiTM Previous Institution Fields -->
                        <div id="uitm_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 mb-3" style="float: left; width: 50%; padding-right: 15px;">
                                    <label class="form-label">Previous UiTM Campus</label>
                                    <select name="previous_uitm_campus" class="form-select searchable-select">
                                        <option value="" disabled selected>Select Your Previous Campus</option>
                                        @foreach($campuses as $campus)
                                            <option value="{{ $campus->name }}">{{ $campus->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-6 mb-3" style="float: left; width: 50%; padding-left: 15px;">
                                    <label class="form-label">Previous UiTM Diploma Program</label>
                                    <select name="previous_uitm_program" class="form-select searchable-select">
                                        <option value="" disabled selected>Select Your Diploma Program</option>
                                        @foreach($uitmDiplomaPrograms as $program)
                                            <option value="{{ $program->name }}">
                                                {{ $program->code ? $program->code . ' - ' : '' }}{{ $program->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Non-UiTM Institution Fields -->
                        <div id="non_uitm_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 mb-3" style="float: left; width: 50%; padding-right: 15px;">
                                    <label class="form-label">Diploma Institution</label>
                                    <select name="previous_institution" class="form-select searchable-select">
                                        <option value="" disabled selected>Select Your Institution</option>
                                        @foreach($nonUitmInstitutions as $institution)
                                            <option value="{{ $institution->name }}">{{ $institution->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-6 mb-3" style="float: left; width: 50%; padding-left: 15px;">
                                    <label class="form-label">Diploma Program Name</label>
                                    <input type="text" name="previous_program" class="form-control" placeholder="e.g., Diploma in Computer Science">
                                </div>
                            </div>
                        </div>

                        {{-- Step 1 Navigation --}}
                        <div class="step-navigation">
                            <div></div>
                            <button type="button" class="btn btn-step btn-next" id="nextToStep2">
                                Upload Transcript <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 2: Upload Transcript --}}
        <div class="form-step" id="step-2">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="fas fa-file-upload"></i> Upload Transcript</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="text-center mb-4">
                                <h5>Upload Your Official Transcript</h5><br>
                                <p class="text-muted">Upload a computer-generated transcript for best accuracy. This transcript will be used for OCR processing to extract your course information automatically. Ensure the document is clear, complete, and in PDF format to achieve accurate results.</p>
                            </div>

                            <div class="mb-4">
                                <label for="transcript_file" class="form-label">Transcript (PDF only, Max 5MB) <span class="text-danger">*</span></label>
                                <input class="form-control form-control-lg @error('transcript_file') is-invalid @enderror" type="file" id="transcript_file" name="transcript_file" accept=".pdf" required>
                                @error('transcript_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Declaration Checkbox --}}
                            <div class="alert alert-warning border-warning" style="background-color: #fff3cd;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="transcript_declaration" name="transcript_declaration" required style="width: 18px; height: 18px; margin-top: 0.25em;">
                                    <label class="form-check-label" for="transcript_declaration" style="margin-left: 8px; line-height: 1.6; font-size: 0.95rem;">
                                        I confirm this is my official, unaltered transcript. I understand that submitting falsified or edited documents is a serious offense and may result in disciplinary action, including rejection of my application and further sanctions.
                                    </label>
                                </div>
                            </div>

                            {{-- Step 2 Navigation --}}
                            <div class="step-navigation">
                                <button type="button" class="btn btn-step btn-previous" id="backToStep1">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="submit" class="btn btn-step btn-submit" id="submitBtn">
                                    Submit Application
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Multi-Step Form Navigation
let currentStep = 1;
const totalSteps = 2;

// Simple Searchable Dropdown Implementation
$(document).ready(function() {
    console.log('Initializing simple searchable dropdowns...');

    // Initialize Bootstrap popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl, {
            container: 'body',
            customClass: 'semester-guide-popover'
        });
    });

    // Initialize searchable dropdowns
    $('.searchable-select').each(function() {
        initSearchableDropdown($(this));
    });

    // Initialize other form behaviors
    toggleInstitutionFields();

    // Multi-step navigation buttons
    $('#nextToStep2').on('click', function() {
        if (validateStep1()) {
            goToStep(2);
        }
    });

    $('#backToStep1').on('click', function() {
        goToStep(1);
    });

    // Set initial program code if already selected
    var programSelect = document.querySelector('select[name="program_name"]');
    if (programSelect && programSelect.value) {
        updateProgramCode(programSelect);
    }

    // Form validation on submit (Step 2 only - file upload check)
    $('form').on('submit', function(e) {
        // Check if transcript file is uploaded
        const transcriptFile = document.querySelector('input[name="transcript_file"]');
        if (!transcriptFile || !transcriptFile.files || transcriptFile.files.length === 0) {
            e.preventDefault();
            alert('Upload your official transcript to proceed.');
            transcriptFile.classList.add('is-invalid');
            transcriptFile.focus();
            return false;
        }

        // Check file size
        if (transcriptFile.files[0].size > 5 * 1024 * 1024) {
            e.preventDefault();
            alert('File size must not exceed 5MB.');
            transcriptFile.classList.add('is-invalid');
            return false;
        }

        // Check if declaration checkbox is checked
        const declarationCheckbox = document.getElementById('transcript_declaration');
        if (!declarationCheckbox || !declarationCheckbox.checked) {
            e.preventDefault();
            alert('Please confirm that you are submitting an official, unaltered transcript by checking the declaration box.');
            declarationCheckbox.focus();
            // Add visual feedback
            const alertBox = declarationCheckbox.closest('.alert');
            if (alertBox) {
                alertBox.style.border = '2px solid #dc3545';
                setTimeout(() => {
                    alertBox.style.border = '';
                }, 3000);
            }
            return false;
        }

        // All validations passed
        return true;
    });

    // Remove error styling when user interacts with inputs
    $('input, textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // Specifically handle file input to remove error when file is selected
    $('#transcript_file').on('change', function() {
        if (this.files && this.files.length > 0) {
            $(this).removeClass('is-invalid');
        }
    });

    // Handle declaration checkbox to remove error border when checked
    $('#transcript_declaration').on('change', function() {
        const alertBox = this.closest('.alert');
        if (this.checked && alertBox) {
            alertBox.style.border = '';
        }
    });

    console.log('✓ All dropdowns initialized!');
});

// Initialize a single searchable dropdown
function initSearchableDropdown($select) {
    var options = [];
    var selectedValue = '';
    var selectedText = $.trim($select.find('option:first').text());

    // Collect all options (trim whitespace)
    $select.find('option').each(function() {
        var value = $(this).val();
        var text = $.trim($(this).text());
        if (value) {
            options.push({ value: value, text: text });
        }
    });

    // Create wrapper
    var $wrapper = $('<div class="searchable-dropdown-wrapper"></div>');
    var $input = $('<input type="text" class="searchable-dropdown-input" placeholder="' + selectedText + '" readonly>');
    var $arrow = $('<span class="searchable-dropdown-arrow">▼</span>');
    var $menu = $('<div class="searchable-dropdown-menu"></div>');

    $wrapper.append($input);
    $wrapper.append($arrow);
    $wrapper.append($menu);

    // Insert after select and hide select
    $select.after($wrapper);

    // Populate menu
    function populateMenu(searchTerm) {
        $menu.empty();
        var filteredOptions = options;

        if (searchTerm) {
            searchTerm = searchTerm.toLowerCase();
            filteredOptions = options.filter(function(opt) {
                return opt.text.toLowerCase().indexOf(searchTerm) > -1;
            });
        }

        if (filteredOptions.length === 0) {
            $menu.append('<div class="searchable-dropdown-option no-results">No results found</div>');
        } else {
            filteredOptions.forEach(function(opt) {
                var $option = $('<div class="searchable-dropdown-option" data-value="' + opt.value + '"></div>');

                // Highlight matching text
                if (searchTerm) {
                    var idx = opt.text.toLowerCase().indexOf(searchTerm);
                    if (idx > -1) {
                        var before = opt.text.substring(0, idx);
                        var match = opt.text.substring(idx, idx + searchTerm.length);
                        var after = opt.text.substring(idx + searchTerm.length);
                        $option.html(before + '<mark>' + match + '</mark>' + after);
                    } else {
                        $option.text(opt.text);
                    }
                } else {
                    $option.text(opt.text);
                }

                if (opt.value === selectedValue) {
                    $option.addClass('selected');
                }

                $menu.append($option);
            });
        }
    }

    // Open dropdown
    $input.on('click', function() {
        $input.removeAttr('readonly');
        $input.val('');
        $input.focus();
        $menu.addClass('show');
        populateMenu('');
    });

    // Search as you type
    $input.on('input', function() {
        populateMenu($input.val());
    });

    // Select option
    $menu.on('click', '.searchable-dropdown-option:not(.no-results)', function() {
        var value = $(this).data('value');

        // Get the original text from options array (not from DOM which may have HTML)
        var selectedOption = options.find(function(opt) { return opt.value === value; });
        var text = selectedOption ? selectedOption.text : $(this).text();

        selectedValue = value;
        $select.val(value).trigger('change');
        $input.val(text);
        $input.attr('readonly', 'readonly');
        $menu.removeClass('show');

        // Trigger program code update if this is program dropdown
        if ($select.attr('name') === 'program_name') {
            updateProgramCode($select[0]);
        }
    });

    // Close on click outside
    $(document).on('click', function(e) {
        if (!$wrapper[0].contains(e.target)) {
            $input.attr('readonly', 'readonly');
            $menu.removeClass('show');
            if (selectedValue) {
                // Get original text from options array
                var selectedOpt = options.find(function(opt) { return opt.value === selectedValue; });
                if (selectedOpt) {
                    $input.val(selectedOpt.text);
                }
            } else {
                $input.val('');
                $input.attr('placeholder', selectedText);
            }
        }
    });
}

function toggleInstitutionFields() {
    const uitmRadio = document.getElementById('uitm_previous');
    const nonUitmRadio = document.getElementById('non_uitm_previous');
    const uitmFields = document.getElementById('uitm_fields');
    const nonUitmFields = document.getElementById('non_uitm_fields');

    // Clear required attributes from all fields first
    const allFields = document.querySelectorAll('#uitm_fields select, #non_uitm_fields select, #non_uitm_fields input');
    allFields.forEach(field => {
        field.removeAttribute('required');
        field.value = '';
        // Disable fields that are not visible to prevent them from being submitted
        field.disabled = true;
    });

    // Remove all existing searchable dropdown wrappers to ensure clean reinitialiation
    $('#uitm_fields .searchable-dropdown-wrapper, #non_uitm_fields .searchable-dropdown-wrapper').remove();

    if (uitmRadio.checked) {
        uitmFields.style.display = 'block';
        nonUitmFields.style.display = 'none';

        // Enable and require UiTM fields
        document.querySelector('select[name="previous_uitm_campus"]').disabled = false;
        document.querySelector('select[name="previous_uitm_campus"]').setAttribute('required', 'required');
        document.querySelector('select[name="previous_uitm_program"]').disabled = false;
        document.querySelector('select[name="previous_uitm_program"]').setAttribute('required', 'required');

        // Initialize searchable dropdowns for UiTM fields
        setTimeout(function() {
            $('#uitm_fields .searchable-select').each(function() {
                initSearchableDropdown($(this));
            });
        }, 100);

    } else if (nonUitmRadio.checked) {
        uitmFields.style.display = 'none';
        nonUitmFields.style.display = 'block';

        // Enable and require non-UiTM fields
        document.querySelector('select[name="previous_institution"]').disabled = false;
        document.querySelector('select[name="previous_institution"]').setAttribute('required', 'required');
        document.querySelector('input[name="previous_program"]').disabled = false;
        document.querySelector('input[name="previous_program"]').setAttribute('required', 'required');

        // Initialize searchable dropdowns for Non-UiTM fields
        setTimeout(function() {
            $('#non_uitm_fields .searchable-select').each(function() {
                initSearchableDropdown($(this));
            });
        }, 100);

    } else {
        uitmFields.style.display = 'none';
        nonUitmFields.style.display = 'none';
    }
}

// Group mapping for each program code
const programGroups = {
    'CS230': ['CS2301B', 'CS2303B', 'CS2303C'],
    'CS240': ['CS2403A'],
    'CS251': ['CS2513A'],
    'CS253': ['CS2531A', 'CS2533B'],
    'CS255': ['CS2551A', 'CS2553B'],
    'CS259': ['CS2593A'],
    'CS264': ['CS2643A'],
    'CS266': ['CS2663A'],
    'CS270': ['CS2703A']
};

function updateProgramCode(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const programCode = selectedOption.getAttribute('data-code') || '';
    document.getElementById('program_code').value = programCode;
}

function updateProgramCodeAndGroups(selectElement) {
    // Update program code
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const programCode = selectedOption.getAttribute('data-code') || '';
    document.getElementById('program_code').value = programCode;

    // Update groups dropdown
    const groupSelect = document.getElementById('group_select');
    groupSelect.innerHTML = '<option value="" disabled selected>Select Your Group</option>';

    if (programCode && programGroups[programCode]) {
        const groups = programGroups[programCode];
        groups.forEach(function(group) {
            const option = document.createElement('option');
            option.value = group;
            option.textContent = group;
            groupSelect.appendChild(option);
        });
        groupSelect.disabled = false;
    } else {
        groupSelect.innerHTML = '<option value="" disabled selected>Select Your Program First</option>';
        groupSelect.disabled = true;
    }
}

// Multi-Step Navigation Functions
function goToStep(stepNumber) {
    // Hide all steps
    $('.form-step').removeClass('active');

    // Show target step
    $('#step-' + stepNumber).addClass('active');

    // Update progress indicators
    $('.step-item').removeClass('active completed');

    for (let i = 1; i <= totalSteps; i++) {
        const $stepItem = $('.step-item[data-step="' + i + '"]');
        if (i < stepNumber) {
            $stepItem.addClass('completed');
        } else if (i === stepNumber) {
            $stepItem.addClass('active');
        }
    }

    // Update breadcrumb
    const breadcrumbTexts = {
        1: 'Student Details',
        2: 'Upload Transcript'
    };
    $('#breadcrumb-step').text(breadcrumbTexts[stepNumber]);

    // Update current step
    currentStep = stepNumber;

    // Scroll to top smoothly
    $('html, body').animate({ scrollTop: 0 }, 400);
}

function validateStep1() {
    let isValid = true;
    const errors = [];

    // Check required text inputs
    const requiredFields = [
        { name: 'full_name', label: 'Full Name' },
        { name: 'student_id', label: 'Student ID' },
        { name: 'ic_number', label: 'IC Number' },
        { name: 'home_address', label: 'Home Address' },
        { name: 'current_semester', label: 'Current Semester' }
    ];

    requiredFields.forEach(field => {
        const input = document.querySelector(`[name="${field.name}"]`);
        if (!input || !input.value.trim()) {
            isValid = false;
            errors.push(field.label);
            if (input) {
                input.classList.add('is-invalid');
            }
        } else {
            if (input) {
                input.classList.remove('is-invalid');
            }
        }
    });

    // Check required select fields
    const requiredSelects = [
        { name: 'campus', label: 'Current Campus' },
        { name: 'program_name', label: 'Programme Code (FSKM)' },
        { name: 'student_group', label: 'Group' }
    ];

    requiredSelects.forEach(field => {
        const select = document.querySelector(`select[name="${field.name}"]`);
        if (!select || !select.value) {
            isValid = false;
            errors.push(field.label);
            if (select) {
                select.classList.add('is-invalid');
            }
        } else {
            if (select) {
                select.classList.remove('is-invalid');
            }
        }
    });

    // Check institution type selection
    const institutionType = document.querySelector('input[name="institution_type"]:checked');
    if (!institutionType) {
        isValid = false;
        errors.push('Institution Type (UiTM or Non-UiTM)');
    } else {
        // Check institution-specific fields
        if (institutionType.value === 'uitm') {
            const uitmCampus = document.querySelector('select[name="previous_uitm_campus"]');
            const uitmProgram = document.querySelector('select[name="previous_uitm_program"]');

            if (!uitmCampus || !uitmCampus.value) {
                isValid = false;
                errors.push('Previous UiTM Campus');
                if (uitmCampus) uitmCampus.classList.add('is-invalid');
            }

            if (!uitmProgram || !uitmProgram.value) {
                isValid = false;
                errors.push('Previous UiTM Diploma Program');
                if (uitmProgram) uitmProgram.classList.add('is-invalid');
            }
        } else if (institutionType.value === 'non_uitm') {
            const nonUitmInstitution = document.querySelector('select[name="previous_institution"]');
            const nonUitmProgram = document.querySelector('input[name="previous_program"]');

            if (!nonUitmInstitution || !nonUitmInstitution.value) {
                isValid = false;
                errors.push('Previous Institution');
                if (nonUitmInstitution) nonUitmInstitution.classList.add('is-invalid');
            }

            if (!nonUitmProgram || !nonUitmProgram.value.trim()) {
                isValid = false;
                errors.push('Previous Program Name');
                if (nonUitmProgram) nonUitmProgram.classList.add('is-invalid');
            }
        }
    }

    // Validate current semester range
    const semesterInput = document.querySelector('input[name="current_semester"]');
    if (semesterInput && semesterInput.value) {
        const semester = parseInt(semesterInput.value);
        if (semester < 1 || semester > 20) {
            isValid = false;
            errors.push('Current Semester (must be between 1-20)');
            semesterInput.classList.add('is-invalid');
        }
    }

    if (!isValid) {
        alert('Please fill in the following required fields:\n\n• ' + errors.join('\n• '));
        // Scroll to first error field
        const firstErrorField = document.querySelector('.is-invalid');
        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstErrorField.focus();
        }
    }

    return isValid;
}
</script>
@endpush
