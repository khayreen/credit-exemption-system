<?php

namespace App\Http\Controllers\ExternalLecturer;

use App\Http\Controllers\Controller;
use App\Models\ApplicationSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SyllabusController extends Controller
{
    public function __construct()
    {
        // Add the 'signed' middleware to protect the form route from unauthorized access.
        $this->middleware('signed')->only('showSyllabusForm');
    }

    public function dashboard()
    {
        // Get pending syllabus requests for this external lecturer
        $pendingRequests = ApplicationSubject::where('status', 'Syllabus Requested')
            ->with('exemptionApplication.student.user')
            ->latest()
            ->get();
            
        // Get completed submissions
        $completedSubmissions = ApplicationSubject::where('status', 'Syllabus Received')
            ->with('exemptionApplication.student.user')
            ->latest()
            ->limit(10)
            ->get();
            
        $stats = [
            'pending_requests' => $pendingRequests->count(),
            'completed_submissions' => $completedSubmissions->count(),
        ];
        
        return view('external_lecturer.dashboard', compact('pendingRequests', 'completedSubmissions', 'stats'));
    }

    /**
     * Show the syllabus submission form.
     */
    public function showSyllabusForm(Request $request, ApplicationSubject $subject)
    {
        // The 'signed' middleware automatically validates the URL before this method is called.
        return view('external_lecturer.submit_syllabus', compact('subject'));
    }

    /**
     * Store the uploaded syllabus file and the submitter's details.
     */
    public function storeSyllabus(Request $request, ApplicationSubject $subject)
    {
        $request->validate([
            'submitter_name' => 'required|string|max:255',
            'submitter_email' => 'required|email|max:255',
            'submitter_institution' => 'required|string|max:255',
            'syllabus_file' => 'required|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('syllabus_file')) {
            $filePath = $request->file('syllabus_file')->store('syllabi', 'private');

            $subject->status = 'Syllabus Received';
            $subject->notes = "Syllabus submitted by: " . $request->submitter_name . " (" . $request->submitter_email . ")" . " from " . $request->submitter_institution . ".";
            $subject->syllabus_path = $filePath; // Save the file path
            $subject->save();
        }

        return redirect()->back()->with('success', 'Thank you! The syllabus has been submitted successfully.');
    }
    
    /**
     * Show the general syllabus upload form for external lecturers
     */
    public function showUploadForm()
    {
        // Get all courses that might need syllabi
        $availableCourses = ApplicationSubject::whereIn('status', ['Syllabus Requested', 'Pending Resource Person'])
            ->with('exemptionApplication.student.user')
            ->latest()
            ->get();
            
        return view('external_lecturer.upload_form', compact('availableCourses'));
    }
    
    /**
     * Store general syllabus upload
     */
    public function storeGeneralSyllabus(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|max:10',
            'course_name' => 'required|string|max:255', 
            'institution_name' => 'required|string|max:255',
            'credit_hours' => 'required|integer|min:1|max:6',
            'syllabus_file' => 'required|file|mimes:pdf|max:5120',
            'course_description' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('syllabus_file')) {
            // Generate unique filename
            $filename = \Str::uuid() . '.pdf';
            $filePath = $request->file('syllabus_file')->storeAs('syllabi/general', $filename, 'private');

            // Create a new application subject for this general submission
            $applicationSubject = ApplicationSubject::create([
                'id' => \Str::uuid(),
                'exemption_application_id' => null, // General submission not tied to specific application
                'course_code' => $request->course_code,
                'course_name' => $request->course_name,
                'credit_hour' => $request->credit_hours,
                'status' => 'General Syllabus Received',
                'syllabus_path' => $filePath,
                'notes' => json_encode([
                    'submitter' => Auth::user()->name,
                    'submitter_email' => Auth::user()->email,
                    'institution' => $request->institution_name,
                    'description' => $request->course_description,
                    'submission_type' => 'general',
                    'submitted_at' => now()
                ]),
                'extraction_method' => 'manual',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->route('external_lecturer.dashboard')
            ->with('success', 'Syllabus uploaded successfully! It will be reviewed by our academic team.');
    }
}
