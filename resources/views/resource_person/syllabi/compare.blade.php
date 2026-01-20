@extends('layouts.app')

@push('styles')
<style>
    /* Consistent square action buttons */
    .action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('resource_person.equivalency_requests.index') }}">Equivalency Requests</a></li>
                <li class="breadcrumb-item"><a href="{{ route('resource_person.equivalency_requests.review', $request) }}">Review Request</a></li>
                <li class="breadcrumb-item active">Syllabus Comparison</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0"><i class="fas fa-columns me-2 text-primary"></i>Side-by-Side Syllabus Comparison</h2>
            </div>
            <a href="{{ route('resource_person.equivalency_requests.review', $request) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back to Review
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Side-by-Side PDF Comparison -->
    <div class="row mb-3">
        <!-- Left Side: Diploma Course Syllabus -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-graduation-cap me-2"></i>
                            <strong>Diploma Course Syllabus</strong>
                        </div>
                        <a href="{{ route('resource_person.external_submission.view_syllabus', $submission) }}"
                           class="btn btn-light btn-sm" target="_blank" title="Open in new tab">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-2 bg-light">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="badge bg-primary">{{ $submission->course_code }}</span>
                        <span class="text-dark fw-bold">{{ $submission->course_name }}</span>
                        <span class="badge bg-secondary">{{ number_format($submission->credit_hours, 1) }} Credits</span>
                    </div>
                    <small class="text-muted"><i class="fas fa-university me-1"></i>{{ $submission->institution_name }}</small>
                </div>
                <div class="card-body p-0" style="height: 650px;">
                    @if($submission->syllabus_file_path)
                        <iframe
                            src="{{ route('resource_person.external_submission.view_syllabus', $submission) }}"
                            width="100%"
                            height="100%"
                            style="border: none;"
                            title="Diploma Course Syllabus">
                        </iframe>
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <div class="text-center">
                                <i class="fas fa-file-pdf fa-4x mb-3"></i>
                                <p>No syllabus file available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Side: Degree Course Syllabus -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-university me-2"></i>
                            <strong>UiTM Degree Course Syllabus</strong>
                        </div>
                        <a href="#" id="openDegreeNewTab" class="btn btn-light btn-sm d-none" target="_blank" title="Open in new tab">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-2 bg-light">
                    <!-- Student's Requested Course Info -->
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="badge bg-success">{{ $request->suggested_degree_course_code }}</span>
                        <span class="text-dark fw-bold">{{ $request->suggested_degree_course_name }}</span>
                        <span class="badge bg-info">Requested by Student</span>
                    </div>
                    <small class="text-muted"><i class="fas fa-graduation-cap me-1"></i>Program: {{ $request->current_program_code }} - {{ $request->current_program_name }}</small>

                    @if($recommendations->isNotEmpty())
                        <hr class="my-2">
                        <div class="row align-items-center">
                            <div class="col">
                                <label class="form-label small text-muted mb-1">Select degree course to compare:</label>
                                <select class="form-select form-select-sm" id="degreeCourseSelect">
                                    <option value="">-- Select a degree course --</option>
                                    @foreach($recommendations as $index => $rec)
                                        @php
                                            $hasSyllabus = $rec['has_syllabus'] ?? false;
                                            $similarity = $rec['similarity'];
                                            $isSuggested = $rec['is_suggested'] ?? false;
                                            $pdfUrl = $hasSyllabus && $rec['syllabus'] ? route('resource_person.syllabi.view_pdf', $rec['syllabus']) : '';
                                        @endphp
                                        <option value="{{ $pdfUrl }}"
                                                data-code="{{ $rec['course_code'] }}"
                                                data-name="{{ $rec['course_name'] }}"
                                                data-credits="{{ $rec['credit_hours'] }}"
                                                data-similarity="{{ $similarity }}"
                                                data-has-syllabus="{{ $hasSyllabus ? '1' : '0' }}"
                                                data-source="{{ $rec['source'] ?? 'unknown' }}"
                                                {{ $isSuggested ? 'selected' : '' }}>
                                            {{ $rec['course_code'] }} - {{ $rec['course_name'] }} ({{ $similarity }}% match){{ $isSuggested ? ' ★' : '' }}{{ !$hasSyllabus ? ' [No PDF]' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="degreeCourseMeta" class="mt-2 d-none">
                            <small class="text-muted">Viewing:</small>
                            <span class="badge bg-success" id="degreeCourseCode"></span>
                            <span class="fw-bold text-dark" id="degreeCourseName"></span>
                            <span class="badge bg-secondary" id="degreeCourseCredits"></span>
                            <span class="badge bg-info" id="degreeSimilarity"></span>
                            <span class="badge bg-warning text-dark d-none" id="noSyllabusBadge"><i class="fas fa-exclamation-triangle me-1"></i>No PDF</span>
                        </div>
                    @else
                        <hr class="my-2">
                        <div class="alert alert-warning mb-0 py-2">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>No degree courses found.</strong><br>
                            <small>No equivalencies exist for {{ $request->current_program_code }} yet. Upload a syllabus for <strong>{{ $request->suggested_degree_course_code }}</strong> to begin.</small>
                        </div>
                    @endif
                </div>
                <div class="card-body p-0" style="height: 600px;">
                    @if($recommendations->isNotEmpty())
                        <div id="degreePdfPlaceholder" class="d-flex align-items-center justify-content-center h-100 text-muted bg-light">
                            <div class="text-center">
                                <i class="fas fa-hand-pointer fa-4x mb-3"></i>
                                <h5>Select a degree course above</h5>
                                <p class="mb-0">The syllabus PDF will appear here</p>
                            </div>
                        </div>
                        <iframe
                            id="degreePdfFrame"
                            src=""
                            width="100%"
                            height="100%"
                            style="border: none; display: none;"
                            title="Degree Course Syllabus">
                        </iframe>
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted bg-light">
                            <div class="text-center">
                                <i class="fas fa-upload fa-4x mb-3"></i>
                                <h5>No Syllabi Available</h5>
                                <p class="text-muted mb-2">Student requested equivalency for:</p>
                                <p class="mb-3">
                                    <span class="badge bg-success fs-6">{{ $request->suggested_degree_course_code }}</span><br>
                                    <span class="text-dark">{{ $request->suggested_degree_course_name }}</span>
                                </p>
                                <a href="{{ route('resource_person.syllabi.create', [
                                    'course_code' => $request->suggested_degree_course_code,
                                    'course_name' => $request->suggested_degree_course_name,
                                    'program_code' => $request->current_program_code,
                                    'return_to' => route('resource_person.equivalency_requests.compare', $request)
                                ]) }}" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>Upload Degree Syllabus
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recommended Courses Quick Reference -->
    @if($recommendations->isNotEmpty())
    @php
        $withSyllabi = $recommendations->filter(fn($r) => $r['has_syllabus'] ?? false);
        $withoutSyllabi = $recommendations->filter(fn($r) => !($r['has_syllabus'] ?? false));
    @endphp
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-dark text-white py-2 d-flex justify-content-between align-items-center"
             data-bs-toggle="collapse" data-bs-target="#recommendationsCollapse"
             style="cursor: pointer;">
            <span>
                <i class="fas fa-list-ol me-2"></i>Degree Courses Similar to <strong>{{ $request->suggested_degree_course_code }}</strong>
                <small class="ms-2">({{ $withSyllabi->count() }} with PDF, {{ $withoutSyllabi->count() }} without)</small>
            </span>
            <i class="fas fa-chevron-down" id="collapseIcon"></i>
        </div>
        <div class="collapse show" id="recommendationsCollapse">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-3 py-2" width="100">
                                    Similarity
                                    <i class="fas fa-info-circle text-muted ms-1" title="Similarity to {{ $request->suggested_degree_course_code }} - {{ $request->suggested_degree_course_name }}" data-bs-toggle="tooltip"></i>
                                </th>
                                <th class="py-2">Course Code</th>
                                <th class="py-2">Course Name</th>
                                <th class="py-2 text-center">Credits</th>
                                <th class="py-2 text-center">Syllabus</th>
                                <th class="py-2 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recommendations as $rec)
                                @php
                                    $hasSyllabus = $rec['has_syllabus'] ?? false;
                                    $similarity = $rec['similarity'];
                                    $isSuggested = $rec['is_suggested'] ?? false;
                                    $source = $rec['source'] ?? 'unknown';
                                    $pdfUrl = $hasSyllabus && $rec['syllabus'] ? route('resource_person.syllabi.view_pdf', $rec['syllabus']) : '';
                                    $badgeClass = match(true) {
                                        $similarity >= 80 => 'bg-success',
                                        $similarity >= 60 => 'bg-info',
                                        $similarity >= 40 => 'bg-warning text-dark',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <tr class="{{ $isSuggested ? 'table-warning' : '' }}">
                                    <td class="px-3 py-2">
                                        <span class="badge {{ $badgeClass }}">{{ $similarity }}%</span>
                                    </td>
                                    <td class="py-2">
                                        <span class="badge bg-primary">{{ $rec['course_code'] }}</span>
                                        @if($isSuggested)
                                            <i class="fas fa-star text-warning ms-1" title="Student's suggested course"></i>
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        {{ $rec['course_name'] }}
                                        @if($source === 'suggested')
                                            <small class="text-muted">(Student suggested)</small>
                                        @endif
                                    </td>
                                    <td class="py-2 text-center">{{ $rec['credit_hours'] }}</td>
                                    <td class="py-2 text-center">
                                        @if($hasSyllabus)
                                            <span class="badge bg-success"><i class="fas fa-file-pdf me-1"></i>Available</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i>Not Uploaded</span>
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        <div class="d-flex gap-1 justify-content-center">
                                            @if($hasSyllabus)
                                                <button type="button" class="btn btn-sm btn-outline-success action-btn compare-btn"
                                                        data-url="{{ $pdfUrl }}"
                                                        data-code="{{ $rec['course_code'] }}"
                                                        data-name="{{ $rec['course_name'] }}"
                                                        data-credits="{{ $rec['credit_hours'] }}"
                                                        data-similarity="{{ $similarity }}"
                                                        data-has-syllabus="1"
                                                        title="View PDF in comparison panel">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('resource_person.syllabi.create', [
                                                    'course_code' => $rec['course_code'],
                                                    'course_name' => $rec['course_name'],
                                                    'program_code' => $request->current_program_code,
                                                    'credit_hours' => $rec['credit_hours'],
                                                    'return_to' => route('resource_person.equivalency_requests.compare', $request)
                                                ]) }}" class="btn btn-sm btn-outline-warning action-btn"
                                                   title="Upload syllabus PDF">
                                                    <i class="fas fa-upload"></i>
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-primary action-btn select-course-btn"
                                                    data-course-code="{{ $rec['course_code'] }}"
                                                    data-similarity="{{ $similarity }}"
                                                    title="Select for decision">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Decision Form -->
    <div class="card shadow-sm mb-4 border-primary">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Make Your Decision</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('resource_person.equivalency_requests.process', $request) }}" method="POST" id="comparisonDecisionForm">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Approved Degree Course Code <span class="text-danger">*</span></label>
                        <input type="text" name="approved_degree_course_code" id="approvedCourseCode"
                               class="form-control" value="{{ old('approved_degree_course_code', $request->suggested_degree_course_code) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Match Percentage <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="match_percentage" id="matchPercentage"
                                   class="form-control" value="{{ old('match_percentage', 85) }}" min="0" max="100" step="1" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">Your assessment (>80% = eligible for exemption)</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Decision <span class="text-danger">*</span></label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="decision" id="decision_approve" value="approved" required>
                            <label class="btn btn-outline-success" for="decision_approve">
                                <i class="fas fa-check me-1"></i>Equivalent
                            </label>
                            <input type="radio" class="btn-check" name="decision" id="decision_reject" value="rejected" required>
                            <label class="btn btn-outline-danger" for="decision_reject">
                                <i class="fas fa-times me-1"></i>Not Equivalent
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Reviewer Notes <span class="text-muted fw-normal">(Optional)</span></label>
                        <textarea name="reviewer_notes" class="form-control" rows="2" placeholder="Any additional notes about your decision...">{{ old('reviewer_notes') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('resource_person.equivalency_requests.review', $request) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitDecisionBtn" disabled>
                        <i class="fas fa-paper-plane me-1"></i>Submit Decision
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

    const degreeCourseSelect = document.getElementById('degreeCourseSelect');
    const degreePdfFrame = document.getElementById('degreePdfFrame');
    const degreePdfPlaceholder = document.getElementById('degreePdfPlaceholder');
    const degreeCourseMeta = document.getElementById('degreeCourseMeta');
    const degreeCourseCode = document.getElementById('degreeCourseCode');
    const degreeCourseName = document.getElementById('degreeCourseName');
    const degreeCourseCredits = document.getElementById('degreeCourseCredits');
    const degreeSimilarity = document.getElementById('degreeSimilarity');
    const noSyllabusBadge = document.getElementById('noSyllabusBadge');
    const openDegreeNewTab = document.getElementById('openDegreeNewTab');
    const approvedCourseCode = document.getElementById('approvedCourseCode');
    const matchPercentage = document.getElementById('matchPercentage');
    const submitDecisionBtn = document.getElementById('submitDecisionBtn');
    const approveRadio = document.getElementById('decision_approve');
    const rejectRadio = document.getElementById('decision_reject');

    // Upload URL template
    const uploadUrlBase = "{{ route('resource_person.syllabi.create') }}";
    const returnUrl = "{{ route('resource_person.equivalency_requests.compare', $request) }}";
    const programCode = "{{ $request->current_program_code }}";

    // Function to show upload prompt for courses without syllabi
    function showUploadPrompt(code, name, credits) {
        if (degreePdfFrame) degreePdfFrame.style.display = 'none';
        if (degreePdfPlaceholder) {
            const uploadUrl = `${uploadUrlBase}?course_code=${encodeURIComponent(code)}&course_name=${encodeURIComponent(name)}&program_code=${encodeURIComponent(programCode)}&credit_hours=${credits}&return_to=${encodeURIComponent(returnUrl)}`;
            degreePdfPlaceholder.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-file-upload fa-4x mb-3 text-warning"></i>
                    <h5>No Syllabus Uploaded</h5>
                    <p class="mb-2">
                        <span class="badge bg-primary fs-6">${code}</span><br>
                        <span class="text-dark">${name}</span>
                    </p>
                    <p class="text-muted mb-3">This course exists in the equivalency database but has no uploaded syllabus PDF.</p>
                    <a href="${uploadUrl}" class="btn btn-warning">
                        <i class="fas fa-upload me-1"></i>Upload Syllabus Now
                    </a>
                </div>
            `;
            degreePdfPlaceholder.style.display = 'flex';
        }

        // Update metadata
        if (degreeCourseMeta) {
            degreeCourseMeta.classList.remove('d-none');
            degreeCourseCode.textContent = code;
            degreeCourseName.textContent = name;
            degreeCourseCredits.textContent = credits + ' Credits';
            degreeSimilarity.textContent = '';
            if (noSyllabusBadge) noSyllabusBadge.classList.remove('d-none');
        }

        if (openDegreeNewTab) openDegreeNewTab.classList.add('d-none');
    }

    // Function to load degree PDF
    function loadDegreePdf(url, code, name, credits, similarity, hasSyllabus) {
        // Hide no-syllabus badge by default
        if (noSyllabusBadge) noSyllabusBadge.classList.add('d-none');

        if (!hasSyllabus || !url) {
            showUploadPrompt(code, name, credits);
            // Still update similarity if available
            if (degreeCourseMeta && similarity) {
                degreeSimilarity.textContent = similarity + '% match';
            }
            return;
        }

        if (degreePdfFrame && url) {
            degreePdfFrame.src = url;
            degreePdfFrame.style.display = 'block';
            if (degreePdfPlaceholder) degreePdfPlaceholder.style.display = 'none';

            // Update metadata
            if (degreeCourseMeta) {
                degreeCourseMeta.classList.remove('d-none');
                degreeCourseCode.textContent = code;
                degreeCourseName.textContent = name;
                degreeCourseCredits.textContent = credits + ' Credits';
                degreeSimilarity.textContent = similarity + '% match';
            }

            // Update new tab link
            if (openDegreeNewTab) {
                openDegreeNewTab.href = url;
                openDegreeNewTab.classList.remove('d-none');
            }
        }
    }

    // Handle dropdown selection
    if (degreeCourseSelect) {
        degreeCourseSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.dataset.code) {
                const hasSyllabus = opt.dataset.hasSyllabus === '1';
                loadDegreePdf(
                    this.value,
                    opt.dataset.code,
                    opt.dataset.name,
                    opt.dataset.credits,
                    opt.dataset.similarity,
                    hasSyllabus
                );
            } else {
                if (degreePdfFrame) degreePdfFrame.style.display = 'none';
                if (degreePdfPlaceholder) {
                    degreePdfPlaceholder.innerHTML = `
                        <div class="text-center">
                            <i class="fas fa-hand-pointer fa-4x mb-3"></i>
                            <h5>Select a degree course above</h5>
                            <p class="mb-0">The syllabus PDF will appear here</p>
                        </div>
                    `;
                    degreePdfPlaceholder.style.display = 'flex';
                }
                if (degreeCourseMeta) degreeCourseMeta.classList.add('d-none');
                if (openDegreeNewTab) openDegreeNewTab.classList.add('d-none');
            }
        });

        // Auto-load if student's suggested course is pre-selected
        if (degreeCourseSelect.selectedIndex > 0) {
            const opt = degreeCourseSelect.options[degreeCourseSelect.selectedIndex];
            const hasSyllabus = opt.dataset.hasSyllabus === '1';
            loadDegreePdf(
                degreeCourseSelect.value,
                opt.dataset.code,
                opt.dataset.name,
                opt.dataset.credits,
                opt.dataset.similarity,
                hasSyllabus
            );
        }
    }

    // Handle compare buttons from table
    document.querySelectorAll('.compare-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            const code = this.dataset.code;
            const name = this.dataset.name;
            const credits = this.dataset.credits;
            const similarity = this.dataset.similarity;
            const hasSyllabus = this.dataset.hasSyllabus === '1';

            loadDegreePdf(url, code, name, credits, similarity, hasSyllabus);

            // Update dropdown to match
            if (degreeCourseSelect) {
                for (let opt of degreeCourseSelect.options) {
                    if (opt.dataset.code === code) {
                        degreeCourseSelect.value = opt.value;
                        break;
                    }
                }
            }

            // Scroll to top to see the PDFs
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    // Handle select course buttons (for decision form)
    document.querySelectorAll('.select-course-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const courseCode = this.dataset.courseCode;
            const similarity = this.dataset.similarity;

            approvedCourseCode.value = courseCode;
            matchPercentage.value = similarity;

            // Visual feedback
            document.querySelectorAll('.select-course-btn').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-primary');
            });
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');

            // Scroll to decision form
            document.getElementById('comparisonDecisionForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    // Enable submit button when decision is selected
    function enableSubmitButton() {
        if (approveRadio.checked || rejectRadio.checked) {
            submitDecisionBtn.disabled = false;
        }
    }

    approveRadio.addEventListener('change', enableSubmitButton);
    rejectRadio.addEventListener('change', enableSubmitButton);

    if (approveRadio.checked || rejectRadio.checked) {
        enableSubmitButton();
    }

    // Collapse icon toggle (start expanded)
    const recommendationsCollapse = document.getElementById('recommendationsCollapse');
    const collapseIcon = document.getElementById('collapseIcon');
    if (recommendationsCollapse && collapseIcon) {
        // Start with up icon since collapsed is shown
        collapseIcon.classList.remove('fa-chevron-down');
        collapseIcon.classList.add('fa-chevron-up');

        recommendationsCollapse.addEventListener('show.bs.collapse', function() {
            collapseIcon.classList.remove('fa-chevron-down');
            collapseIcon.classList.add('fa-chevron-up');
        });
        recommendationsCollapse.addEventListener('hide.bs.collapse', function() {
            collapseIcon.classList.remove('fa-chevron-up');
            collapseIcon.classList.add('fa-chevron-down');
        });
    }
});
</script>
@endpush
@endsection
