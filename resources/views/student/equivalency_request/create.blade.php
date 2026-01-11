@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2"><i class="fas fa-search me-2"></i>Course Equivalency Checker</h2>
                    <p class="text-muted">Check if your diploma course is equivalent to a UiTM degree course</p>
                </div>
                <a href="{{ route('student.equivalency.request.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-list me-2"></i>My Requests
                </a>
            </div>
        </div>
    </div>

    <!-- Information Alert -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>How This Works</h5>
                <ol class="mb-0 ps-3">
                    <li>Select your diploma course and the UiTM degree course you want to compare</li>
                    <li>Click "Check Equivalency" to see if they are already recognized as equivalent</li>
                    <li>If no equivalency exists, you can submit a request for review by your Program Coordinator</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Equivalency Checker Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search Courses</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Diploma Course <span class="text-danger">*</span></label>
                            <select id="diploma_course_select" name="diploma_course" class="form-select searchable-select">
                                <option value="">Select diploma course...</option>
                                @foreach($diplomaCourses as $course)
                                    <option value="{{ $course->diploma_course_code }}">
                                        {{ $course->diploma_course_code }} - {{ $course->diploma_course_name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Start typing to search diploma courses from all institutions</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UiTM Degree Course <span class="text-danger">*</span></label>
                            <select id="degree_course_select" name="degree_course" class="form-select searchable-select">
                                <option value="">Select degree course...</option>
                                @foreach($degreeCourses as $course)
                                    <option value="{{ $course->degree_course_code }}">{{ $course->degree_course_code }} - {{ $course->degree_course_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Start typing to search UiTM degree courses</small>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="button" id="checkEquivalencyBtn" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i>Check Equivalency
                        </button>
                    </div>
                </div>
            </div>

            <!-- Equivalent Result Card (Hidden Initially) -->
            <div id="equivalentResult" class="card shadow-sm mb-4" style="display: none; border-left: 6px solid #198754; background: linear-gradient(to right, rgba(25, 135, 84, 0.08) 0%, rgba(25, 135, 84, 0.02) 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success p-3 me-3">
                            <i class="fas fa-check-circle text-white fs-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-success fw-bold">Courses Are Equivalent</h5>
                            <small class="text-muted">This course pairing is recognized for credit transfer</small>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="bg-light rounded p-3">
                                <div class="text-muted small mb-2">Diploma Course</div>
                                <div class="mb-2" id="resultDiplomaInfo" style="font-size: 1.05rem;"></div>
                                <div class="d-flex justify-content-between text-muted small">
                                    <span>Credit Hours: <span id="resultDiplomaCreditHour"></span></span>
                                </div>
                                <div class="text-muted small mt-2">
                                    <span id="resultInstitution"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light rounded p-3">
                                <div class="text-muted small mb-2">UiTM Degree Course</div>
                                <div class="mb-2" id="resultDegreeInfo" style="font-size: 1.05rem;"></div>
                                <div class="d-flex justify-content-between text-muted small">
                                    <span>Credit Hours: <span id="resultDegreeCreditHour"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-success bg-opacity-10 rounded p-3 text-center">
                        <small class="text-muted">You can use this information when filling out your credit exemption application</small>
                    </div>
                </div>
            </div>

            <!-- Not Equivalent Result Card (Hidden Initially) -->
            <div id="notEquivalentResult" class="card shadow-sm mb-4" style="display: none; border-left: 6px solid #dc3545; background: linear-gradient(to right, rgba(220, 53, 69, 0.08) 0%, rgba(220, 53, 69, 0.02) 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-danger p-3 me-3">
                            <i class="fas fa-times-circle text-white fs-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-danger fw-bold">Courses Are Not Equivalent</h5>
                            <small class="text-muted">Match percentage is below the required threshold</small>
                        </div>
                    </div>

                    <div class="row g-4 mb-3">
                        <div class="col-md-6">
                            <div class="bg-light rounded p-3">
                                <div class="text-muted small mb-2">Diploma Course</div>
                                <div class="mb-2" id="notEquivDiplomaInfo" style="font-size: 1.05rem;"></div>
                                <div class="d-flex justify-content-between text-muted small">
                                    <span>Credit Hours: <span id="notEquivDiplomaCreditHour"></span></span>
                                </div>
                                <div class="text-muted small mt-2">
                                    <span id="notEquivInstitution"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light rounded p-3">
                                <div class="text-muted small mb-2">UiTM Degree Course</div>
                                <div class="mb-2" id="notEquivDegreeInfo" style="font-size: 1.05rem;"></div>
                                <div class="d-flex justify-content-between text-muted small">
                                    <span>Credit Hours: <span id="notEquivDegreeCreditHour"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-danger bg-opacity-10 rounded p-3 mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Match Percentage</span>
                            <span class="fs-5" id="notEquivMatchPercentage"></span>%
                        </div>
                        <div class="text-muted small mt-2 text-center">
                            Courses with match percentage below 80% are not automatically recognized as equivalent
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="button" id="requestEquivalencyBtnNotEquiv" class="btn btn-primary">
                            Request Equivalency Review
                        </button>
                    </div>
                </div>
            </div>

            <!-- Not Found Result Card (Hidden Initially) -->
            <div id="notFoundResult" class="card shadow-sm mb-4" style="display: none; border-left: 6px solid #ffc107; background: linear-gradient(to right, rgba(255, 193, 7, 0.08) 0%, rgba(255, 193, 7, 0.02) 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-warning p-3 me-3">
                            <i class="fas fa-exclamation-circle text-white fs-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-warning fw-bold">No Equivalency Found</h5>
                            <small class="text-muted">This course pairing is not in our system</small>
                        </div>
                    </div>

                    <div class="bg-light rounded p-3 mb-3">
                        <div class="text-muted small mb-3">Selected Courses</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="small text-muted mb-1">Diploma Course</div>
                                <div id="notFoundDiploma"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted mb-1">UiTM Degree Course</div>
                                <div id="notFoundDegree"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-warning bg-opacity-10 rounded p-3 mb-3 text-center">
                        <small class="text-muted">You can request a manual review to determine if these courses can be considered equivalent</small>
                    </div>

                    <div class="d-grid">
                        <button type="button" id="requestEquivalencyBtn" class="btn btn-primary">
                            Request Equivalency Review
                        </button>
                    </div>
                </div>
            </div>

            <!-- Request Form (Hidden Initially) -->
            <div id="requestForm" class="card shadow-sm mb-4" style="display: none;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-file-signature me-2"></i>Submit Equivalency Request</h5>
                </div>
                <div class="card-body">
                    <form id="equivalencyRequestForm" action="{{ route('student.equivalency.request.store') }}" method="POST">
                        @csrf

                        <!-- Hidden fields for course codes and names -->
                        <input type="hidden" name="diploma_course_code" id="hidden_diploma_code">
                        <input type="hidden" name="diploma_course_name" id="hidden_diploma_name">
                        <input type="hidden" name="suggested_degree_course_code" id="hidden_degree_code">
                        <input type="hidden" name="suggested_degree_course_name" id="hidden_degree_name">

                        <!-- Diploma Institution Information -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Diploma Institution <span class="text-danger">*</span></label>
                                <select name="diploma_institution" class="form-select searchable-select @error('diploma_institution') is-invalid @enderror" required>
                                    <option value="">Select institution...</option>
                                    @foreach($institutions as $institution)
                                        <option value="{{ $institution->name }}" {{ old('diploma_institution') == $institution->name ? 'selected' : '' }}>
                                            {{ $institution->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Institution where you completed this diploma course</small>
                                @error('diploma_institution')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- External Lecturer Contact Information -->
                        <div class="alert alert-warning mb-3">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Important: Official Syllabus Verification</h6>
                            <p class="mb-0">To ensure authenticity, we will request the official course syllabus directly from your lecturer. Please provide your lecturer's official institutional email address.</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lecturer's Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="external_lecturer_name" class="form-control @error('external_lecturer_name') is-invalid @enderror"
                                       value="{{ old('external_lecturer_name') }}"
                                       placeholder="e.g., Dr. Ahmad bin Abdullah"
                                       maxlength="255" required>
                                <small class="text-muted">Full name of the lecturer who taught this diploma course</small>
                                @error('external_lecturer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lecturer's Official Email <span class="text-danger">*</span></label>
                                <input type="email" name="external_lecturer_email" class="form-control @error('external_lecturer_email') is-invalid @enderror"
                                       value="{{ old('external_lecturer_email') }}"
                                       placeholder="e.g., ahmad.abdullah@university.edu.my"
                                       maxlength="255" required>
                                <small class="text-muted">Must be official institutional email address</small>
                                @error('external_lecturer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <button type="button" id="cancelRequestBtn" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" id="submitBtn" class="btn btn-primary">
                                <span id="submitBtnText">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Request
                                </span>
                                <span id="submitBtnLoading" class="d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Submitting...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Current Program Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Your Current Program</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Program:</strong><br>{{ $student->program_name ?? 'Not Set' }}</p>
                    <p class="mb-2"><strong>Campus:</strong><br>{{ $student->campus ?? 'Not Set' }}</p>
                    <p class="mb-0"><strong>Student ID:</strong><br>{{ $student->matric_no ?? Auth::user()->name }}</p>
                </div>
            </div>

            <!-- Guidelines -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0 ps-3">
                        <li class="mb-2">Use the search tool to check existing equivalencies first</li>
                        <li class="mb-2">Only submit a request if no equivalency is found</li>
                        <li class="mb-2">Provide accurate lecturer contact information</li>
                        <li class="mb-2">Your Program Coordinator will review the request</li>
                        <li class="mb-0">You will be notified of the decision via email</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Hide original select */
    .searchable-select {
        display: none;
    }

    /* Result cards - establish base stacking context */
    #equivalentResult,
    #notEquivalentResult,
    #notFoundResult,
    #requestForm {
        position: relative;
        z-index: 1;
    }

    /* Searchable Dropdown Styles */
    .searchable-dropdown-wrapper {
        position: relative;
        width: 100%;
        z-index: 100;
    }

    /* Dramatically increase z-index when dropdown is open to appear above ALL content */
    .searchable-dropdown-wrapper:has(.searchable-dropdown-menu.show) {
        z-index: 99999;
    }

    .searchable-dropdown-input {
        width: 100%;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        cursor: pointer;
        position: relative;
        z-index: 1;
    }

    .searchable-dropdown-input:focus {
        color: #212529;
        background-color: #fff;
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .searchable-dropdown-arrow {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #6c757d;
        font-size: 0.75rem;
        z-index: 2;
    }

    .searchable-dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 99998;
        max-height: 300px;
        overflow-y: auto;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        margin-top: 0.25rem;
    }

    .searchable-dropdown-menu.show {
        display: block;
    }

    .searchable-dropdown-option {
        padding: 0.5rem 0.75rem;
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
        background-color: #fff;
        position: relative;
        z-index: 1;
    }

    .searchable-dropdown-option:hover {
        background-color: #f8f9fa;
    }

    .searchable-dropdown-option.selected {
        background-color: #e9ecef;
        font-weight: 500;
    }

    .searchable-dropdown-option.no-results {
        color: #6c757d;
        cursor: default;
    }

    .searchable-dropdown-option.no-results:hover {
        background-color: #fff;
    }

    .searchable-dropdown-option mark {
        background-color: #fff3cd;
        padding: 0.1em 0;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Initializing searchable dropdowns...');

    // Initialize searchable dropdowns
    $('.searchable-select').each(function() {
        initSearchableDropdown($(this));
    });

    // Now initialize the equivalency checker
    initializeEquivalencyChecker();

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
            options.push({
                value: value,
                text: text
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

    // Populate menu with fuzzy search
    function populateMenu(searchTerm) {
        $menu.empty();
        var filteredOptions = options;

        if (searchTerm) {
            searchTerm = searchTerm.toLowerCase();
            // Fuzzy search: match anywhere in the text
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

        // Get the original text from options array
        var selectedOption = options.find(function(opt) { return opt.value === value; });
        var text = selectedOption ? selectedOption.text : $(this).text();

        selectedValue = value;
        $select.val(value).trigger('change');
        $input.val(text);
        $input.attr('readonly', 'readonly');
        $menu.removeClass('show');
    });

    // Close on click outside
    $(document).on('click', function(e) {
        if (!$wrapper[0].contains(e.target)) {
            $input.attr('readonly', 'readonly');
            $menu.removeClass('show');
            if (selectedValue) {
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

function initializeEquivalencyChecker() {
    const studentProgramCode = '{{ $student->program_code ?? "" }}';
    const csrfToken = '{{ csrf_token() }}';

    // Check if student has a program code
    if (!studentProgramCode) {
        console.error('No program code found for student');
        alert('Error: Your program code is not set. Please contact the administrator.');
        return;
    }

    // Check Equivalency Button
    $('#checkEquivalencyBtn').on('click', function() {
        const diplomaCode = $('#diploma_course_select').val();
        const degreeCode = $('#degree_course_select').val();

        if (!diplomaCode || !degreeCode) {
            alert('Please select both diploma and degree courses');
            return;
        }

        // Get selected option text for display
        const diplomaText = $('#diploma_course_select option:selected').text();
        const degreeText = $('#degree_course_select option:selected').text();

        // Show loading state
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Checking...');

        // Hide previous results
        $('#equivalentResult').hide();
        $('#notEquivalentResult').hide();
        $('#notFoundResult').hide();
        $('#requestForm').hide();

        $.ajax({
            url: '{{ route("student.api.equivalency.check") }}',
            method: 'POST',
            data: {
                diploma_code: diplomaCode,
                degree_code: degreeCode,
                program_code: studentProgramCode,
                _token: csrfToken
            },
            success: function(response) {
                if (response.found) {
                    // Check if equivalent based on match_percentage
                    if (response.is_equivalent) {
                        // Show EQUIVALENT result (match_percentage >= 80%)
                        $('#resultDiplomaInfo').text(`${diplomaCode} - ${response.data.diploma_course_name}`);
                        $('#resultDiplomaCreditHour').text(parseFloat(response.data.diploma_credit_hour).toFixed(2));
                        $('#resultInstitution').text(response.data.diploma_institution);
                        $('#resultDegreeInfo').text(`${degreeCode} - ${response.data.degree_course_name}`);
                        $('#resultDegreeCreditHour').text(parseFloat(response.data.degree_credit_hour).toFixed(2));

                        $('#equivalentResult').slideDown();
                    } else {
                        // Show NOT EQUIVALENT result (match_percentage < 80%)
                        $('#notEquivDiplomaInfo').text(`${diplomaCode} - ${response.data.diploma_course_name}`);
                        $('#notEquivDiplomaCreditHour').text(parseFloat(response.data.diploma_credit_hour).toFixed(2));
                        $('#notEquivInstitution').text(response.data.diploma_institution);
                        $('#notEquivDegreeInfo').text(`${degreeCode} - ${response.data.degree_course_name}`);
                        $('#notEquivDegreeCreditHour').text(parseFloat(response.data.degree_credit_hour).toFixed(2));
                        $('#notEquivMatchPercentage').text(response.match_percentage);

                        $('#notEquivalentResult').slideDown();
                    }
                } else {
                    // Show NOT FOUND result
                    $('#notFoundDiploma').text(diplomaText || diplomaCode);
                    $('#notFoundDegree').text(degreeText || degreeCode);

                    $('#notFoundResult').slideDown();
                }
            },
            error: function(xhr) {
                alert('Error checking equivalency. Please try again.');
                console.error(xhr);
            },
            complete: function() {
                $('#checkEquivalencyBtn').prop('disabled', false)
                    .html('<i class="fas fa-search me-2"></i>Check Equivalency');
            }
        });
    });

    // Request Equivalency Button (for NOT FOUND result)
    $('#requestEquivalencyBtn').on('click', function() {
        const diplomaCode = $('#diploma_course_select').val();
        const degreeCode = $('#degree_course_select').val();

        // Get selected option text (format: "CODE - NAME")
        const diplomaText = $('#diploma_course_select option:selected').text().trim();
        const degreeText = $('#degree_course_select option:selected').text().trim();

        // Extract course names from the text (remove course code prefix)
        const diplomaName = diplomaText.includes(' - ') ? diplomaText.split(' - ').slice(1).join(' - ').trim() : diplomaText;
        const degreeName = degreeText.includes(' - ') ? degreeText.split(' - ').slice(1).join(' - ').trim() : degreeText;

        // Pre-populate hidden fields
        $('#hidden_diploma_code').val(diplomaCode);
        $('#hidden_diploma_name').val(diplomaName);
        $('#hidden_degree_code').val(degreeCode);
        $('#hidden_degree_name').val(degreeName);

        // Show request form
        $('#requestForm').slideDown();

        // Scroll to form
        $('html, body').animate({
            scrollTop: $('#requestForm').offset().top - 100
        }, 500);
    });

    // Request Equivalency Button (for NOT EQUIVALENT result)
    $('#requestEquivalencyBtnNotEquiv').on('click', function() {
        const diplomaCode = $('#diploma_course_select').val();
        const degreeCode = $('#degree_course_select').val();

        // Get selected option text (format: "CODE - NAME")
        const diplomaText = $('#diploma_course_select option:selected').text().trim();
        const degreeText = $('#degree_course_select option:selected').text().trim();

        // Extract course names from the text (remove course code prefix)
        const diplomaName = diplomaText.includes(' - ') ? diplomaText.split(' - ').slice(1).join(' - ').trim() : diplomaText;
        const degreeName = degreeText.includes(' - ') ? degreeText.split(' - ').slice(1).join(' - ').trim() : degreeText;

        // Pre-populate hidden fields
        $('#hidden_diploma_code').val(diplomaCode);
        $('#hidden_diploma_name').val(diplomaName);
        $('#hidden_degree_code').val(degreeCode);
        $('#hidden_degree_name').val(degreeName);

        // Show request form
        $('#requestForm').slideDown();

        // Scroll to form
        $('html, body').animate({
            scrollTop: $('#requestForm').offset().top - 100
        }, 500);
    });

    // Cancel Request Button
    $('#cancelRequestBtn').on('click', function() {
        $('#requestForm').slideUp();
        $('#hidden_diploma_code').val('');
        $('#hidden_diploma_name').val('');
        $('#hidden_degree_code').val('');
        $('#hidden_degree_name').val('');
        $('select[name="diploma_institution"]').val('').trigger('change');
        $('input[name="external_lecturer_name"]').val('');
        $('input[name="external_lecturer_email"]').val('');
    });

    // Form submission
    const form = document.getElementById('equivalencyRequestForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitBtnLoading = document.getElementById('submitBtnLoading');

    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            form.classList.add('was-validated');

            const firstError = form.querySelector(':invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            return false;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtnText.classList.add('d-none');
        submitBtnLoading.classList.remove('d-none');
    });
}
</script>
@endpush

@endsection
