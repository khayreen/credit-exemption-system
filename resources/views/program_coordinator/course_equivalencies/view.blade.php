@extends('layouts.app')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>All Course Equivalencies
                    </h4>
                    <div class="d-flex gap-2 align-items-center">
                        <a href="{{ $backRoute ?? route('program_coordinator.equivalency_lists.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        @if(in_array(Auth::user()->role, ['program_coordinator', 'resource_person']))
                            <button type="button" class="btn btn-add-mapping" data-bs-toggle="modal" data-bs-target="#addMappingModal">
                                <i class="fas fa-plus-circle me-2"></i>Add New Mapping
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" id="info_alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Comprehensive View:</strong> <span id="filter_description">Showing all course equivalencies from all equivalency lists (including drafts).</span>
                    </div>

                    <!-- Filters Row -->
                    <div class="row mb-4 g-3 align-items-end">
                        <!-- Degree Program Selection -->
                        <div class="col-md-5">
                            <label for="degree_program_select" class="form-label fw-bold">
                                <i class="fas fa-graduation-cap me-1"></i>Select Degree Program
                            </label>
                            <select id="degree_program_select" class="form-select form-select-lg" required>
                                <option value="ALL" data-name="All Programs" selected>🌐 ALL PROGRAMS (View All)</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->code }}" data-name="{{ $program->name }}">
                                        {{ $program->code }} - {{ $program->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Institution Filter -->
                        <div class="col-md-5">
                            <label for="institution_filter" class="form-label fw-bold">
                                <i class="fas fa-university me-1"></i>Institution
                            </label>
                            <select id="institution_filter" class="form-select form-select-lg">
                                <option value="" selected>All Institutions</option>
                                @foreach($institutions ?? [] as $institution)
                                    <option value="{{ $institution }}">{{ $institution }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Reset Button -->
                        <div class="col-md-2">
                            <button type="button" id="reset_filters_btn" class="btn btn-outline-secondary btn-lg w-100">
                                <i class="fas fa-redo me-2"></i>Reset
                            </button>
                        </div>
                    </div>

                    <!-- Hidden status filter (show all mappings including drafts) -->
                    <input type="hidden" id="status_filter" value="include_drafts">
                    <input type="hidden" id="category_filter" value="">
                    <input type="hidden" id="semester_filter" value="">

                    <!-- Loading Indicator -->
                    <div id="loading_indicator" style="display: none;" class="text-center mb-3 py-5">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Loading course equivalencies...</p>
                    </div>

                    <!-- Equivalencies Table -->
                    <div id="equivalencies_section" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                            <div>
                                <h5 id="program_title" class="mb-1"></h5>
                                <small class="text-muted" id="table_subtitle">Showing all equivalencies for this program</small>
                            </div>
                            <div>
                                <div class="badge bg-primary fs-5 me-2">
                                    <i class="fas fa-list-alt me-1"></i>
                                    <span id="total_equivalencies">0</span> Total
                                </div>
                                <div class="badge bg-success fs-5 me-2">
                                    <i class="fas fa-check-circle me-1"></i>
                                    <span id="eligible_count">0</span> Eligible
                                </div>
                                <div class="badge bg-warning text-dark fs-5">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    <span id="not_eligible_count">0</span> Not Eligible
                                </div>
                            </div>
                        </div>

                        <!-- Search Filter -->
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" id="search_input" class="form-control" placeholder="Search by diploma course code, name, or institution...">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="equivalencies_table">
                                <thead class="table-dark" id="table_header">
                                    <tr id="header_row">
                                        <th style="width: 5%">#</th>
                                        <th style="width: 12%">Diploma Course</th>
                                        <th style="width: 20%">Diploma Course Name</th>
                                        <th style="width: 6%">Cr</th>
                                        <th style="width: 18%">Institution</th>
                                        <th style="width: 10%">Degree Course</th>
                                        <th style="width: 18%">Degree Course Name</th>
                                        <th style="width: 6%">Cr</th>
                                        <th style="width: 8%" class="text-center">Match %</th>
                                        <th style="width: 7%" class="text-center">Eligible</th>
                                        @if(in_array(Auth::user()->role, ['program_coordinator', 'resource_person']))
                                            <th style="width: 100px; min-width: 100px;" class="text-center">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody id="equivalencies_tbody">
                                    <!-- Dynamic content will be loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Export Options -->
                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-success" onclick="exportToExcel()">
                                <i class="fas fa-file-excel me-2"></i>Export to Excel
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>Print
                            </button>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="empty_state" style="display: none;" class="text-center py-5">
                        <i class="fas fa-search fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No Course Equivalencies Found</h5>
                        <p class="text-muted">No equivalencies have been published for the selected degree program yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Mapping Modal -->
@if(in_array(Auth::user()->role, ['program_coordinator', 'resource_person']))
<div class="modal fade" id="addMappingModal" tabindex="-1" aria-labelledby="addMappingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addMappingForm">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addMappingModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Add New Course Mapping
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Program Code Selection -->
                        <div class="col-md-12">
                            <label for="add_program_code" class="form-label fw-bold">Degree Program <span class="text-danger">*</span></label>
                            <select class="form-select" id="add_program_code" name="program_code" required>
                                <option value="">Select Degree Program</option>
                                <option value="CDCS230">CDCS230 - Bachelor of Computer Science (Hons.)</option>
                                <option value="CDCS251">CDCS251 - Bachelor of Computer Science (Hons.) Netcentric Computing</option>
                                <option value="CDCS253">CDCS253 - Bachelor of Computer Science (Hons.) Multimedia Computing</option>
                                <option value="CDCS255">CDCS255 - Bachelor of Computer Science (Hons.) Computer Networking</option>
                                <option value="CDCS266">CDCS266 - Bachelor of Information Systems (Hons.) Information Systems Engineering</option>
                            </select>
                            <small class="form-text text-muted">Select the degree program for this course mapping</small>
                        </div>

                        <!-- Diploma Institution -->
                        <div class="col-md-12">
                            <label for="add_diploma_institution" class="form-label fw-bold">Diploma Institution <span class="text-danger">*</span></label>
                            <select class="form-select" id="add_diploma_institution" name="diploma_institution" required>
                                <option value="">Select or type institution name</option>
                                @foreach($institutions as $institution)
                                    <option value="{{ $institution }}">{{ $institution }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>Select from existing institutions or type a new one
                            </small>
                        </div>

                        <!-- Diploma Course Details -->
                        <div class="col-md-8">
                            <label for="add_diploma_course_code" class="form-label fw-bold">Diploma Course Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_diploma_course_code" name="diploma_course_code" required placeholder="e.g., CSC138">
                        </div>
                        <div class="col-md-4">
                            <label for="add_diploma_credit_hour" class="form-label fw-bold">Credits <span class="text-danger">*</span></label>
                            <select class="form-select" id="add_diploma_credit_hour" name="diploma_credit_hour" required>
                                <option value="">Select</option>
                                @for ($i = 1; $i <= 10; $i += 0.5)
                                    <option value="{{ number_format($i, 2, '.', '') }}">{{ number_format($i, 2) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="add_diploma_course_name" class="form-label fw-bold">Diploma Course Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_diploma_course_name" name="diploma_course_name" required placeholder="e.g., Data Structures and Algorithms">
                        </div>

                        <!-- Degree Course Details -->
                        <div class="col-md-8">
                            <label for="add_degree_course_code" class="form-label fw-bold">Degree Course Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_degree_course_code" name="degree_course_code" required placeholder="e.g., CSC424">
                        </div>
                        <div class="col-md-4">
                            <label for="add_degree_credit_hour" class="form-label fw-bold">Credits <span class="text-danger">*</span></label>
                            <select class="form-select" id="add_degree_credit_hour" name="degree_credit_hour" required>
                                <option value="">Select</option>
                                @for ($i = 1; $i <= 10; $i += 0.5)
                                    <option value="{{ number_format($i, 2, '.', '') }}">{{ number_format($i, 2) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="add_degree_course_name" class="form-label fw-bold">Degree Course Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_degree_course_name" name="degree_course_name" required placeholder="e.g., Advanced Data Structures">
                        </div>

                        <!-- Match Percentage and Eligibility -->
                        <div class="col-md-6">
                            <label for="add_match_percentage" class="form-label fw-bold">Match Percentage <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="add_match_percentage" name="match_percentage" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label for="add_is_eligible" class="form-label fw-bold">Eligible for Exemption <span class="text-danger">*</span></label>
                            <select class="form-select" id="add_is_eligible" name="is_eligible" required>
                                <option value="1">Yes (≥80%)</option>
                                <option value="0">No (<80%)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Save Mapping
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Mapping Modal -->
<div class="modal fade" id="editMappingModal" tabindex="-1" aria-labelledby="editMappingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editMappingForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_mapping_id" name="mapping_id">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editMappingModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Course Mapping
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Program Code (read-only) -->
                        <div class="col-md-12">
                            <label for="edit_program_code" class="form-label fw-bold">Degree Program</label>
                            <input type="text" class="form-control" id="edit_program_code" name="program_code" readonly>
                            <small class="form-text text-muted">Program code cannot be changed after creation</small>
                        </div>

                        <!-- Diploma Institution -->
                        <div class="col-md-12">
                            <label for="edit_diploma_institution" class="form-label fw-bold">Diploma Institution <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_diploma_institution" name="diploma_institution" required>
                                <option value="">Select or type institution name</option>
                                @foreach($institutions as $institution)
                                    <option value="{{ $institution }}">{{ $institution }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>Select from existing institutions or type a new one
                            </small>
                        </div>

                        <!-- Diploma Course Details -->
                        <div class="col-md-8">
                            <label for="edit_diploma_course_code" class="form-label fw-bold">Diploma Course Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_diploma_course_code" name="diploma_course_code" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_diploma_credit_hour" class="form-label fw-bold">Credits <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_diploma_credit_hour" name="diploma_credit_hour" required>
                                <option value="">Select</option>
                                @for ($i = 1; $i <= 10; $i += 0.5)
                                    <option value="{{ number_format($i, 2, '.', '') }}">{{ number_format($i, 2) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="edit_diploma_course_name" class="form-label fw-bold">Diploma Course Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_diploma_course_name" name="diploma_course_name" required>
                        </div>

                        <!-- Degree Course Details -->
                        <div class="col-md-8">
                            <label for="edit_degree_course_code" class="form-label fw-bold">Degree Course Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_degree_course_code" name="degree_course_code" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_degree_credit_hour" class="form-label fw-bold">Credits <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_degree_credit_hour" name="degree_credit_hour" required>
                                <option value="">Select</option>
                                @for ($i = 1; $i <= 10; $i += 0.5)
                                    <option value="{{ number_format($i, 2, '.', '') }}">{{ number_format($i, 2) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="edit_degree_course_name" class="form-label fw-bold">Degree Course Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_degree_course_name" name="degree_course_name" required>
                        </div>

                        <!-- Match Percentage and Eligibility -->
                        <div class="col-md-6">
                            <label for="edit_match_percentage" class="form-label fw-bold">Match Percentage <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_match_percentage" name="match_percentage" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_is_eligible" class="form-label fw-bold">Eligible for Exemption <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_is_eligible" name="is_eligible" required>
                                <option value="1">Yes (≥80%)</option>
                                <option value="0">No (<80%)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Mapping
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
@media print {
    .btn, .alert, .card-header, .input-group { display: none !important; }
    .card { box-shadow: none !important; border: none !important; }
}

/* Highlight animation for newly added mappings */
.newly-added-highlight {
    animation: highlightFade 3s ease-in-out;
    background-color: #d4edda !important;
}

@keyframes highlightFade {
    0% {
        background-color: #28a745;
        transform: scale(1.02);
    }
    10% {
        background-color: #d4edda;
        transform: scale(1);
    }
    100% {
        background-color: transparent;
    }
}

/* Action Buttons Styling */
.btn-group .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease-in-out;
    border-width: 1.5px;
}

.btn-group .btn-outline-primary {
    color: #0d6efd;
    border-color: #0d6efd;
}

.btn-group .btn-outline-primary:hover {
    background-color: #0d6efd;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
}

.btn-group .btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
}

.btn-group .btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
}

.btn-group .btn:active {
    transform: translateY(0);
}

.btn-group {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 0.25rem;
}

/* Add New Mapping Button - Prominent Design */
.btn-add-mapping {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    font-weight: 600;
    font-size: 0.95rem;
    padding: 0.625rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.5);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    letter-spacing: 0.025em;
}

.btn-add-mapping::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.5s;
}

.btn-add-mapping:hover::before {
    left: 100%;
}

.btn-add-mapping:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.6);
    color: white;
}

.btn-add-mapping:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.5);
}

.btn-add-mapping i {
    font-size: 1.1rem;
}

/* Subtle pulse animation to draw attention */
@keyframes pulse-glow {
    0%, 100% {
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.5);
    }
    50% {
        box-shadow: 0 4px 20px rgba(245, 158, 11, 0.7);
    }
}

.btn-add-mapping {
    animation: pulse-glow 3s ease-in-out infinite;
}

.btn-add-mapping:hover {
    animation: none;
}
</style>

<!-- Select2 JS (must be loaded before using it) -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
let allEquivalencies = [];
let filteredEquivalencies = [];
let currentProgramCode = '';
let availableSemesters = [];
let availableInstitutions = [];

document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('degree_program_select');
    const statusFilter = document.getElementById('status_filter');
    const categoryFilter = document.getElementById('category_filter');
    const semesterFilter = document.getElementById('semester_filter');
    const institutionFilter = document.getElementById('institution_filter');
    const loadingIndicator = document.getElementById('loading_indicator');
    const equivalenciesSection = document.getElementById('equivalencies_section');
    const emptyState = document.getElementById('empty_state');
    const programTitle = document.getElementById('program_title');
    const totalEquivalencies = document.getElementById('total_equivalencies');
    const eligibleCount = document.getElementById('eligible_count');
    const notEligibleCount = document.getElementById('not_eligible_count');
    const equivalenciesTbody = document.getElementById('equivalencies_tbody');
    const searchInput = document.getElementById('search_input');
    const headerRow = document.getElementById('header_row');
    const filterDescription = document.getElementById('filter_description');

    // Load equivalencies automatically when program or institution is selected
    programSelect.addEventListener('change', function() {
        loadEquivalencies();
    });

    institutionFilter.addEventListener('change', function() {
        loadEquivalencies();
    });

    // Reset button event listener
    const resetBtn = document.getElementById('reset_filters_btn');
    resetBtn.addEventListener('click', function() {
        resetFilters();
    });

    function loadEquivalencies() {
        const programCode = programSelect.value;
        const programName = programSelect.options[programSelect.selectedIndex].dataset.name;
        const status = statusFilter.value;
        const category = categoryFilter.value;
        const semester = semesterFilter.value;
        const institution = institutionFilter.value;

        currentProgramCode = programCode;

        if (!programCode) {
            equivalenciesSection.style.display = 'none';
            emptyState.style.display = 'none';
            return;
        }

        // Update table header based on selection
        updateTableHeader(programCode);

        // Show loading state
        loadingIndicator.style.display = 'block';
        equivalenciesSection.style.display = 'none';
        emptyState.style.display = 'none';

        // Set program title
        if (programCode === 'ALL') {
            programTitle.textContent = '🌐 All Programs - Comprehensive View';
        } else {
            programTitle.textContent = `${programCode} - ${programName}`;
        }

        // Build query parameters
        let queryParams = `program_code=${programCode}&status=${status}`;
        if (category) queryParams += `&category=${category}`;
        if (semester) queryParams += `&semester=${encodeURIComponent(semester)}`;
        if (institution) queryParams += `&institution=${encodeURIComponent(institution)}`;

        // Fetch equivalencies via API
        fetch(`{{ $apiRoute ?? route('program_coordinator.api.existing_equivalencies') }}?${queryParams}`)
            .then(response => response.json())
            .then(data => {
                allEquivalencies = data;
                filteredEquivalencies = data;

                // Extract unique semesters and institutions for filter dropdowns
                extractFilterOptions(data);

                loadingIndicator.style.display = 'none';

                if (data.length === 0) {
                    emptyState.style.display = 'block';
                } else {
                    updateTableSubtitle();
                    renderEquivalencies(data, programCode);
                    equivalenciesSection.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error loading equivalencies:', error);
                loadingIndicator.style.display = 'none';
                alert('Failed to load course equivalencies. Please try again.');
            });
    }

    function extractFilterOptions(data) {
        // Extract unique semesters (not used since we removed semester filter)
        const semesters = [...new Set(data.map(eq => eq.list_semester).filter(s => s))];
        if (semesters.length > 0 && JSON.stringify(semesters.sort()) !== JSON.stringify(availableSemesters.sort())) {
            availableSemesters = semesters.sort().reverse(); // Most recent first
            populateSemesterFilter();
        }

        // DO NOT extract/update institutions - they are pre-populated from server-side
        // This prevents the dropdown from losing options when filtering
    }

    function populateSemesterFilter() {
        const currentValue = semesterFilter.value;
        semesterFilter.innerHTML = '<option value="">All Semesters</option>';
        availableSemesters.forEach(semester => {
            const option = document.createElement('option');
            option.value = semester;
            option.textContent = semester;
            if (semester === currentValue) option.selected = true;
            semesterFilter.appendChild(option);
        });
    }

    function populateInstitutionFilter() {
        // Institution dropdown is now pre-populated from server-side
        // This function is kept for compatibility but does nothing
        // to prevent dropdown options from being removed when filtering
    }

    function updateFilterDescription() {
        // Fixed description for all_published view
        filterDescription.textContent = 'Showing all published course equivalencies (current and historical data).';
    }

    function updateTableSubtitle() {
        const tableSubtitle = document.getElementById('table_subtitle');
        const institution = institutionFilter.value;

        let parts = ['all published equivalencies'];

        // Institution part
        if (institution) {
            parts.push(`institution: ${institution}`);
        }

        const subtitle = `Showing ${parts.join(' | ')}`;
        tableSubtitle.textContent = subtitle;
    }

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();

        filteredEquivalencies = allEquivalencies.filter(eq => {
            return eq.diploma_course_code.toLowerCase().includes(searchTerm) ||
                   eq.diploma_course_name.toLowerCase().includes(searchTerm) ||
                   eq.diploma_institution.toLowerCase().includes(searchTerm) ||
                   eq.degree_course_code.toLowerCase().includes(searchTerm) ||
                   eq.degree_course_name.toLowerCase().includes(searchTerm) ||
                   (eq.program_code && eq.program_code.toLowerCase().includes(searchTerm));
        });

        renderEquivalencies(filteredEquivalencies, currentProgramCode);
    });

    function updateTableHeader(programCode) {
        const hasActions = @json(in_array(Auth::user()->role, ['program_coordinator', 'resource_person']));

        if (programCode === 'ALL') {
            let html = `
                <th style="width: 4%">#</th>
                <th style="width: 8%">Program</th>
                <th style="width: 10%">Diploma Course</th>
                <th style="width: 18%">Diploma Course Name</th>
                <th style="width: 5%">Cr</th>
                <th style="width: 16%">Institution</th>
                <th style="width: 9%">Degree Course</th>
                <th style="width: 16%">Degree Course Name</th>
                <th style="width: 5%">Cr</th>
                <th style="width: 6%" class="text-center">Match %</th>
                <th style="width: 5%" class="text-center">Eligible</th>
            `;
            if (hasActions) {
                html += `<th style="width: 100px; min-width: 100px;" class="text-center">Actions</th>`;
            }
            headerRow.innerHTML = html;
        } else {
            let html = `
                <th style="width: 5%">#</th>
                <th style="width: 12%">Diploma Course</th>
                <th style="width: 20%">Diploma Course Name</th>
                <th style="width: 6%">Cr</th>
                <th style="width: 18%">Institution</th>
                <th style="width: 10%">Degree Course</th>
                <th style="width: 18%">Degree Course Name</th>
                <th style="width: 6%">Cr</th>
                <th style="width: 8%" class="text-center">Match %</th>
                <th style="width: 7%" class="text-center">Eligible</th>
            `;
            if (hasActions) {
                html += `<th style="width: 100px; min-width: 100px;" class="text-center">Actions</th>`;
            }
            headerRow.innerHTML = html;
        }
    }

    function renderEquivalencies(data, programCode) {
        equivalenciesTbody.innerHTML = '';
        const hasActions = @json(in_array(Auth::user()->role, ['program_coordinator', 'resource_person']));

        let eligible = 0;
        let notEligible = 0;

        data.forEach((eq, index) => {
            if (eq.is_eligible) {
                eligible++;
            } else {
                notEligible++;
            }

            const row = document.createElement('tr');
            row.setAttribute('data-mapping-id', eq.id); // Add mapping ID for highlighting
            let actionsHtml = '';

            if (hasActions) {
                actionsHtml = `
                    <td class="text-center">
                        <div class="btn-group" role="group" aria-label="Actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="editMapping('${eq.id}')" title="Edit" style="width: 36px; height: 32px;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteMapping('${eq.id}', '${eq.diploma_course_code}')" title="Delete" style="width: 36px; height: 32px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
            }

            if (programCode === 'ALL') {
                row.innerHTML = `
                    <td class="text-muted">${index + 1}</td>
                    <td><span class="badge bg-dark">${eq.program_code || 'N/A'}</span></td>
                    <td><strong>${eq.diploma_course_code}</strong></td>
                    <td><small>${eq.diploma_course_name}</small></td>
                    <td class="text-center">${eq.diploma_credit_hour}</td>
                    <td>
                        <small class="text-muted">${eq.diploma_institution}</small>
                        <br>
                        <span class="badge bg-${eq.list_category === 'internal' ? 'primary' : 'info'} badge-sm">
                            ${eq.list_category === 'internal' ? 'CS110' : 'External'}
                        </span>
                    </td>
                    <td><strong>${eq.degree_course_code}</strong></td>
                    <td><small>${eq.degree_course_name}</small></td>
                    <td class="text-center">${eq.degree_credit_hour}</td>
                    <td class="text-center">
                        <span class="badge bg-${eq.match_percentage >= 80 ? 'success' : 'warning'}">
                            ${parseFloat(eq.match_percentage).toFixed(0)}%
                        </span>
                    </td>
                    <td class="text-center">
                        ${eq.is_eligible ?
                            '<i class="fas fa-check-circle text-success fa-lg" title="Eligible"></i>' :
                            '<i class="fas fa-times-circle text-danger fa-lg" title="Not Eligible"></i>'}
                    </td>
                    ${actionsHtml}
                `;
            } else {
                row.innerHTML = `
                    <td class="text-muted">${index + 1}</td>
                    <td><strong>${eq.diploma_course_code}</strong></td>
                    <td><small>${eq.diploma_course_name}</small></td>
                    <td class="text-center">${eq.diploma_credit_hour}</td>
                    <td>
                        <small class="text-muted">${eq.diploma_institution}</small>
                        <br>
                        <span class="badge bg-${eq.list_category === 'internal' ? 'primary' : 'info'} badge-sm">
                            ${eq.list_category === 'internal' ? 'CS110' : 'External'}
                        </span>
                    </td>
                    <td><strong>${eq.degree_course_code}</strong></td>
                    <td><small>${eq.degree_course_name}</small></td>
                    <td class="text-center">${eq.degree_credit_hour}</td>
                    <td class="text-center">
                        <span class="badge bg-${eq.match_percentage >= 80 ? 'success' : 'warning'}">
                            ${parseFloat(eq.match_percentage).toFixed(0)}%
                        </span>
                    </td>
                    <td class="text-center">
                        ${eq.is_eligible ?
                            '<i class="fas fa-check-circle text-success fa-lg" title="Eligible"></i>' :
                            '<i class="fas fa-times-circle text-danger fa-lg" title="Not Eligible"></i>'}
                    </td>
                    ${actionsHtml}
                `;
            }
            equivalenciesTbody.appendChild(row);
        });

        totalEquivalencies.textContent = data.length;
        eligibleCount.textContent = eligible;
        notEligibleCount.textContent = notEligible;

        // Scroll to and highlight newly added mapping
        if (window.newlyAddedMappingId) {
            console.log('Looking for newly added mapping with ID:', window.newlyAddedMappingId);
            setTimeout(() => {
                const newRow = document.querySelector(`tr[data-mapping-id="${window.newlyAddedMappingId}"]`);
                console.log('Found row:', newRow);
                if (newRow) {
                    console.log('Scrolling to and highlighting row');
                    // Scroll to the new row
                    newRow.scrollIntoView({ behavior: 'smooth', block: 'center' });

                    // Add highlight class
                    newRow.classList.add('newly-added-highlight');

                    // Remove highlight after 3 seconds
                    setTimeout(() => {
                        newRow.classList.remove('newly-added-highlight');
                    }, 3000);
                } else {
                    console.warn('Row not found for mapping ID:', window.newlyAddedMappingId);
                }

                // Clear the stored ID
                window.newlyAddedMappingId = null;
            }, 500); // Increased delay to ensure rendering is complete
        }
    }

    function resetFilters() {
        document.getElementById('institution_filter').value = '';
        document.getElementById('degree_program_select').value = 'ALL';

        // Reload with default filters
        loadEquivalencies();
    }

    function exportToExcel() {
        if (filteredEquivalencies.length === 0) {
            alert('No data to export');
            return;
        }

        // Create CSV content
        let csv = 'Diploma Course Code,Diploma Course Name,Diploma Credits,Institution,Degree Course Code,Degree Course Name,Degree Credits,Match %,Eligible\n';

        filteredEquivalencies.forEach(eq => {
            csv += `"${eq.diploma_course_code}","${eq.diploma_course_name}",${eq.diploma_credit_hour},"${eq.diploma_institution}","${eq.degree_course_code}","${eq.degree_course_name}",${eq.degree_credit_hour},${eq.match_percentage},"${eq.is_eligible ? 'Yes' : 'No'}"\n`;
        });

        // Download CSV
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `course_equivalencies_${document.getElementById('degree_program_select').value}_${new Date().toISOString().split('T')[0]}.csv`;
        a.click();
        window.URL.revokeObjectURL(url);
    }

    // Initialize filter description
    updateFilterDescription();

    // Auto-load ALL PROGRAMS view on page load
    loadEquivalencies();

    // Make loadEquivalencies globally accessible for CRUD operations
    window.loadEquivalencies = loadEquivalencies;
});

// CRUD Operations for PC and RP
@if(in_array(Auth::user()->role, ['program_coordinator', 'resource_person']))

// Add New Mapping
document.getElementById('addMappingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const role = '{{ Auth::user()->role }}';
    const url = role === 'program_coordinator'
        ? '{{ route("program_coordinator.course_equivalencies.store") }}'
        : '{{ route("resource_person.course_equivalencies.store") }}';

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            console.log('Successfully added mapping:', data.mapping);
            bootstrap.Modal.getInstance(document.getElementById('addMappingModal')).hide();

            // Store the new mapping ID and program code for highlighting
            window.newlyAddedMappingId = data.mapping.id;
            const newMappingProgramCode = data.mapping.program_code;
            console.log('Stored mapping ID for highlight:', window.newlyAddedMappingId);
            console.log('Switching to program:', newMappingProgramCode);

            // Clear the search input to ensure the new mapping is visible
            document.getElementById('search_input').value = '';

            // Switch to the program of the newly added mapping
            document.getElementById('degree_program_select').value = newMappingProgramCode;

            // Reset the form after storing the program code
            document.getElementById('addMappingForm').reset();

            alert(data.message || 'Course mapping added successfully!');

            // Reload the table with the correct program filter
            console.log('Reloading equivalencies for program:', newMappingProgramCode);
            loadEquivalencies();
        } else {
            console.error('Server returned error:', data.message);
            alert('Failed to add mapping: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('An error occurred while adding the mapping: ' + error.message);
    });
});

// Edit Mapping
function editMapping(mappingId) {
    const role = '{{ Auth::user()->role }}';
    const url = role === 'program_coordinator'
        ? `/program-coordinator/course-equivalencies/${mappingId}`
        : `/resource-person/course-equivalencies/${mappingId}`;

    fetch(url, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const mapping = data.mapping;

            // Populate edit form
            document.getElementById('edit_mapping_id').value = mapping.id;
            document.getElementById('edit_program_code').value = mapping.program_code;

            // Set diploma institution with Select2
            const $editInstitution = $('#edit_diploma_institution');
            // Check if the value exists in the dropdown
            if ($editInstitution.find("option[value='" + mapping.diploma_institution + "']").length === 0) {
                // Create a new option if it doesn't exist (custom institution)
                const newOption = new Option(mapping.diploma_institution, mapping.diploma_institution, true, true);
                $editInstitution.append(newOption);
            }
            $editInstitution.val(mapping.diploma_institution).trigger('change');

            document.getElementById('edit_diploma_course_code').value = mapping.diploma_course_code;
            document.getElementById('edit_diploma_course_name').value = mapping.diploma_course_name;
            $('#edit_diploma_credit_hour').val(mapping.diploma_credit_hour).trigger('change');
            document.getElementById('edit_degree_course_code').value = mapping.degree_course_code;
            document.getElementById('edit_degree_course_name').value = mapping.degree_course_name;
            $('#edit_degree_credit_hour').val(mapping.degree_credit_hour).trigger('change');
            document.getElementById('edit_match_percentage').value = mapping.match_percentage;
            document.getElementById('edit_is_eligible').value = mapping.is_eligible ? '1' : '0';

            // Show modal
            new bootstrap.Modal(document.getElementById('editMappingModal')).show();
        } else {
            alert(data.message || 'Failed to load mapping details');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while loading mapping details');
    });
}

// Update Mapping
document.getElementById('editMappingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const mappingId = document.getElementById('edit_mapping_id').value;
    const formData = new FormData(this);
    const role = '{{ Auth::user()->role }}';
    const url = role === 'program_coordinator'
        ? `/program-coordinator/course-equivalencies/${mappingId}`
        : `/resource-person/course-equivalencies/${mappingId}`;

    fetch(url, {
        method: 'POST', // Laravel will handle _method = PUT
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('editMappingModal')).hide();
            alert(data.message || 'Course mapping updated successfully!');
            loadEquivalencies(); // Reload the table
        } else {
            alert(data.message || 'Failed to update mapping');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the mapping');
    });
});

// Delete Mapping
function deleteMapping(mappingId, courseCode) {
    if (!confirm(`Are you sure you want to delete the mapping for ${courseCode}?\n\nThis action cannot be undone.`)) {
        return;
    }

    const role = '{{ Auth::user()->role }}';
    const url = role === 'program_coordinator'
        ? `/program-coordinator/course-equivalencies/${mappingId}`
        : `/resource-person/course-equivalencies/${mappingId}`;

    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Course mapping deleted successfully!');
            loadEquivalencies(); // Reload the table
        } else {
            alert(data.message || 'Failed to delete mapping');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the mapping');
    });
}

@endif

// Initialize Select2 for institution dropdowns (with tags support for custom entries)
$(document).ready(function() {
    // Add modal institution dropdown
    $('#add_diploma_institution').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select or type institution name',
        allowClear: true,
        tags: true,  // Allow custom tags (new institutions)
        dropdownParent: $('#addMappingModal'),
        width: '100%',
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term,
                text: term,
                newTag: true
            };
        }
    });

    // Edit modal institution dropdown
    $('#edit_diploma_institution').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select or type institution name',
        allowClear: true,
        tags: true,  // Allow custom tags (new institutions)
        dropdownParent: $('#editMappingModal'),
        width: '100%',
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term,
                text: term,
                newTag: true
            };
        }
    });

    // Reset Select2 when modal is closed
    $('#addMappingModal').on('hidden.bs.modal', function () {
        $('#add_diploma_institution').val(null).trigger('change');
    });
});

</script>

@endsection
