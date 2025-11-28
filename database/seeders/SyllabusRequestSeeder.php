<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApplicationSubject;
use App\Models\ExemptionApplication;
use App\Models\Student;
use Illuminate\Support\Str;

class SyllabusRequestSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get the first student and their application for testing
        $student = Student::first();
        
        if (!$student) {
            echo "No students found. Please run student seeder first.\n";
            return;
        }

        $application = ExemptionApplication::where('student_id', $student->id)->first();
        
        if (!$application) {
            // Create a test application if none exists
            $application = ExemptionApplication::create([
                'id' => Str::uuid(),
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'matric_no' => $student->matric_no,
                'ic_number' => $student->ic_number,
                'current_program' => $student->program_name,
                'current_program_code' => 'CS251',
                'current_campus' => $student->campus,
                'previous_institution' => 'Metropolitan University College',
                'previous_program' => 'Diploma in Computer Science',
                'status' => 'Under Review',
            ]);
        }

        // Create some test syllabus requests
        $syllabusRequests = [
            [
                'course_code' => 'DCS101',
                'course_name' => 'Introduction to Programming',
                'credit_hour' => 3,
                'grade' => 3.67,
                'status' => 'Syllabus Requested'
            ],
            [
                'course_code' => 'DCS201',
                'course_name' => 'Database Management Systems',
                'credit_hour' => 4,
                'grade' => 3.33,
                'status' => 'Syllabus Requested'
            ],
            [
                'course_code' => 'DCS301',
                'course_name' => 'Software Engineering Principles',
                'credit_hour' => 3,
                'grade' => 3.50,
                'status' => 'Syllabus Requested'
            ],
            [
                'course_code' => 'DMT102',
                'course_name' => 'Discrete Mathematics',
                'credit_hour' => 3,
                'grade' => 3.00,
                'status' => 'Pending Resource Person'
            ]
        ];

        foreach ($syllabusRequests as $request) {
            ApplicationSubject::create([
                'id' => Str::uuid(),
                'exemption_application_id' => $application->id,
                'course_code' => $request['course_code'],
                'course_name' => $request['course_name'],
                'credit_hour' => $request['credit_hour'],
                'grade' => $request['grade'],
                'status' => $request['status'],
                'extraction_method' => 'manual',
                'notes' => 'Test syllabus request for external lecturer demo',
            ]);
        }

        echo "Created test syllabus requests:\n";
        echo "- DCS101: Introduction to Programming (Syllabus Requested)\n";
        echo "- DCS201: Database Management Systems (Syllabus Requested)\n";
        echo "- DCS301: Software Engineering Principles (Syllabus Requested)\n";
        echo "- DMT102: Discrete Mathematics (Pending Resource Person)\n";
    }
}