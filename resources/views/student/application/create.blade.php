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

    /* Optgroup header styling */
    .searchable-dropdown-group-header {
        padding: 8px 12px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #667eea;
        background-color: #f0f2ff;
        border-bottom: 1px solid #d0d4f0;
        cursor: default;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    /* Grouped option indentation */
    .searchable-dropdown-option.grouped-option {
        padding-left: 28px;
        font-size: 0.9rem;
        position: relative;
    }

    .searchable-dropdown-option.grouped-option::before {
        content: "└─";
        position: absolute;
        left: 12px;
        color: #999;
        font-size: 0.85rem;
    }

    /* Hide original select */
    .searchable-select {
        display: none;
    }

    /* ========================================
       FLYOUT MENU STYLING - Clean Design
       Dropdown opens BELOW, Submenu flies RIGHT
       ======================================== */

    .flyout-dropdown-wrapper {
        position: relative !important;
        width: 100% !important;
        overflow: visible !important;
    }

    .flyout-dropdown-input {
        width: 100%;
        padding: 0.5rem 2.5rem 0.5rem 0.75rem;
        font-size: 0.95rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        background-color: white;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .flyout-dropdown-input:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .flyout-dropdown-arrow {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #6c757d;
        font-size: 0.75rem;
        transition: all 0.2s ease;
    }

    .flyout-dropdown-wrapper .flyout-dropdown-menu.show ~ .flyout-dropdown-arrow,
    .flyout-dropdown-arrow.open {
        transform: translateY(-50%) rotate(180deg);
        color: #667eea;
    }

    /* Main Dropdown Menu - Simple clean design */
    .flyout-dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        z-index: 9999 !important;
        background: white !important;
        border: 1px solid #ced4da !important;
        border-radius: 4px !important;
        margin-top: 2px !important;
        max-height: 300px !important;
        overflow-y: auto !important;
        overflow-x: visible !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
    }

    .flyout-dropdown-menu.show {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    /* Simple programme list */
    .flyout-menu-column {
        width: 100% !important;
        display: block !important;
    }

    /* Menu Items (Programmes) - Simple clean design */
    .flyout-menu-item {
        position: relative;
        padding: 10px 12px;
        cursor: pointer;
        font-size: 0.95rem;
        color: #333;
        background-color: white;
        transition: background-color 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .flyout-menu-item:hover,
    .flyout-menu-item.active {
        background-color: #f8f9fa;
    }

    .flyout-item-text {
        flex: 1;
        color: #333;
    }

    .flyout-item-label {
        line-height: 1.5;
    }

    .flyout-chevron {
        font-size: 0.7rem;
        color: #999;
        margin-left: 8px;
    }

    .flyout-menu-item:hover .flyout-chevron,
    .flyout-menu-item.active .flyout-chevron {
        color: #667eea;
    }

    /* Submenu (Groups) - Vertical expansion below programme */
    .flyout-submenu {
        display: none;
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 4px 0;
    }

    .flyout-submenu.expanded {
        display: block;
    }

    .flyout-menu-item.expanded {
        background-color: #f8f9fa;
    }

    .flyout-menu-item.expanded .flyout-chevron {
        transform: rotate(90deg);
        color: #667eea;
    }

    /* Group items in submenu */
    .flyout-group-item {
        padding: 8px 12px 8px 32px;
        cursor: pointer;
        font-size: 0.88rem;
        color: #555;
        background-color: transparent;
        transition: all 0.15s ease;
        border-left: 3px solid transparent;
    }

    .flyout-group-item:hover {
        background-color: #ffffff;
        border-left-color: #667eea;
        color: #667eea;
        padding-left: 36px;
    }

    .flyout-group-item.selected {
        background-color: #667eea;
        color: white;
        border-left-color: #5568d3;
        font-weight: 500;
    }

    .flyout-group-item.selected:hover {
        padding-left: 32px;
    }

    /* Ensure parent container doesn't clip the flyout */
    .col-md-6:has(.flyout-dropdown-wrapper) {
        overflow: visible !important;
    }

    .row:has(.flyout-dropdown-wrapper) {
        overflow: visible !important;
    }

    .card-body:has(.flyout-dropdown-wrapper) {
        overflow: visible !important;
    }

    .card:has(.flyout-dropdown-wrapper) {
        overflow: visible !important;
    }

    .form-step:has(.flyout-dropdown-wrapper) {
        overflow: visible !important;
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
                            {{-- PROGRAMME & GROUP - NOW ON THE LEFT --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Programme & Group <span class="text-danger">*</span></label>

                                <!-- Flyout Menu Dropdown -->
                                <div class="flyout-dropdown-wrapper">
                                    <input type="text"
                                           class="flyout-dropdown-input form-control"
                                           id="flyout_selected_display"
                                           placeholder="Select Your Programme and Group"
                                           readonly
                                           autocomplete="off"
                                           required
                                           style="cursor: pointer;">
                                    <span class="flyout-dropdown-arrow">▼</span>

                                    <!-- Main Dropdown Menu - Simple single column with inline submenus -->
                                    <div class="flyout-dropdown-menu" id="flyout_menu">
                                        <div class="flyout-menu-column">
                                            <div class="flyout-menu-item" data-code="CDCS230" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.)" data-groups='["CDCS2301B","CDCS2303B","CDCS2303C"]'>
                                                <span class="flyout-item-text">CDCS230 - Bachelor of Computer Science (Hons.)</span>
                                                <i class="fas fa-caret-right flyout-chevron"></i>
                                                <div class="flyout-submenu">
                                                    <div class="flyout-group-item" data-group="CDCS2301B" data-code="CDCS230" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.)">CDCS2301B</div>
                                                    <div class="flyout-group-item" data-group="CDCS2303B" data-code="CDCS230" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.)">CDCS2303B</div>
                                                    <div class="flyout-group-item" data-group="CDCS2303C" data-code="CDCS230" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.)">CDCS2303C</div>
                                                </div>
                                            </div>
                                            <div class="flyout-menu-item" data-code="CDCS251" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) NETCENTRIC COMPUTING" data-groups='["CDCS2513A"]'>
                                                <span class="flyout-item-text">CDCS251 - Bachelor of Computer Science (Hons.) Netcentric Computing</span>
                                                <i class="fas fa-caret-right flyout-chevron"></i>
                                                <div class="flyout-submenu">
                                                    <div class="flyout-group-item" data-group="CDCS2513A" data-code="CDCS251" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) NETCENTRIC COMPUTING">CDCS2513A</div>
                                                </div>
                                            </div>
                                            <div class="flyout-menu-item" data-code="CDCS253" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) MULTIMEDIA COMPUTING" data-groups='["CDCS2531A","CDCS2533B"]'>
                                                <span class="flyout-item-text">CDCS253 - Bachelor of Computer Science (Hons.) Multimedia Computing</span>
                                                <i class="fas fa-caret-right flyout-chevron"></i>
                                                <div class="flyout-submenu">
                                                    <div class="flyout-group-item" data-group="CDCS2531A" data-code="CDCS253" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) MULTIMEDIA COMPUTING">CDCS2531A</div>
                                                    <div class="flyout-group-item" data-group="CDCS2533B" data-code="CDCS253" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) MULTIMEDIA COMPUTING">CDCS2533B</div>
                                                </div>
                                            </div>
                                            <div class="flyout-menu-item" data-code="CDCS255" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) COMPUTER NETWORKS" data-groups='["CDCS2551A","CDCS2553B"]'>
                                                <span class="flyout-item-text">CDCS255 - Bachelor of Computer Science (Hons.) Computer Networks</span>
                                                <i class="fas fa-caret-right flyout-chevron"></i>
                                                <div class="flyout-submenu">
                                                    <div class="flyout-group-item" data-group="CDCS2551A" data-code="CDCS255" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) COMPUTER NETWORKS">CDCS2551A</div>
                                                    <div class="flyout-group-item" data-group="CDCS2553B" data-code="CDCS255" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) COMPUTER NETWORKS">CDCS2553B</div>
                                                </div>
                                            </div>
                                            <div class="flyout-menu-item" data-code="CDCS266" data-name="BACHELOR OF INFORMATION SYSTEMS (HONS.) INFORMATION SYSTEMS ENGINEERING" data-groups='["CDCS2663A"]'>
                                                <span class="flyout-item-text">CDCS266 - Bachelor of Information Systems (Hons.) Information Systems Engineering</span>
                                                <i class="fas fa-caret-right flyout-chevron"></i>
                                                <div class="flyout-submenu">
                                                    <div class="flyout-group-item" data-group="CDCS2663A" data-code="CDCS266" data-name="BACHELOR OF INFORMATION SYSTEMS (HONS.) INFORMATION SYSTEMS ENGINEERING">CDCS2663A</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden Inputs for Form Submission -->
                                <input type="hidden" name="student_group" id="student_group" value="{{ old('student_group', '') }}" required>
                                <input type="hidden" name="program_name" id="program_name" value="{{ old('program_name', '') }}">
                                <input type="hidden" name="program_code" id="program_code" value="{{ old('program_code', '') }}">
                                <input type="hidden" name="faculty" value="FAKULTI SAINS KOMPUTER DAN MATEMATIK">
                                <input type="hidden" name="faculty_id" value="">

                                @error('student_group')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- CURRENT CAMPUS - NOW ON THE RIGHT --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Campus <span class="text-danger">*</span></label>
                                <select name="campus" id="campus_select" class="form-select searchable-select" required>
                                    <option value="" disabled selected>Select Your Campus</option>
                                    @foreach($campuses as $campus)
                                        <option value="{{ $campus->name }}" {{ old('campus') == $campus->name ? 'selected' : '' }}>{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
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
                                <select name="current_semester" class="form-select @error('current_semester') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('current_semester') ? '' : 'selected' }}>Select your semester</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('current_semester') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('current_semester')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3"></div>
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
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Diploma Institution</label>
                                    <select name="previous_institution" class="form-select searchable-select">
                                        <option value="" disabled selected>Select Your Institution</option>
                                        @foreach($nonUitmInstitutions as $institution)
                                            <option value="{{ $institution->name }}">{{ $institution->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Diploma Programme Code</label>
                                    <input type="text" name="previous_program_code" class="form-control text-uppercase @error('previous_program_code') is-invalid @enderror" value="{{ old('previous_program_code') }}" placeholder="e.g., CS110" maxlength="10">
                                    <small class="text-muted">Enter your diploma programme code if available</small>
                                    @error('previous_program_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Diploma Programme Name</label>
                                    <input type="text" name="previous_program" class="form-control @error('previous_program') is-invalid @enderror" value="{{ old('previous_program') }}" placeholder="e.g., Diploma in Computer Science">
                                    @error('previous_program')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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

        {{-- STEP 2: Upload Transcript or Manual Entry --}}
        <div class="form-step" id="step-2">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="fas fa-file-upload"></i> Submit Transcript Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            {{-- Entry Method Selection --}}
                            <div class="mb-4" id="entry_method_selection">
                                {{-- For UiTM Previous Institution - OCR Only --}}
                                <div id="uitm_entry_options">
                                    <div class="alert alert-info border-primary" style="background-color: #e7f3ff;">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-robot me-3 mt-1" style="font-size: 2rem; color: #0d6efd;"></i>
                                            <div>
                                                <h6 class="mb-2"><strong>OCR Automatic Transcript Processing</strong></h6>
                                                <p class="mb-2">For UiTM diploma students, we use OCR (Optical Character Recognition) technology to automatically extract your course information from your official transcript.</p>
                                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                                    <i class="fas fa-check-circle me-1 text-success"></i> Fast and accurate course extraction<br>
                                                    <i class="fas fa-check-circle me-1 text-success"></i> Automatic grade and credit hour detection<br>
                                                    <i class="fas fa-check-circle me-1 text-success"></i> Instant exemption eligibility analysis
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="entry_method" id="entry_method_ocr" value="ocr">
                                </div>

                                {{-- For Non-UiTM Previous Institution - Manual Entry Only --}}
                                <div id="non_uitm_entry_options" style="display: none;">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Manual Entry Required:</strong> OCR automatic extraction is only available for UiTM transcripts. For students from non-UiTM institution, please enter your diploma course details manually.
                                    </div>
                                    <input type="hidden" name="entry_method" id="entry_method_manual_hidden" value="manual" disabled>
                                </div>
                            </div>

                            {{-- OCR Upload Section --}}
                            <div id="ocr_section">
                                <div class="text-center mb-4">
                                    <h5>Upload Your Official UiTM Transcript</h5>
                                    <p class="text-muted">Upload a computer-generated transcript for best accuracy. This transcript will be used for OCR processing to extract your course information automatically. Ensure the document is clear, complete, and in PDF format to achieve accurate results.</p>
                                </div>

                                <div class="mb-4">
                                    <label for="transcript_file" class="form-label">Transcript (PDF only, Max 5MB) <span class="text-danger">*</span></label>
                                    <input class="form-control form-control-lg @error('transcript_file') is-invalid @enderror" type="file" id="transcript_file" name="transcript_file" accept=".pdf">
                                    @error('transcript_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Declaration Checkbox --}}
                                <div class="alert alert-warning border-warning" style="background-color: #fff3cd;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="transcript_declaration" name="transcript_declaration" style="width: 18px; height: 18px; margin-top: 0.25em;">
                                        <label class="form-check-label" for="transcript_declaration" style="margin-left: 8px; line-height: 1.6; font-size: 0.95rem;">
                                            I confirm this is my official, unaltered transcript. I understand that submitting falsified or edited documents is a serious offense and may result in disciplinary action, including rejection of my application and further sanctions.
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Manual Entry Section --}}
                            <div id="manual_entry_section" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i><strong>Manual Entry Instructions:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Enter each course from your diploma transcript</li>
                                        <li>Provide course code, name, grade, and credit hours</li>
                                        <li>System will automatically check for course equivalencies</li>
                                        <li>The exemption status shown (EXEMPTED, NOT FOUND, etc.) is <strong>preliminary only</strong></li>
                                        <li>Your application will be reviewed by your academic advisor</li>
                                        <li>Make sure to enter all courses before submitting</li>
                                    </ul>
                                </div>

                                {{-- Course Entry Form --}}
                                <div class="card mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add Course</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control text-uppercase" id="manual_course_code" placeholder="e.g., CSC159" maxlength="10" pattern="[A-Z]{2,4}\d{3}" title="Format: 2-4 letters + 3 digits (e.g., CSC159)">
                                                <small class="text-muted">Format: ABC123</small>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="manual_course_name" placeholder="e.g., COMPUTER ORGANIZATION" maxlength="255">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Grade <span class="text-danger">*</span></label>
                                                <select class="form-select" id="manual_grade">
                                                    <option value="">Select</option>
                                                    <option value="A+">A+</option>
                                                    <option value="A">A</option>
                                                    <option value="A-">A-</option>
                                                    <option value="B+">B+</option>
                                                    <option value="B">B</option>
                                                    <option value="B-">B-</option>
                                                    <option value="C+">C+</option>
                                                    <option value="C">C</option>
                                                    <option value="C-">C-</option>
                                                    <option value="D+">D+</option>
                                                    <option value="D">D</option>
                                                    <option value="F">F</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="manual_credit_hours" placeholder="3" min="1" max="6" step="0.5" value="3">
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label" style="visibility: hidden;">Action</label>
                                                <button type="button" class="btn btn-primary w-100" onclick="addManualCourse()">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Courses List --}}
                                <div class="card">
                                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><i class="fas fa-list me-2"></i>Added Courses</h6>
                                        <span class="badge bg-light text-dark" id="course_count">0 courses</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="manual_courses_list" class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="15%">Course Code</th>
                                                        <th width="40%">Course Name</th>
                                                        <th width="10%">Grade</th>
                                                        <th width="12%">Credit Hours</th>
                                                        <th width="18%">Exemption Status</th>
                                                        <th width="5%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="manual_courses_tbody">
                                                    <tr class="text-center text-muted">
                                                        <td colspan="6" class="py-4">
                                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                                            <p class="mb-0">No courses added yet. Use the form above to add courses.</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- Hidden input to store manual courses as JSON --}}
                                <input type="hidden" name="manual_courses" id="manual_courses_data" value="[]">

                                {{-- Manual Entry Declaration --}}
                                <div class="alert alert-warning border-warning mt-3" style="background-color: #fff3cd;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="manual_declaration" name="manual_declaration" style="width: 18px; height: 18px; margin-top: 0.25em;">
                                        <label class="form-check-label" for="manual_declaration" style="margin-left: 8px; line-height: 1.6; font-size: 0.95rem;">
                                            I confirm that all the course information I have entered is accurate and matches my official transcript. I understand that providing false information is a serious offense and may result in disciplinary action.
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 2 Navigation --}}
                            <div class="step-navigation mt-4">
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
            // Check institution type and configure Step 2 entry methods accordingly
            updateEntryMethodOptions();
            goToStep(2);
        }
    });

    $('#backToStep1').on('click', function() {
        goToStep(1);
    });

    // Note: Flyout menu initialization handles old values automatically

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

    // Collect all options (trim whitespace) and preserve optgroup structure
    $select.find('option').each(function() {
        var value = $(this).val();
        var text = $.trim($(this).text());
        var $optgroup = $(this).parent('optgroup');
        var groupLabel = $optgroup.length ? $optgroup.attr('label') : null;

        if (value) {
            options.push({
                value: value,
                text: text,
                group: groupLabel
            });
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
                return opt.text.toLowerCase().indexOf(searchTerm) > -1 ||
                       (opt.group && opt.group.toLowerCase().indexOf(searchTerm) > -1);
            });
        }

        if (filteredOptions.length === 0) {
            $menu.append('<div class="searchable-dropdown-option no-results">No results found</div>');
        } else {
            var currentGroup = null;

            filteredOptions.forEach(function(opt) {
                // Add group header if this is a new group
                if (opt.group && opt.group !== currentGroup) {
                    var $groupHeader = $('<div class="searchable-dropdown-group-header"></div>');
                    $groupHeader.text(opt.group);
                    $menu.append($groupHeader);
                    currentGroup = opt.group;
                }

                var $option = $('<div class="searchable-dropdown-option" data-value="' + opt.value + '"></div>');

                // Add indentation for grouped options
                if (opt.group) {
                    $option.addClass('grouped-option');
                }

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

        // Note: Campus dropdown uses searchable dropdown, program/group uses flyout menu
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

        // Show UiTM entry options (OCR only), hide non-UiTM options
        const uitmEntryOptions = document.getElementById('uitm_entry_options');
        const nonUitmEntryOptions = document.getElementById('non_uitm_entry_options');
        if (uitmEntryOptions) uitmEntryOptions.style.display = 'block';
        if (nonUitmEntryOptions) {
            nonUitmEntryOptions.style.display = 'none';
            const manualHiddenInput = document.getElementById('entry_method_manual_hidden');
            if (manualHiddenInput) manualHiddenInput.disabled = true;
        }

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
        document.querySelector('input[name="previous_program_code"]').disabled = false;

        // Show non-UiTM entry options (manual only), hide UiTM options
        const uitmEntryOptions = document.getElementById('uitm_entry_options');
        const nonUitmEntryOptions = document.getElementById('non_uitm_entry_options');
        if (uitmEntryOptions) uitmEntryOptions.style.display = 'none';
        if (nonUitmEntryOptions) {
            nonUitmEntryOptions.style.display = 'block';
            const manualHiddenInput = document.getElementById('entry_method_manual_hidden');
            if (manualHiddenInput) manualHiddenInput.disabled = false;
        }

        // Force manual entry mode for non-UiTM students
        toggleEntryMethod(true);

        // Initialize searchable dropdowns for Non-UiTM fields
        setTimeout(function() {
            $('#non_uitm_fields .searchable-select').each(function() {
                initSearchableDropdown($(this));
            });
        }, 100);

    } else {
        uitmFields.style.display = 'none';
        nonUitmFields.style.display = 'none';

        // Hide both entry option sections
        const uitmEntryOptions = document.getElementById('uitm_entry_options');
        const nonUitmEntryOptions = document.getElementById('non_uitm_entry_options');
        if (uitmEntryOptions) uitmEntryOptions.style.display = 'none';
        if (nonUitmEntryOptions) nonUitmEntryOptions.style.display = 'none';
    }
}

// ========================================
// FLYOUT MENU FUNCTIONALITY
// Simple dropdown with horizontal inline submenus
// ========================================

// Use vanilla JavaScript to avoid jQuery conflicts
document.addEventListener('DOMContentLoaded', function() {
    const flyoutInput = document.getElementById('flyout_selected_display');
    const flyoutMenu = document.getElementById('flyout_menu');
    const flyoutArrow = document.querySelector('.flyout-dropdown-arrow');
    let selectedGroup = '';
    let selectedProgramCode = '';
    let selectedProgramName = '';

    console.log('Flyout menu initialized (vanilla JS)', {
        input: flyoutInput,
        menu: flyoutMenu,
        arrow: flyoutArrow
    });

    // Open/Close menu on input click and wrapper click
    if (flyoutInput && flyoutMenu) {
        const wrapper = document.querySelector('.flyout-dropdown-wrapper');

        // Click handler function
        const toggleMenu = function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Dropdown clicked! Current classes:', flyoutMenu.className);
            flyoutMenu.classList.toggle('show');
            if (flyoutArrow) flyoutArrow.classList.toggle('open');
            console.log('After toggle, show class:', flyoutMenu.classList.contains('show'));
        };

        // Add click to both input and wrapper
        flyoutInput.addEventListener('click', toggleMenu);
        if (wrapper) {
            wrapper.addEventListener('click', function(e) {
                // Only trigger if clicking the wrapper or input, not submenu
                if (e.target === wrapper || e.target === flyoutInput || e.target === flyoutArrow) {
                    toggleMenu(e);
                }
            });
        }
    } else {
        console.error('Flyout elements not found!', { input: flyoutInput, menu: flyoutMenu });
    }

    // Handle programme item click - expand/collapse groups
    document.addEventListener('click', function(e) {
        const programmeItem = e.target.closest('.flyout-menu-item');

        if (programmeItem && !e.target.classList.contains('flyout-group-item')) {
            e.stopPropagation();

            // Toggle expansion
            const submenu = programmeItem.querySelector('.flyout-submenu');
            const isExpanded = programmeItem.classList.contains('expanded');

            // Close all other expanded items
            document.querySelectorAll('.flyout-menu-item.expanded').forEach(item => {
                if (item !== programmeItem) {
                    item.classList.remove('expanded');
                    const otherSubmenu = item.querySelector('.flyout-submenu');
                    if (otherSubmenu) otherSubmenu.classList.remove('expanded');
                }
            });

            // Toggle current item
            if (isExpanded) {
                programmeItem.classList.remove('expanded');
                if (submenu) submenu.classList.remove('expanded');
            } else {
                programmeItem.classList.add('expanded');
                if (submenu) submenu.classList.add('expanded');
            }
        }
    });

    // Handle group selection (click on group item)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('flyout-group-item')) {
            e.stopPropagation();

            const groupItem = e.target;
            const group = groupItem.getAttribute('data-group');
            const code = groupItem.getAttribute('data-code');
            const name = groupItem.getAttribute('data-name');

            // Update selection
            selectedGroup = group;
            selectedProgramCode = code;
            selectedProgramName = name;

            // Update display input
            flyoutInput.value = code + ' - ' + group;

            // Update hidden form inputs
            document.getElementById('student_group').value = group;
            document.getElementById('program_code').value = code;
            document.getElementById('program_name').value = name;

            // Visual feedback - mark as selected
            document.querySelectorAll('.flyout-group-item').forEach(item => {
                item.classList.remove('selected');
            });
            groupItem.classList.add('selected');

            // Close expanded submenu and menu
            document.querySelectorAll('.flyout-menu-item.expanded').forEach(item => {
                item.classList.remove('expanded');
                const submenu = item.querySelector('.flyout-submenu');
                if (submenu) submenu.classList.remove('expanded');
            });

            flyoutMenu.classList.remove('show');
            if (flyoutArrow) flyoutArrow.classList.remove('open');

            console.log('✓ Selected:', { group, code, name });
        }
    });

    // Close menu on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.flyout-dropdown-wrapper')) {
            // Collapse all expanded items
            document.querySelectorAll('.flyout-menu-item.expanded').forEach(item => {
                item.classList.remove('expanded');
                const submenu = item.querySelector('.flyout-submenu');
                if (submenu) submenu.classList.remove('expanded');
            });

            flyoutMenu.classList.remove('show');
            if (flyoutArrow) flyoutArrow.classList.remove('open');
        }
    });

    // Keyboard navigation (ESC to close)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && flyoutMenu.classList.contains('show')) {
            // Collapse all expanded items
            document.querySelectorAll('.flyout-menu-item.expanded').forEach(item => {
                item.classList.remove('expanded');
                const submenu = item.querySelector('.flyout-submenu');
                if (submenu) submenu.classList.remove('expanded');
            });

            flyoutMenu.classList.remove('show');
            if (flyoutArrow) flyoutArrow.classList.remove('open');
        }
    });

    // Restore previously selected value (from old() Laravel helper)
    const oldGroup = document.getElementById('student_group').value;
    const oldCode = document.getElementById('program_code').value;

    if (oldGroup && oldCode) {
        selectedGroup = oldGroup;
        selectedProgramCode = oldCode;
        selectedProgramName = document.getElementById('program_name').value;
        flyoutInput.value = oldCode + ' - ' + oldGroup;

        // Mark the selected group item
        document.querySelectorAll('.flyout-group-item').forEach(function(item) {
            if (item.getAttribute('data-group') === oldGroup &&
                item.getAttribute('data-code') === oldCode) {
                item.classList.add('selected');
            }
        });
    }
});

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

    // Check required select and hidden fields
    // Campus - select field
    const campusSelect = document.querySelector('select[name="campus"]');
    if (!campusSelect || !campusSelect.value) {
        isValid = false;
        errors.push('Current Campus');
        if (campusSelect) campusSelect.classList.add('is-invalid');
    } else {
        if (campusSelect) campusSelect.classList.remove('is-invalid');
    }

    // Programme & Group - hidden input field
    const studentGroupInput = document.getElementById('student_group');
    const flyoutDisplayInput = document.getElementById('flyout_selected_display');
    if (!studentGroupInput || !studentGroupInput.value) {
        isValid = false;
        errors.push('Programme & Group');
        if (flyoutDisplayInput) flyoutDisplayInput.classList.add('is-invalid');
    } else {
        if (flyoutDisplayInput) flyoutDisplayInput.classList.remove('is-invalid');
    }

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

    // Validate current semester selection
    const semesterSelect = document.querySelector('select[name="current_semester"]');
    if (semesterSelect && !semesterSelect.value) {
        isValid = false;
        errors.push('Current Semester');
        semesterSelect.classList.add('is-invalid');
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

// ========================================
// MANUAL TRANSCRIPT ENTRY FUNCTIONS
// ========================================

// Array to store manually entered courses
let manualCourses = [];

// Update entry method options based on institution type selection in Step 1
function updateEntryMethodOptions() {
    const institutionType = document.querySelector('input[name="institution_type"]:checked');
    const uitmEntryOptions = document.getElementById('uitm_entry_options');
    const nonUitmEntryOptions = document.getElementById('non_uitm_entry_options');
    const hiddenEntryMethod = document.getElementById('entry_method_manual_hidden');

    if (!institutionType) {
        console.warn('No institution type selected');
        return;
    }

    if (institutionType.value === 'non_uitm') {
        // Non-UiTM: Show manual entry only
        uitmEntryOptions.style.display = 'none';
        nonUitmEntryOptions.style.display = 'block';

        // Enable hidden input for non-UiTM
        if (hiddenEntryMethod) hiddenEntryMethod.disabled = false;

        // Automatically trigger manual entry mode
        toggleEntryMethod(true); // Force manual mode
    } else {
        // UiTM: Show OCR only (no manual entry option)
        uitmEntryOptions.style.display = 'block';
        nonUitmEntryOptions.style.display = 'none';

        // Disable non-UiTM hidden input
        if (hiddenEntryMethod) hiddenEntryMethod.disabled = true;

        // UiTM students always use OCR (handled by hidden input in HTML)
        // Force OCR mode
        toggleEntryMethod(false);
    }
}

// Toggle between OCR upload and Manual Entry
function toggleEntryMethod(forceManual = false) {
    const ocrSection = document.getElementById('ocr_section');
    const manualSection = document.getElementById('manual_entry_section');
    const transcriptFile = document.getElementById('transcript_file');
    const transcriptDeclaration = document.getElementById('transcript_declaration');
    const manualDeclaration = document.getElementById('manual_declaration');

    // Check if UiTM student (has hidden OCR input)
    const ocrHiddenInput = document.querySelector('#uitm_entry_options input[name="entry_method"][value="ocr"]');

    // For UiTM students, always show OCR section (no manual entry option)
    if (ocrHiddenInput && ocrHiddenInput.type === 'hidden') {
        ocrSection.style.display = 'block';
        manualSection.style.display = 'none';
        transcriptFile.setAttribute('required', 'required');
        transcriptDeclaration.setAttribute('required', 'required');
        if (manualDeclaration) manualDeclaration.removeAttribute('required');
        return;
    }

    // For non-UiTM students, determine if manual mode should be active
    let manualMode = forceManual;

    if (!forceManual) {
        const ocrRadio = document.getElementById('entry_method_ocr');
        const manualRadioUitm = document.getElementById('entry_method_manual_uitm');

        // Check which radio is selected (if available)
        if (ocrRadio && ocrRadio.type === 'radio' && ocrRadio.checked) {
            manualMode = false;
        } else if (manualRadioUitm && manualRadioUitm.checked) {
            manualMode = true;
        }
    }

    if (manualMode) {
        // Show manual section, hide OCR section
        ocrSection.style.display = 'none';
        manualSection.style.display = 'block';

        // Disable OCR fields, enable manual fields
        transcriptFile.removeAttribute('required');
        transcriptDeclaration.removeAttribute('required');
        if (manualDeclaration) manualDeclaration.setAttribute('required', 'required');
    } else {
        // Show OCR section, hide manual section
        ocrSection.style.display = 'block';
        manualSection.style.display = 'none';

        // Enable OCR fields, disable manual fields
        transcriptFile.setAttribute('required', 'required');
        transcriptDeclaration.setAttribute('required', 'required');
        if (manualDeclaration) manualDeclaration.removeAttribute('required');
    }
}

// Add manual course to the list
function addManualCourse() {
    // Get input values
    const courseCode = document.getElementById('manual_course_code').value.trim().toUpperCase();
    const courseName = document.getElementById('manual_course_name').value.trim();
    const grade = document.getElementById('manual_grade').value;
    const creditHours = parseFloat(document.getElementById('manual_credit_hours').value);

    // Validate inputs
    if (!courseCode || !courseName || !grade || !creditHours) {
        alert('Please fill in all course fields before adding.');
        return;
    }

    // Validate course code format
    const courseCodePattern = /^[A-Z]{2,4}\d{3}$/;
    if (!courseCodePattern.test(courseCode)) {
        alert('Invalid course code format. Use format like CSC159 (2-4 letters + 3 digits).');
        document.getElementById('manual_course_code').focus();
        return;
    }

    // Check for duplicates
    if (manualCourses.some(course => course.code === courseCode)) {
        alert('This course code has already been added.');
        return;
    }

    // Get program code from Step 1
    const programCode = document.getElementById('program_code').value;

    // Check equivalency via AJAX
    checkCourseEquivalency(courseCode, courseName, grade, creditHours, programCode);
}

// Check course equivalency and add to table
function checkCourseEquivalency(courseCode, courseName, grade, creditHours, programCode) {
    // Show loading state
    const addButton = document.querySelector('button[onclick="addManualCourse()"]');
    const originalButtonHtml = addButton.innerHTML;
    addButton.disabled = true;
    addButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    // Get institution info for non-UiTM students
    const institutionType = document.querySelector('input[name="institution_type"]:checked')?.value || 'uitm';
    let institution = null;
    if (institutionType === 'non_uitm') {
        institution = document.querySelector('select[name="previous_institution"]')?.value || null;
    } else {
        institution = document.querySelector('select[name="previous_uitm_campus"]')?.value || 'UiTM';
    }

    // Make AJAX request to check equivalency with institution-aware matching
    fetch('{{ route("student.application.check-equivalency") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            course_code: courseCode,
            program_code: programCode || 'CDCS251',
            institution: institution,
            institution_type: institutionType
        })
    })
    .then(response => response.json())
    .then(data => {
        // Convert grade to GPA
        const gradeGPA = gradeToGPA(grade);

        // Determine exemption status based on three-criteria validation
        let status = 'pending';
        let statusBadge = '';
        let exemptionReason = '';

        if (data.found) {
            const gradeAcceptable = isGradeAcceptable(grade);
            const matchPercentageOK = data.match_percentage > 80;

            if (gradeAcceptable && matchPercentageOK) {
                status = 'exempted';
                statusBadge = '<span class="badge bg-success"><i class="fas fa-check-circle"></i> EXEMPTED</span>';
                exemptionReason = `All criteria met: Course found, grade ${grade} ≥ C, match ${data.match_percentage}% > 80%`;
            } else if (!gradeAcceptable) {
                status = 'not_eligible_grade';
                statusBadge = '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> GRADE TOO LOW</span>';
                exemptionReason = `Grade ${grade} is below minimum requirement (C required)`;
            } else if (!matchPercentageOK) {
                status = 'not_eligible_match';
                statusBadge = '<span class="badge bg-warning text-dark"><i class="fas fa-exclamation-circle"></i> LOW MATCH</span>';
                exemptionReason = `Match percentage ${data.match_percentage}% is below 80% threshold`;
            }
        } else {
            status = 'not_found';
            statusBadge = '<span class="badge bg-secondary"><i class="fas fa-question-circle"></i> NOT FOUND</span>';
            // Use institution-specific message if available
            exemptionReason = data.message || `Course not found in ${programCode} equivalency database`;
        }

        // Create course object
        const course = {
            code: courseCode,
            name: courseName,
            grade: grade,
            gradeGPA: gradeGPA,
            creditHours: creditHours,
            status: status,
            exemptionReason: exemptionReason,
            equivalentCourse: data.degree_course_code || null,
            matchPercentage: data.match_percentage || 0
        };

        // Add to array
        manualCourses.push(course);

        // Update hidden input
        document.getElementById('manual_courses_data').value = JSON.stringify(manualCourses);

        // Add to table
        addCourseToTable(course, statusBadge);

        // Clear form
        clearManualEntryForm();

        // Restore button
        addButton.disabled = false;
        addButton.innerHTML = originalButtonHtml;

        // Update counter
        updateCourseCount();
    })
    .catch(error => {
        console.error('Error checking equivalency:', error);
        alert('An error occurred while checking course equivalency. The course will be added for manual review.');

        // Add course without equivalency check
        const course = {
            code: courseCode,
            name: courseName,
            grade: grade,
            gradeGPA: gradeToGPA(grade),
            creditHours: creditHours,
            status: 'pending',
            exemptionReason: 'Pending equivalency verification',
            equivalentCourse: null,
            matchPercentage: 0
        };

        manualCourses.push(course);
        document.getElementById('manual_courses_data').value = JSON.stringify(manualCourses);

        const statusBadge = '<span class="badge bg-info"><i class="fas fa-clock"></i> PENDING</span>';
        addCourseToTable(course, statusBadge);
        clearManualEntryForm();

        addButton.disabled = false;
        addButton.innerHTML = originalButtonHtml;
        updateCourseCount();
    });
}

// Add course to the table display
function addCourseToTable(course, statusBadge) {
    const tbody = document.getElementById('manual_courses_tbody');

    // Remove empty state if exists
    if (tbody.querySelector('.text-muted')) {
        tbody.innerHTML = '';
    }

    // Create row
    const row = document.createElement('tr');
    row.setAttribute('data-course-code', course.code);
    row.innerHTML = `
        <td><code class="text-primary">${course.code}</code></td>
        <td>${course.name}</td>
        <td>${course.grade}</td>
        <td>${course.creditHours}</td>
        <td>${statusBadge}</td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeManualCourse('${course.code}')">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
}

// Remove course from the list
function removeManualCourse(courseCode) {
    if (!confirm(`Remove course ${courseCode}?`)) {
        return;
    }

    // Remove from array
    manualCourses = manualCourses.filter(course => course.code !== courseCode);

    // Update hidden input
    document.getElementById('manual_courses_data').value = JSON.stringify(manualCourses);

    // Remove from table
    const row = document.querySelector(`tr[data-course-code="${courseCode}"]`);
    if (row) {
        row.remove();
    }

    // Update counter
    updateCourseCount();

    // Restore empty state if no courses
    const tbody = document.getElementById('manual_courses_tbody');
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr class="text-center text-muted">
                <td colspan="6" class="py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p class="mb-0">No courses added yet. Use the form above to add courses.</p>
                </td>
            </tr>
        `;
    }
}

// Clear manual entry form
function clearManualEntryForm() {
    document.getElementById('manual_course_code').value = '';
    document.getElementById('manual_course_name').value = '';
    document.getElementById('manual_grade').value = '';
    document.getElementById('manual_credit_hours').value = '3';
    document.getElementById('manual_course_code').focus();
}

// Update course counter
function updateCourseCount() {
    const count = manualCourses.length;
    document.getElementById('course_count').textContent = count + (count === 1 ? ' course' : ' courses');
}

// Convert letter grade to GPA
function gradeToGPA(grade) {
    const gradeMap = {
        'A+': 4.00, 'A': 4.00, 'A-': 3.67,
        'B+': 3.33, 'B': 3.00, 'B-': 2.67,
        'C+': 2.33, 'C': 2.00, 'C-': 1.67,
        'D+': 1.33, 'D': 1.00, 'F': 0.00
    };
    return gradeMap[grade] || 0.00;
}

// Check if grade is acceptable (C or above)
function isGradeAcceptable(grade) {
    const acceptableGrades = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'];
    return acceptableGrades.includes(grade);
}

// Update form validation for manual entry
$(document).ready(function() {
    // Modify form submit validation
    const originalFormSubmit = $('form').off('submit').on('submit', function(e) {
        // Get entry method from either radio buttons or hidden inputs
        let entryMethod = document.querySelector('input[name="entry_method"]:checked');

        // If no radio is checked, check for hidden inputs (UiTM OCR or non-UiTM manual)
        if (!entryMethod) {
            // Check for UiTM OCR hidden input
            const uitmOcrHidden = document.querySelector('#uitm_entry_options input[name="entry_method"][value="ocr"]');
            if (uitmOcrHidden && uitmOcrHidden.type === 'hidden') {
                entryMethod = { value: uitmOcrHidden.value };
            }

            // Check for non-UiTM manual hidden input
            if (!entryMethod) {
                const hiddenEntryMethod = document.getElementById('entry_method_manual_hidden');
                if (hiddenEntryMethod && !hiddenEntryMethod.disabled) {
                    entryMethod = { value: hiddenEntryMethod.value };
                }
            }
        }

        if (!entryMethod) {
            e.preventDefault();
            alert('Please select an entry method (OCR Upload or Manual Entry).');
            return false;
        }

        if (entryMethod.value === 'ocr') {
            // OCR validation (existing)
            const transcriptFile = document.querySelector('input[name="transcript_file"]');
            if (!transcriptFile || !transcriptFile.files || transcriptFile.files.length === 0) {
                e.preventDefault();
                alert('Upload your official transcript to proceed.');
                transcriptFile.classList.add('is-invalid');
                transcriptFile.focus();
                return false;
            }

            if (transcriptFile.files[0].size > 5 * 1024 * 1024) {
                e.preventDefault();
                alert('File size must not exceed 5MB.');
                transcriptFile.classList.add('is-invalid');
                return false;
            }

            const declarationCheckbox = document.getElementById('transcript_declaration');
            if (!declarationCheckbox || !declarationCheckbox.checked) {
                e.preventDefault();
                alert('Please confirm that you are submitting an official, unaltered transcript by checking the declaration box.');
                declarationCheckbox.focus();
                const alertBox = declarationCheckbox.closest('.alert');
                if (alertBox) {
                    alertBox.style.border = '2px solid #dc3545';
                    setTimeout(() => { alertBox.style.border = ''; }, 3000);
                }
                return false;
            }
        } else {
            // Manual entry validation
            if (manualCourses.length === 0) {
                e.preventDefault();
                alert('Please add at least one course before submitting.');
                document.getElementById('manual_course_code').focus();
                return false;
            }

            const manualDeclaration = document.getElementById('manual_declaration');
            if (!manualDeclaration || !manualDeclaration.checked) {
                e.preventDefault();
                alert('Please confirm that the course information you entered is accurate by checking the declaration box.');
                manualDeclaration.focus();
                const alertBox = manualDeclaration.closest('.alert');
                if (alertBox) {
                    alertBox.style.border = '2px solid #dc3545';
                    setTimeout(() => { alertBox.style.border = ''; }, 3000);
                }
                return false;
            }
        }

        return true;
    });

    // Auto-uppercase course code input
    $('#manual_course_code').on('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Auto-uppercase diploma programme code input
    $('input[name="previous_program_code"]').on('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Handle Enter key in manual entry form
    $('#manual_entry_section input, #manual_entry_section select').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            addManualCourse();
        }
    });
});
</script>
@endpush
