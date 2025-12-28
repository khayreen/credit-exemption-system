@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Course Equivalency Management</h4>
                    <a href="{{ route('resource_person.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Degree Program Selection -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="degree_program_select" class="form-label">
                                <strong>Select Degree Program</strong>
                            </label>
                            <select id="degree_program_select" class="form-select" required>
                                <option value="">-- Select Degree Program --</option>
                                @foreach($degreePrograms as $program)
                                    <option value="{{ $program->code }}" data-name="{{ $program->name }}">
                                        {{ $program->code }} - {{ $program->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="institution_filter" class="form-label">
                                <strong>Filter by Institution</strong>
                            </label>
                            <select id="institution_filter" class="form-select">
                                <option value="">All Institutions</option>
                                <option value="Politeknik">Politeknik</option>
                                <option value="UTM">UTM</option>
                                <option value="UiTM">UiTM</option>
                                <option value="MMU">MMU</option>
                                <option value="GMI">GMI</option>
                                <option value="UPSI">UPSI</option>
                                <option value="Kolej">Kolej</option>
                            </select>
                            <small class="text-muted">Filter equivalencies by diploma institution</small>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" id="load_equivalencies_btn" class="btn btn-primary me-2" disabled>
                                <i class="fas fa-download"></i> Load Equivalencies
                            </button>
                            <button type="button" id="add_new_btn" class="btn btn-success" disabled>
                                <i class="fas fa-plus"></i> Add New Mapping
                            </button>
                        </div>
                    </div>

                    <!-- Existing Equivalencies Display -->
                    <div id="equivalencies_section" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5 class="text-primary"><span id="program_display_name"></span></h5>
                                <!-- <p class="text-muted">Existing diploma-to-degree course mappings with match percentages.</p> -->
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="equivalencies_table">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="10%">Diploma Course Code</th>
                                        <th width="15%">Diploma Course Name</th>
                                        <th width="12%">Institution</th>
                                        <th width="6%">Dip. Credits</th>
                                        <th width="10%">Degree Course Code</th>
                                        <th width="15%">Degree Course Name</th>
                                        <th width="6%">Deg. Credits</th>
                                        <th width="8%">Match %</th>
                                        <th width="8%">Source</th>
                                        <th width="7%">Actions</th>
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
                        <hr class="my-4">
                        <h5 class="text-success mb-3">Add New Course Equivalency</h5>
                        
                        <form id="add_equivalency_form" method="POST" action="{{ route('resource_person.course_equivalencies.store_bulk') }}">
                            @csrf
                            <input type="hidden" name="degree_program_code" id="selected_program_code">
                            
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">Diploma Course Code(s)</label>
                                    <textarea name="diploma_courses[0][diploma_course_code]" 
                                              class="form-control" rows="2" 
                                              placeholder="Examples:&#10;CSC402&#10;CSC138/CSC126&#10;CSC138/CSC126 + CSC186"
                                              title="Enter single course (CSC402) or multiple courses (CSC138/CSC126) or combinations (CSC138/CSC126 + CSC186)"
                                              required></textarea>
                                    <small class="text-muted">Supports: single, multiple (/), or combined (+) courses</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Diploma Course Name</label>
                                    <input type="text" name="diploma_courses[0][diploma_course_name]" 
                                           class="form-control" placeholder="e.g., Programming I" required>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Diploma Credits</label>
                                    <input type="number" name="diploma_courses[0][diploma_credit_hours]" 
                                           class="form-control" min="1" step="1" placeholder="3" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Degree Course Code</label>
                                    <input type="text" name="diploma_courses[0][degree_course_code]" 
                                           class="form-control" placeholder="e.g., CS131" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Degree Course Name</label>
                                    <input type="text" name="diploma_courses[0][degree_course_name]" 
                                           class="form-control" placeholder="e.g., Programming I" required>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Degree Credits</label>
                                    <input type="number" name="diploma_courses[0][degree_credit_hours]" 
                                           class="form-control degree-course-credits" min="1" step="1" placeholder="3" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="form-label">Equivalency %</label>
                                    <input type="number" name="diploma_courses[0][equivalency_percentage]" 
                                           class="form-control" min="0" max="100" step="0.1" placeholder="85.0" required>
                                </div>
                                <div class="col-md-10 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success">
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
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editEquivalencyModal" tabindex="-1" aria-labelledby="editEquivalencyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEquivalencyModalLabel">Edit Course Equivalency</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit_equivalency_form">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_equivalency_id">
                    
                    <!-- Header Row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="fas fa-graduation-cap"></i> Diploma Course Information</h6>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success mb-3"><i class="fas fa-university"></i> Degree Course Information</h6>
                        </div>
                    </div>
                    
                    <!-- Course Codes Row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Diploma Course Code(s)</label>
                            <textarea id="edit_diploma_course_code" name="diploma_course_code" 
                                      class="form-control" rows="2"
                                      placeholder="Examples:&#10;CSC402&#10;CSC138/CSC126&#10;CSC138/CSC126 + CSC186"
                                      title="Enter single course (CSC402) or multiple courses (CSC138/CSC126) or combinations (CSC138/CSC126 + CSC186)"
                                      required></textarea>
                            <small class="text-muted">Supports: single, multiple (/), or combined (+) courses</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Degree Course Code</label>
                            <input type="text" id="edit_degree_course_code" name="degree_course_code" 
                                   class="form-control" placeholder="e.g., CS131" required>
                        </div>
                    </div>
                    
                    <!-- Course Names Row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Diploma Course Name</label>
                            <input type="text" id="edit_diploma_course_name" name="diploma_course_name" 
                                   class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Degree Course Name</label>
                            <input type="text" id="edit_degree_course_name" name="degree_course_name" 
                                   class="form-control" placeholder="e.g., Programming I" required>
                        </div>
                    </div>
                    
                    <!-- Credit Hours Row -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Diploma Credit Hours</label>
                            <input type="number" id="edit_diploma_credit_hours" name="diploma_credit_hours" 
                                   class="form-control" min="1" step="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Degree Credit Hours</label>
                            <input type="number" id="edit_degree_credit_hours" name="degree_credit_hours" 
                                   class="form-control" min="1" step="1" required>
                        </div>
                    </div>
                    
                    <!-- Equivalency Percentage - Centered at Bottom -->
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <label class="form-label text-center d-block"><i class="fas fa-percentage"></i> Equivalency Percentage</label>
                            <input type="number" id="edit_equivalency_percentage" name="equivalency_percentage" 
                                   class="form-control text-center" min="0" max="100" step="0.1" required>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Equivalency
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let degreeCourses = [];
    let currentProgramCode = '';
    
    // Define route URLs
    const routes = {
        getDegreeCourses: '{{ route("resource_person.api.degree_program_courses") }}',
        getExistingEquivalencies: '{{ route("resource_person.api.existing_equivalencies") }}',
        storeBulk: '{{ route("resource_person.course_equivalencies.store_bulk") }}',
        updateEquivalency: '/resource-person/course-equivalencies/',
        deleteEquivalency: '/resource-person/course-equivalencies/'
    };

    // Enable buttons when program is selected
    $('#degree_program_select').change(function() {
        const selected = $(this).val();
        $('#load_equivalencies_btn, #add_new_btn').prop('disabled', !selected);
        if (!selected) {
            $('#equivalencies_section, #new_equivalency_form').hide();
        }
    });

    // Load existing equivalencies and degree courses for selected program
    $('#load_equivalencies_btn').click(function() {
        const programCode = $('#degree_program_select').val();
        const programName = $('#degree_program_select option:selected').data('name');
        const institutionFilter = $('#institution_filter').val();

        if (!programCode) return;

        currentProgramCode = programCode;
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

        // Build query parameters with institution filter
        const params = { program_code: programCode };
        if (institutionFilter) {
            params.institution = institutionFilter;
        }

        // Load degree courses and existing equivalencies
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

            // Populate existing equivalencies table
            populateEquivalenciesTable(equivalencies);

            // Show sections
            $('#equivalencies_section').show();
        })
        .fail(function() {
            alert('Error loading data. Please try again.');
        })
        .always(function() {
            $('#load_equivalencies_btn').prop('disabled', false).html('<i class="fas fa-download"></i> Load Equivalencies');
        });
    });

    // Re-load when institution filter changes
    $('#institution_filter').change(function() {
        if ($('#degree_program_select').val()) {
            $('#load_equivalencies_btn').click();
        }
    });

    // Show new equivalency form
    $('#add_new_btn').click(function() {
        const programCode = $('#degree_program_select').val();
        if (programCode) {
            currentProgramCode = programCode;
            $('#selected_program_code').val(programCode);
            $('#new_equivalency_form').toggle();
            
            // Auto-scroll to the Add New Course Equivalency section
            setTimeout(function() {
                $('html, body').animate({
                    scrollTop: $('#new_equivalency_form').offset().top - 100
                }, 800);
            }, 100);
        } else {
            alert('Please select a degree program first.');
        }
    });

    // Populate equivalencies table
    function populateEquivalenciesTable(equivalencies) {
        const tbody = $('#equivalencies_body');
        tbody.empty();

        if (equivalencies.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="11" class="text-center text-muted">
                        No existing equivalencies found for this program.
                    </td>
                </tr>
            `);
            return;
        }

        equivalencies.forEach(function(equiv, index) {
            const degreeCourse = equiv.degree_course || {};
            // Get source badge
            let sourceBadge = '<span class="badge bg-secondary">Manual</span>';
            if (equiv.source === 'imported') {
                sourceBadge = '<span class="badge bg-info">Imported</span>';
            } else if (equiv.source === 'seeded') {
                sourceBadge = '<span class="badge bg-success">Seeded</span>';
            }

            const row = $(`
                <tr>
                    <td class="text-center"><strong>${index + 1}</strong></td>
                    <td><strong>${equiv.diploma_course_code}</strong></td>
                    <td>${equiv.diploma_course_name}</td>
                    <td><small>${equiv.diploma_institution || 'N/A'}</small></td>
                    <td class="text-center">${equiv.diploma_credit_hour}</td>
                    <td><strong>${equiv.degree_course_code}</strong></td>
                    <td>${equiv.degree_course_name || 'N/A'}</td>
                    <td class="text-center">${equiv.degree_credit_hour || 'N/A'}</td>
                    <td class="text-center"><span class="badge bg-primary">${equiv.match_percentage}%</span></td>
                    <td class="text-center">${sourceBadge}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-warning edit-equivalency"
                                data-id="${equiv.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-equivalency"
                                data-id="${equiv.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            // Store the equivalency data directly on the button element
            row.find('.edit-equivalency').data('equiv', equiv);
            tbody.append(row);
        });
    }


    // Edit equivalency
    $(document).on('click', '.edit-equivalency', function() {
        console.log('Edit button clicked'); // Debug log
        const equiv = $(this).data('equiv');
        console.log('Equivalency data:', equiv); // Debug log
        
        if (!equiv) {
            alert('Error: No equivalency data found');
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

    // Handle edit form submission
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
                $('#load_equivalencies_btn').click(); // Reload the table
                
                // Show success message
                $('body').prepend(`
                    <div class="alert alert-success alert-dismissible fade show position-fixed" 
                         style="top: 20px; right: 20px; z-index: 9999;">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            },
            error: function() {
                alert('Error updating equivalency. Please try again.');
            }
        });
    });

    // Delete equivalency
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
                $('#load_equivalencies_btn').click(); // Reload the table
                
                // Show success message
                $('body').prepend(`
                    <div class="alert alert-success alert-dismissible fade show position-fixed" 
                         style="top: 20px; right: 20px; z-index: 9999;">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            },
            error: function() {
                alert('Error deleting equivalency. Please try again.');
            }
        });
    });

    // Handle new equivalency form submission
    $('#add_equivalency_form').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.post(routes.storeBulk, formData)
        .done(function(response) {
            // Reset form
            $('#add_equivalency_form')[0].reset();
            $('#new_equivalency_form').hide();
            
            // Reload equivalencies
            $('#load_equivalencies_btn').click();
            
            // Show success message
            $('body').prepend(`
                <div class="alert alert-success alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999;">
                    New equivalency added successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
        })
        .fail(function(xhr, status, error) {
            console.log('Error response:', xhr.responseText);
            console.log('Status:', status);
            console.log('Error:', error);
            
            let errorMessage = 'Error adding new equivalency. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.errors) {
                        errorMessage = Object.values(response.errors).flat().join('\n');
                    }
                } catch (e) {
                    errorMessage = xhr.responseText;
                }
            }
            
            alert(errorMessage);
        });
    });

    // Helper function to format and validate diploma course codes
    function formatCourseCode(input) {
        let value = input.trim().toUpperCase();
        
        // Replace multiple spaces with single space
        value = value.replace(/\s+/g, ' ');
        
        // Ensure proper spacing around + symbol
        value = value.replace(/\s*\+\s*/g, ' + ');
        
        return value;
    }

    // Add real-time formatting to course code inputs
    $(document).on('blur', 'textarea[name*="diploma_course_code"], #edit_diploma_course_code', function() {
        const formatted = formatCourseCode($(this).val());
        $(this).val(formatted);
    });

    // Add input validation hints
    $(document).on('input', 'textarea[name*="diploma_course_code"], #edit_diploma_course_code', function() {
        const value = $(this).val().trim();
        const $feedback = $(this).siblings('.validation-feedback');
        
        // Remove existing feedback
        $feedback.remove();
        
        if (value.length > 0) {
            // Check for common patterns
            const hasValidPattern = /^[A-Z]{2,4}\d{2,4}/.test(value) || 
                                  /^[A-Z]{2,4}\d{2,4}\/[A-Z]{2,4}\d{2,4}/.test(value) ||
                                  /\+/.test(value);
            
            if (!hasValidPattern && value.length > 3) {
                $(this).after('<div class="validation-feedback text-warning small">💡 Tip: Use formats like CSC402, CSC138/CSC126, or CSC138/CSC126 + CSC186</div>');
            }
        }
    });
});
</script>
@endpush
@endsection