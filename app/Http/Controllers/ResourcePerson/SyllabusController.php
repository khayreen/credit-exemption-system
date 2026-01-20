<?php

namespace App\Http\Controllers\ResourcePerson;

use App\Http\Controllers\Controller;
use App\Models\CourseEquivalency;
use App\Models\DegreeCourseSyllabus;
use App\Services\SyllabusMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SyllabusController extends Controller
{
    protected SyllabusMatchingService $matchingService;

    public function __construct(SyllabusMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    /**
     * Display a listing of degree course syllabi.
     */
    public function index(Request $request)
    {
        $query = DegreeCourseSyllabus::with('uploader')
            ->orderBy('program_code')
            ->orderBy('course_code');

        // Filter by program code
        if ($request->filled('program_code')) {
            $query->where('program_code', $request->program_code);
        }

        // Filter by course code search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('course_code', 'LIKE', "%{$search}%")
                  ->orWhere('course_name', 'LIKE', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $syllabi = $query->paginate(15)->withQueryString();

        // Get program codes for filter dropdown
        $programCodes = DegreeCourseSyllabus::select('program_code')
            ->distinct()
            ->orderBy('program_code')
            ->pluck('program_code');

        // Statistics
        $stats = [
            'total' => DegreeCourseSyllabus::count(),
            'active' => DegreeCourseSyllabus::where('is_active', true)->count(),
            'programs' => DegreeCourseSyllabus::distinct('program_code')->count('program_code'),
        ];

        return view('resource_person.syllabi.index', compact('syllabi', 'programCodes', 'stats'));
    }

    /**
     * Show the form for uploading a new syllabus.
     */
    public function create(Request $request)
    {
        // Get all unique degree courses from equivalencies (not grouped by program)
        // One course code can be taken by multiple programs, so we just need unique courses
        $degreeCourses = CourseEquivalency::select('degree_course_code', 'degree_course_name', 'degree_credit_hour')
            ->distinct()
            ->orderBy('degree_course_code')
            ->get()
            ->mapWithKeys(function ($course) {
                return [
                    $course->degree_course_code => [
                        'name' => $course->degree_course_name ?? 'Unknown',
                        'credit_hours' => $course->degree_credit_hour ?? 3,
                    ]
                ];
            });

        // Pre-fill data from query parameters (when coming from comparison page)
        $prefill = [
            'course_code' => $request->query('course_code'),
            'course_name' => $request->query('course_name'),
            'credit_hours' => $request->query('credit_hours', 3),
            'return_to' => $request->query('return_to'), // Return URL after upload
        ];

        return view('resource_person.syllabi.create', compact('degreeCourses', 'prefill'));
    }

    /**
     * Store a newly uploaded syllabus.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20',
            'course_name' => 'required|string|max:255',
            'credit_hours' => 'required|numeric|min:1|max:10',
            'description' => 'nullable|string|max:1000',
            'syllabus_file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        // Check for duplicate (by course code only, since program is no longer used)
        $exists = DegreeCourseSyllabus::where('course_code', strtoupper($validated['course_code']))
            ->where('is_active', true)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['course_code' => 'A syllabus for this course code already exists.']);
        }

        // Store the file
        $file = $request->file('syllabus_file');
        $originalName = $file->getClientOriginalName();
        $filename = Str::uuid() . '.pdf';
        $path = $file->storeAs('syllabi/degree_courses', $filename);

        // Calculate digital signature (SHA256)
        $digitalSignature = hash_file('sha256', $file->getRealPath());

        // Create the record
        $syllabus = DegreeCourseSyllabus::create([
            'course_code' => strtoupper($validated['course_code']),
            'course_name' => $validated['course_name'],
            'credit_hours' => $validated['credit_hours'],
            'syllabus_file_path' => $path,
            'syllabus_file_original_name' => $originalName,
            'digital_signature' => $digitalSignature,
            'description' => $validated['description'],
            'uploaded_by' => Auth::id(),
            'is_active' => true,
        ]);

        // Check if there's a return URL (coming from comparison page)
        $returnTo = $request->input('return_to');
        if ($returnTo && filter_var($returnTo, FILTER_VALIDATE_URL)) {
            return redirect($returnTo)
                ->with('success', "Syllabus for {$syllabus->course_code} - {$syllabus->course_name} uploaded successfully.");
        }

        return redirect()
            ->route('resource_person.syllabi.index')
            ->with('success', "Syllabus for {$syllabus->course_code} - {$syllabus->course_name} uploaded successfully.");
    }

    /**
     * Display the specified syllabus.
     */
    public function show(DegreeCourseSyllabus $syllabus)
    {
        return view('resource_person.syllabi.show', compact('syllabus'));
    }

    /**
     * Show the form for editing a syllabus.
     */
    public function edit(DegreeCourseSyllabus $syllabus)
    {
        return view('resource_person.syllabi.edit', compact('syllabus'));
    }

    /**
     * Update the specified syllabus.
     */
    public function update(Request $request, DegreeCourseSyllabus $syllabus)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20',
            'course_name' => 'required|string|max:255',
            'credit_hours' => 'required|numeric|min:1|max:10',
            'description' => 'nullable|string|max:1000',
            'syllabus_file' => 'nullable|file|mimes:pdf|max:10240',
            'is_active' => 'boolean',
        ]);

        // Check for duplicate (excluding current record)
        $exists = DegreeCourseSyllabus::where('course_code', strtoupper($validated['course_code']))
            ->where('is_active', true)
            ->where('id', '!=', $syllabus->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['course_code' => 'A syllabus for this course code already exists.']);
        }

        // If new file uploaded, replace old one
        if ($request->hasFile('syllabus_file')) {
            // Delete old file
            if ($syllabus->syllabus_file_path && Storage::exists($syllabus->syllabus_file_path)) {
                Storage::delete($syllabus->syllabus_file_path);
            }

            // Store new file
            $file = $request->file('syllabus_file');
            $originalName = $file->getClientOriginalName();
            $filename = Str::uuid() . '.pdf';
            $path = $file->storeAs('syllabi/degree_courses', $filename);

            $syllabus->syllabus_file_path = $path;
            $syllabus->syllabus_file_original_name = $originalName;
            $syllabus->digital_signature = hash_file('sha256', $file->getRealPath());
        }

        $syllabus->course_code = strtoupper($validated['course_code']);
        $syllabus->course_name = $validated['course_name'];
        $syllabus->credit_hours = $validated['credit_hours'];
        $syllabus->description = $validated['description'];
        $syllabus->is_active = $request->boolean('is_active', true);
        $syllabus->save();

        return redirect()
            ->route('resource_person.syllabi.index')
            ->with('success', "Syllabus for {$syllabus->course_code} updated successfully.");
    }

    /**
     * Remove the specified syllabus.
     */
    public function destroy(DegreeCourseSyllabus $syllabus)
    {
        // Delete the file
        if ($syllabus->syllabus_file_path && Storage::exists($syllabus->syllabus_file_path)) {
            Storage::delete($syllabus->syllabus_file_path);
        }

        $courseCode = $syllabus->course_code;
        $syllabus->delete();

        return redirect()
            ->route('resource_person.syllabi.index')
            ->with('success', "Syllabus for {$courseCode} deleted successfully.");
    }

    /**
     * View the syllabus PDF file.
     */
    public function viewPdf(DegreeCourseSyllabus $syllabus)
    {
        if (!$syllabus->syllabus_file_path || !Storage::exists($syllabus->syllabus_file_path)) {
            abort(404, 'Syllabus file not found.');
        }

        return response()->file(
            Storage::path($syllabus->syllabus_file_path),
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * API: Get similar courses for comparison (used by AJAX).
     * Finds degree courses similar to the student's requested degree course.
     */
    public function apiGetSimilarCourses(Request $request)
    {
        $request->validate([
            'suggested_course_code' => 'required|string',
            'suggested_course_name' => 'required|string',
            'program_code' => 'required|string',
        ]);

        $recommendations = $this->matchingService->findSimilarCourses(
            $request->suggested_course_code,
            $request->suggested_course_name,
            $request->program_code
        );

        return response()->json([
            'success' => true,
            'recommendations' => $recommendations->map(function ($item) {
                return [
                    'id' => $item['syllabus']?->id ?? null,
                    'course_code' => $item['course_code'],
                    'course_name' => $item['course_name'],
                    'credit_hours' => $item['credit_hours'],
                    'similarity' => $item['similarity'],
                    'is_suggested' => $item['is_suggested'] ?? false,
                    'has_syllabus' => $item['has_syllabus'] ?? false,
                    'source' => $item['source'] ?? 'unknown',
                ];
            }),
        ]);
    }

    /**
     * API: Get syllabus details for a specific course.
     */
    public function apiGetSyllabus(Request $request, string $courseCode)
    {
        $programCode = $request->query('program_code');

        $syllabus = DegreeCourseSyllabus::active()
            ->where('course_code', $courseCode)
            ->when($programCode, fn($q) => $q->where('program_code', $programCode))
            ->first();

        if (!$syllabus) {
            return response()->json([
                'success' => false,
                'message' => 'Syllabus not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'syllabus' => [
                'id' => $syllabus->id,
                'course_code' => $syllabus->course_code,
                'course_name' => $syllabus->course_name,
                'credit_hours' => $syllabus->credit_hours,
                'program_code' => $syllabus->program_code,
                'description' => $syllabus->description,
                'academic_session' => $syllabus->academic_session,
                'uploaded_at' => $syllabus->created_at->format('d M Y'),
                'view_url' => route('resource_person.syllabi.view_pdf', $syllabus),
            ],
        ]);
    }

    /**
     * Show the side-by-side syllabus comparison view for an equivalency request.
     */
    public function compare(\App\Models\CourseEquivalencyRequest $request)
    {
        // Ensure the request has a syllabus submission
        if (!$request->syllabus_received_at || !$request->externalLecturerRequest || !$request->externalLecturerRequest->submission) {
            return redirect()
                ->route('resource_person.equivalency_requests.review', $request)
                ->with('error', 'No external syllabus has been submitted yet for this request.');
        }

        $submission = $request->externalLecturerRequest->submission;

        // Find similar degree courses based on the STUDENT'S REQUESTED degree course
        // This helps RP find alternative degree syllabi to reference if the requested one isn't uploaded
        $recommendations = $this->matchingService->findSimilarCourses(
            $request->suggested_degree_course_code,
            $request->suggested_degree_course_name ?? 'Unknown Course',
            $request->current_program_code,
            10 // Return top 10 recommendations
        );

        return view('resource_person.syllabi.compare', compact('request', 'submission', 'recommendations'));
    }
}
