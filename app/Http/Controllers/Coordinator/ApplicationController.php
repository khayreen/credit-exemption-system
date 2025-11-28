<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\ExemptionApplication;
use App\Models\ApplicationSubject;
use App\Models\Course;
use App\Models\CourseEquivalency;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications for the coordinator.
     */
    public function index()
    {
        $applications = \App\Models\ExemptionApplication::where('status', 'Reviewed by Lecturer')->with('student.user')->latest()->get();
        
        $stats = [
            'pending_equivalency' => $applications->count(),
            'pending_resource_person' => \App\Models\ExemptionApplication::where('status', 'Pending Resource Person Review')->count(),
        ];

        return view('coordinator.dashboard', compact('applications', 'stats'));
    }

    /**
     * Show the list of subjects for a specific application that need review.
     */
    public function show(ExemptionApplication $application)
    {
        $forwardedSubjects = $application->applicationSubjects()
            ->where('status', 'Forward to Coordinator')
            ->get();

        return view('coordinator.show', compact('application', 'forwardedSubjects'));
    }

    /**
     * Show the dedicated form for creating a new equivalency for a single subject.
     */
    public function createEquivalency(ExemptionApplication $application, ApplicationSubject $subject)
    {
        $degreeCourses = Course::orderBy('code')->get();
        return view('coordinator.create_equivalency', compact('application', 'subject', 'degreeCourses'));
    }

    /**
     * Store the new course equivalency record from the dedicated form.
     */
    public function storeEquivalency(Request $request, ExemptionApplication $application, ApplicationSubject $subject)
    {
        $request->validate([
            'degree_course_code' => 'required|string|exists:courses,code',
            'match_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Create the official course equivalency record in the database with program context
        CourseEquivalency::create([
            'diploma_course_code' => $subject->course_code,
            'diploma_course_name' => $subject->course_name,
            'diploma_credit_hour' => $subject->credit_hour,
            'diploma_institution' => $application->previous_institution,
            'degree_course_code' => $request->degree_course_code,
            'match_percentage' => $request->match_percentage,
            'program_code' => $application->current_program_code ?? 'GENERAL',
            'approved_by_user_id' => Auth::id(),
            'notes' => 'Created during application review for student: ' . $application->student->matric_no . 
                      ' (OCR extracted: ' . ($subject->extraction_method == 'ocr' ? 'Yes' : 'No') . ')',
        ]);

        // Update the subject's status to show it has been approved
        $subject->status = 'Approved (Equivalent to ' . $request->degree_course_code . ')';
        $subject->save();

        // Check if the overall application status needs to be updated
        $this->updateApplicationStatus($application);

        return redirect()->route('coordinator.application.show', $application)->with('success', 'Equivalency saved successfully!');
    }

    /**
     * Handle the "Forward to Resource Person" button click for a single subject.
     */
    public function forwardSubject(ApplicationSubject $subject)
    {
        $subject->status = 'Pending Resource Person';
        $subject->save();
        
        // Check if the overall application status needs to be updated
        $this->updateApplicationStatus($subject->exemptionApplication);

        return redirect()->route('coordinator.application.show', $subject->exemptionApplication)->with('success', 'Subject has been forwarded to Resource Person.');
    }

    /**
     * A helper function to check if an application is fully processed by the coordinator
     * and update its status accordingly.
     */
    private function updateApplicationStatus(ExemptionApplication $application)
    {
        // Check if there are any subjects still waiting for the coordinator's action
        $pendingSubjects = $application->applicationSubjects()
            ->where('status', 'Forward to Coordinator')
            ->count();

        // If no subjects are left for the coordinator...
        if ($pendingSubjects === 0) {
            // ...check if any were sent to the resource person.
            $resourcePersonSubjects = $application->applicationSubjects()
                ->where('status', 'Pending Resource Person')
                ->count();

            if ($resourcePersonSubjects > 0) {
                // If some subjects were forwarded, update the status
                $application->status = 'Pending Resource Person Review';
            } else {
                // If all subjects were approved directly, the application is complete
                $application->status = 'Completed';
            }
            $application->save();
        }
        // If there are still subjects for the coordinator to review, do nothing to the overall status.
    }
    
    /**
     * Handle the "Reject" button click for a single subject.
     */
    public function rejectSubject(ApplicationSubject $subject)
    {
        $subject->status = 'Rejected';
        $subject->save();

        $application = $subject->exemptionApplication;
        $this->updateApplicationStatus($application);

        return redirect()->route('coordinator.application.show', $application)
                        ->with('success', 'Subject rejected successfully.');
    }
    
    /**
     * Display course equivalencies management page (read-only for coordinators).
     */
    public function viewCourseEquivalencies()
    {
        // Get all SARJANA MUDA programs
        $programs = Program::where('name', 'LIKE', '%SARJANA MUDA%')
                          ->orderBy('name')
                          ->get(['code', 'name']);
                                     
        return view('coordinator.course_equivalencies.view', compact('programs'));
    }
    
    /**
     * API endpoint to get existing course equivalencies for a program (read-only for coordinators).
     */
    public function getExistingEquivalencies(Request $request)
    {
        $request->validate([
            'program_code' => 'required|string'
        ]);
        
        $equivalencies = CourseEquivalency::where('program_code', $request->program_code)
                                        ->orderBy('diploma_course_code')
                                        ->get();
        
        return response()->json($equivalencies);
    }
}
