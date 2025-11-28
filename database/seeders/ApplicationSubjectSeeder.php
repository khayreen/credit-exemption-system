<?php

namespace Database\Seeders;

use App\Models\ApplicationSubject;
use App\Models\ExemptionApplication;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApplicationSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First check if we have any exemption applications
        $application = ExemptionApplication::first();
        
        if (!$application) {
            // Create a test student and application if none exist
            $user = User::firstOrCreate(
                ['email' => 'student@test.com'],
                [
                    'name' => 'Test Student',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'role' => 'student',
                ]
            );

            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'matric_no' => 'STU001',
                    'ic_number' => '990101011234',
                    'program_name' => 'Bachelor of Computer Science',
                    'campus' => 'Shah Alam',
                    'intake_semester' => '2023/2024',
                ]
            );

            $application = ExemptionApplication::create([
                'student_id' => $student->id,
                'previous_institution' => 'Politeknik Shah Alam',
                'previous_program' => 'Diploma in Computer Science',
                'status' => 'Submitted',
                'student_name' => 'Test Student',
                'matric_no' => 'STU001',
                'ic_number' => '990101011234',
                'current_program' => 'CS251',
                'current_campus' => 'UiTM Shah Alam',
                'current_faculty' => 'Faculty of Computer and Mathematical Sciences',
            ]);
        }

        // Create sample application subjects for review
        $subjects = [
            [
                'course_code' => 'CSC119',
                'course_name' => 'Introduction to Programming',
                'credit_hour' => 4,
                'grade' => 3.67,
                'status' => 'Pending Resource Person',
            ],
            [
                'course_code' => 'CSC148',
                'course_name' => 'Object Oriented Programming',
                'credit_hour' => 4,
                'grade' => 3.33,
                'status' => 'Pending Resource Person',
            ],
            [
                'course_code' => 'CSC235',
                'course_name' => 'Database Systems',
                'credit_hour' => 3,
                'grade' => 3.50,
                'status' => 'Syllabus Received',
            ],
            [
                'course_code' => 'CSC264',
                'course_name' => 'Data Structures and Algorithms',
                'credit_hour' => 4,
                'grade' => 3.00,
                'status' => 'Pending Resource Person',
            ],
            [
                'course_code' => 'CSC305',
                'course_name' => 'Web Development',
                'credit_hour' => 3,
                'grade' => 3.75,
                'status' => 'Syllabus Received',
            ],
        ];

        foreach ($subjects as $subjectData) {
            ApplicationSubject::create([
                'exemption_application_id' => $application->id,
                'course_code' => $subjectData['course_code'],
                'course_name' => $subjectData['course_name'],
                'credit_hour' => $subjectData['credit_hour'],
                'grade' => $subjectData['grade'],
                'status' => $subjectData['status'],
            ]);
        }

        $this->command->info('Application subjects seeded successfully. Total: ' . count($subjects));
    }
}