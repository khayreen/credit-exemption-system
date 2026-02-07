<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CDCS251CourseSeeder extends Seeder
{
    /**
     * CDCS251 - Bachelor of Computer Science (Hons.) Netcentric Computing
     * Full curriculum - 43 courses
     */
    public function run(): void
    {
        $courses = [
            // Part 01 (Semester 1)
            ['code' => 'CSC402', 'name' => 'Programming I', 'credit_hour' => 3.0, 'semester' => 1, 'type' => 'core'],
            ['code' => 'CSC413', 'name' => 'Introduction to Interactive Multimedia', 'credit_hour' => 3.0, 'semester' => 1, 'type' => 'core'],
            ['code' => 'CSC429', 'name' => 'Computer Organization and Architecture', 'credit_hour' => 3.0, 'semester' => 1, 'type' => 'core'],
            ['code' => 'CTU552', 'name' => 'Philosophy and Current Issues', 'credit_hour' => 2.0, 'semester' => 1, 'type' => 'core'],
            ['code' => 'HBU111', 'name' => 'National Kesatria I', 'credit_hour' => 1.0, 'semester' => 1, 'type' => 'elective'],
            ['code' => 'ITT400', 'name' => 'Introduction to Data Communication and Networking', 'credit_hour' => 3.0, 'semester' => 1, 'type' => 'core'],
            ['code' => 'MAT406', 'name' => 'Foundation Mathematics', 'credit_hour' => 3.0, 'semester' => 1, 'type' => 'core'],

            // Part 02 (Semester 2)
            ['code' => 'CSC404', 'name' => 'Programming II', 'credit_hour' => 3.0, 'semester' => 2, 'type' => 'core'],
            ['code' => 'CSC520', 'name' => 'Principles of Operating Systems', 'credit_hour' => 3.0, 'semester' => 2, 'type' => 'core'],
            ['code' => 'CSC574', 'name' => 'Dynamic Web Application Development', 'credit_hour' => 3.0, 'semester' => 2, 'type' => 'core'],
            ['code' => 'HBU121', 'name' => 'National Kesatria II', 'credit_hour' => 1.0, 'semester' => 2, 'type' => 'elective'],
            ['code' => 'ICT450', 'name' => 'Database Design and Development', 'credit_hour' => 3.0, 'semester' => 2, 'type' => 'core'],
            ['code' => 'MAT421', 'name' => 'Calculus I', 'credit_hour' => 3.0, 'semester' => 2, 'type' => 'core'],
            ['code' => 'STA416', 'name' => 'Applied Probability and Statistics', 'credit_hour' => 3.0, 'semester' => 2, 'type' => 'core'],

            // Part 03 (Semester 3)
            ['code' => 'CSC435', 'name' => 'Object-Oriented Programming', 'credit_hour' => 3.0, 'semester' => 3, 'type' => 'core'],
            ['code' => 'CSC510', 'name' => 'Discrete Structures', 'credit_hour' => 3.0, 'semester' => 3, 'type' => 'core'],
            ['code' => 'CTU554', 'name' => 'Values and Civilization II', 'credit_hour' => 2.0, 'semester' => 3, 'type' => 'core'],
            ['code' => 'HBU131', 'name' => 'National Kesatria III', 'credit_hour' => 1.0, 'semester' => 3, 'type' => 'elective'],
            ['code' => 'ITT450', 'name' => 'Information and Network Security', 'credit_hour' => 3.0, 'semester' => 3, 'type' => 'core'],
            ['code' => 'ITT501', 'name' => 'Netcentric Fundamentals', 'credit_hour' => 3.0, 'semester' => 3, 'type' => 'core'],
            ['code' => 'ITT531', 'name' => 'Principles of Networking', 'credit_hour' => 3.0, 'semester' => 3, 'type' => 'core'],
            ['code' => 'TAC401', 'name' => 'Introductory Arabic (Level I)', 'credit_hour' => 2.0, 'semester' => 3, 'type' => 'elective'],

            // Part 04 (Semester 4)
            ['code' => 'ISP542', 'name' => 'Fundamentals of Artificial Intelligence', 'credit_hour' => 3.0, 'semester' => 4, 'type' => 'core'],
            ['code' => 'ITT545', 'name' => 'Web Engineering', 'credit_hour' => 3.0, 'semester' => 4, 'type' => 'core'],
            ['code' => 'ITT557', 'name' => 'Web Application Security', 'credit_hour' => 3.0, 'semester' => 4, 'type' => 'elective'],
            ['code' => 'ITT569', 'name' => 'Internet of Things (IoT)', 'credit_hour' => 3.0, 'semester' => 4, 'type' => 'elective'],
            ['code' => 'ITT588', 'name' => 'Front-End Technology', 'credit_hour' => 3.0, 'semester' => 4, 'type' => 'core'],
            ['code' => 'LCC401', 'name' => 'English for Mediating Texts', 'credit_hour' => 2.0, 'semester' => 4, 'type' => 'core'],
            ['code' => 'TAC451', 'name' => 'Introductory Arabic (Level II)', 'credit_hour' => 2.0, 'semester' => 4, 'type' => 'elective'],

            // Part 05 (Semester 5)
            ['code' => 'CSP600', 'name' => 'Project Formulation', 'credit_hour' => 3.0, 'semester' => 5, 'type' => 'core'],
            ['code' => 'ICT602', 'name' => 'Mobile Technology and Development', 'credit_hour' => 3.0, 'semester' => 5, 'type' => 'core'],
            ['code' => 'ITT550', 'name' => 'Network Design and Management', 'credit_hour' => 3.0, 'semester' => 5, 'type' => 'elective'],
            ['code' => 'ITT626', 'name' => 'Back-End Technology', 'credit_hour' => 3.0, 'semester' => 5, 'type' => 'core'],
            ['code' => 'ITT661', 'name' => 'Emergent Technology', 'credit_hour' => 3.0, 'semester' => 5, 'type' => 'elective'],
            ['code' => 'LCC500', 'name' => 'English for Workplace Communication', 'credit_hour' => 2.0, 'semester' => 5, 'type' => 'core'],
            ['code' => 'TAC501', 'name' => 'Introductory Arabic (Level III)', 'credit_hour' => 2.0, 'semester' => 5, 'type' => 'elective'],

            // Part 06 (Semester 6)
            ['code' => 'CSP650', 'name' => 'Project', 'credit_hour' => 6.0, 'semester' => 6, 'type' => 'core'],
            ['code' => 'EET699', 'name' => 'English Exit Test', 'credit_hour' => 0.0, 'semester' => 6, 'type' => 'core'],
            ['code' => 'ENT600', 'name' => 'Technology Entrepreneurship', 'credit_hour' => 3.0, 'semester' => 6, 'type' => 'core'],
            ['code' => 'ICT652', 'name' => 'Ethical, Social, and Professional Issues in ICT', 'credit_hour' => 3.0, 'semester' => 6, 'type' => 'core'],
            ['code' => 'ITT420', 'name' => 'Network and System Administration', 'credit_hour' => 3.0, 'semester' => 6, 'type' => 'core'],
            ['code' => 'ITT632', 'name' => 'Mobile Cloud Computing', 'credit_hour' => 3.0, 'semester' => 6, 'type' => 'core'],

            // Part 07 (Semester 7)
            ['code' => 'CST688', 'name' => 'Computing Science Industrial Training', 'credit_hour' => 7.0, 'semester' => 7, 'type' => 'core'],
        ];

        $programCode = 'CDCS251';

        foreach ($courses as $courseData) {
            Course::updateOrCreate(
                [
                    'code' => $courseData['code'],
                ],
                [
                    'name' => $courseData['name'],
                    'credit_hour' => $courseData['credit_hour'],
                    'semester' => $courseData['semester'],
                    'type' => $courseData['type'],
                    'program_code' => $programCode,
                ]
            );
        }

        $this->command->info("Imported 43 courses for CDCS251 - Netcentric Computing");
    }
}
