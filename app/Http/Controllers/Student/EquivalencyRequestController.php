<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ApplicationSubject;
use App\Models\Course;
use App\Models\CourseEquivalency;
use App\Models\CourseEquivalencyRequest;
use App\Models\ExemptionApplication;
use App\Models\Institution;
use App\Models\ProgramCoordinator;
use App\Models\Student;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EquivalencyRequestController extends Controller
{
    /**
     * Display form to create new equivalency request
     */
    public function create()
    {
        $student = Auth::user()->student;

        // Check if student has an exemption application
        $exemptionApplication = ExemptionApplication::where('student_id', $student->id)->first();
        $hasExemptionApplication = $exemptionApplication !== null;

        // Get courses from student's transcript (if they have an application)
        $transcriptCourses = collect();
        if ($hasExemptionApplication) {
            $transcriptCourses = ApplicationSubject::where('exemption_application_id', $exemptionApplication->id)
                ->select('course_code', 'course_name', 'credit_hour', 'grade', 'status')
                ->orderBy('course_code')
                ->get();
        }

        // Get student's current program information
        $campuses = Campus::orderBy('name')->get();

        // Load non-UiTM institutions for diploma institution selection
        $institutions = Institution::where('name', 'NOT LIKE', '%UiTM%')
            ->where('name', 'NOT LIKE', '%UITM%')
            ->orderBy('name')
            ->get();

        // Load UNIQUE diploma courses from course_equivalencies
        // Students will select ONE diploma course code from this list
        $diplomaCourses = DB::table('course_equivalencies')
            ->select(
                'diploma_course_code',
                DB::raw('MAX(diploma_course_name) as diploma_course_name'),
                DB::raw('MAX(diploma_institution) as diploma_institution'),
                DB::raw('MAX(diploma_credit_hour) as diploma_credit_hour')
            )
            ->groupBy('diploma_course_code')
            ->orderBy('diploma_course_code')
            ->get();

        // Load UNIQUE degree courses from course_equivalencies
        // Students will select ONE degree course code from this list
        $degreeCourses = DB::table('course_equivalencies')
            ->select(
                'degree_course_code',
                DB::raw('MAX(degree_course_name) as degree_course_name'),
                DB::raw('MAX(degree_credit_hour) as degree_credit_hour')
            )
            ->groupBy('degree_course_code')
            ->orderBy('degree_course_code')
            ->get();

        // Load courses from student's program syllabus for validation
        // This ensures students can only request equivalency for courses in their program
        $programSyllabusCourses = collect();
        if ($student->program_code) {
            $programSyllabusCourses = Course::where('program_code', $student->program_code)
                ->select('code', 'name')
                ->orderBy('code')
                ->get();
        }

        return view('student.equivalency_request.create', compact(
            'student',
            'campuses',
            'institutions',
            'diplomaCourses',
            'degreeCourses',
            'hasExemptionApplication',
            'transcriptCourses',
            'programSyllabusCourses'
        ));
    }

    /**
     * Store new equivalency request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'diploma_course_code' => 'required|string|max:20',
            'diploma_course_name' => 'required|string|max:255',
            'diploma_institution' => 'required|string|max:255',
            'suggested_degree_course_code' => 'required|string|max:20',
            'suggested_degree_course_name' => 'required|string|max:255',
            'external_lecturer_name' => 'required|string|max:255',
            'external_lecturer_email' => 'required|email|max:255',
        ]);

        try {
            DB::beginTransaction();

            $student = Auth::user()->student;

            // Check if student record exists
            if (!$student) {
                Log::error('Student record not found for user', [
                    'user_id' => Auth::user()->id,
                    'user_email' => Auth::user()->email
                ]);

                return back()
                    ->withErrors(['error' => 'Student profile not found. Please contact system administrator.'])
                    ->withInput();
            }

            // Validate: Check if student has a credit exemption application
            $exemptionApplication = ExemptionApplication::where('student_id', $student->id)->first();

            if (!$exemptionApplication) {
                Log::warning('Student tried to request equivalency without exemption application', [
                    'student_id' => $student->id,
                    'diploma_course_code' => $validated['diploma_course_code']
                ]);

                return back()
                    ->withErrors(['diploma_course_code' => 'You must submit a Credit Exemption Application first before requesting course equivalency. Please apply for credit exemption with your transcript.'])
                    ->withInput();
            }

            // Validate: Check if diploma course code exists in student's transcript
            $diplomaCourseCode = strtoupper(trim($validated['diploma_course_code']));
            $courseInTranscript = ApplicationSubject::where('exemption_application_id', $exemptionApplication->id)
                ->where(function ($query) use ($diplomaCourseCode) {
                    $query->where('course_code', $diplomaCourseCode)
                          ->orWhere('course_code', 'LIKE', "%{$diplomaCourseCode}%");
                })
                ->first();

            if (!$courseInTranscript) {
                Log::warning('Student requested equivalency for course not in transcript', [
                    'student_id' => $student->id,
                    'diploma_course_code' => $diplomaCourseCode,
                    'application_id' => $exemptionApplication->id
                ]);

                return back()
                    ->withErrors(['diploma_course_code' => "The course code \"{$diplomaCourseCode}\" was not found in your transcript. You can only request equivalency for courses you have taken during your diploma. Please check your Credit Exemption Application to see which courses are in your transcript."])
                    ->withInput();
            }

            // Validate: Check if degree course code exists in student's program syllabus
            $programCode = $student->program_code;
            $degreeCourseCode = strtoupper(trim($validated['suggested_degree_course_code']));
            $courseInProgramSyllabus = Course::where('code', $degreeCourseCode)
                ->where('program_code', $programCode)
                ->first();

            if (!$courseInProgramSyllabus) {
                Log::warning('Student requested equivalency for degree course not in their program syllabus', [
                    'student_id' => $student->id,
                    'degree_course_code' => $degreeCourseCode,
                    'program_code' => $programCode
                ]);

                return back()
                    ->withErrors(['suggested_degree_course_code' => "The degree course code \"{$degreeCourseCode}\" does not exist in your program syllabus ({$programCode}). You can only request equivalency for courses that are part of your degree program curriculum."])
                    ->withInput();
            }

            // Find the appropriate Program Coordinator for this student's program
            $coordinator = ProgramCoordinator::getCoordinatorForProgram($programCode);

            if (!$coordinator) {
                Log::warning('No Program Coordinator found for program', [
                    'program_code' => $programCode,
                    'student_id' => $student->id
                ]);
            }

            // Get diploma course details from equivalencies table (if available, for credit hours only)
            $diplomaCourse = CourseEquivalency::where('diploma_course_code', strtoupper(trim($validated['diploma_course_code'])))
                ->where('program_code', $programCode)
                ->first();

            // Create the equivalency request
            $equivalencyRequest = CourseEquivalencyRequest::create([
                'student_id' => $student->id,
                'coordinator_id' => $coordinator ? $coordinator->id : null,
                'diploma_course_code' => strtoupper(trim($validated['diploma_course_code'])),
                'diploma_course_name' => trim($validated['diploma_course_name']),
                'diploma_institution' => trim($validated['diploma_institution']),
                'diploma_program' => $student->program_name ?? 'Unknown Program',
                'diploma_credit_hours' => $diplomaCourse?->diploma_credit_hour ?? 0,
                'diploma_grade' => null, // Grade not required for equivalency requests
                'suggested_degree_course_code' => strtoupper(trim($validated['suggested_degree_course_code'])),
                'suggested_degree_course_name' => trim($validated['suggested_degree_course_name']),
                'current_program_code' => $programCode,
                'current_program_name' => $student->program_name ?? 'Unknown Program',
                'justification' => null,
                'external_lecturer_name' => trim($validated['external_lecturer_name']),
                'external_lecturer_email' => strtolower(trim($validated['external_lecturer_email'])),
                'status' => 'pending',
            ]);

            Log::info('Course equivalency request created', [
                'request_id' => $equivalencyRequest->id,
                'student_id' => $student->id,
                'diploma_course_code' => $equivalencyRequest->diploma_course_code,
                'coordinator_id' => $coordinator?->id
            ]);

            DB::commit();

            return redirect()
                ->route('student.equivalency.request.index')
                ->with('success', 'Course equivalency request submitted successfully! Your Program Coordinator will review your request.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creating equivalency request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'student_id' => Auth::user()->student->id ?? null,
                'user_id' => Auth::user()->id ?? null
            ]);

            return back()
                ->withErrors(['error' => 'An error occurred while submitting your request: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display list of student's equivalency requests
     */
    public function index()
    {
        $student = Auth::user()->student;

        $requests = CourseEquivalencyRequest::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.equivalency_request.index', compact('requests'));
    }

    /**
     * Display specific equivalency request details
     */
    public function show($id)
    {
        $student = Auth::user()->student;

        $request = CourseEquivalencyRequest::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        return view('student.equivalency_request.show', compact('request'));
    }
}
