@extends('layouts.app')

@push('styles')
<!-- IBM Plex Sans Typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ========================================
   INDUSTRIAL INSTITUTIONAL DESIGN SYSTEM
   UiTM Credit Exemption - Course Equivalencies
   ======================================== */

:root {
    --uitm-primary: #1e3a8a;
    --uitm-primary-dark: #1e293b;
    --uitm-primary-light: #3b82f6;
    --uitm-red: #dc2626;
    --uitm-amber: #f59e0b;
    --uitm-amber-light: #fbbf24;
    --uitm-green: #10b981;
    --uitm-green-light: #34d399;
    --neutral-900: #171717;
    --neutral-800: #262626;
    --neutral-700: #404040;
    --neutral-600: #525252;
    --neutral-500: #737373;
    --neutral-400: #a3a3a3;
    --neutral-300: #d4d4d4;
    --neutral-200: #e5e5e5;
    --neutral-100: #f5f5f5;
    --neutral-50: #fafafa;
}

.equivalency-container,
.equivalency-container * {
    font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
}

.mono-text, code {
    font-family: 'IBM Plex Mono', monospace !important;
}

/* Page Header */
.page-header {
    position: relative;
    background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.page-header-pattern {
    position: absolute;
    inset: 0;
    opacity: 0.07;
    background-image:
        linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
}

.page-header-glow {
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
    pointer-events: none;
}

.page-header-content {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-header-eyebrow {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--uitm-amber);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.page-header-eyebrow::before {
    content: '';
    width: 24px;
    height: 2px;
    background: var(--uitm-amber);
}

.page-header-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0;
    line-height: 1.2;
}

/* Buttons */
.btn-industrial {
    padding: 0.6rem 1.25rem;
    font-weight: 600;
    font-size: 0.85rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.btn-industrial-secondary {
    background: rgba(255,255,255,0.15);
    color: white;
    border: 2px solid rgba(255,255,255,0.3);
}

.btn-industrial-secondary:hover {
    background: rgba(255,255,255,0.25);
    color: white;
    transform: translateY(-2px);
}

.btn-industrial-primary {
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    color: white;
    border: none;
}

.btn-industrial-primary:hover {
    background: linear-gradient(135deg, var(--uitm-primary-dark), var(--uitm-primary));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
}

.btn-industrial-light {
    background: white;
    color: var(--neutral-700);
    border: 2px solid var(--neutral-200);
}

.btn-industrial-light:hover {
    background: var(--neutral-50);
    transform: translateY(-2px);
}

/* Alerts */
.alert-industrial {
    border-radius: 10px;
    border: none;
    padding: 1rem 1.25rem;
    margin-bottom: 1rem;
}

.alert-industrial-info {
    background: linear-gradient(135deg, rgba(30, 58, 138, 0.08), rgba(59, 130, 246, 0.05));
    border-left: 4px solid var(--uitm-primary);
    color: var(--neutral-800);
}

.alert-industrial-warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(251, 191, 36, 0.05));
    border-left: 4px solid var(--uitm-amber);
    color: var(--neutral-800);
}

/* Industrial Cards */
.industrial-card {
    background: white;
    border-radius: 12px;
    border: 2px solid var(--neutral-200);
    overflow: hidden;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.industrial-card:hover {
    border-color: var(--neutral-300);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.card-header-industrial {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.25rem 1.5rem;
    background: var(--neutral-50);
    border-bottom: 2px solid var(--neutral-200);
}

.card-header-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    color: white;
    font-size: 1rem;
}

.card-header-success .card-header-icon {
    background: linear-gradient(135deg, var(--uitm-green), var(--uitm-green-light));
}

.card-header-text h5 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin: 0;
}

.card-header-text p {
    font-size: 0.75rem;
    color: var(--neutral-500);
    margin: 0;
}

.card-body-industrial {
    padding: 1.5rem;
}

/* Stat Cards */
.stat-card {
    background: white;
    border-radius: 12px;
    border: 2px solid var(--neutral-200);
    padding: 1.25rem;
    text-align: center;
    height: 100%;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
}

.stat-card-primary {
    border-color: var(--uitm-primary);
    border-left: 4px solid var(--uitm-primary);
}

.stat-card-success {
    border-color: var(--uitm-green);
    border-left: 4px solid var(--uitm-green);
}

.stat-card-info {
    border-color: var(--uitm-primary-light);
    border-left: 4px solid var(--uitm-primary-light);
}

.stat-number {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-number-primary { color: var(--uitm-primary); }
.stat-number-success { color: var(--uitm-green); }
.stat-number-info { color: var(--uitm-primary-light); }

.stat-label {
    font-size: 0.8rem;
    color: var(--neutral-500);
}

/* Industrial Table */
.industrial-table-wrapper {
    border-radius: 12px;
    border: 2px solid var(--neutral-200);
    overflow: hidden;
}

.industrial-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 0;
}

.industrial-table th {
    background: var(--neutral-50);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    color: var(--neutral-600);
    padding: 1rem;
    border-bottom: 2px solid var(--neutral-200);
    text-align: left;
}

.industrial-table td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--neutral-100);
    color: var(--neutral-700);
    font-size: 0.9rem;
}

.industrial-table tbody tr:last-child td {
    border-bottom: none;
}

.industrial-table tbody tr:hover {
    background: var(--neutral-50);
}

.industrial-table code {
    font-family: 'IBM Plex Mono', monospace !important;
    font-size: 0.85rem;
    color: var(--uitm-primary);
    background: rgba(30, 58, 138, 0.08);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-weight: 600;
}

/* Status Badges */
.status-badge {
    font-family: 'IBM Plex Sans', sans-serif;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.35rem 0.75rem;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}

.status-badge-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}

.status-badge-warning {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

.status-badge-info {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
}

.status-badge-secondary {
    background: linear-gradient(135deg, var(--neutral-200), var(--neutral-100));
    color: var(--neutral-600);
}

/* List Header Card */
.list-header-card {
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-dark));
    color: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.list-header-card.external {
    background: linear-gradient(135deg, var(--uitm-green), var(--uitm-green-light));
}

.list-header-info {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
}

.list-header-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.list-header-subtitle {
    font-size: 0.85rem;
    opacity: 0.9;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    background: var(--neutral-50);
    border-radius: 10px;
    padding: 1.25rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--neutral-500);
}

.info-item-value {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--neutral-800);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--neutral-100);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--uitm-amber);
    font-size: 2rem;
}

.empty-state h5 {
    font-weight: 700;
    color: var(--neutral-800);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--neutral-500);
}

/* Responsive */
@media (max-width: 767px) {
    .page-header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .stat-card {
        margin-bottom: 1rem;
    }
}
</style>
@endpush

@section('content')
<div class="equivalency-container">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-pattern"></div>
        <div class="page-header-glow"></div>
        <div class="page-header-content">
            <div>
                <div class="page-header-eyebrow">Credit Exemption System</div>
                <h1 class="page-header-title"><i class="fas fa-clipboard-list me-2"></i>Course Equivalency Lists</h1>
            </div>
            <a href="{{ route('student.dashboard') }}" class="btn-industrial btn-industrial-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert-industrial alert-industrial-warning">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
        </div>
    @endif

    @if($selectedProgram)
        {{-- Program Info Banner --}}
        <div class="alert-industrial alert-industrial-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Your Enrolled Program:</strong> <code class="mono-text">{{ $selectedProgram }}</code> - {{ $programName }}
        </div>

        {{-- Important Notice --}}
        <div class="alert-industrial alert-industrial-warning">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-triangle me-3 mt-1" style="font-size: 1.5rem; color: var(--uitm-amber);"></i>
                <div>
                    <h6 class="mb-2 fw-bold">Important Notice</h6>
                    <p class="mb-0" style="font-size: 0.9rem; line-height: 1.6;">
                        <strong>For students from other institutions or those who did not complete CS110 (UiTM Diploma in Computer Science):</strong>
                        The equivalency lists shown here may not apply to your diploma courses. Please contact your <strong>Academic Advisor</strong> to discuss course equivalency options for your specific institution and diploma program.
                    </p>
                </div>
            </div>
        </div>

        {{-- Published Equivalency Lists --}}
        <div class="industrial-card">
            <div class="card-header-industrial card-header-success">
                <div class="card-header-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="card-header-text">
                    <h5>Published Course Equivalency Lists (HEA Endorsed)</h5>
                    <p>Endorsed by the Higher Education Authority for {{ $selectedProgram }}</p>
                </div>
            </div>
            <div class="card-body-industrial">
                @php
                    $publishedLists = collect([$internalList])->merge($externalLists->flatten())->filter();
                @endphp

                @if($publishedLists->count() > 0)
                    @foreach($publishedLists as $list)
                        {{-- List Header Card --}}
                        <div class="list-header-card {{ $list->isInternal() ? '' : 'external' }}">
                            <div class="list-header-info">
                                <div>
                                    <div class="list-header-title">
                                        @if($list->isInternal())
                                            <i class="fas fa-home me-2"></i>CS110 (UiTM Diploma in Computer Science)
                                        @else
                                            <i class="fas fa-globe me-2"></i>{{ $list->source_institution }}
                                        @endif
                                    </div>
                                    <div class="list-header-subtitle">
                                        <i class="fas fa-arrow-right me-1"></i>
                                        {{ $list->program_code }} - {{ $list->program_name }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <a href="{{ route('student.course_equivalencies.pdf', $list) }}" class="btn-industrial btn-industrial-light mb-2" target="_blank">
                                        <i class="fas fa-file-pdf"></i> View as PDF
                                    </a>
                                    <div class="mt-2">
                                        <span class="status-badge status-badge-secondary">{{ $list->semester }}</span>
                                        @if($list->is_active)
                                            <span class="status-badge status-badge-warning ms-1">CURRENT</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info Grid --}}
                        <div class="info-grid mb-4">
                            <div class="info-item">
                                <span class="info-item-label">Source</span>
                                <span class="info-item-value">{{ $list->source_display }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-item-label">Target Program</span>
                                <span class="info-item-value mono-text">{{ $list->program_code }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-item-label">Semester</span>
                                <span class="info-item-value">{{ $list->semester }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-item-label">Published</span>
                                <span class="info-item-value">{{ $list->published_at?->format('d M Y') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-item-label">Endorsed By</span>
                                <span class="info-item-value">{{ $list->endorser->name ?? 'HEA Unit' }}</span>
                            </div>
                        </div>

                        {{-- Statistics --}}
                        @php
                            $eligibleCourses = $list->courseEquivalencies->where('is_eligible', true)->sortBy('diploma_course_code');
                            $totalDegreeCredits = $eligibleCourses->sum('degree_credit_hour');
                            $totalDiplomaCredits = $eligibleCourses->sum('diploma_credit_hour');
                            // Maximum credits: CDCS266 = 39, others = 36
                            $maxCredits = ($list->program_code === 'CDCS266') ? 39 : 36;
                        @endphp
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3 mb-md-0">
                                <div class="stat-card stat-card-primary">
                                    <div class="stat-number stat-number-primary">{{ $eligibleCourses->count() }}</div>
                                    <div class="stat-label">Eligible Course Mappings</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <div class="stat-card stat-card-success">
                                    <div class="stat-number stat-number-success">{{ $totalDegreeCredits }}</div>
                                    <div class="stat-label">Total Degree Credit Hours</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <div class="stat-card stat-card-info">
                                    <div class="stat-number stat-number-info">{{ $totalDiplomaCredits }}</div>
                                    <div class="stat-label">Total Diploma Credit Hours</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card" style="border-color: var(--uitm-red); border-left: 4px solid var(--uitm-red);">
                                    <div class="stat-number" style="color: var(--uitm-red);">{{ $maxCredits }}</div>
                                    <div class="stat-label">Maximum Exemptible Credits</div>
                                </div>
                            </div>
                        </div>

                        {{-- Equivalency Table --}}
                        <div class="industrial-card mb-4">
                            <div class="card-header-industrial">
                                <div class="card-header-icon">
                                    <i class="fas fa-list"></i>
                                </div>
                                <div class="card-header-text">
                                    <h5>Eligible Course Equivalencies</h5>
                                    <p>Courses with ≥80% match percentage</p>
                                </div>
                                <span class="status-badge status-badge-success ms-auto">{{ $eligibleCourses->count() }} courses</span>
                            </div>
                            <div class="industrial-table-wrapper" style="border: none; border-radius: 0;">
                                <div class="table-responsive">
                                    <table class="industrial-table">
                                        <thead>
                                            <tr>
                                                <th width="50">#</th>
                                                <th width="35%">Your Diploma Course<br><small style="font-weight: 400; text-transform: none;">({{ $list->isInternal() ? 'CS110' : $list->source_institution }})</small></th>
                                                <th width="10%" class="text-center">Diploma<br>Credits</th>
                                                <th width="35%">Equivalent Degree Course<br><small style="font-weight: 400; text-transform: none;">({{ $list->program_code }})</small></th>
                                                <th width="10%" class="text-center">Degree<br>Credits</th>
                                                <th width="10%" class="text-center">Match</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($eligibleCourses as $eq)
                                            <tr>
                                                <td class="text-muted">{{ $loop->iteration }}</td>
                                                <td>
                                                    <code>{{ $eq->diploma_course_code }}</code>
                                                    <br><small class="text-muted">{{ $eq->diploma_course_name }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="status-badge status-badge-info">{{ $eq->diploma_credit_hour }}</span>
                                                </td>
                                                <td>
                                                    <code>{{ $eq->degree_course_code }}</code>
                                                    <br><small class="text-muted">{{ $eq->degree_course_name }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="status-badge status-badge-info">{{ $eq->degree_credit_hour }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="status-badge status-badge-success">{{ $eq->match_percentage }}%</span>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">
                                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                                    <p class="mb-0">No eligible course mappings available.</p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert-industrial alert-industrial-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No published equivalency lists available for this program yet.
                    </div>
                @endif
            </div>
        </div>

        {{-- Information Section --}}
        <div class="industrial-card">
            <div class="card-header-industrial">
                <div class="card-header-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="card-header-text">
                    <h5>Important Information</h5>
                </div>
            </div>
            <div class="card-body-industrial">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3"><i class="fas fa-check-circle text-success me-2"></i>Eligibility Criteria</h6>
                        <ul class="text-muted" style="font-size: 0.9rem;">
                            <li><strong>Match Percentage:</strong> Course equivalency must be >= 80%</li>
                            <li><strong>Grade Requirement:</strong> You must have achieved grade C or above in the diploma course</li>
                            <li>Both criteria must be met for credit exemption eligibility</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3"><i class="fas fa-lightbulb text-warning me-2"></i>Tips</h6>
                        <ul class="text-muted" style="font-size: 0.9rem;">
                            <li>Check if your diploma courses are in the equivalency list before applying</li>
                            <li>Prepare your official transcript showing grades</li>
                            <li>Contact your academic advisor if you have questions about specific courses</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- No Program Registered --}}
        <div class="industrial-card">
            <div class="card-body-industrial">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5>No Program Registered</h5>
                    <p>You need to have a registered degree program to view course equivalency lists.</p>
                    <p class="text-muted">Please contact your academic advisor or the HEA office to register your program.</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
