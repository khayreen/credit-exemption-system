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

// Default Laravel auth routes, with email verification enabled
Auth::routes(['verify' => true]);

// Routes for our custom 2FA flow
Route::get('/2fa', [LoginSecurityController::class, 'show2faForm'])->name('2fa.index');
Route::get('/2fa/verify', function () { return view('google2fa.verify'); })->name('2fa.verify');
Route::post('/2fa/verify', [LoginSecurityController::class, 'verify2fa'])->name('2fa.verify.post');


// Main application routes that require a user to be logged in.
// The 'verified' middleware has been removed and is now handled in the LoginController.
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');

        // Profile Routes
        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

        // Student-only routes
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Student\ApplicationController::class, 'dashboard'])->name('dashboard');
        Route::get('application/create', [App\Http\Controllers\Student\ApplicationController::class, 'create'])->name('application.create');
        Route::post('application/store', [App\Http\Controllers\Student\ApplicationController::class, 'store'])->name('application.store');
        Route::get('application/status', [App\Http\Controllers\Student\ApplicationController::class, 'status'])->name('application.status');
        Route::post('application/{application}/reprocess', [App\Http\Controllers\Student\ApplicationController::class, 'reprocessCombinedEquivalencies'])->name('application.reprocess');

        // Transcript viewing route
        Route::get('application/{application}/transcript', [App\Http\Controllers\Student\ApplicationController::class, 'viewTranscript'])->name('application.transcript');
        
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
        
        // Route for requesting syllabus from external lecturers
        Route::post('subject/{subject}/request-syllabus', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'requestSyllabus'])->name('subject.request_syllabus');
        
        // Course Equivalency Management Routes
        Route::get('course-equivalencies', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'manageCourseEquivalencies'])->name('course_equivalencies.manage');
        Route::post('course-equivalencies/bulk', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'storeBulkEquivalencies'])->name('course_equivalencies.store_bulk');
        Route::get('api/degree-program-courses', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'getDegreeProgramCourses'])->name('api.degree_program_courses');
        Route::get('api/existing-equivalencies', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'getExistingEquivalencies'])->name('api.existing_equivalencies');
        Route::put('course-equivalencies/{equivalencyId}', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'updateEquivalency'])->name('course_equivalencies.update');
        Route::delete('course-equivalencies/{equivalencyId}', [App\Http\Controllers\ResourcePerson\ApplicationController::class, 'deleteEquivalency'])->name('course_equivalencies.delete');
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
    });
});

// External lecturer submission routes (public, token-based authentication)
Route::prefix('external-lecturer')->name('external.lecturer.')->group(function () {
    Route::get('submission/{token}', [App\Http\Controllers\ExternalLecturer\SubmissionController::class, 'showForm'])->name('submission.form');
    Route::post('submission/{token}', [App\Http\Controllers\ExternalLecturer\SubmissionController::class, 'submitSyllabus'])->name('submission.submit');
});
