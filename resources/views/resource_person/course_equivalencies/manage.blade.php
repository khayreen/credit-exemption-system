@extends('layouts.app')

@section('title', 'Course Equivalency Management')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e293b;
        --uitm-primary-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --uitm-amber-dark: #d97706;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success: #059669;
        --success-light: #d1fae5;
        --danger: #dc2626;
        --danger-light: #fee2e2;
        --warning: #ea580c;
        --warning-light: #ffedd5;
        --teal: #0d9488;
        --teal-light: #ccfbf1;
        --info: #0284c7;
        --info-light: #e0f2fe;
        --neutral-700: #404040;
        --neutral-600: #525252;
        --neutral-500: #737373;
        --neutral-400: #a3a3a3;
        --neutral-300: #d4d4d4;
        --neutral-200: #e5e5e5;
        --neutral-100: #f5f5f5;
        --neutral-50: #fafafa;
    }

    body {
        font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--industrial-light);
    }

    /* Industrial Card */
    .industrial-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .industrial-card-header {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--industrial-dark) 100%);
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .industrial-card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .industrial-card-header h4 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .industrial-card-body {
        padding: 1.5rem;
    }

    /* Filter Section */
    .filter-section {
        background: var(--neutral-50);
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .form-label-industrial {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-select-industrial,
    .form-control-industrial {
        border: 2px solid var(--neutral-200);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        width: 100%;
        background-color: white;
    }

    .form-select-industrial:focus,
    .form-control-industrial:focus {
        border-color: var(--uitm-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        outline: none;
    }

    .form-text-industrial {
        font-size: 0.8rem;
        color: var(--neutral-500);
        margin-top: 0.375rem;
    }

    /* Buttons */
    .btn-primary-industrial {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-primary-industrial:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    .btn-primary-industrial:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-success-industrial {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-success-industrial:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    .btn-success-industrial:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-secondary-industrial {
        background: white;
        border: 2px solid var(--neutral-300);
        color: var(--neutral-700);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary-industrial:hover {
        background: var(--neutral-50);
        border-color: var(--neutral-400);
        color: var(--neutral-600);
    }

    .btn-action {
        padding: 0.4rem 0.6rem;
        border-radius: 6px;
        font-size: 0.8rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-action.edit {
        background: var(--warning-light);
        color: var(--warning);
    }

    .btn-action.edit:hover {
        background: var(--warning);
        color: white;
    }

    .btn-action.delete {
        background: var(--danger-light);
        color: var(--danger);
    }

    .btn-action.delete:hover {
        background: var(--danger);
        color: white;
    }

    /* Program Display */
    .program-display {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--uitm-primary);
        margin-bottom: 1rem;
    }

    /* Alert Styles */
    .alert-industrial {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .alert-industrial.success {
        background: var(--success-light);
        border-left: 4px solid var(--success);
        color: #047857;
    }

    .alert-industrial.danger {
        background: var(--danger-light);
        border-left: 4px solid var(--danger);
        color: #b91c1c;
    }

    /* Table Styles */
    .industrial-table {
        width: 100%;
        border-collapse: collapse;
    }

    .industrial-table thead th {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: white;
        background: var(--industrial-dark);
        padding: 0.75rem 0.5rem;
        text-align: left;
        white-space: nowrap;
    }

    .industrial-table tbody td {
        padding: 0.75rem 0.5rem;
        border-bottom: 1px solid var(--neutral-100);
        font-size: 0.85rem;
        color: var(--neutral-700);
        vertical-align: middle;
    }

    .industrial-table tbody tr:hover {
        background: var(--neutral-50);
    }

    .industrial-table tbody tr:last-child td {
        border-bottom: none;
    }

    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-primary);
        font-size: 0.8rem;
    }

    .badge-match {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        font-weight: 600;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
    }

    .badge-source {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .badge-source.manual {
        background: var(--neutral-100);
        color: var(--neutral-600);
    }

    .badge-source.imported {
        background: var(--info-light);
        color: var(--info);
    }

    .badge-source.seeded {
        background: var(--success-light);
        color: var(--success);
    }

    /* New Equivalency Form */
    .new-form-section {
        background: linear-gradient(135deg, var(--success-light) 0%, white 100%);
        border: 2px solid var(--success);
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .new-form-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--success);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--neutral-600);
        margin-bottom: 0.375rem;
    }

    .form-group input,
    .form-group textarea {
        border: 2px solid var(--neutral-200);
        border-radius: 8px;
        padding: 0.625rem 0.75rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: var(--success);
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        outline: none;
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--industrial-dark) 100%);
        border: none;
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        color: white;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-body h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-body h6.text-primary {
        color: var(--uitm-primary);
    }

    .modal-body h6 i {
        color: var(--uitm-amber);
    }

    .modal-footer {
        background: var(--neutral-50);
        border-top: 2px solid var(--neutral-200);
        padding: 1rem 1.5rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--neutral-500);
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: slideIn 0.3s ease;
    }

    .toast-notification.success {
        background: var(--success);
        color: white;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @media (max-width: 768px) {
        .industrial-card-body {
            padding: 1rem;
        }

        .filter-section {
            padding: 1rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .industrial-table {
            font-size: 0.8rem;
        }

        .industrial-table thead th,
        .industrial-table tbody td {
            padding: 0.5rem 0.375rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="industrial-card">
        <div class="industrial-card-header">
            <h4><i class="fas fa-exchange-alt me-2"></i>Course Equivalency Management</h4>
            <a href="{{ route('resource_person.dashboard') }}" class="btn-secondary-industrial">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        <div class="industrial-card-body">
            @if(session('success'))
                <div class="alert-industrial success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-industrial danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="degree_program_select" class="form-label-industrial">Select Degree Program</label>
                        <select id="degree_program_select" class="form-select-industrial" required>
                            <option value="">-- Select Degree Program --</option>
                            @foreach($degreePrograms as $program)
                                <option value="{{ $program->code }}" data-name="{{ $program->name }}">
                                    {{ $program->code }} - {{ $program->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="institution_filter" class="form-label-industrial">Filter by Institution</label>
                        <select id="institution_filter" class="form-select-industrial">
                            <option value="">All Institutions</option>
                            <option value="Politeknik">Politeknik</option>
                            <option value="UTM">UTM</option>
                            <option value="UiTM">UiTM</option>
                            <option value="MMU">MMU</option>
                            <option value="GMI">GMI</option>
                            <option value="UPSI">UPSI</option>
                            <option value="Kolej">Kolej</option>
                        </select>
                        <div class="form-text-industrial">Filter equivalencies by diploma institution</div>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="button" id="load_equivalencies_btn" class="btn-primary-industrial" disabled>
                            <i class="fas fa-download"></i> Load
                        </button>
                        <button type="button" id="add_new_btn" class="btn-success-industrial" disabled>
                            <i class="fas fa-plus"></i> Add New
                        </button>
                    </div>
                </div>
            </div>

            <!-- Equivalencies Section -->
            <div id="equivalencies_section" style="display: none;">
                <div class="program-display">
                    <i class="fas fa-graduation-cap me-2"></i>
                    <span id="program_display_name"></span>
                </div>

                <div class="table-responsive">
                    <table class="industrial-table" id="equivalencies_table">
                        <thead>
                            <tr>
                                <th width="3%">#</th>
                                <th width="10%">Diploma Code</th>
                                <th width="14%">Diploma Name</th>
                                <th width="10%">Institution</th>
                                <th width="5%">Cr</th>
                                <th width="10%">Degree Code</th>
                                <th width="14%">Degree Name</th>
                                <th width="5%">Cr</th>
                                <th width="7%">Match</th>
                                <th width="8%">Source</th>
                                <th width="9%">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="equivalencies_body">
                            <!-- Existing equivalencies will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- New Equivalency Form -->
            <div id="new_equivalency_form" style="display: none;">
                <div class="new-form-section">
                    <div class="new-form-title">
                        <i class="fas fa-plus-circle"></i>
                        Add New Course Equivalency
                    </div>

                    <form id="add_equivalency_form" method="POST" action="{{ route('resource_person.course_equivalencies.store_bulk') }}">
                        @csrf
                        <input type="hidden" name="degree_program_code" id="selected_program_code">

                        <div class="form-row">
                            <div class="form-group" style="grid-column: span 2;">
                                <label>Diploma Course Code(s)</label>
                                <textarea name="diploma_courses[0][diploma_course_code]"
                                          rows="2"
                                          placeholder="Examples:&#10;CSC402&#10;CSC138/CSC126&#10;CSC138/CSC126 + CSC186"
                                          title="Enter single course (CSC402) or multiple courses (CSC138/CSC126) or combinations (CSC138/CSC126 + CSC186)"
                                          required></textarea>
                                <small class="form-text-industrial">Supports: single, multiple (/), or combined (+) courses</small>
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label>Diploma Course Name</label>
                                <input type="text" name="diploma_courses[0][diploma_course_name]"
                                       placeholder="e.g., Programming I" required>
                            </div>
                            <div class="form-group">
                                <label>Diploma Credits</label>
                                <input type="number" name="diploma_courses[0][diploma_credit_hours]"
                                       min="1" step="1" placeholder="3" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group" style="grid-column: span 2;">
                                <label>Degree Course Code</label>
                                <input type="text" name="diploma_courses[0][degree_course_code]"
                                       placeholder="e.g., CS131" required>
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label>Degree Course Name</label>
                                <input type="text" name="diploma_courses[0][degree_course_name]"
                                       placeholder="e.g., Programming I" required>
                            </div>
                            <div class="form-group">
                                <label>Degree Credits</label>
                                <input type="number" name="diploma_courses[0][degree_credit_hours]"
                                       min="1" step="1" placeholder="3" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Equivalency %</label>
                                <input type="number" name="diploma_courses[0][equivalency_percentage]"
                                       min="0" max="100" step="0.1" placeholder="85.0" required>
                            </div>
                            <div class="form-group d-flex align-items-end">
                                <button type="submit" class="btn-success-industrial">
                                    <i class="fas fa-save"></i> Save New Equivalency
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editEquivalencyModal" tabindex="-1" aria-labelledby="editEquivalencyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEquivalencyModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Course Equivalency
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit_equivalency_form">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_equivalency_id">

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary"><i class="fas fa-graduation-cap"></i> Diploma Course</h6>
                        </div>
                        <div class="col-md-6">
                            <h6 style="color: var(--success);"><i class="fas fa-university"></i> Degree Course</h6>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-industrial">Diploma Course Code(s)</label>
                            <textarea id="edit_diploma_course_code" name="diploma_course_code"
                                      class="form-control-industrial" rows="2"
                                      placeholder="Examples:&#10;CSC402&#10;CSC138/CSC126"
                                      required></textarea>
                            <div class="form-text-industrial">Supports: single, multiple (/), or combined (+)</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-industrial">Degree Course Code</label>
                            <input type="text" id="edit_degree_course_code" name="degree_course_code"
                                   class="form-control-industrial" placeholder="e.g., CS131" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-industrial">Diploma Course Name</label>
                            <input type="text" id="edit_diploma_course_name" name="diploma_course_name"
                                   class="form-control-industrial" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-industrial">Degree Course Name</label>
                            <input type="text" id="edit_degree_course_name" name="degree_course_name"
                                   class="form-control-industrial" placeholder="e.g., Programming I" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label-industrial">Diploma Credit Hours</label>
                            <input type="number" id="edit_diploma_credit_hours" name="diploma_credit_hours"
                                   class="form-control-industrial" min="1" step="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-industrial">Degree Credit Hours</label>
                            <input type="number" id="edit_degree_credit_hours" name="degree_credit_hours"
                                   class="form-control-industrial" min="1" step="1" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 offset-md-4">
                            <label class="form-label-industrial text-center d-block">
                                <i class="fas fa-percentage me-1"></i> Equivalency Percentage
                            </label>
                            <input type="number" id="edit_equivalency_percentage" name="equivalency_percentage"
                                   class="form-control-industrial text-center" min="0" max="100" step="0.1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-industrial" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-industrial">
                        <i class="fas fa-save"></i> Update Equivalency
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let degreeCourses = [];
    let currentProgramCode = '';

    const routes = {
        getDegreeCourses: '{{ route("resource_person.api.degree_program_courses") }}',
        getExistingEquivalencies: '{{ route("resource_person.api.existing_equivalencies") }}',
        storeBulk: '{{ route("resource_person.course_equivalencies.store_bulk") }}',
        updateEquivalency: '/resource-person/course-equivalencies/',
        deleteEquivalency: '/resource-person/course-equivalencies/'
    };

    $('#degree_program_select').change(function() {
        const selected = $(this).val();
        $('#load_equivalencies_btn, #add_new_btn').prop('disabled', !selected);
        if (!selected) {
            $('#equivalencies_section, #new_equivalency_form').hide();
        }
    });

    $('#load_equivalencies_btn').click(function() {
        const programCode = $('#degree_program_select').val();
        const programName = $('#degree_program_select option:selected').data('name');
        const institutionFilter = $('#institution_filter').val();

        if (!programCode) return;

        currentProgramCode = programCode;
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

        const params = { program_code: programCode };
        if (institutionFilter) {
            params.institution = institutionFilter;
        }

        $.when(
            $.get(routes.getDegreeCourses, { program_code: programCode }),
            $.get(routes.getExistingEquivalencies, params)
        )
        .done(function(coursesResponse, equivalenciesResponse) {
            degreeCourses = coursesResponse[0];
            const equivalencies = equivalenciesResponse[0];

            $('#selected_program_code').val(programCode);
            let displayText = programCode + ' - ' + programName;
            if (institutionFilter) {
                displayText += ' (Filtered: ' + institutionFilter + ')';
            }
            $('#program_display_name').text(displayText);

            populateEquivalenciesTable(equivalencies);
            $('#equivalencies_section').show();
        })
        .fail(function() {
            showToast('Error loading data. Please try again.', 'danger');
        })
        .always(function() {
            $('#load_equivalencies_btn').prop('disabled', false).html('<i class="fas fa-download"></i> Load');
        });
    });

    $('#institution_filter').change(function() {
        if ($('#degree_program_select').val()) {
            $('#load_equivalencies_btn').click();
        }
    });

    $('#add_new_btn').click(function() {
        const programCode = $('#degree_program_select').val();
        if (programCode) {
            currentProgramCode = programCode;
            $('#selected_program_code').val(programCode);
            $('#new_equivalency_form').toggle();

            setTimeout(function() {
                $('html, body').animate({
                    scrollTop: $('#new_equivalency_form').offset().top - 100
                }, 800);
            }, 100);
        } else {
            showToast('Please select a degree program first.', 'warning');
        }
    });

    function populateEquivalenciesTable(equivalencies) {
        const tbody = $('#equivalencies_body');
        tbody.empty();

        if (equivalencies.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="11" class="empty-state">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p class="mb-0">No existing equivalencies found for this program.</p>
                    </td>
                </tr>
            `);
            return;
        }

        equivalencies.forEach(function(equiv, index) {
            let sourceBadge = '<span class="badge-source manual">Manual</span>';
            if (equiv.source === 'imported') {
                sourceBadge = '<span class="badge-source imported">Imported</span>';
            } else if (equiv.source === 'seeded') {
                sourceBadge = '<span class="badge-source seeded">Seeded</span>';
            }

            const row = $(`
                <tr>
                    <td class="text-center"><strong>${index + 1}</strong></td>
                    <td><span class="course-code">${equiv.diploma_course_code}</span></td>
                    <td><small>${equiv.diploma_course_name}</small></td>
                    <td><small>${equiv.diploma_institution || 'N/A'}</small></td>
                    <td class="text-center">${equiv.diploma_credit_hour}</td>
                    <td><span class="course-code">${equiv.degree_course_code}</span></td>
                    <td><small>${equiv.degree_course_name || 'N/A'}</small></td>
                    <td class="text-center">${equiv.degree_credit_hour || 'N/A'}</td>
                    <td class="text-center"><span class="badge-match">${equiv.match_percentage}%</span></td>
                    <td class="text-center">${sourceBadge}</td>
                    <td class="text-center">
                        <button type="button" class="btn-action edit edit-equivalency" data-id="${equiv.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn-action delete delete-equivalency" data-id="${equiv.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            row.find('.edit-equivalency').data('equiv', equiv);
            tbody.append(row);
        });
    }

    function showToast(message, type = 'success') {
        const toast = $(`
            <div class="toast-notification ${type}">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                ${message}
            </div>
        `);
        $('body').append(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    $(document).on('click', '.edit-equivalency', function() {
        const equiv = $(this).data('equiv');

        if (!equiv) {
            showToast('Error: No equivalency data found', 'danger');
            return;
        }

        $('#edit_equivalency_id').val(equiv.id);
        $('#edit_diploma_course_code').val(equiv.diploma_course_code);
        $('#edit_diploma_course_name').val(equiv.diploma_course_name);
        $('#edit_diploma_credit_hours').val(equiv.diploma_credit_hour);
        $('#edit_degree_course_code').val(equiv.degree_course_code);
        $('#edit_degree_course_name').val(equiv.degree_course_name || '');
        $('#edit_degree_credit_hours').val(equiv.degree_credit_hour || '');
        $('#edit_equivalency_percentage').val(equiv.match_percentage);

        $('#editEquivalencyModal').modal('show');
    });

    $('#edit_equivalency_form').submit(function(e) {
        e.preventDefault();
        const equivalencyId = $('#edit_equivalency_id').val();
        const formData = $(this).serialize();

        $.ajax({
            url: routes.updateEquivalency + equivalencyId,
            method: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#editEquivalencyModal').modal('hide');
                $('#load_equivalencies_btn').click();
                showToast(response.message || 'Equivalency updated successfully!');
            },
            error: function() {
                showToast('Error updating equivalency. Please try again.', 'danger');
            }
        });
    });

    $(document).on('click', '.delete-equivalency', function() {
        if (!confirm('Are you sure you want to delete this equivalency?')) return;

        const equivalencyId = $(this).data('id');

        $.ajax({
            url: routes.deleteEquivalency + equivalencyId,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#load_equivalencies_btn').click();
                showToast(response.message || 'Equivalency deleted successfully!');
            },
            error: function() {
                showToast('Error deleting equivalency. Please try again.', 'danger');
            }
        });
    });

    $('#add_equivalency_form').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.post(routes.storeBulk, formData)
        .done(function(response) {
            $('#add_equivalency_form')[0].reset();
            $('#new_equivalency_form').hide();
            $('#load_equivalencies_btn').click();
            showToast('New equivalency added successfully!');
        })
        .fail(function(xhr) {
            let errorMessage = 'Error adding new equivalency. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showToast(errorMessage, 'danger');
        });
    });

    function formatCourseCode(input) {
        let value = input.trim().toUpperCase();
        value = value.replace(/\s+/g, ' ');
        value = value.replace(/\s*\+\s*/g, ' + ');
        return value;
    }

    $(document).on('blur', 'textarea[name*="diploma_course_code"], #edit_diploma_course_code', function() {
        const formatted = formatCourseCode($(this).val());
        $(this).val(formatted);
    });
});
</script>
@endpush
