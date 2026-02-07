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

    .main-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .main-card-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .main-card-header h4 {
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.25);
        color: white;
    }

    .main-card-body {
        padding: 1.5rem;
    }

    .info-alert {
        background: rgba(13, 148, 136, 0.1);
        border: 1px solid rgba(13, 148, 136, 0.2);
        border-left: 4px solid var(--info);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: var(--info);
        font-size: 0.9rem;
    }

    .info-alert strong {
        color: #0f766e;
    }

    /* Program Select */
    .program-select-wrapper {
        margin-bottom: 1.5rem;
    }

    .program-select-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .program-select-label i {
        color: var(--uitm-amber);
    }

    .program-select {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        font-family: inherit;
        transition: all 0.2s ease;
        background: white;
    }

    .program-select:focus {
        outline: none;
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    /* Loading */
    .loading-indicator {
        text-align: center;
        padding: 4rem 2rem;
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid var(--industrial-light);
        border-top-color: var(--uitm-blue);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1.5rem;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .loading-text {
        color: var(--industrial-gray);
    }

    /* Stats Bar */
    .stats-bar {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #e2e8f0 100%);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .stats-title {
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .stats-title small {
        display: block;
        font-weight: 400;
        color: var(--industrial-gray);
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .stats-badges {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .stat-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
    }

    .stat-badge.total {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .stat-badge.eligible {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .stat-badge.not-eligible {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
    }

    .stat-badge i {
        font-size: 0.9rem;
    }

    /* Search */
    .search-wrapper {
        margin-bottom: 1.5rem;
    }

    .search-input-group {
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    /* Table */
    .equivalencies-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .equivalencies-table thead th {
        background: linear-gradient(135deg, var(--industrial-dark) 0%, #1e293b 100%);
        color: white;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        border: none;
    }

    .equivalencies-table thead th:first-child {
        border-radius: 12px 0 0 0;
    }

    .equivalencies-table thead th:last-child {
        border-radius: 0 12px 0 0;
    }

    .equivalencies-table tbody tr {
        transition: all 0.2s ease;
    }

    .equivalencies-table tbody tr:nth-child(even) {
        background: var(--industrial-light);
    }

    .equivalencies-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.05);
    }

    .equivalencies-table tbody td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .course-name {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .credit-hours {
        font-family: 'IBM Plex Mono', monospace;
        text-align: center;
    }

    .institution-name {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .category-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-top: 0.35rem;
    }

    .category-badge.internal {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .category-badge.external {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info);
    }

    .program-badge {
        display: inline-block;
        background: var(--industrial-dark);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .match-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .match-badge.high {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .match-badge.low {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
    }

    .eligible-icon {
        font-size: 1.25rem;
    }

    /* Export Buttons */
    .export-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn-export {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-export.excel {
        background: var(--success);
        color: white;
    }

    .btn-export.excel:hover {
        background: #047857;
        transform: translateY(-2px);
    }

    .btn-export.print {
        background: var(--industrial-gray);
        color: white;
    }

    .btn-export.print:hover {
        background: var(--industrial-dark);
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: var(--industrial-gray);
        font-weight: 500;
    }

    .empty-state p {
        color: #94a3b8;
    }

    @media print {
        .btn-back, .info-alert, .search-wrapper, .export-buttons { display: none !important; }
        .main-card { box-shadow: none !important; border: none !important; }
    }

    @media (max-width: 768px) {
        .stats-bar {
            flex-direction: column;
            text-align: center;
        }

        .stats-badges {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-card">
        <div class="main-card-header">
            <h4><i class="fas fa-clipboard-list"></i> All Course Equivalencies</h4>
            <a href="{{ route('hea.equivalency_lists.grouped') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Lists
            </a>
        </div>
        <div class="main-card-body">
            <div class="info-alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Comprehensive View:</strong> This shows all course equivalencies across all internal and external lists (including drafts).
                Use this to quickly search and verify existing mappings.
            </div>

            <!-- Degree Program Selection -->
            <div class="program-select-wrapper">
                <label for="degree_program_select" class="program-select-label">
                    <i class="fas fa-graduation-cap"></i>
                    Select Degree Program
                </label>
                <select id="degree_program_select" class="program-select" required>
                    <option value="ALL" data-name="All Programs" selected>🌐 ALL PROGRAMS (View All)</option>
                    @foreach($programs as $program)
                        <option value="{{ $program->code }}" data-name="{{ $program->name }}">
                            {{ $program->code }} - {{ $program->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Loading Indicator -->
            <div id="loading_indicator" style="display: none;" class="loading-indicator">
                <div class="loading-spinner"></div>
                <p class="loading-text">Loading course equivalencies...</p>
            </div>

            <!-- Equivalencies Section -->
            <div id="equivalencies_section" style="display: none;">
                <div class="stats-bar">
                    <div class="stats-title">
                        <span id="program_title"></span>
                        <small>Showing all equivalencies for this program (including drafts)</small>
                    </div>
                    <div class="stats-badges">
                        <div class="stat-badge total">
                            <i class="fas fa-list-alt"></i>
                            <span id="total_equivalencies">0</span> Total
                        </div>
                        <div class="stat-badge eligible">
                            <i class="fas fa-check-circle"></i>
                            <span id="eligible_count">0</span> Eligible
                        </div>
                        <div class="stat-badge not-eligible">
                            <i class="fas fa-exclamation-circle"></i>
                            <span id="not_eligible_count">0</span> Not Eligible
                        </div>
                    </div>
                </div>

                <!-- Search Filter -->
                <div class="search-wrapper">
                    <div class="search-input-group">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="search_input" class="search-input" placeholder="Search by diploma course code, name, or institution...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="equivalencies-table" id="equivalencies_table">
                        <thead id="table_header">
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
                <div class="export-buttons">
                    <button type="button" class="btn-export excel" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </button>
                    <button type="button" class="btn-export print" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>

            <!-- Empty State -->
            <div id="empty_state" style="display: none;" class="empty-state">
                <i class="fas fa-search"></i>
                <h5>No Course Equivalencies Found</h5>
                <p>No equivalencies have been published for the selected degree program yet.</p>
            </div>
        </div>
    </div>
</div>

<script>
let allEquivalencies = [];
let filteredEquivalencies = [];
let currentProgramCode = '';

document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('degree_program_select');
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

    programSelect.addEventListener('change', function() {
        const programCode = this.value;
        const programName = this.options[this.selectedIndex].dataset.name;
        currentProgramCode = programCode;

        if (!programCode) {
            equivalenciesSection.style.display = 'none';
            emptyState.style.display = 'none';
            return;
        }

        updateTableHeader(programCode);

        loadingIndicator.style.display = 'block';
        equivalenciesSection.style.display = 'none';
        emptyState.style.display = 'none';

        if (programCode === 'ALL') {
            programTitle.textContent = '🌐 All Programs - Comprehensive View';
        } else {
            programTitle.textContent = `${programCode} - ${programName}`;
        }

        fetch(`{{ route('hea.api.existing_equivalencies') }}?program_code=${programCode}`)
            .then(response => response.json())
            .then(data => {
                allEquivalencies = data;
                filteredEquivalencies = data;

                loadingIndicator.style.display = 'none';

                if (data.length === 0) {
                    emptyState.style.display = 'block';
                } else {
                    renderEquivalencies(data, programCode);
                    equivalenciesSection.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error loading equivalencies:', error);
                loadingIndicator.style.display = 'none';
                alert('Failed to load course equivalencies. Please try again.');
            });
    });

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

    programSelect.dispatchEvent(new Event('change'));

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
                    <td><span class="program-badge">${eq.program_code || 'N/A'}</span></td>
                    <td><span class="course-code">${eq.diploma_course_code}</span></td>
                    <td><span class="course-name">${eq.diploma_course_name}</span></td>
                    <td class="credit-hours">${eq.diploma_credit_hour}</td>
                    <td>
                        <span class="institution-name">${eq.diploma_institution}</span>
                        <br>
                        <span class="category-badge ${eq.list_category === 'internal' ? 'internal' : 'external'}">
                            ${eq.list_category === 'internal' ? 'CS110' : 'External'}
                        </span>
                    </td>
                    <td><span class="course-code">${eq.degree_course_code}</span></td>
                    <td><span class="course-name">${eq.degree_course_name}</span></td>
                    <td class="credit-hours">${eq.degree_credit_hour}</td>
                    <td class="text-center">
                        <span class="match-badge ${eq.match_percentage >= 80 ? 'high' : 'low'}">
                            ${parseFloat(eq.match_percentage).toFixed(0)}%
                        </span>
                    </td>
                    <td class="text-center">
                        ${eq.is_eligible ?
                            '<i class="fas fa-check-circle eligible-icon" style="color: var(--success);" title="Eligible"></i>' :
                            '<i class="fas fa-times-circle eligible-icon" style="color: var(--danger);" title="Not Eligible"></i>'}
                    </td>
                `;
            } else {
                row.innerHTML = `
                    <td class="text-muted">${index + 1}</td>
                    <td><span class="course-code">${eq.diploma_course_code}</span></td>
                    <td><span class="course-name">${eq.diploma_course_name}</span></td>
                    <td class="credit-hours">${eq.diploma_credit_hour}</td>
                    <td>
                        <span class="institution-name">${eq.diploma_institution}</span>
                        <br>
                        <span class="category-badge ${eq.list_category === 'internal' ? 'internal' : 'external'}">
                            ${eq.list_category === 'internal' ? 'CS110' : 'External'}
                        </span>
                    </td>
                    <td><span class="course-code">${eq.degree_course_code}</span></td>
                    <td><span class="course-name">${eq.degree_course_name}</span></td>
                    <td class="credit-hours">${eq.degree_credit_hour}</td>
                    <td class="text-center">
                        <span class="match-badge ${eq.match_percentage >= 80 ? 'high' : 'low'}">
                            ${parseFloat(eq.match_percentage).toFixed(0)}%
                        </span>
                    </td>
                    <td class="text-center">
                        ${eq.is_eligible ?
                            '<i class="fas fa-check-circle eligible-icon" style="color: var(--success);" title="Eligible"></i>' :
                            '<i class="fas fa-times-circle eligible-icon" style="color: var(--danger);" title="Not Eligible"></i>'}
                    </td>
                `;
            }
            equivalenciesTbody.appendChild(row);
        });

        totalEquivalencies.textContent = data.length;
        eligibleCount.textContent = eligible;
        notEligibleCount.textContent = notEligible;
    }
});

function exportToExcel() {
    if (filteredEquivalencies.length === 0) {
        alert('No data to export');
        return;
    }

    let csv = 'Diploma Course Code,Diploma Course Name,Diploma Credits,Institution,Degree Course Code,Degree Course Name,Degree Credits,Match %,Eligible\n';

    filteredEquivalencies.forEach(eq => {
        csv += `"${eq.diploma_course_code}","${eq.diploma_course_name}",${eq.diploma_credit_hour},"${eq.diploma_institution}","${eq.degree_course_code}","${eq.degree_course_name}",${eq.degree_credit_hour},${eq.match_percentage},"${eq.is_eligible ? 'Yes' : 'No'}"\n`;
    });

    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `course_equivalencies_${document.getElementById('degree_program_select').value}_${new Date().toISOString().split('T')[0]}.csv`;
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
@endsection
