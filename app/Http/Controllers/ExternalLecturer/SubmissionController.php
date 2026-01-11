<?php

namespace App\Http\Controllers\ExternalLecturer;

use App\Http\Controllers\Controller;
use App\Models\ExternalLecturerRequest;
use App\Models\ExternalLecturerSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    /**
     * Show the syllabus submission form for external lecturers
     */
    public function showForm($token)
    {
        $request = ExternalLecturerRequest::where('access_token', $token)->first();

        if (!$request) {
            return view('external_lecturer.submission.invalid_token');
        }

        if ($request->isExpired()) {
            return view('external_lecturer.submission.expired_token');
        }

        if ($request->status === 'submitted') {
            return view('external_lecturer.submission.already_submitted', compact('request'));
        }

        // Determine the request type and load appropriate relationships
        if ($request->application_subject_id) {
            // This is for an exemption application workflow
            $requestType = 'application_subject';
            $applicationSubject = $request->applicationSubject;
            $exemptionApplication = $applicationSubject->exemptionApplication;
            $equivalencyRequest = null;

            return view('external_lecturer.submission.form', compact('request', 'requestType', 'applicationSubject', 'exemptionApplication', 'equivalencyRequest'));
        } elseif ($request->course_equivalency_request_id) {
            // This is for a course equivalency request workflow
            $requestType = 'course_equivalency_request';
            $equivalencyRequest = $request->courseEquivalencyRequest()->with('student.user')->first();
            $applicationSubject = null;
            $exemptionApplication = null;

            return view('external_lecturer.submission.form', compact('request', 'requestType', 'equivalencyRequest', 'applicationSubject', 'exemptionApplication'));
        } else {
            // Invalid request - neither workflow type is set
            Log::error('External lecturer request has no valid workflow type', [
                'request_id' => $request->id,
                'token' => $token
            ]);
            return view('external_lecturer.submission.invalid_token');
        }
    }

    /**
     * Handle syllabus submission from external lecturer
     */
    public function submitSyllabus(Request $request, $token)
    {
        $externalRequest = ExternalLecturerRequest::where('access_token', $token)->first();

        if (!$externalRequest || $externalRequest->isExpired() || $externalRequest->status === 'submitted') {
            return redirect()->back()->with('error', 'Invalid or expired submission link.');
        }

        $request->validate([
            'external_lecturer_name' => 'required|string|max:255',
            'institution_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:20',
            'course_name' => 'required|string|max:255',
            'credit_hours' => 'required|numeric|min:1|max:10',
            'justification_notes' => 'required|string|max:2000',
            'syllabus_file' => 'required|file|mimes:pdf|max:5120' // 5MB max
        ]);

        try {
            DB::beginTransaction();

            // Store the uploaded file
            $file = $request->file('syllabus_file');
            $fileName = Str::uuid() . '.pdf';
            $filePath = $file->storeAs('syllabi/external_submissions', $fileName);

            // Create or update User and ExternalLecturer records
            $user = \App\Models\User::firstOrCreate(
                ['email' => $externalRequest->external_lecturer_email],
                [
                    'name' => $request->external_lecturer_name,
                    'role' => 'external_lecturer',
                    'password' => bcrypt(Str::random(32)), // Random password (they use token access)
                ]
            );

            // Create or update ExternalLecturer profile
            \App\Models\ExternalLecturer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $request->external_lecturer_name,
                    'email' => $externalRequest->external_lecturer_email,
                    'institution_name' => $request->institution_name,
                ]
            );

            // Create submission record
            $submission = ExternalLecturerSubmission::create([
                'external_lecturer_request_id' => $externalRequest->id,
                'institution_name' => $request->institution_name,
                'course_code' => $request->course_code,
                'course_name' => $request->course_name,
                'credit_hours' => $request->credit_hours,
                'justification_notes' => $request->justification_notes,
                'syllabus_file_path' => $filePath,
                'syllabus_file_original_name' => $file->getClientOriginalName(),
                'digital_signature' => hash_file('sha256', $file->getPathname())
            ]);

            // Update external request
            $externalRequest->update([
                'external_lecturer_name' => $request->external_lecturer_name,
                'status' => 'submitted',
                'submitted_at' => now()
            ]);

            // Update the appropriate parent record based on workflow type
            if ($externalRequest->application_subject_id) {
                // Update application subject status for exemption application workflow
                $externalRequest->applicationSubject->update([
                    'status' => 'Syllabus Received'
                ]);
            } elseif ($externalRequest->course_equivalency_request_id) {
                // Update course equivalency request status for equivalency request workflow
                $externalRequest->courseEquivalencyRequest->update([
                    'syllabus_received_at' => now(),
                    'status' => 'under_review' // Keep it under review for RP to make final decision
                ]);
            }

            DB::commit();

            Log::info('External lecturer syllabus submitted successfully', [
                'external_request_id' => $externalRequest->id,
                'submission_id' => $submission->id,
                'lecturer_email' => $externalRequest->external_lecturer_email,
                'course_code' => $request->course_code
            ]);

            return view('external_lecturer.submission.success', compact('externalRequest', 'submission'));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to submit syllabus', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'token' => $token,
                'external_request_id' => $externalRequest->id ?? null
            ]);
            return back()->with('error', 'Failed to submit syllabus. Please try again.');
        }
    }
}
