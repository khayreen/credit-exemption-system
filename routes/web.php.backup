<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student\ApplicationController as StudentApplicationController;
use App\Http\Controllers\AcademicAdvisor\ApplicationController as AcademicAdvisorApplicationController;
use App\Http\Controllers\LoginSecurityController;

Route::get('/', function () {
    return view('welcome');
});

// Email template preview (only available in local/staging environments)
if (app()->environment('local', 'staging')) {
    Route::get('/preview-email', function () {
        return view('emails.verify-email', [
            'userName' => 'Syameer Anwari',
            'verificationUrl' => url('/email/verify/sample-token'),
        ]);
    });
}

// Default Laravel auth routes, with email verification enabled
Auth::routes(['verify' => true]);

// Custom email verification routes
Route::get('/email/verify', function (Illuminate\Http\Request $request) {
    // If already verified, redirect to home
    if ($request->user() && $request->user()->hasVerifiedEmail()) {
        return redirect()->route('home');
    }
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('resent', true);
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// Registration QR code setup routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/register/qr-setup', [App\Http\Controllers\Auth\RegisterController::class, 'showQrSetup'])->name('register.qr.setup');
    Route::post('/register/qr-setup', [App\Http\Controllers\Auth\RegisterController::class, 'verifyQr'])->name('register.qr.verify');
});

// Routes for our custom 2FA flow
Route::get('/2fa', [LoginSecurityController::class, 'show2faForm'])->name('2fa.index');
Route::get('/2fa/verify', function () { return view('google2fa.verify'); })->name('2fa.verify');
Route::post('/2fa/verify', [LoginSecurityController::class, 'verify2fa'])->name('2fa.verify.post');

// 2FA Setup routes (requires auth only - email verification is checked in LoginController)
Route::middleware(['auth'])->group(function () {
    Route::get('/2fa/setup', [App\Http\Controllers\Auth\TwoFactorController::class, 'showSetup'])->name('2fa.setup');
    Route::post('/2fa/setup/verify', [App\Http\Controllers\Auth\TwoFactorController::class, 'verify'])->name('2fa.verify');
});

// 2FA Login routes (no auth middleware - user is logging in)
Route::get('/2fa/login', [App\Http\Controllers\Auth\TwoFactorController::class, 'showLogin'])->name('2fa.login');
Route::post('/2fa/login', [App\Http\Controllers\Auth\TwoFactorController::class, 'verifyLogin'])->name('2fa.login.verify');

// Main application routes that require a user to be logged in.
// The 'verified' middleware has been removed and is now handled in the LoginController.
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');

        // Profile Routes
        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/request-program-change', [App\Http\Controllers\ProfileController::class, 'requestProgramChange'])->name('profile.requestProgramChange');
        Route::post('/profile/change-password', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.changePassword');

        // Student-only routes
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Student\ApplicationController::class, 'dashboard'])->name('dashboard');
        Route::get('application/create', [App\Http\Controllers\Student\ApplicationController::class, 'create'])->name('application.create');
        Route::post('application/store', [App\Http\Controllers\Student\ApplicationController::class, 'store'])->name('application.store');
        Route::get('application/status', [App\Http\Controllers\Student\ApplicationController::class, 'status'])->name('application.status');
        Route::post('application/{application}/reprocess', [App\Http\Controllers\Student\ApplicationController::class, 'reprocessCombinedEquivalencies'])->name('application.reprocess');

        // AJAX route for manual entry equivalency checking
        Route::post('application/check-equivalency', [App\Http\Controllers\Student\ApplicationController::class, 'checkEquivalency'])->name('application.check-equivalency');

        // Transcript viewing route
        Route::get('application/{application}/transcript', [App\Http\Controllers\Student\ApplicationController::class, 'viewTranscript'])->name('application.transcript');

        // Course Validation PDF routes
        Route::get('application/{application}/validation', [App\Http\Controllers\Student\ApplicationController::class, 'viewCourseValidation'])->name('application.validation');
        Route::get('application/{application}/validation/download', [App\Http\Controllers\Student\ApplicationController::class, 'downloadCourseValidation'])->name('application.validation.download');

        // Course Equivalency Request routes
        Route::get('equivalency-request/create', [App\Http\Controllers\Student\EquivalencyRequestController::class, 'create'])->name('equivalency.request.create');
        Route::post('equivalency-request/store', [App\Http\Controllers\Student\EquivalencyRequestController::class, 'store'])->name('equivalency.request.store');
        Route::get('equivalency-request/list', [App\Http\Controllers\Student\EquivalencyRequestController::class, 'index'])->name('equivalency.request.index');
        Route::get('equivalency-request/{request}', [App\Http\Controllers\Student\EquivalencyRequestController::class, 'show'])->name('equivalency.request.show');

        // Published Course Equivalency Lists (View Only)
        Route::get('course-equivalencies', [App\Http\Controllers\Student\EquivalencyViewController::class, 'index'])->name('course_equivalencies.index');
        Route::get('equivalency-list-pdf/{list}', [App\Http\Controllers\Student\EquivalencyViewController::class, 'downloadPdf'])->name('course_equivalencies.pdf');
        Route::get('course-equivalencies/{category}/{source?}', [App\Http\Controllers\Student\EquivalencyViewController::class, 'show'])->name('course_equivalencies.show');

        // Additional student resource routes
        Route::get('terms', function () { return view('student.terms'); })->name('terms');
        Route::get('faq', function () { return view('student.faq'); })->name('faq');
        Route::get('help', function () { return view('student.help'); })->name('help');
        Route::get('forms', function () { return view('student.forms'); })->name('forms');
    });

    // Academic Advisor-only routes
    Route::middleware(['role:academic_advisor'])->prefix('academic-advisor')->name('academic_advisor.')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'index'])->name('dashboard');
        Route::get('application/{application}', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'show'])->name('application.show');
        // This route now points to the new 'processDecisions' method
        Route::post('application/{application}/process', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'processDecisions'])->name('application.process');
        // New route for individual subject decisions
        Route::post('application/{application}/subject/{subject}/decision', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'processSubjectDecision'])->name('application.subject.decision');
        // Route for bulk approval of pre-qualified courses
        Route::post('application/{application}/bulk-approve-prequalified', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'bulkApprovePreQualified'])->name('application.bulk_approve_prequalified');
        // Route for bulk rejection of manual review courses
        Route::post('application/{application}/bulk-reject-manual-review', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'bulkRejectManualReview'])->name('application.bulk_reject_manual_review');
        Route::get('application/{application}/transcript', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'viewTranscript'])->name('application.transcript');

        // Equivalency Lists and Course Mappings (Read-Only)
        Route::get('equivalency-lists', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'viewEquivalencyLists'])->name('equivalency_lists.index');
        Route::get('equivalency-lists/{list}', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'showEquivalencyList'])->name('equivalency_lists.show');
        Route::get('equivalency-lists/{list}/pdf', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'downloadListPdf'])->name('equivalency_lists.pdf');
        Route::get('course-equivalencies', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'viewAllCourseEquivalencies'])->name('course_equivalencies.view');
        Route::get('api/existing-equivalencies', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'getExistingEquivalencies'])->name('api.existing_equivalencies');

        // My Students
        Route::get('my-students', [App\Http\Controllers\AcademicAdvisor\ApplicationController::class, 'myStudents'])->name('my_students');
    });

    // Coordinator-only routes
    Route::middleware(['role:coordinator'])->prefix('coordinator')->name('coordinator.')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Coordinator\ApplicationController::class, 'index'])->name('dashboard');
        Route::get('application/{application}', [App\Http\Controllers\Coordinator\ApplicationController::class, 'show'])->name('application.show');
        
        // New routes for the dedicated equivalency page
        Route::get('application/{application}/subject/{subject}/equivalency', [App\Http\Controllers\Coordinator\ApplicationController::class, 'createEquivalency'])->name('equivalency.create');
        Route::post('application/{application}/subject/{subject}/equivalency', [App\Http\Controllers\Coordinator\ApplicationController::class, 'storeEquivalency'])->name('equivalency.store');
    
        // Routes to handle subject actions
        Route::post('subject/{subject}/forward', [App\Http\Controllers\Coordinator\ApplicationController::class, 'forwardSubject'])->name('subject.forward');
        Route::post('subject/{subject}/reject', [App\Http\Controllers\Coordinator\ApplicationController::class, 'rejectSubject'])->name('subject.reject');
        
        // Course Equivalencies View (Read-only)
        Route::get('course-equivalencies', [App\Http\Controllers\Coordinator\ApplicationController::class, 'viewCourseEquivalencies'])->name('course_equivalencies.view');
        Route::get('api/existing-equivalencies', [App\Http\Controllers\Coordinator\ApplicationController::class, 'getExistingEquivalencies'])->name('api.existing_equivalencies');
    });

    // Program Coordinator-only routes (NEW ARCHITECTURE)
    Route::middleware(['role:program_coordinator'])->prefix('program-coordinator')->name('program_coordinator.')->group(function () {
        // Equivalency List Management (Full CRUD - Both PCs have EQUAL access to ALL lists)
        Route::get('equivalency-lists', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'index'])->name('equivalency_lists.index');
        Route::get('equivalency-lists/drafts', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'drafts'])->name('equivalency_lists.drafts');
        Route::get('equivalency-lists/create', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'create'])->name('equivalency_lists.create');
        Route::post('equivalency-lists', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'store'])->name('equivalency_lists.store');
        Route::get('equivalency-lists/{list}', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'show'])->name('equivalency_lists.show');
        Route::get('equivalency-lists/{list}/edit', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'edit'])->name('equivalency_lists.edit');
        Route::post('equivalency-lists/{list}/publish', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'publish'])->name('equivalency_lists.publish');
        Route::delete('equivalency-lists/{list}', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'destroy'])->name('equivalency_lists.destroy');

        // Course Mapping Management
        Route::post('equivalency-lists/{list}/mappings', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'addMapping'])->name('equivalency_lists.add_mapping');
        Route::put('equivalency-lists/{list}/mappings/{mapping}', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'updateMapping'])->name('equivalency_lists.update_mapping');
        Route::delete('equivalency-lists/{list}/mappings/{mapping}', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'deleteMapping'])->name('equivalency_lists.delete_mapping');

        // Pending Mappings from Resource Persons
        Route::get('pending-mappings', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'pendingMappings'])->name('pending_mappings.index');
        Route::post('pending-mappings/{mapping}/add', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'addPendingMapping'])->name('pending_mappings.add');
        Route::post('pending-mappings/{mapping}/reject', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'rejectPendingMapping'])->name('pending_mappings.reject');

        // View All Course Equivalencies
        Route::get('course-equivalencies', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'viewAllCourseEquivalencies'])->name('course_equivalencies.view');
        Route::get('equivalency-lists/{list}/pdf', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'downloadListPdf'])->name('equivalency_lists.pdf');
        Route::get('api/existing-equivalencies', [App\Http\Controllers\ProgramCoordinator\EquivalencyListController::class, 'getExistingEquivalencies'])->name('api.existing_equivalencies');

        // Old equivalency request routes (for backward compatibility if needed)
        Route::get('dashboard', [App\Http\Controllers\ProgramCoordinator\EquivalencyRequestController::class, 'dashboard'])->name('dashboard');
        Route::get('course/{diplomaCourseCode}', [App\Http\Controllers\ProgramCoordinator\EquivalencyRequestController::class, 'showCourseRequests'])->name('course_requests');
        Route::post('make-decision', [App\Http\Controllers\ProgramCoordinator\EquivalencyRequestController::class, 'makeDecision'])->name('make_decision');
        Route::post('forward-to-rp', [App\Http\Controllers\ProgramCoordinator\EquivalencyRequestController::class, 'forwardToRP'])->name('forward_to_rp');
    });

        // Resource Person-only routes
    Route::middleware(['role:resource_person'])->prefix('resource-person')->name('resource_person.')->group(function () {
        // The main dashboard for the Resource Person
        Route::get('dashboard', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'index'])->name('dashboard');

        // The new, dedicated page for reviewing a single subject
        Route::get('subject/{subject}/review', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'review'])->name('subject.review');

        // This route handles the form submission from the review page
        Route::post('subject/{subject}/process', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'processFinding'])->name('subject.process');

        // This is the new route for securely viewing a submitted syllabus
        Route::get('subject/{subject}/syllabus', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'viewSyllabus'])->name('subject.view_syllabus');

        // Route for viewing external lecturer submission syllabus
        Route::get('external-submission/{submission}/syllabus', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'viewExternalSyllabus'])->name('external_submission.view_syllabus');

        // Route for requesting syllabus from external lecturers
        Route::post('subject/{subject}/request-syllabus', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'requestSyllabus'])->name('subject.request_syllabus');

        // Course Equivalency Management Routes
        Route::get('course-equivalencies', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'manageCourseEquivalencies'])->name('course_equivalencies.manage');
        Route::post('course-equivalencies/bulk', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'storeBulkEquivalencies'])->name('course_equivalencies.store_bulk');
        Route::get('api/degree-program-courses', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'getDegreeProgramCourses'])->name('api.degree_program_courses');
        Route::get('api/existing-equivalencies', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'getExistingEquivalencies'])->name('api.existing_equivalencies');
        Route::put('course-equivalencies/{equivalencyId}', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'updateEquivalency'])->name('course_equivalencies.update');
        Route::delete('course-equivalencies/{equivalencyId}', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'deleteEquivalency'])->name('course_equivalencies.delete');

        // Course Equivalency Request Routes
        Route::get('equivalency-requests', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'viewEquivalencyRequests'])->name('equivalency_requests.index');
        Route::get('equivalency-requests/{request}', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'reviewEquivalencyRequest'])->name('equivalency_requests.review');
        Route::post('equivalency-requests/{request}/process', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'processEquivalencyRequest'])->name('equivalency_requests.process');
        Route::get('equivalency-requests/{request}/preview-email', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'previewSyllabusEmail'])->name('equivalency_requests.preview_email');
        Route::post('equivalency-requests/{request}/send-syllabus-email', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'sendSyllabusEmail'])->name('equivalency_requests.send_email');
        Route::post('equivalency-requests/{request}/request-syllabus', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'requestSyllabusForEquivalency'])->name('equivalency_requests.request_syllabus');

        // Equivalency Lists - View All (READ-ONLY access to all lists)
        Route::get('equivalency-lists/view-all', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'viewAllLists'])->name('equivalency_lists.view_all');

        // Published Equivalency Lists - Program-based view (same UI as Academic Advisor)
        Route::get('equivalency-lists/published', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'viewPublishedEquivalencyLists'])->name('equivalency_lists.published');

        // CS110 Internal Equivalency List Management (ONE continuous list per program)
        Route::get('cs110-lists', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'index'])->name('equivalency_lists.index');
        Route::get('cs110-lists/{programCode}/edit', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'edit'])->name('equivalency_lists.edit');
        Route::post('cs110-lists/{programCode}/submit', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'submit'])->name('equivalency_lists.submit');
        Route::post('cs110-lists/{programCode}/mappings', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'addMapping'])->name('equivalency_lists.add_mapping');
        Route::put('cs110-lists/{programCode}/mappings/{mapping}', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'updateCourseMapping'])->name('equivalency_lists.update_mapping');
        Route::delete('cs110-lists/{programCode}/mappings/{mapping}', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'deleteMapping'])->name('equivalency_lists.delete_mapping');

        // Course Mapping Forwarding (RP evaluates and forwards to PC for external workflow)
        Route::get('mappings/create', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'createMapping'])->name('equivalency_mappings.create');
        Route::post('mappings', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'storeMapping'])->name('equivalency_mappings.store');
        Route::get('mappings/{mapping}', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'showMapping'])->name('equivalency_mappings.show');
        Route::put('mappings/{mapping}', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'updateMapping'])->name('equivalency_mappings.update');
        Route::delete('mappings/{mapping}', [App\Http\Controllers\ResourcePerson\EquivalencyListController::class, 'deletePendingMapping'])->name('equivalency_mappings.delete');
    });

        // External Lecturer-only routes
    Route::middleware(['role:external_lecturer'])->prefix('external-lecturer')->name('external_lecturer.')->group(function () {
        // A simple dashboard after they log in
        Route::get('dashboard', [App\Http\Controllers\ExternalLecturer\SyllabusController::class, 'dashboard'])->name('dashboard');
        // Syllabus upload form routes
        Route::get('upload-syllabus', [App\Http\Controllers\ExternalLecturer\SyllabusController::class, 'showUploadForm'])->name('upload_form');
        Route::post('upload-syllabus', [App\Http\Controllers\ExternalLecturer\SyllabusController::class, 'storeGeneralSyllabus'])->name('store_general');
    });

    // This route is for the public-facing syllabus submission form.
    // It will be protected by a signed URL, not a login.
    Route::get('syllabus/submit/{subject}', [App\Http\Controllers\ExternalLecturer\SyllabusController::class, 'showSyllabusForm'])->name('syllabus.submit_form');
    Route::post('syllabus/submit/{subject}', [App\Http\Controllers\ExternalLecturer\SyllabusController::class, 'storeSyllabus'])->name('syllabus.store');

    
        // HEA Personnel-only routes
    Route::middleware(['role:hea_personnel'])->prefix('hea')->name('hea.')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Hea\DashboardController::class, 'index'])->name('dashboard');
        Route::get('users', [App\Http\Controllers\Hea\DashboardController::class, 'users'])->name('users.index');
        Route::get('applications', [App\Http\Controllers\Hea\DashboardController::class, 'applications'])->name('applications.index');
        Route::get('logs', [App\Http\Controllers\Hea\DashboardController::class, 'logs'])->name('logs.index');
        Route::get('settings', [App\Http\Controllers\Hea\DashboardController::class, 'settings'])->name('settings');
        Route::patch('settings', [App\Http\Controllers\Hea\DashboardController::class, 'updateSettings'])->name('settings.update');

        // User Management Routes (NEW - for AA/PC/RP approval)
        Route::get('users/management', [App\Http\Controllers\Hea\UserManagementController::class, 'index'])->name('users.management');
        Route::get('users/pending', [App\Http\Controllers\Hea\UserManagementController::class, 'pending'])->name('users.pending');
        Route::get('users/active', [App\Http\Controllers\Hea\UserManagementController::class, 'active'])->name('users.active');
        Route::post('users/{user}/approve', [App\Http\Controllers\Hea\UserManagementController::class, 'approve'])->name('users.approve');
        Route::get('users/{user}/programs', [App\Http\Controllers\Hea\UserManagementController::class, 'managePrograms'])->name('users.programs.manage');
        Route::post('users/{user}/programs', [App\Http\Controllers\Hea\UserManagementController::class, 'updatePrograms'])->name('users.programs.update');
        Route::post('users/{user}/deactivate', [App\Http\Controllers\Hea\UserManagementController::class, 'deactivate'])->name('users.deactivate');

        // Equivalency List Endorsement & Publication (HEA Workflow)
        Route::get('equivalency-lists/pending', [App\Http\Controllers\Hea\EquivalencyListController::class, 'pending'])->name('equivalency_lists.pending');
        Route::get('equivalency-lists/{list}/review', [App\Http\Controllers\Hea\EquivalencyListController::class, 'review'])->name('equivalency_lists.review');
        Route::post('equivalency-lists/{list}/endorse', [App\Http\Controllers\Hea\EquivalencyListController::class, 'endorse'])->name('equivalency_lists.endorse');
        Route::post('equivalency-lists/{list}/reject', [App\Http\Controllers\Hea\EquivalencyListController::class, 'reject'])->name('equivalency_lists.reject');

        // Equivalency List Monitoring (READ-ONLY)
        Route::get('equivalency-lists/grouped', [App\Http\Controllers\Hea\EquivalencyListController::class, 'viewGrouped'])->name('equivalency_lists.grouped');
        Route::get('equivalency-lists', [App\Http\Controllers\Hea\EquivalencyListController::class, 'index'])->name('equivalency_lists.index');
        Route::get('equivalency-lists/drafts', [App\Http\Controllers\Hea\EquivalencyListController::class, 'drafts'])->name('equivalency_lists.drafts');
        Route::get('equivalency-lists/published', [App\Http\Controllers\Hea\EquivalencyListController::class, 'published'])->name('equivalency_lists.published');

        // Published Equivalency Lists - Program-based view (same UI as Academic Advisor)
        Route::get('equivalency-lists/published-view', [App\Http\Controllers\Hea\EquivalencyListController::class, 'viewPublishedEquivalencyLists'])->name('equivalency_lists.published_view');
        Route::get('equivalency-lists/statistics', [App\Http\Controllers\Hea\EquivalencyListController::class, 'statistics'])->name('equivalency_lists.statistics');
        Route::delete('equivalency-lists/{list}', [App\Http\Controllers\Hea\EquivalencyListController::class, 'destroy'])->name('equivalency_lists.destroy');
        Route::get('equivalency-lists/{list}', [App\Http\Controllers\Hea\EquivalencyListController::class, 'show'])->name('equivalency_lists.show');
        Route::get('equivalency-lists/{list}/pdf', [App\Http\Controllers\Hea\EquivalencyListController::class, 'downloadPdf'])->name('equivalency_lists.pdf');
        Route::get('pending-mappings', [App\Http\Controllers\Hea\EquivalencyListController::class, 'pendingMappings'])->name('pending_mappings.index');

        // All Course Mappings View
        Route::get('course-equivalencies', [App\Http\Controllers\Hea\EquivalencyListController::class, 'viewAllCourseEquivalencies'])->name('course_equivalencies.view');
        Route::get('api/existing-equivalencies', [App\Http\Controllers\Hea\EquivalencyListController::class, 'getExistingEquivalencies'])->name('api.existing_equivalencies');
    });

    // System Administrator-only routes (for HEA approval)
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('hea-approvals', [App\Http\Controllers\Admin\HeaApprovalController::class, 'index'])->name('hea.approvals');
        Route::post('hea/{user}/approve', [App\Http\Controllers\Admin\HeaApprovalController::class, 'approve'])->name('hea.approve');
        Route::post('hea/{user}/reject', [App\Http\Controllers\Admin\HeaApprovalController::class, 'reject'])->name('hea.reject');
    });
});

// Admin quick approve route (no auth needed - uses token)
Route::get('admin/hea/{user}/quick-approve/{token}', [App\Http\Controllers\Admin\HeaApprovalController::class, 'quickApprove'])
    ->name('admin.hea.quick-approve');

// External lecturer submission routes (public, token-based authentication)
Route::prefix('external-lecturer')->name('external.lecturer.')->group(function () {
    Route::get('submission/{token}', [App\Http\Controllers\ExternalLecturer\SubmissionController::class, 'showForm'])->name('submission.form');
    Route::post('submission/{token}', [App\Http\Controllers\ExternalLecturer\SubmissionController::class, 'submitSyllabus'])->name('submission.submit');
});
