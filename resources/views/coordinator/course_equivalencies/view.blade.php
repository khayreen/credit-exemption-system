@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Course Equivalencies Overview</h4>
                    <a href="{{ route('coordinator.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Information:</strong> This is a read-only view of existing course equivalencies. 
                        Only Resource Persons can modify these equivalencies.
                    </div>

                    <!-- Degree Program Selection -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="degree_program_select" class="form-label">
                                <strong>Select Degree Program</strong>
                            </label>
                            <select id="degree_program_select" class="form-select" required>
                                <option value="">-- Select Degree Program --</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->code }}" data-name="{{ $program->name }}">
                                        {{ $program->code }} - {{ $program->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="button" id="load_equivalencies_btn" class="btn btn-primary" disabled>
                                <i class="fas fa-eye"></i> View Equivalencies
                            </button>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loading_indicator" style="display: none;" class="text-center mb-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading course equivalencies...</p>
                    </div>

                    <!-- Equivalencies Table -->
                    <div id="equivalencies_section" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 id="program_title" class="mb-0"></h5>
                            <div class="badge bg-info fs-6">
                                <span id="total_equivalencies">0</span> Total Equivalencies
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 15%">Diploma Course Code</th>
                                        <th style="width: 25%">Diploma Course Name</th>
                                        <th style="width: 8%">Credits</th>
                                        <th style="width: 12%">Degree Course Code</th>
                                        <th style="width: 25%">Degree Course Name</th>
                                        <th style="width: 8%">Credits</th>
                                        <th style="width: 12%">Match %</th>
                                    </tr>
                                </thead>
                                <tbody id="equivalencies_tbody">
                                    <!-- Dynamic content will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="empty_state" style="display: none;" class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Course Equivalencies Found</h5>
                        <p class="text-muted">No equivalencies have been created for the selected degree program yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('degree_program_select');
    const loadBtn = document.getElementById('load_equivalencies_btn');
    const loadingIndicator = document.getElementById('loading_indicator');
    const equivalenciesSection = document.getElementById('equivalencies_section');
    const emptyState = document.getElementById('empty_state');
    const programTitle = document.getElementById('program_title');
    const totalEquivalencies = document.getElementById('total_equivalencies');
    const equivalenciesTbody = document.getElementById('equivalencies_tbody');

    // Enable/disable load button based on program selection
    programSelect.addEventListener('change', function() {
        loadBtn.disabled = !this.value;
        hideAllSections();
    });

    // Load equivalencies when button is clicked
    loadBtn.addEventListener('click', function() {
        const programCode = programSelect.value;
        const programName = programSelect.selectedOptions[0].dataset.name;
        
        if (!programCode) return;

        loadEquivalencies(programCode, programName);
    });

    function hideAllSections() {
        loadingIndicator.style.display = 'none';
        equivalenciesSection.style.display = 'none';
        emptyState.style.display = 'none';
    }

    function loadEquivalencies(programCode, programName) {
        hideAllSections();
        loadingIndicator.style.display = 'block';
        loadBtn.disabled = true;

        fetch(`{{ route('coordinator.api.existing_equivalencies') }}?program_code=${programCode}`)
            .then(response => response.json())
            .then(equivalencies => {
                hideAllSections();
                
                if (equivalencies.length === 0) {
                    emptyState.style.display = 'block';
                } else {
                    displayEquivalencies(equivalencies, programName);
                    equivalenciesSection.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error loading equivalencies:', error);
                hideAllSections();
                alert('Error loading equivalencies. Please try again.');
            })
            .finally(() => {
                loadBtn.disabled = false;
            });
    }

    function displayEquivalencies(equivalencies, programName) {
        programTitle.textContent = `Course Equivalencies for ${programName}`;
        totalEquivalencies.textContent = equivalencies.length;
        
        equivalenciesTbody.innerHTML = '';
        
        equivalencies.forEach((equiv, index) => {
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td><strong>${index + 1}</strong></td>
                <td>
                    <span class="badge bg-primary fs-6">${equiv.diploma_course_code || 'N/A'}</span>
                </td>
                <td>${equiv.diploma_course_name || 'N/A'}</td>
                <td>
                    <span class="badge bg-secondary">${equiv.diploma_credit_hour || 'N/A'}</span>
                </td>
                <td>
                    <span class="badge bg-success fs-6">${equiv.degree_course_code || 'N/A'}</span>
                </td>
                <td>${equiv.degree_course_name || 'N/A'}</td>
                <td>
                    <span class="badge bg-secondary">${equiv.degree_credit_hour || 'N/A'}</span>
                </td>
                <td>
                    <span class="badge ${getMatchPercentageBadge(equiv.match_percentage)} fs-6">
                        ${equiv.match_percentage || 0}%
                    </span>
                </td>
            `;
            
            equivalenciesTbody.appendChild(row);
        });
    }

    function getMatchPercentageBadge(percentage) {
        if (percentage >= 90) return 'bg-success';
        if (percentage >= 80) return 'bg-warning';
        if (percentage >= 70) return 'bg-info';
        return 'bg-danger';
    }
});
</script>
@endsection