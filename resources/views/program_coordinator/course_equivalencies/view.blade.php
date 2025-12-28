@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>All Course Equivalencies
                    </h4>
                    <a href="{{ $backRoute ?? route('program_coordinator.equivalency_lists.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" id="info_alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Comprehensive View:</strong> <span id="filter_description">Showing all published course equivalencies (current and historical data).</span>
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

                    <!-- Hidden status filter (default to all_published) -->
                    <input type="hidden" id="status_filter" value="all_published">
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
                        @if(Auth::user()->role == 'program_coordinator')
                            <a href="{{ route('program_coordinator.equivalency_lists.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus-circle me-2"></i>Create New Equivalency List
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .alert, .card-header, .input-group { display: none !important; }
    .card { box-shadow: none !important; border: none !important; }
}
</style>

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
        if (programCode === 'ALL') {
            headerRow.innerHTML = `
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
        } else {
            headerRow.innerHTML = `
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
        }
    }

    function renderEquivalencies(data, programCode) {
        equivalenciesTbody.innerHTML = '';

        let eligible = 0;
        let notEligible = 0;

        data.forEach((eq, index) => {
            if (eq.is_eligible) {
                eligible++;
            } else {
                notEligible++;
            }

            const row = document.createElement('tr');

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
                `;
            }
            equivalenciesTbody.appendChild(row);
        });

        totalEquivalencies.textContent = data.length;
        eligibleCount.textContent = eligible;
        notEligibleCount.textContent = notEligible;
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
});
</script>
@endsection
