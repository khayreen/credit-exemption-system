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

        $applicationSubject = $request->applicationSubject;
        $exemptionApplication = $applicationSubject->exemptionApplication;

        return view('external_lecturer.submission.form', compact('request', 'applicationSubject', 'exemptionApplication'));
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
            'credit_hours' => 'required|integer|min:1|max:10',
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

            // Update application subject status
            $externalRequest->applicationSubject->update([
                'status' => 'Syllabus Received'
            ]);

            DB::commit();

            return view('external_lecturer.submission.success', compact('externalRequest', 'submission'));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to submit syllabus: ' . $e->getMessage());
            return back()->with('error', 'Failed to submit syllabus. Please try again.');
        }
    }
}
