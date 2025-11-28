<?php

namespace Database\Seeders;

use App\Models\CourseEquivalency;
use App\Models\User;
use Illuminate\Database\Seeder;

class CS251CourseEquivalencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a resource person user for approval
        $resourcePersonUser = User::where('role', 'resource_person')->first();
        if (!$resourcePersonUser) {
            // Create a test resource person if none exists
            $resourcePersonUser = User::create([
                'name' => 'Resource Person',
                'email' => 'rp@test.com',
                'password' => bcrypt('password'),
                'role' => 'resource_person',
                'email_verified_at' => now(),
            ]);
        }

        // CS251 Course Equivalencies based on the document
        $cs251Equivalencies = [
            [
                'diploma_course_code' => 'CSC402',
                'diploma_course_name' => 'Programming I',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS131',
                'degree_course_name' => 'Programming I',
                'degree_credit_hour' => 4,
                'match_percentage' => 85.0,
            ],
            [
                'diploma_course_code' => 'CSC413',
                'diploma_course_name' => 'Introduction to Interactive Multimedia',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS239',
                'degree_course_name' => 'Computer Graphics',
                'degree_credit_hour' => 3,
                'match_percentage' => 80.0,
            ],
            [
                'diploma_course_code' => 'CSC429',
                'diploma_course_name' => 'Computer Architecture & Organization',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS230',
                'degree_course_name' => 'Computer Organization and Architecture',
                'degree_credit_hour' => 3,
                'match_percentage' => 90.0,
            ],
            [
                'diploma_course_code' => 'CSC435',
                'diploma_course_name' => 'Object Oriented Programming',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS232',
                'degree_course_name' => 'Object Oriented Programming',
                'degree_credit_hour' => 4,
                'match_percentage' => 95.0,
            ],
            [
                'diploma_course_code' => 'CSC138/CSC126 + CSC186',
                'diploma_course_name' => 'Structure Programming (Combined)',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC404',
                'degree_course_name' => 'Programming II',
                'degree_credit_hour' => 3,
                'match_percentage' => 90.0,
            ],
            [
                'diploma_course_code' => 'ICT450',
                'diploma_course_name' => 'Database Design and Development',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS233',
                'degree_course_name' => 'Database Systems',
                'degree_credit_hour' => 4,
                'match_percentage' => 88.0,
            ],
            [
                'diploma_course_code' => 'STA416/MAT133',
                'diploma_course_name' => 'Applied Probability & Statistics',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS141',
                'degree_course_name' => 'Discrete Structure',
                'degree_credit_hour' => 3,
                'match_percentage' => 75.0,
            ],
            [
                'diploma_course_code' => 'ITT400',
                'diploma_course_name' => 'Intro to Data Communication & Networking',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS235',
                'degree_course_name' => 'Computer Networks',
                'degree_credit_hour' => 3,
                'match_percentage' => 85.0,
            ],
            [
                'diploma_course_code' => 'MAT406',
                'diploma_course_name' => 'Foundation Mathematics',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS141',
                'degree_course_name' => 'Discrete Structure',
                'degree_credit_hour' => 3,
                'match_percentage' => 70.0,
            ],
            [
                'diploma_course_code' => 'MAT421',
                'diploma_course_name' => 'Calculus I',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS141',
                'degree_course_name' => 'Discrete Structure',
                'degree_credit_hour' => 3,
                'match_percentage' => 80.0,
            ],
            [
                'diploma_course_code' => 'CSC574',
                'diploma_course_name' => 'Dynamic Web Application Development',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS246',
                'degree_course_name' => 'Web Technology',
                'degree_credit_hour' => 3,
                'match_percentage' => 92.0,
            ],
            // Additional equivalencies for commonly seen UiTM courses
            [
                'diploma_course_code' => 'CSC126',
                'diploma_course_name' => 'FUNDAMENTALS OF ALGORITHMS',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS132',
                'degree_course_name' => 'Programming II',
                'degree_credit_hour' => 4,
                'match_percentage' => 85.0,
            ],
            [
                'diploma_course_code' => 'CSC159',
                'diploma_course_name' => 'COMPUTER ORGANIZATION',
                'diploma_credit_hour' => 4,
                'degree_course_code' => 'CS230',
                'degree_course_name' => 'Computer Organization and Architecture',
                'degree_credit_hour' => 3,
                'match_percentage' => 90.0,
            ],
            [
                'diploma_course_code' => 'CSC186',
                'diploma_course_name' => 'OBJECT ORIENTED',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS232',
                'degree_course_name' => 'Object Oriented Programming',
                'degree_credit_hour' => 4,
                'match_percentage' => 89.0,
            ],
            [
                'diploma_course_code' => 'CSC253',
                'diploma_course_name' => 'INTERACTIVE MULTIMEDIA',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS239',
                'degree_course_name' => 'Computer Graphics',
                'degree_credit_hour' => 3,
                'match_percentage' => 88.0,
            ],
            [
                'diploma_course_code' => 'CSC264',
                'diploma_course_name' => 'INTRODUCTION TO WEB AND',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CS246',
                'degree_course_name' => 'Web Technology',
                'degree_credit_hour' => 3,
                'match_percentage' => 90.0,
            ],
        ];

        foreach ($cs251Equivalencies as $equivalency) {
            CourseEquivalency::updateOrCreate(
                [
                    'diploma_course_code' => $equivalency['diploma_course_code'],
                    'degree_course_code' => $equivalency['degree_course_code'],
                    'program_code' => 'CS251',
                ],
                [
                    'diploma_course_name' => $equivalency['diploma_course_name'],
                    'diploma_credit_hour' => $equivalency['diploma_credit_hour'],
                    'diploma_institution' => 'Politeknik/UiTM Diploma Programs',
                    'degree_course_name' => $equivalency['degree_course_name'],
                    'degree_credit_hour' => $equivalency['degree_credit_hour'],
                    'match_percentage' => $equivalency['match_percentage'],
                    'program_code' => 'CS251',
                    'approved_by_user_id' => $resourcePersonUser->id,
                    'notes' => 'Pre-populated CS251 equivalency based on official curriculum mapping',
                ]
            );
        }

        $this->command->info('CS251 course equivalencies seeded successfully. Total: ' . count($cs251Equivalencies));
    }
}