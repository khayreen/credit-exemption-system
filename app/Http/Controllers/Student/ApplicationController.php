<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Institution;
use App\Models\Program;
use App\Models\ExemptionApplication;
use App\Models\Transcript;
use App\Models\CourseEquivalency;
use App\Models\ApplicationSubject;
use App\Models\Campus;
use App\Models\Faculty;
use \Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use \Google\Cloud\Vision\V1\Image;
use \Google\Cloud\Vision\V1\Feature;
use \Google\Cloud\Vision\V1\AnnotateImageRequest;
use \Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Smalot\PdfParser\Parser as PdfParser;
use Barryvdh\DomPDF\Facade\Pdf;

class ApplicationController extends Controller
{
    /**
     * Display the student's main dashboard.
     */
    public function dashboard()
    {
        $student = Auth::user()->student;

        if (!$student) {
            // If no student record exists, redirect to profile completion
            return redirect()->route('profile.show')
                           ->with('warning', 'Please complete your student profile to access the dashboard.');
        }

        // Get all applications for the student
        $applications = ExemptionApplication::where('student_id', $student->id)
                                           ->with('applicationSubjects')
                                           ->orderBy('created_at', 'desc')
                                           ->get();

        // Calculate detailed statistics
        $stats = [
            'total' => $applications->count(),
            'pending' => $applications->whereIn('status', ['pending', 'under_review', 'Pending Academic Advisor'])->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
            'in_progress' => $applications->whereIn('status', ['under_review', 'Pending Academic Advisor', 'Pending Resource Person'])->count(),
        ];

        // Calculate course-level statistics
        $totalCourses = 0;
        $exemptedCourses = 0;
        $pendingCourses = 0;
        $rejectedCourses = 0;

        foreach ($applications as $application) {
            $subjects = $application->applicationSubjects;
            $totalCourses += $subjects->count();
            $exemptedCourses += $subjects->whereIn('status', ['exempted', 'Approved'])->count();
            $pendingCourses += $subjects->whereIn('status', ['pending', 'under_review', 'Pending Resource Person'])->count();
            $rejectedCourses += $subjects->where('status', 'Rejected')->count();
        }

        $courseStats = [
            'total' => $totalCourses,
            'exempted' => $exemptedCourses,
            'pending' => $pendingCourses,
            'rejected' => $rejectedCourses,
            'exemption_rate' => $totalCourses > 0 ? round(($exemptedCourses / $totalCourses) * 100) : 0,
        ];

        // Get recent applications (last 5)
        $recentApplications = $applications->take(5);

        // Get latest application for quick status display
        $latestApplication = $applications->first();

        // Profile completion check
        $profileCompletion = $this->calculateProfileCompletion($student);

        return view('student.dashboard', compact(
            'stats',
            'courseStats',
            'recentApplications',
            'latestApplication',
            'profileCompletion',
            'student'
        ));
    }

    /**
     * Calculate student profile completion percentage
     */
    private function calculateProfileCompletion($student)
    {
        $fields = [
            'matric_no', 'program_name', 'program_code', 'ic_number',
            'campus', 'intake_semester', 'home_address', 'faculty_id', 'mode_of_study'
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($student->$field)) {
                $completed++;
            }
        }

        return [
            'percentage' => round(($completed / count($fields)) * 100),
            'completed' => $completed,
            'total' => count($fields),
            'is_complete' => $completed === count($fields)
        ];
    }

    /**
     * Show the application creation form.
     */
    public function create()
    {
        $student = Auth::user()->student;
        
        // Separate UiTM and non-UiTM institutions
        $nonUitmInstitutions = Institution::where('name', 'NOT LIKE', '%UiTM%')
                                        ->where('name', 'NOT LIKE', '%UITM%')
                                        ->orderBy('name')->get();
        
        // Get degree programs only (exclude ASASI and DIPLOMA programs)
        $programs = Program::where(function($query) {
                        // Exclude programs that start with ASASI or DIPLOMA keywords
                        $query->where('name', 'NOT LIKE', '%ASASI%')
                              ->where('name', 'NOT LIKE', '%DIPLOMA%')
                              // Also exclude by common ASASI and DIPLOMA code patterns
                              ->where('code', 'NOT LIKE', 'PI%')  // ASASI codes often start with PI
                              ->where('code', 'NOT LIKE', 'D%')   // DIPLOMA codes start with D
                              ->where('code', 'NOT LIKE', '%ASASI%');
                    })
                    // Additional filter to only include degree programs (typically 2-letter + 3-digit codes)
                    ->where(function($query) {
                        $query->whereRaw('LENGTH(code) = 5 AND code REGEXP "^[A-Z]{2}[0-9]{3}$"')
                              ->orWhereNull('code'); // Include programs without codes for backward compatibility
                    })
                    ->orderBy('name')->get();
        
        // For UiTM diploma programs, filter more specifically
        $uitmDiplomaPrograms = Program::where(function($query) {
                                    // Include programs with 'DIPLOMA' in name
                                    $query->where('name', 'LIKE', '%DIPLOMA%')
                                          // Or codes that start with common diploma patterns
                                          ->orWhere(function($subQuery) {
                                              $subQuery->where('code', 'LIKE', 'D%')
                                                       // But exclude SARJANA programs
                                                       ->where('name', 'NOT LIKE', '%SARJANA%')
                                                       ->where('name', 'NOT LIKE', '%BACHELOR%')
                                                       ->where('name', 'NOT LIKE', '%DEGREE%');
                                          });
                                })
                                // Additional safeguard to exclude degree-level programs
                                ->where('name', 'NOT LIKE', '%SARJANA%')
                                ->where('name', 'NOT LIKE', '%BACHELOR%')
                                ->where('name', 'NOT LIKE', '%DEGREE%')
                                ->orderBy('name')->get();
        
        $campuses = Campus::orderBy('name')->get();
        $faculties = Faculty::where('is_active', true)->orderBy('name')->get();
        
        return view('student.application.create', compact(
            'nonUitmInstitutions', 
            'programs', 
            'uitmDiplomaPrograms',
            'campuses', 
            'faculties', 
            'student'
        ));
    }

    /**
     * Process the uploaded transcript and create exemption application.
     */
    public function store(Request $request)
    {
        try {
            // Validate input data based on institution type and entry method
            $validationRules = [
                'full_name' => 'required|string|max:100',
                'student_id' => 'required|string|max:50',
                'ic_number' => 'required|string|max:20',
                'home_address' => 'required|string|max:500',
                'campus' => 'required|string',
                'faculty' => 'required|string',
                'faculty_id' => 'nullable|uuid|exists:faculties,id',
                'program_name' => 'required|string',
                'program_code' => 'nullable|string|max:10',
                'student_group' => 'required|string|max:20',
                'current_semester' => 'required|integer|min:1|max:20',
                'institution_type' => 'required|in:uitm,non_uitm',
                'entry_method' => 'required|in:ocr,manual',
            ];

            // Add conditional validation rules based on institution type
            if ($request->institution_type === 'uitm') {
                $validationRules['previous_uitm_campus'] = 'required|string';
                $validationRules['previous_uitm_program'] = 'required|string';
                // Make sure we ignore the non-UiTM fields when UiTM is selected
                $validationRules['previous_institution'] = 'nullable';
                $validationRules['previous_program'] = 'nullable';
            } else {
                $validationRules['previous_institution'] = 'required|string';
                $validationRules['previous_program'] = 'required|string';
                $validationRules['previous_program_code'] = 'nullable|string|max:10';
                // Make sure we ignore the UiTM fields when non-UiTM is selected
                $validationRules['previous_uitm_campus'] = 'nullable';
                $validationRules['previous_uitm_program'] = 'nullable';
            }

            // Add conditional validation rules based on entry method
            if ($request->entry_method === 'ocr') {
                $validationRules['transcript_file'] = 'required|file|mimes:pdf|max:5120';
                $validationRules['manual_courses'] = 'nullable';
            } else {
                $validationRules['transcript_file'] = 'nullable';
                $validationRules['manual_courses'] = 'required|json';
            }

            $request->validate($validationRules);

            $student = Auth::user()->student;

            // --- Determine previous institution and program based on type ---
            if ($request->institution_type === 'uitm') {
                // Campus names already include "UiTM " prefix, so use them directly
                $previousInstitution = $request->previous_uitm_campus;
                $previousProgram = $request->previous_uitm_program;
                $previousProgramCode = null; // UiTM students don't need to enter code manually
            } else {
                $previousInstitution = $request->previous_institution;
                $previousProgram = $request->previous_program;
                $previousProgramCode = $request->previous_program_code;
            }
            
            // Start database transaction for data consistency
            DB::beginTransaction();
            
            try {
                // --- Create Exemption Application with Student Snapshot ---
                $application = ExemptionApplication::create([
                    'student_id' => $student->id,
                    'previous_institution' => $previousInstitution,
                    'previous_program' => $previousProgram,
                    'previous_program_code' => $previousProgramCode,
                    'status' => 'Submitted',
                    // Student snapshot at time of application (from form input)
                    'student_name' => $request->full_name,
                    'matric_no' => $request->student_id,
                    'ic_number' => $request->ic_number,
                    'home_address' => $request->home_address,
                    'current_program' => $request->program_name,
                    'current_program_code' => $request->program_code,
                    'student_group' => $request->student_group,
                    'current_campus' => $request->campus,
                    'current_faculty' => $request->faculty,
                    'current_semester' => $request->current_semester
                ]);

                // --- Handle Transcript Submission Based on Entry Method ---
                if ($request->entry_method === 'manual') {
                    // --- MANUAL ENTRY WORKFLOW ---
                    Log::info('Processing manual course entry', [
                        'application_id' => $application->id,
                        'user_id' => Auth::id()
                    ]);

                    $manualCoursesData = json_decode($request->manual_courses, true);

                    if (empty($manualCoursesData)) {
                        throw new \Exception('No manual courses provided. Please add at least one course.');
                    }

                    $coursesProcessed = 0;
                    foreach ($manualCoursesData as $courseData) {
                        // Process each manually entered course with institution-aware matching
                        $this->processManualCourse(
                            $application->id,
                            $courseData,
                            $request->program_code ?? 'CDCS251',
                            $previousInstitution,
                            $request->institution_type
                        );
                        $coursesProcessed++;
                    }

                    Log::info('Manual courses processed successfully', [
                        'application_id' => $application->id,
                        'courses_count' => $coursesProcessed
                    ]);

                    DB::commit();

                    return redirect()
                        ->route('student.application.status')
                        ->with('success', "Application submitted successfully! {$coursesProcessed} courses processed via manual entry.");

                } else {
                    // --- OCR UPLOAD WORKFLOW ---
                    // --- Handle File Upload ---
                    if (!$request->hasFile('transcript_file')) {
                        throw new \Exception('Transcript file is required but not provided.');
                    }

                    $transcriptFile = $request->file('transcript_file');
                
                if (!$transcriptFile->isValid()) {
                    throw new \Exception('Uploaded file is corrupted or invalid.');
                }

                // Generate secure filename using UUID
                $fileName = Str::uuid() . '.pdf';
                
                try {
                    $filePath = $transcriptFile->storeAs('transcripts', $fileName);
                    
                    if (!$filePath) {
                        throw new \Exception('Failed to store transcript file.');
                    }
                    
                    $fileHash = hash_file('sha256', $transcriptFile->getRealPath());
                    
                    // --- Create Transcript Record ---
                    $transcript = Transcript::create([
                        'exemption_application_id' => $application->id,
                        'original_filename' => $transcriptFile->getClientOriginalName(),
                        'file_path' => $filePath,
                        'digital_signature' => $fileHash, // SHA256 hash for document integrity verification
                        'file_hash' => $fileHash,
                        'ocr_status' => 'pending'
                    ]);
                    
                } catch (\Exception $e) {
                    Log::error('File storage error: ' . $e->getMessage(), [
                        'application_id' => $application->id,
                        'user_id' => Auth::id()
                    ]);
                    throw new \Exception('Failed to process uploaded file. Please try again.');
                }

                // --- OCR Processing with Comprehensive Error Handling ---
                $foundCourses = [];
                $ocrConfidence = 0;
                
                try {
                    // Update transcript status to processing
                    $transcript->update(['ocr_status' => 'processing']);
                    
                    // Check if Google Cloud Vision is properly configured
                    $credentialsPath = env('GOOGLE_CLOUD_CREDENTIALS_PATH', 'storage/app/keys/service-account-credentials.json');
                    
                    // Support both absolute and relative paths (same logic as VisionServiceProvider)
                    if (!file_exists($credentialsPath)) {
                        $credentialsPath = storage_path('app/keys/service-account-credentials.json');
                    }
                    
                    if (!file_exists($credentialsPath)) {
                        throw new \Exception('Google Cloud Vision API credentials file not found. Please check GOOGLE_CLOUD_CREDENTIALS_PATH environment variable.');
                    }
                    
                    $imageAnnotator = app(ImageAnnotatorClient::class);
                    
                    // Read file content and validate
                    $filePath = $transcriptFile->getRealPath();
                    $fileSize = filesize($filePath);
                    $mimeType = $transcriptFile->getMimeType();
                    
                    Log::info('OCR File Debug Info', [
                        'file_size' => $fileSize,
                        'mime_type' => $mimeType,
                        'file_path' => $filePath,
                        'application_id' => $application->id
                    ]);
                    
                    if ($fileSize === 0) {
                        throw new \Exception('Uploaded file is empty.');
                    }
                    
                    if ($fileSize > 10 * 1024 * 1024) { // 10MB limit
                        throw new \Exception('File too large for OCR processing. Maximum size is 10MB.');
                    }
                    
                    $content = file_get_contents($filePath);
                    if ($content === false) {
                        throw new \Exception('Failed to read transcript file content.');
                    }
                    
                    Log::info('Processing file for text extraction', [
                        'application_id' => $application->id,
                        'mime_type' => $mimeType,
                        'file_size' => $fileSize,
                        'content_length' => strlen($content)
                    ]);
                    
                    // Handle PDF files with direct text extraction
                    if ($mimeType === 'application/pdf') {
                        Log::info('Using PDF text extraction', ['application_id' => $application->id]);
                        
                        try {
                            $pdfParser = new PdfParser();
                            $pdf = $pdfParser->parseContent($content);
                            $fullText = $pdf->getText();
                            $ocrConfidence = 100; // PDF text extraction is 100% accurate
                            
                            Log::info('PDF text extracted successfully', [
                                'application_id' => $application->id,
                                'text_length' => strlen($fullText)
                            ]);
                            
                        } catch (\Exception $e) {
                            Log::error('PDF parsing error: ' . $e->getMessage(), [
                                'application_id' => $application->id
                            ]);
                            throw new \Exception('Failed to extract text from PDF: ' . $e->getMessage());
                        }
                        
                    } else {
                        // Use Google Vision API for image files
                        Log::info('Using Google Vision API for image', ['application_id' => $application->id]);
                        
                        $image = new Image();
                        $image->setContent($content);
                        
                        // Create feature for text detection
                        $feature = new Feature();
                        $feature->setType(Feature\Type::TEXT_DETECTION);
                        
                        // Create the annotation request
                        $annotationRequest = new AnnotateImageRequest();
                        $annotationRequest->setImage($image);
                        $annotationRequest->setFeatures([$feature]);
                        
                        // Create batch request
                        $batchRequest = new BatchAnnotateImagesRequest();
                        $batchRequest->setRequests([$annotationRequest]);
                        
                        // Make the API call
                        $response = $imageAnnotator->batchAnnotateImages($batchRequest);
                        $annotations = $response->getResponses();
                        
                        if ($annotations->count() === 0) {
                            throw new \Exception('No response from Google Vision API');
                        }
                        
                        $annotation = $annotations[0];
                        if ($annotation->hasError()) {
                            $error = $annotation->getError();
                            Log::error('Google Vision API detailed error', [
                                'application_id' => $application->id,
                                'error_code' => $error->getCode(),
                                'error_message' => $error->getMessage(),
                                'file_type' => $mimeType,
                                'file_size' => $fileSize
                            ]);
                            throw new \Exception('Google Vision API error: ' . $error->getMessage());
                        }
                        
                        $fullTextAnnotation = $annotation->getFullTextAnnotation();
                        if (!$fullTextAnnotation) {
                            Log::warning('No text detected in image for application: ' . $application->id);
                            $fullText = '';
                            $ocrConfidence = 0;
                        } else {
                            $fullText = $fullTextAnnotation->getText();
                            
                            // Calculate confidence score
                            $textAnnotations = $annotation->getTextAnnotations();
                            if ($textAnnotations->count() > 0) {
                                $totalConfidence = 0;
                                $count = 0;
                                foreach ($textAnnotations as $textAnnotation) {
                                    if ($textAnnotation->getConfidence() > 0) {
                                        $totalConfidence += $textAnnotation->getConfidence();
                                        $count++;
                                    }
                                }
                                $ocrConfidence = $count > 0 ? ($totalConfidence / $count) * 100 : 0;
                            } else {
                                $ocrConfidence = 0;
                            }
                        }
                    }
                    
                    // Process course matching with program-specific filtering
                    $foundCourses = $this->processCourseMatching($fullText, $application->id, $request->program_code);
                    
                    // Update transcript with OCR results
                    $transcript->update([
                        'ocr_status' => 'completed',
                        'ocr_processed_at' => now(),
                        'ocr_raw_text' => $fullText,
                        'ocr_confidence_score' => $ocrConfidence
                    ]);
                    
                    $imageAnnotator->close();
                    
                } catch (\Google\ApiCore\ApiException $e) {
                    Log::error('Google Vision API error: ' . $e->getMessage(), [
                        'application_id' => $application->id,
                        'status_code' => $e->getCode()
                    ]);
                    
                    $transcript->update([
                        'ocr_status' => 'failed',
                        'ocr_error_message' => 'Google Vision API error: ' . $e->getMessage()
                    ]);
                    
                    // Continue without OCR if API fails
                    session()->flash('warning', 'OCR processing failed, but your application was submitted successfully. Manual review will be required.');
                    
                } catch (\Exception $e) {
                    Log::error('OCR processing error: ' . $e->getMessage(), [
                        'application_id' => $application->id
                    ]);
                    
                    $transcript->update([
                        'ocr_status' => 'failed',
                        'ocr_error_message' => $e->getMessage()
                    ]);
                    
                    session()->flash('warning', 'Document analysis failed, but your application was submitted successfully.');
                }

                // --- Steganography Check with Error Handling ---
                try {
                    if ($this->hasHiddenData($transcriptFile->getRealPath())) {
                        DB::rollBack();
                        Storage::delete($filePath); // Clean up uploaded file
                        return back()->withErrors(['transcript_file' => 'The uploaded transcript appears to be modified. Please upload an original document.'])->withInput();
                    }
                } catch (\Exception $e) {
                    Log::error('Steganography check error: ' . $e->getMessage());
                    // Continue processing if steganography check fails
                }

                    // --- Update Student Information ---
                    $student->update([
                        'program_name' => $request->program_name,
                        'campus' => $request->campus,
                        'home_address' => $request->home_address,
                        'faculty_id' => $request->faculty_id,
                        'intake_semester' => $request->current_semester
                    ]);

                    DB::commit();

                    return redirect()->route('student.application.status')
                        ->with('success', 'Application submitted successfully! Your transcript has been processed and courses have been analyzed.');
                } // End of OCR workflow else block
                    
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Application creation error: ' . $e->getMessage(), [
                    'user_id' => Auth::id(),
                    'request_data' => $request->except(['transcript_file'])
                ]);
                
                return back()->withErrors(['general' => 'An error occurred while processing your application. Please try again.'])->withInput();
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error in application store: ' . $e->getMessage());
            return back()->withErrors(['general' => 'An unexpected error occurred. Please contact support if this persists.'])->withInput();
        }
    }

    /**
     * Reprocess an existing application to check for combined course equivalencies.
     * This method is useful for updating applications submitted before the combined equivalency feature.
     */
    public function reprocessCombinedEquivalencies($applicationId)
    {
        try {
            $application = ExemptionApplication::findOrFail($applicationId);

            // Verify ownership
            $student = Auth::user()->student;
            if ($application->student_id !== $student->id) {
                return back()->withErrors(['error' => 'Unauthorized access to this application.']);
            }

            Log::info('Reprocessing application for combined equivalencies', [
                'application_id' => $applicationId,
                'program_code' => $application->current_program_code
            ]);

            // Get all existing courses from this application
            $existingSubjects = ApplicationSubject::where('exemption_application_id', $applicationId)->get();

            $transcriptCourses = [];
            foreach ($existingSubjects as $subject) {
                // Store course data with letter grade (convert from GPA if needed)
                $letterGrade = $this->convertGPAToGrade($subject->grade);
                $transcriptCourses[$subject->course_code] = [
                    'grade' => $letterGrade,
                    'name' => $subject->course_name,
                    'gpa' => $subject->grade
                ];
            }

            // Query for combined equivalencies - ONLY from PUBLISHED lists
            $combinedEquivalencies = CourseEquivalency::where('program_code', $application->current_program_code)
                ->whereHas('equivalencyList', function($query) {
                    $query->whereNotNull('published_at'); // Only published lists
                })
                ->where(function($query) {
                    $query->where('diploma_course_code', 'LIKE', '%/%')
                          ->orWhere('diploma_course_code', 'LIKE', '%+%');
                })
                ->get();

            $newExemptionsCreated = 0;

            foreach ($combinedEquivalencies as $combinedEq) {
                $parsedGroups = $this->parseCombinedCourseCode($combinedEq->diploma_course_code);

                // Check if requirements are met
                $allGroupsSatisfied = true;
                $allGradesAcceptable = true;
                $lowestGrade = 'A+';
                $lowestGradeGPA = 4.00;
                $combinedCourseNames = [];
                $matchedCourses = [];

                foreach ($parsedGroups as $group) {
                    $groupSatisfied = false;

                    foreach ($group as $alternativeCode) {
                        if (isset($transcriptCourses[$alternativeCode])) {
                            $groupSatisfied = true;
                            $courseGrade = $transcriptCourses[$alternativeCode]['grade'];
                            $courseGPA = $transcriptCourses[$alternativeCode]['gpa'];
                            $matchedCourses[] = $alternativeCode;
                            $combinedCourseNames[] = $transcriptCourses[$alternativeCode]['name'];

                            if (!$this->isGradeAcceptable($courseGrade)) {
                                $allGradesAcceptable = false;
                            }

                            if ($courseGPA < $lowestGradeGPA) {
                                $lowestGrade = $courseGrade;
                                $lowestGradeGPA = $courseGPA;
                            }
                            break;
                        }
                    }

                    if (!$groupSatisfied) {
                        $allGroupsSatisfied = false;
                        break;
                    }
                }

                if ($allGroupsSatisfied && $allGradesAcceptable && $combinedEq->match_percentage > 80) {
                    // Check if already exists
                    $existingCombined = ApplicationSubject::where('exemption_application_id', $applicationId)
                        ->where('course_code', $combinedEq->diploma_course_code)
                        ->first();

                    if (!$existingCombined) {
                        // Also check if the degree course is already exempted
                        $degreeAlreadyExempted = false;
                        foreach ($existingSubjects as $subject) {
                            $notes = json_decode($subject->notes, true);
                            if (isset($notes['equivalent_course']) && $notes['equivalent_course'] === $combinedEq->degree_course_code) {
                                $degreeAlreadyExempted = true;
                                break;
                            }
                        }

                        if (!$degreeAlreadyExempted) {
                            $combinedCourseName = implode(' + ', $combinedCourseNames);
                            $exemptionReason = 'All criteria met: Combined courses ' . implode(' & ', $matchedCourses) .
                                              ', lowest grade ' . $lowestGrade . ' ≥ C, match ' . $combinedEq->match_percentage . '% > 80%';

                            // Determine status based on whether application has been reviewed
                            $subjectStatus = ($application->status === 'Reviewed by Academic Advisor') ? 'Approved' : 'exempted';

                            ApplicationSubject::create([
                                'exemption_application_id' => $applicationId,
                                'course_code' => implode(' & ', $matchedCourses), // Use & separator for display
                                'course_name' => $combinedCourseName,
                                'credit_hour' => $combinedEq->diploma_credit_hour,
                                'grade' => $lowestGradeGPA,
                                'status' => $subjectStatus,
                                'extraction_method' => 'ocr',
                                'needs_verification' => false,
                                'ocr_confidence_score' => 95.0,
                                'exemption_reason' => $exemptionReason,
                                'notes' => json_encode([
                                    'equivalent_course' => $combinedEq->degree_course_code,
                                    'match_percentage' => $combinedEq->match_percentage,
                                    'degree_course_name' => $combinedEq->degree_course_name,
                                    'required_courses' => $matchedCourses,
                                    'combination_type' => 'multi_course',
                                    'original_combined_code' => $combinedEq->diploma_course_code
                                ])
                            ]);

                            $newExemptionsCreated++;

                            Log::info('Combined exemption created during reprocessing', [
                                'application_id' => $applicationId,
                                'matched_courses' => $matchedCourses,
                                'degree_course' => $combinedEq->degree_course_code
                            ]);
                        }
                    }
                }
            }

            return redirect()->route('student.application.status')
                ->with('success', 'Application reprocessed successfully! ' . $newExemptionsCreated . ' new combined exemption(s) found.');

        } catch (\Exception $e) {
            Log::error('Error reprocessing combined equivalencies: ' . $e->getMessage(), [
                'application_id' => $applicationId,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to reprocess application. Please try again.']);
        }
    }

    /**
     * Process OCR text and extract course information with program-specific filtering.
     * Implements three critical criteria:
     * 1. Course Found: Diploma course exists in the selected program's equivalency database
     * 2. Grade Requirement: Student achieved C or above (rejects C- and below)  
     * 3. Match Threshold: Course equivalency percentage >80%
     */
    private function processCourseMatching($fullText, $applicationId, $programCode)
    {
        try {
            $lines = explode("\n", $fullText);
            $foundCourses = [];

            // Handle case where program code is empty
            if (empty($programCode)) {
                Log::warning('Program code is empty for course matching', [
                    'application_id' => $applicationId
                ]);
                return $foundCourses; // Return empty array if no program code
            }

            // Get application to check if it's already been reviewed
            $application = ExemptionApplication::find($applicationId);
            
            Log::info('Processing course matching for program: ' . $programCode, [
                'application_id' => $applicationId,
                'total_lines' => count($lines)
            ]);
            
            foreach ($lines as $line) {
                // Clean up the line
                $line = trim($line);
                
                // UiTM transcript pattern: Course name followed by course code, then numbers and grade
                // Example: COMPUTER ORGANIZATIONCSC159	4 4.00	LUA
                // First try specific known UiTM course prefixes
                // H[A-Z]{2,3} matches all co-curriculum courses (HBU, HSK, HED, etc.)
                if (preg_match('/(CSC|MAT|ICT|CTU|ELC|H[A-Z]{2,3}|UED|ENT|MGT|STA|IEL|ITT|ISP)\d{3}/', $line, $codeMatches, PREG_OFFSET_CAPTURE)) {
                    $courseCode = $codeMatches[0][0];
                    $codePosition = $codeMatches[0][1];
                    
                    // Extract course name (everything before the code)
                    $courseName = trim(substr($line, 0, $codePosition));
                    
                    // Extract rest of line (everything after the code)
                    $restOfLine = substr($line, $codePosition + strlen($courseCode));
                    
                    // Parse numbers and grade from rest of line
                    if (preg_match('/\s+(\d+(?:\.\d+)?)\s+(\d+(?:\.\d+)?)\s+(LU[A-Z][+-]?|[A-Z][+-]?)/', $restOfLine, $detailMatches)) {
                        $creditHour = floatval($detailMatches[1]);
                        $grade = trim($detailMatches[3]);
                        
                        // Extract just the letter grade part (A+, B-, etc.) from LUA, LUB+, etc.
                        if (preg_match('/LU([A-Z][+-]?)/', $grade, $gradeMatches)) {
                            $grade = $gradeMatches[1];
                        }
                        
                        $gradeGPA = $this->convertGradeToGPA($grade);
                        
                        Log::info('UiTM format - Found course in OCR: ' . $courseCode . ' - ' . $grade, [
                            'application_id' => $applicationId,
                            'program_code' => $programCode,
                            'credit_hour' => $creditHour,
                            'course_name' => $courseName,
                            'raw_line' => $line
                        ]);
                    } else {
                        Log::debug('UiTM format - Found course code but could not parse details: ' . $courseCode, [
                            'application_id' => $applicationId,
                            'rest_of_line' => $restOfLine
                        ]);
                        continue;
                    }
                }
                // Fallback pattern for any UiTM course code format we might have missed
                elseif (preg_match('/([A-Z]{2,4}\d{3})/', $line, $codeMatches, PREG_OFFSET_CAPTURE)) {
                    $courseCode = $codeMatches[0][0];
                    $codePosition = $codeMatches[0][1];
                    
                    // Extract course name (everything before the code)
                    $courseName = trim(substr($line, 0, $codePosition));
                    
                    // Extract rest of line (everything after the code)
                    $restOfLine = substr($line, $codePosition + strlen($courseCode));
                    
                    // Parse numbers and grade from rest of line
                    if (preg_match('/\s+(\d+(?:\.\d+)?)\s+(\d+(?:\.\d+)?)\s+(LU[A-Z][+-]?|[A-Z][+-]?)/', $restOfLine, $detailMatches)) {
                        $creditHour = floatval($detailMatches[1]);
                        $grade = trim($detailMatches[3]);
                        
                        // Extract just the letter grade part (A+, B-, etc.) from LUA, LUB+, etc.
                        if (preg_match('/LU([A-Z][+-]?)/', $grade, $gradeMatches)) {
                            $grade = $gradeMatches[1];
                        }
                        
                        $gradeGPA = $this->convertGradeToGPA($grade);
                        
                        Log::info('UiTM fallback - Found course in OCR: ' . $courseCode . ' - ' . $grade, [
                            'application_id' => $applicationId,
                            'program_code' => $programCode,
                            'credit_hour' => $creditHour,
                            'course_name' => $courseName,
                            'raw_line' => $line
                        ]);
                    } else {
                        Log::debug('UiTM fallback - Found course code but could not parse details: ' . $courseCode, [
                            'application_id' => $applicationId,
                            'rest_of_line' => $restOfLine
                        ]);
                        continue;
                    }
                }
                // Fallback pattern for other transcript formats
                elseif (preg_match('/^([A-Z]{3}\s*\d{3})\s+(.*?)\s+([A-Z][+-]?)$/', $line, $matches)) {
                    $courseCode = preg_replace('/\s+/', '', $matches[1]);
                    $courseName = trim($matches[2]);
                    $grade = trim($matches[3]);
                    $gradeGPA = $this->convertGradeToGPA($grade);
                    $creditHour = $this->extractCreditHours($courseName);
                    
                    Log::info('Generic format - Found course in OCR: ' . $courseCode . ' - ' . $grade, [
                        'application_id' => $applicationId,
                        'program_code' => $programCode,
                        'raw_line' => $line
                    ]);
                } else {
                    // Log lines that don't match for debugging
                    if (strlen($line) > 10 && preg_match('/[A-Z]{2,4}\d{3}/', $line)) {
                        Log::debug('Line did not match any pattern: ' . $line, [
                            'application_id' => $applicationId
                        ]);
                    }
                    continue;
                }
                
                // Process the matched course data (common for both patterns)
                try {
                    // Special handling for co-curriculum courses (H-prefix)
                    $isCoCurriculum = substr($courseCode, 0, 1) === 'H';

                    if ($isCoCurriculum) {
                        // Co-curriculum courses don't need equivalency lookup
                        // They automatically map to HXXXXX if grade is acceptable
                        $gradeAcceptable = $this->isGradeAcceptable($grade);

                        if ($gradeAcceptable) {
                            // Determine status based on whether application has been reviewed
                            $status = ($application && $application->status === 'Reviewed by Academic Advisor') ? 'Approved' : 'exempted';
                            $exemptionReason = 'All criteria met: Co-curriculum course with grade ' . $grade . ' ≥ C (auto-exempted for degree co-curriculum HXXXXX)';
                            $exemptionEligible = true;
                            $equivalentCourse = 'HXXXXX'; // Generic co-curriculum placeholder
                            $matchPercentage = 100; // Co-curriculum courses are 100% match
                        } else {
                            $status = 'not_eligible_grade';
                            $exemptionReason = 'Co-curriculum grade ' . $grade . ' is below minimum requirement (C required)';
                            $exemptionEligible = false;
                            $equivalentCourse = 'HXXXXX';
                            $matchPercentage = 0;
                        }

                        // No equivalency object for co-curriculum
                        $equivalency = null;
                    } else {
                        // Regular course processing - CRITICAL: Match ONLY against PUBLISHED lists
                        $equivalency = CourseEquivalency::where('diploma_course_code', $courseCode)
                                                      ->where('program_code', $programCode)
                                                      ->whereHas('equivalencyList', function($query) {
                                                          $query->whereNotNull('published_at'); // Only published lists
                                                      })
                                                      ->first();

                        // Initialize variables for the three criteria
                        $courseFound = $equivalency ? true : false;
                        $gradeAcceptable = $this->isGradeAcceptable($grade);
                        $matchPercentageOK = $equivalency && $equivalency->match_percentage > 80;

                        // Determine exemption eligibility based on ALL three criteria
                        $exemptionEligible = $courseFound && $gradeAcceptable && $matchPercentageOK;

                        // Determine status and reason
                        $status = 'not_eligible';
                        $exemptionReason = '';
                        $equivalentCourse = $equivalency ? $equivalency->degree_course_code : '-';
                        $matchPercentage = $equivalency ? $equivalency->match_percentage : 0;

                        if (!$courseFound) {
                            $status = 'not_found';
                            $exemptionReason = 'Course not found in ' . $programCode . ' equivalency database';
                        } elseif (!$gradeAcceptable) {
                            $status = 'not_eligible_grade';
                            $exemptionReason = 'Grade ' . $grade . ' is below minimum requirement (C required)';
                        } elseif (!$matchPercentageOK) {
                            $status = 'not_eligible_match';
                            $exemptionReason = 'Match percentage ' . ($equivalency->match_percentage ?? 0) . '% is below 80% threshold';
                        } else {
                            $status = 'exempted';
                            $exemptionReason = 'All criteria met: Course found, grade ' . $grade . ' ≥ C, match ' . $equivalency->match_percentage . '% > 80%';
                        }
                    }
                        
                    $courseData = [
                        'code' => $courseCode,
                        'name' => $courseName,
                        'grade' => $grade,
                        'is_equivalent' => $exemptionEligible,
                        'equivalent_to' => $equivalentCourse,
                        'status' => $exemptionEligible ? 'Ready for Exemption' : 'Not Eligible',
                        'match_percentage' => $matchPercentage,
                        'exemption_reason' => $exemptionReason
                    ];

                    $foundCourses[] = $courseData;

                    // Create ApplicationSubject record for each course
                    ApplicationSubject::create([
                        'exemption_application_id' => $applicationId,
                        'course_code' => $courseCode,
                        'course_name' => $courseName,
                        'credit_hour' => isset($creditHour) ? $creditHour : $this->extractCreditHours($courseName),
                        'grade' => $gradeGPA,
                        'status' => $status,
                        'extraction_method' => 'ocr',
                        'needs_verification' => !$exemptionEligible,
                        'ocr_confidence_score' => 85.0, // Default confidence
                        'exemption_reason' => $exemptionReason,
                        'notes' => json_encode([
                            'equivalent_course' => $equivalentCourse,
                            'match_percentage' => $matchPercentage,
                            'degree_course_name' => $isCoCurriculum ? 'Co-Curriculum (To be selected)' : ($equivalency->degree_course_name ?? null)
                        ])
                    ]);
                    
                    Log::info('Course processed: ' . $courseCode, [
                        'status' => $status,
                        'eligible' => $exemptionEligible,
                        'reason' => $exemptionReason,
                        'application_id' => $applicationId
                    ]);
                    
                } catch (\Exception $e) {
                    Log::error('Error processing course equivalency for code: ' . $courseCode, [
                        'error' => $e->getMessage(),
                        'application_id' => $applicationId,
                        'program_code' => $programCode
                    ]);
                    continue;
                }
            }

            // PHASE 2: Check for multi-course combination equivalencies
            // After processing individual courses, check if combinations of courses can exempt additional degree courses
            try {
                Log::info('Checking for multi-course combination equivalencies', [
                    'application_id' => $applicationId,
                    'program_code' => $programCode
                ]);

                // Get all course codes found in the transcript with their grades
                $transcriptCourses = [];
                foreach ($foundCourses as $courseData) {
                    $transcriptCourses[$courseData['code']] = [
                        'grade' => $courseData['grade'],
                        'name' => $courseData['name']
                    ];
                }

                // Query for combined equivalencies - ONLY from PUBLISHED lists
                $combinedEquivalencies = CourseEquivalency::where('program_code', $programCode)
                    ->whereHas('equivalencyList', function($query) {
                        $query->whereNotNull('published_at'); // Only published lists
                    })
                    ->where(function($query) {
                        $query->where('diploma_course_code', 'LIKE', '%/%')
                              ->orWhere('diploma_course_code', 'LIKE', '%+%');
                    })
                    ->get();

                Log::info('Found combined equivalencies', [
                    'count' => $combinedEquivalencies->count(),
                    'application_id' => $applicationId
                ]);

                foreach ($combinedEquivalencies as $combinedEq) {
                    // Parse the combined course code to extract groups
                    // Example: "CSC138/CSC126 + CSC186" means (CSC138 OR CSC126) AND CSC186
                    $parsedGroups = $this->parseCombinedCourseCode($combinedEq->diploma_course_code);

                    Log::info('Checking combined equivalency', [
                        'combined_code' => $combinedEq->diploma_course_code,
                        'degree_course' => $combinedEq->degree_course_code,
                        'parsed_groups' => $parsedGroups,
                        'application_id' => $applicationId
                    ]);

                    // Check if requirements are met based on AND/OR logic
                    $allGroupsSatisfied = true;
                    $allGradesAcceptable = true;
                    $lowestGrade = 'A+';
                    $combinedCourseNames = [];
                    $matchedCourses = []; // Track which courses actually matched
                    $individualGrades = []; // Track individual grades for each matched course

                    foreach ($parsedGroups as $group) {
                        // For each group (connected by "+"), check if at least one course from the "/" alternatives is present
                        $groupSatisfied = false;

                        foreach ($group as $alternativeCode) {
                            if (isset($transcriptCourses[$alternativeCode])) {
                                // Found a match for this group
                                $groupSatisfied = true;
                                $courseGrade = $transcriptCourses[$alternativeCode]['grade'];
                                $matchedCourses[] = $alternativeCode;
                                $combinedCourseNames[] = $transcriptCourses[$alternativeCode]['name'];
                                $individualGrades[$alternativeCode] = $courseGrade; // Store individual grade for each course

                                // Check if grade is acceptable
                                if (!$this->isGradeAcceptable($courseGrade)) {
                                    $allGradesAcceptable = false;
                                    Log::debug('Course grade not acceptable', [
                                        'course_code' => $alternativeCode,
                                        'grade' => $courseGrade
                                    ]);
                                }

                                // Track lowest grade for reporting
                                $gradeGPA = $this->convertGradeToGPA($courseGrade);
                                $lowestGradeGPA = $this->convertGradeToGPA($lowestGrade);
                                if ($gradeGPA < $lowestGradeGPA) {
                                    $lowestGrade = $courseGrade;
                                }

                                // For "/" alternatives, we only need ONE match, so break after finding the first
                                break;
                            }
                        }

                        if (!$groupSatisfied) {
                            $allGroupsSatisfied = false;
                            Log::debug('Required course group not satisfied', [
                                'group_alternatives' => $group,
                                'combined_eq' => $combinedEq->diploma_course_code
                            ]);
                            break;
                        }
                    }

                    // Update the check variable name for clarity
                    $allCoursesFound = $allGroupsSatisfied;

                    // If all required courses are present with acceptable grades, check match percentage
                    if ($allCoursesFound && $allGradesAcceptable) {
                        $matchPercentageOK = $combinedEq->match_percentage > 80;

                        if ($matchPercentageOK) {
                            // Check if this degree course is already exempted by another equivalency
                            // We check the notes field which contains the equivalent_course (degree course code)
                            $existingSubjects = ApplicationSubject::where('exemption_application_id', $applicationId)
                                ->where('status', 'exempted')
                                ->get();

                            $alreadyExempted = false;
                            foreach ($existingSubjects as $subject) {
                                $notes = json_decode($subject->notes, true);
                                if (isset($notes['equivalent_course']) && $notes['equivalent_course'] === $combinedEq->degree_course_code) {
                                    $alreadyExempted = true;
                                    break;
                                }
                            }

                            if (!$alreadyExempted) {
                                // Create ApplicationSubject for the combined exemption
                                $combinedCourseName = implode(' + ', $combinedCourseNames);
                                $exemptionReason = 'All criteria met: Combined courses ' . implode(' & ', $matchedCourses) .
                                                  ', lowest grade ' . $lowestGrade . ' ≥ C, match ' . $combinedEq->match_percentage . '% > 80%';

                                // Determine status based on whether application has been reviewed
                                $subjectStatus = ($application && $application->status === 'Reviewed by Academic Advisor') ? 'Approved' : 'exempted';

                                ApplicationSubject::create([
                                    'exemption_application_id' => $applicationId,
                                    'course_code' => implode(' & ', $matchedCourses), // Use & separator for display
                                    'course_name' => $combinedCourseName,
                                    'credit_hour' => $combinedEq->diploma_credit_hour,
                                    'grade' => $this->convertGradeToGPA($lowestGrade), // Use lowest grade
                                    'status' => $subjectStatus,
                                    'extraction_method' => 'ocr',
                                    'needs_verification' => false,
                                    'ocr_confidence_score' => 95.0,
                                    'exemption_reason' => $exemptionReason,
                                    'notes' => json_encode([
                                        'equivalent_course' => $combinedEq->degree_course_code,
                                        'match_percentage' => $combinedEq->match_percentage,
                                        'degree_course_name' => $combinedEq->degree_course_name,
                                        'required_courses' => $matchedCourses,
                                        'individual_grades' => $individualGrades, // Store individual grades for each course
                                        'combination_type' => 'multi_course',
                                        'original_combined_code' => $combinedEq->diploma_course_code
                                    ])
                                ]);

                                // Add to foundCourses array for display
                                $foundCourses[] = [
                                    'code' => $combinedEq->diploma_course_code,
                                    'name' => $combinedCourseName,
                                    'grade' => $lowestGrade,
                                    'is_equivalent' => true,
                                    'equivalent_to' => $combinedEq->degree_course_code,
                                    'status' => 'Ready for Exemption (Combined)',
                                    'match_percentage' => $combinedEq->match_percentage,
                                    'exemption_reason' => $exemptionReason
                                ];

                                Log::info('Combined exemption created', [
                                    'combined_code' => $combinedEq->diploma_course_code,
                                    'degree_course' => $combinedEq->degree_course_code,
                                    'required_courses' => $requiredCodes,
                                    'application_id' => $applicationId
                                ]);
                            } else {
                                Log::info('Degree course already exempted by another equivalency', [
                                    'degree_course' => $combinedEq->degree_course_code,
                                    'application_id' => $applicationId
                                ]);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error checking combined equivalencies: ' . $e->getMessage(), [
                    'application_id' => $applicationId,
                    'program_code' => $programCode,
                    'trace' => $e->getTraceAsString()
                ]);
                // Don't fail the entire process if combination checking fails
            }

            Log::info('Course matching completed', [
                'application_id' => $applicationId,
                'program_code' => $programCode,
                'total_courses_found' => count($foundCourses),
                'eligible_courses' => array_filter($foundCourses, function($course) {
                    return $course['is_equivalent'];
                })
            ]);

            return $foundCourses;
        } catch (\Exception $e) {
            Log::error('Error in course matching: ' . $e->getMessage(), [
                'application_id' => $applicationId,
                'program_code' => $programCode
            ]);
            return [];
        }
    }

    /**
     * Check if grade meets minimum requirement (C or above).
     * Accepts: A+, A, A-, B+, B, B-, C+, C
     * Rejects: C-, D+, D, F
     */
    private function isGradeAcceptable($grade)
    {
        $acceptableGrades = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'];
        return in_array($grade, $acceptableGrades);
    }

    /**
     * Extract credit hours from course name (basic implementation).
     */
    private function extractCreditHours($courseName)
    {
        // Look for credit hour patterns in course name
        if (preg_match('/\((\d+)\s*credits?\)/i', $courseName, $matches)) {
            return (int) $matches[1];
        }
        
        // Default to 3 credit hours if not found
        return 3;
    }

    /**
     * Convert letter grade to GPA value.
     */
    private function convertGradeToGPA($grade)
    {
        $gradeMap = [
            'A+' => 4.00, 'A' => 4.00, 'A-' => 3.67,
            'B+' => 3.33, 'B' => 3.00, 'B-' => 2.67,
            'C+' => 2.33, 'C' => 2.00, 'C-' => 1.67,
            'D+' => 1.33, 'D' => 1.00, 'F' => 0.00
        ];
        
        return $gradeMap[$grade] ?? null;
    }

    /**
     * Convert GPA value back to letter grade.
     */
    private function convertGPAToGrade($gpa)
    {
        $gpaMap = [
            4.00 => 'A', 3.67 => 'A-', 3.33 => 'B+', 3.00 => 'B', 2.67 => 'B-',
            2.33 => 'C+', 2.00 => 'C', 1.67 => 'C-', 1.33 => 'D+', 1.00 => 'D', 0.00 => 'F'
        ];

        return $gpaMap[$gpa] ?? 'Unknown';
    }

    /**
     * Parse combined course code into groups with OR alternatives.
     * Returns array of groups, where each group is connected by AND (+),
     * and within each group, courses are connected by OR (/).
     *
     * Examples:
     * - "CSC138/CSC126 + CSC186" → [['CSC138', 'CSC126'], ['CSC186']]
     *   Means: (CSC138 OR CSC126) AND CSC186
     * - "STA416/MAT133" → [['STA416', 'MAT133']]
     *   Means: STA416 OR MAT133
     * - "CSC138/CSC126+CSC186" → [['CSC138', 'CSC126'], ['CSC186']]
     *
     * Logic:
     * - "+" connects groups (AND logic between groups)
     * - "/" connects alternatives within a group (OR logic within group)
     * - For the combination to match, we need at least ONE course from EACH group
     */
    private function parseCombinedCourseCode($combinedCode)
    {
        // Split by "+" to get groups that must ALL be satisfied (AND logic)
        $plusGroups = preg_split('/\s*\+\s*/', $combinedCode);

        $parsedGroups = [];

        foreach ($plusGroups as $group) {
            // Within each group, split by "/" to get alternatives (OR logic)
            $orAlternatives = preg_split('/\s*\/\s*/', $group);

            // Trim whitespace from each alternative
            $orAlternatives = array_map('trim', $orAlternatives);

            // Add this group of alternatives to our result
            $parsedGroups[] = $orAlternatives;
        }

        return $parsedGroups;
    }

    /**
     * Get user-friendly status display text.
     */
    private function getStatusDisplayText($status)
    {
        $statusMap = [
            'exempted' => 'Ready for Exemption',
            'not_found' => 'Not Found in Database',
            'not_eligible_grade' => 'Grade Too Low',
            'not_eligible_match' => 'Low Match Percentage',
            'not_eligible' => 'Not Eligible'
        ];
        
        return $statusMap[$status] ?? 'Unknown Status';
    }

    private function getWorkflowStatusText($status)
    {
        $workflowMap = [
            // OCR analysis statuses
            'exempted' => 'Pending Lecturer Approval',
            'not_found' => 'Pending Lecturer Review',
            'not_eligible_grade' => 'Pending Lecturer Review', 
            'not_eligible_match' => 'Pending Lecturer Review',
            'not_eligible' => 'Pending Lecturer Review',
            
            // Lecturer decision statuses
            'Approved' => 'Approved by Lecturer',
            'Rejected' => 'Rejected by Lecturer',
            'Forward to Coordinator' => 'Under Coordinator Review'
        ];
        
        return $workflowMap[$status] ?? 'Under Review';
    }

    /**
     * Display the student's application status and OCR results.
     */
    public function status()
    {
        $student = Auth::user()->student;
        $applications = ExemptionApplication::where('student_id', $student->id)
                                                      ->with(['transcript', 'applicationSubjects', 'reviewer'])
                                                      ->orderBy('created_at', 'desc')
                                                      ->get();
        
        // Build OCR results from database instead of session
        $ocrResults = [];
        if ($applications->isNotEmpty()) {
            $latestApplication = $applications->first();
            $ocrResults = $latestApplication->applicationSubjects->map(function($subject) {
                $notes = $subject->notes ? json_decode($subject->notes, true) : null;
                return [
                    'code' => $subject->course_code,
                    'name' => $subject->course_name,
                    'grade' => $this->convertGPAToGrade($subject->grade),
                    'is_equivalent' => $subject->status === 'exempted',
                    'equivalent_to' => $notes ? ($notes['equivalent_course'] ?? 'N/A') : 'N/A',
                    'status' => $this->getStatusDisplayText($subject->status),
                    'workflow_status' => $this->getWorkflowStatusText($subject->status),
                    'match_percentage' => $notes ? ($notes['match_percentage'] ?? 0) : 0,
                    'exemption_reason' => $subject->exemption_reason
                ];
            })->toArray();
        }
        
        return view('student.application.status', compact('applications', 'ocrResults'));
    }

    /**
     * Display the student's transcript file.
     */
    public function viewTranscript(ExemptionApplication $application)
    {
        try {
            // Log the access attempt for debugging
            Log::info('Transcript access attempt', [
                'application_id' => $application->id,
                'user_id' => Auth::id(),
                'student_id' => Auth::user()->student->id ?? null
            ]);

            // Verify ownership - critical security check
            if ($application->student_id !== Auth::user()->student->id) {
                Log::warning('Unauthorized transcript access attempt', [
                    'application_id' => $application->id,
                    'application_student_id' => $application->student_id,
                    'requesting_student_id' => Auth::user()->student->id ?? null
                ]);
                abort(403, 'Unauthorized access to transcript');
            }
            
            $transcript = $application->transcript;
            if (!$transcript) {
                Log::error('Transcript record not found', [
                    'application_id' => $application->id
                ]);
                abort(404, 'Transcript not found for this application');
            }
            
            // Check multiple possible file locations due to storage configuration changes
            $filePath = null;
            
            // First try the private disk (new storage location)
            if (Storage::disk('private')->exists($transcript->file_path)) {
                $filePath = Storage::disk('private')->path($transcript->file_path);
            }
            // Then try the default disk (old storage location) 
            elseif (Storage::exists($transcript->file_path)) {
                $filePath = Storage::path($transcript->file_path);
            }
            // Finally try the legacy path with private subdirectory
            else {
                $legacyPath = 'private/' . $transcript->file_path;
                if (file_exists(storage_path('app/' . $legacyPath))) {
                    $filePath = storage_path('app/' . $legacyPath);
                }
            }
            
            if (!$filePath || !file_exists($filePath)) {
                Log::error('Transcript file not found in any storage location', [
                    'file_path' => $transcript->file_path,
                    'transcript_id' => $transcript->id,
                    'checked_locations' => [
                        'private_disk' => Storage::disk('private')->path($transcript->file_path),
                        'default_disk' => Storage::path($transcript->file_path),
                        'legacy_path' => storage_path('app/private/' . $transcript->file_path)
                    ]
                ]);
                abort(404, 'Transcript file not found on server');
            }
            
            Log::info('Attempting to serve transcript file', [
                'file_path' => $filePath,
                'original_filename' => $transcript->original_filename
            ]);
            
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $transcript->original_filename . '"'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error serving transcript', [
                'error' => $e->getMessage(),
                'application_id' => $application->id ?? null
            ]);
            abort(500, 'Error serving transcript file');
        }
    }

    /**
     * AJAX endpoint to check course equivalency for manual entry
     * Supports institution-aware matching for non-UiTM students
     */
    public function checkEquivalency(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:10',
            'program_code' => 'required|string|max:10',
            'institution' => 'nullable|string|max:255',
            'institution_type' => 'nullable|string|in:uitm,non_uitm'
        ]);

        $courseCode = strtoupper(trim($validated['course_code']));
        $programCode = trim($validated['program_code']);
        $institution = $validated['institution'] ?? null;
        $institutionType = $validated['institution_type'] ?? 'uitm';

        // Use institution-aware matching for non-UiTM students
        if ($institutionType === 'non_uitm' && $institution) {
            $equivalency = $this->findEquivalencyForNonUiTM($courseCode, $programCode, $institution);
        } else {
            // Standard UiTM matching - ONLY from PUBLISHED lists
            $equivalency = CourseEquivalency::where('diploma_course_code', $courseCode)
                                          ->where('program_code', $programCode)
                                          ->whereHas('equivalencyList', function($query) {
                                              $query->whereNotNull('published_at'); // Only published lists
                                          })
                                          ->first();
        }

        if ($equivalency) {
            return response()->json([
                'found' => true,
                'degree_course_code' => $equivalency->degree_course_code,
                'degree_course_name' => $equivalency->degree_course_name,
                'match_percentage' => $equivalency->match_percentage,
                'diploma_credit_hour' => $equivalency->diploma_credit_hour,
                'diploma_institution' => $equivalency->diploma_institution,
                'message' => $institutionType === 'non_uitm'
                    ? "Found in {$equivalency->diploma_institution} equivalency database"
                    : null
            ]);
        }

        return response()->json([
            'found' => false,
            'degree_course_code' => null,
            'degree_course_name' => null,
            'match_percentage' => 0,
            'diploma_credit_hour' => null,
            'message' => $institutionType === 'non_uitm' && $institution
                ? "Not found in {$institution} equivalency database - requires manual review"
                : null
        ]);
    }

    /**
     * Find equivalency for non-UiTM students with institution-aware matching
     */
    private function findEquivalencyForNonUiTM($courseCode, $programCode, $institution)
    {
        // Try exact institution match first - ONLY from PUBLISHED lists
        $equivalency = CourseEquivalency::where('diploma_course_code', $courseCode)
            ->where('program_code', $programCode)
            ->where('diploma_institution', $institution)
            ->whereHas('equivalencyList', function($query) {
                $query->whereNotNull('published_at'); // Only published lists
            })
            ->first();

        if ($equivalency) {
            return $equivalency;
        }

        // Try partial institution match using keyword extraction - ONLY from PUBLISHED lists
        $institutionKeyword = $this->extractInstitutionKeyword($institution);

        if ($institutionKeyword) {
            $equivalency = CourseEquivalency::where('diploma_course_code', $courseCode)
                ->where('program_code', $programCode)
                ->where('diploma_institution', 'LIKE', "%{$institutionKeyword}%")
                ->whereHas('equivalencyList', function($query) {
                    $query->whereNotNull('published_at'); // Only published lists
                })
                ->first();

            if ($equivalency) {
                return $equivalency;
            }
        }

        // Fallback: Try matching without institution - ONLY from PUBLISHED lists
        return CourseEquivalency::where('diploma_course_code', $courseCode)
            ->where('program_code', $programCode)
            ->whereHas('equivalencyList', function($query) {
                $query->whereNotNull('published_at'); // Only published lists
            })
            ->first();
    }

    /**
     * Extract key identifier from institution name for fuzzy matching
     */
    private function extractInstitutionKeyword($institution)
    {
        // Common institution type keywords
        $keywords = [
            'Politeknik' => 'Politeknik',
            'Polytechnic' => 'Politeknik',
            'UTM' => 'UTM',
            'Universiti Teknologi Malaysia' => 'UTM',
            'UiTM' => 'UiTM',
            'Universiti Teknologi MARA' => 'UiTM',
            'MMU' => 'MMU',
            'Multimedia University' => 'MMU',
            'GMI' => 'GMI',
            'German Malaysia Institute' => 'GMI',
            'UPSI' => 'UPSI',
            'Kolej' => 'Kolej',
            'College' => 'Kolej',
            'KPTM' => 'KPTM',
            'IPT' => 'IPT',
        ];

        foreach ($keywords as $pattern => $keyword) {
            if (stripos($institution, $pattern) !== false) {
                return $keyword;
            }
        }

        // Return first word if no keyword match (e.g., "Politeknik Kuala Lumpur" -> "Politeknik")
        $words = explode(' ', $institution);
        return $words[0] ?? null;
    }

    /**
     * Process a manually entered course and create ApplicationSubject record
     * Supports institution-aware matching for non-UiTM students
     */
    private function processManualCourse($applicationId, $courseData, $programCode, $institution = null, $institutionType = 'uitm')
    {
        $courseCode = strtoupper(trim($courseData['code']));
        $courseName = trim($courseData['name']);
        $grade = $courseData['grade'];
        $gradeGPA = $courseData['gradeGPA'];
        $creditHours = $courseData['creditHours'];

        // Look up equivalency with institution-aware matching - ONLY from PUBLISHED lists
        if ($institutionType === 'non_uitm' && $institution) {
            $equivalency = $this->findEquivalencyForNonUiTM($courseCode, $programCode, $institution);
        } else {
            $equivalency = CourseEquivalency::where('diploma_course_code', $courseCode)
                                          ->where('program_code', $programCode)
                                          ->whereHas('equivalencyList', function($query) {
                                              $query->whereNotNull('published_at'); // Only published lists
                                          })
                                          ->first();
        }

        // Apply three-criteria validation
        $courseFound = $equivalency ? true : false;
        $gradeAcceptable = $this->isGradeAcceptable($grade);
        $matchPercentageOK = $equivalency && $equivalency->match_percentage > 80;

        // Determine status and exemption reason
        if (!$courseFound) {
            $status = 'not_found';
            $exemptionReason = "Course not found in {$programCode} equivalency database";
        } elseif (!$gradeAcceptable) {
            $status = 'not_eligible_grade';
            $exemptionReason = "Grade {$grade} is below minimum requirement (C required)";
        } elseif (!$matchPercentageOK) {
            $status = 'not_eligible_match';
            $exemptionReason = "Match percentage {$equivalency->match_percentage}% is below 80% threshold";
        } else {
            $status = 'exempted';
            $exemptionReason = "All criteria met: Course found, grade {$grade} ≥ C, match {$equivalency->match_percentage}% > 80%";
        }

        // Prepare notes JSON
        $notes = [];
        if ($equivalency) {
            $notes = [
                'equivalent_course' => $equivalency->degree_course_code,
                'match_percentage' => $equivalency->match_percentage,
                'degree_course_name' => $equivalency->degree_course_name
            ];
        }

        // Create ApplicationSubject record
        ApplicationSubject::create([
            'exemption_application_id' => $applicationId,
            'course_code' => $courseCode,
            'course_name' => $courseName,
            'grade' => $gradeGPA,
            'credit_hour' => $creditHours,
            'status' => $status,
            'extraction_method' => 'manual',
            'ocr_confidence_score' => null, // Not applicable for manual entry
            'needs_verification' => !($courseFound && $gradeAcceptable && $matchPercentageOK),
            'exemption_reason' => $exemptionReason,
            'notes' => !empty($notes) ? json_encode($notes) : null
        ]);

        Log::info('Manual course processed', [
            'application_id' => $applicationId,
            'course_code' => $courseCode,
            'status' => $status
        ]);
    }

    /**
     * Basic LSB Steganography Detection (Proof of Concept)
     */
    private function hasHiddenData($pdfPath)
    {
        return false; // For now, always assume the file is clean.
    }

    /**
     * View Course Validation PDF
     */
    public function viewCourseValidation(ExemptionApplication $application)
    {
        try {
            // Verify ownership - critical security check
            if ($application->student_id !== Auth::user()->student->id) {
                Log::warning('Unauthorized course validation access attempt', [
                    'application_id' => $application->id,
                    'application_student_id' => $application->student_id,
                    'requesting_student_id' => Auth::user()->student->id ?? null
                ]);
                abort(403, 'Unauthorized access to course validation');
            }

            // Check if application has been reviewed
            if ($application->status !== 'Reviewed by Academic Advisor') {
                return back()->with('error', 'Course validation is only available after academic advisor review.');
            }

            $pdfData = $this->prepareCourseValidationData($application);

            $pdf = Pdf::loadView('student.application.validation-pdf', $pdfData);
            $pdf->setPaper('a4', 'portrait');

            return $pdf->stream('course-validation-' . $application->matric_no . '.pdf');

        } catch (\Exception $e) {
            Log::error('Error viewing course validation', [
                'error' => $e->getMessage(),
                'application_id' => $application->id ?? null
            ]);
            return back()->with('error', 'Error generating course validation PDF.');
        }
    }

    /**
     * Download Course Validation PDF
     */
    public function downloadCourseValidation(ExemptionApplication $application)
    {
        try {
            // Verify ownership - critical security check
            if ($application->student_id !== Auth::user()->student->id) {
                Log::warning('Unauthorized course validation download attempt', [
                    'application_id' => $application->id,
                    'application_student_id' => $application->student_id,
                    'requesting_student_id' => Auth::user()->student->id ?? null
                ]);
                abort(403, 'Unauthorized access to course validation');
            }

            // Check if application has been reviewed
            if ($application->status !== 'Reviewed by Academic Advisor') {
                return back()->with('error', 'Course validation is only available after academic advisor review.');
            }

            $pdfData = $this->prepareCourseValidationData($application);

            $pdf = Pdf::loadView('student.application.validation-pdf', $pdfData);
            $pdf->setPaper('a4', 'portrait');

            return $pdf->download('course-validation-' . $application->matric_no . '.pdf');

        } catch (\Exception $e) {
            Log::error('Error downloading course validation', [
                'error' => $e->getMessage(),
                'application_id' => $application->id ?? null
            ]);
            return back()->with('error', 'Error downloading course validation PDF.');
        }
    }

    /**
     * Prepare data for Course Validation PDF
     */
    private function prepareCourseValidationData(ExemptionApplication $application)
    {
        // Load reviewer relationship if not already loaded
        $application->load('reviewer');

        $student = $application->student;
        $subjects = $application->applicationSubjects;

        // Get exempted/approved subjects only
        $exemptedSubjects = $subjects->filter(function($subject) {
            return $subject->status === 'exempted' || $subject->status === 'Approved';
        });

        // Calculate statistics
        $totalCourses = $subjects->count();

        // Define correct credit hours for degree courses
        $degreeCreditHours = [
            'CSC402' => 3,
            'CSC413' => 3,
            'CSC429' => 3,
            'CSC435' => 3,
            'CSC404' => 3,
            'ICT450' => 3,
            'STA416' => 3,
            'ITT400' => 3,
            'MAT406' => 3,
            'MAT421' => 3,
            'CSC574' => 3,
        ];

        // Count unique degree courses (excluding co-curricular HXX courses)
        $academicDegreeCourses = [];
        $totalCredits = 0;

        foreach ($exemptedSubjects as $subject) {
            $notes = $subject->notes ? json_decode($subject->notes, true) : null;
            $equivalentCourse = $notes['equivalent_course'] ?? null;

            // Skip co-curricular courses (starting with HXX)
            if ($equivalentCourse && (str_starts_with($equivalentCourse, 'HXX') || $equivalentCourse === 'HXXXXX')) {
                continue;
            }

            // Use correct credit hour from mapping, fallback to 3 if not found
            $creditHour = $degreeCreditHours[$equivalentCourse] ?? 3;

            if ($equivalentCourse && !in_array($equivalentCourse, $academicDegreeCourses)) {
                $academicDegreeCourses[] = $equivalentCourse;
                $totalCredits += $creditHour;
            }
        }

        $exemptedCount = count($academicDegreeCourses);
        $notExemptedCount = $totalCourses - $exemptedSubjects->count();

        // Determine current session
        $currentMonth = date('n');
        if ($currentMonth >= 9 || $currentMonth <= 2) {
            $session = '1 ' . date('Y') . '/' . (date('Y') + 1);
        } else {
            $session = '2 ' . (date('Y') - 1) . '/' . date('Y');
        }

        // Determine process status text
        $processStatus = 'Course Registration Validated';
        if ($application->status === 'Reviewed by Academic Advisor') {
            $processStatus = '4C - Course Registration Validated';
        }

        // Status text for courses
        $statusText = 'B';

        return [
            'application' => $application,
            'student' => $student,
            'exemptedSubjects' => $exemptedSubjects,
            'totalCourses' => $totalCourses,
            'exemptedCount' => $exemptedCount,
            'notExemptedCount' => $notExemptedCount,
            'totalCredits' => $totalCredits,
            'session' => $session,
            'processStatus' => $processStatus,
            'statusText' => $statusText,
            'advisor' => $application->reviewer, // Academic Advisor who reviewed the application
        ];
    }
}
