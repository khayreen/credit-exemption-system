<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            // Computer Science (CS) Courses
            ['code' => 'CS110', 'name' => 'Computer Technology', 'credit_hour' => 3],
            ['code' => 'CS131', 'name' => 'Programming I', 'credit_hour' => 4],
            ['code' => 'CS132', 'name' => 'Programming II', 'credit_hour' => 4],
            ['code' => 'CS141', 'name' => 'Discrete Structure', 'credit_hour' => 3],
            ['code' => 'CS230', 'name' => 'Computer Organization and Architecture', 'credit_hour' => 3],
            ['code' => 'CS231', 'name' => 'Data Structure and Algorithm', 'credit_hour' => 4],
            ['code' => 'CS232', 'name' => 'Object Oriented Programming', 'credit_hour' => 4],
            ['code' => 'CS233', 'name' => 'Database Systems', 'credit_hour' => 4],
            ['code' => 'CS234', 'name' => 'Operating Systems', 'credit_hour' => 3],
            ['code' => 'CS235', 'name' => 'Computer Networks', 'credit_hour' => 3],
            ['code' => 'CS236', 'name' => 'Software Engineering', 'credit_hour' => 3],
            ['code' => 'CS237', 'name' => 'Human Computer Interaction', 'credit_hour' => 3],
            ['code' => 'CS238', 'name' => 'System Analysis and Design', 'credit_hour' => 3],
            ['code' => 'CS239', 'name' => 'Computer Graphics', 'credit_hour' => 3],
            ['code' => 'CS240', 'name' => 'Artificial Intelligence', 'credit_hour' => 3],
            ['code' => 'CS241', 'name' => 'Machine Learning', 'credit_hour' => 3],
            ['code' => 'CS242', 'name' => 'Data Mining', 'credit_hour' => 3],
            ['code' => 'CS243', 'name' => 'Compiler Design', 'credit_hour' => 3],
            ['code' => 'CS244', 'name' => 'Parallel Computing', 'credit_hour' => 3],
            ['code' => 'CS245', 'name' => 'Computer Security', 'credit_hour' => 3],
            ['code' => 'CS246', 'name' => 'Web Technology', 'credit_hour' => 3],
            ['code' => 'CS247', 'name' => 'Mobile Application Development', 'credit_hour' => 3],
            ['code' => 'CS248', 'name' => 'Advanced Database Systems', 'credit_hour' => 3],
            ['code' => 'CS249', 'name' => 'Digital Image Processing', 'credit_hour' => 3],
            ['code' => 'CS250', 'name' => 'Computer Vision', 'credit_hour' => 3],
            ['code' => 'CS251', 'name' => 'Software Project Management', 'credit_hour' => 3],
            ['code' => 'CS252', 'name' => 'Distributed Systems', 'credit_hour' => 3],
            ['code' => 'CS253', 'name' => 'Cloud Computing', 'credit_hour' => 3],
            ['code' => 'CS254', 'name' => 'Big Data Analytics', 'credit_hour' => 3],
            ['code' => 'CS255', 'name' => 'Cybersecurity', 'credit_hour' => 3],
            ['code' => 'CS256', 'name' => 'Blockchain Technology', 'credit_hour' => 3],
            ['code' => 'CS257', 'name' => 'Internet of Things', 'credit_hour' => 3],
            ['code' => 'CS258', 'name' => 'Game Development', 'credit_hour' => 3],
            ['code' => 'CS259', 'name' => 'Virtual Reality', 'credit_hour' => 3],
            ['code' => 'CS260', 'name' => 'Augmented Reality', 'credit_hour' => 3],
            ['code' => 'CS261', 'name' => 'Natural Language Processing', 'credit_hour' => 3],
            ['code' => 'CS262', 'name' => 'Computer Ethics', 'credit_hour' => 2],
            ['code' => 'CS263', 'name' => 'Research Methodology', 'credit_hour' => 2],
            ['code' => 'CS264', 'name' => 'Final Year Project I', 'credit_hour' => 3],
            ['code' => 'CS265', 'name' => 'Final Year Project II', 'credit_hour' => 3],
            ['code' => 'CS266', 'name' => 'Industrial Training', 'credit_hour' => 6],

            // Information Technology (IT) Courses
            ['code' => 'IT110', 'name' => 'Information Technology Fundamentals', 'credit_hour' => 3],
            ['code' => 'IT120', 'name' => 'Digital Logic Design', 'credit_hour' => 3],
            ['code' => 'IT130', 'name' => 'Programming Fundamentals', 'credit_hour' => 4],
            ['code' => 'IT131', 'name' => 'Structured Programming', 'credit_hour' => 4],
            ['code' => 'IT140', 'name' => 'Mathematics for Computing', 'credit_hour' => 3],
            ['code' => 'IT210', 'name' => 'Information Systems', 'credit_hour' => 3],
            ['code' => 'IT220', 'name' => 'Computer Hardware', 'credit_hour' => 3],
            ['code' => 'IT230', 'name' => 'Data Structures', 'credit_hour' => 3],
            ['code' => 'IT231', 'name' => 'Algorithm Analysis', 'credit_hour' => 3],
            ['code' => 'IT240', 'name' => 'Statistics for IT', 'credit_hour' => 3],
            ['code' => 'IT250', 'name' => 'IT Project Management', 'credit_hour' => 3],
            ['code' => 'IT310', 'name' => 'Enterprise Systems', 'credit_hour' => 3],
            ['code' => 'IT320', 'name' => 'Network Administration', 'credit_hour' => 3],
            ['code' => 'IT321', 'name' => 'Network Security', 'credit_hour' => 3],
            ['code' => 'IT330', 'name' => 'Web Development', 'credit_hour' => 4],
            ['code' => 'IT331', 'name' => 'Advanced Web Development', 'credit_hour' => 4],
            ['code' => 'IT340', 'name' => 'Database Management', 'credit_hour' => 4],
            ['code' => 'IT341', 'name' => 'Advanced Database', 'credit_hour' => 3],
            ['code' => 'IT350', 'name' => 'IT Service Management', 'credit_hour' => 3],
            ['code' => 'IT360', 'name' => 'Business Intelligence', 'credit_hour' => 3],
            ['code' => 'IT370', 'name' => 'IT Governance', 'credit_hour' => 3],
            ['code' => 'IT380', 'name' => 'Information Security', 'credit_hour' => 3],
            ['code' => 'IT390', 'name' => 'IT Audit', 'credit_hour' => 3],
            ['code' => 'IT410', 'name' => 'Enterprise Architecture', 'credit_hour' => 3],
            ['code' => 'IT420', 'name' => 'Wireless Networks', 'credit_hour' => 3],
            ['code' => 'IT430', 'name' => 'E-Commerce Systems', 'credit_hour' => 3],
            ['code' => 'IT440', 'name' => 'Data Warehousing', 'credit_hour' => 3],
            ['code' => 'IT450', 'name' => 'IT Strategy', 'credit_hour' => 3],
            ['code' => 'IT460', 'name' => 'Knowledge Management', 'credit_hour' => 3],
            ['code' => 'IT470', 'name' => 'Digital Forensics', 'credit_hour' => 3],
            ['code' => 'IT480', 'name' => 'Risk Management', 'credit_hour' => 3],
            ['code' => 'IT490', 'name' => 'IT Capstone Project', 'credit_hour' => 6],

            // Information Systems (IS) Courses
            ['code' => 'IS110', 'name' => 'Introduction to Information Systems', 'credit_hour' => 3],
            ['code' => 'IS120', 'name' => 'Business Fundamentals', 'credit_hour' => 3],
            ['code' => 'IS210', 'name' => 'Systems Analysis', 'credit_hour' => 3],
            ['code' => 'IS220', 'name' => 'Business Process Management', 'credit_hour' => 3],
            ['code' => 'IS230', 'name' => 'Information Systems Design', 'credit_hour' => 3],
            ['code' => 'IS240', 'name' => 'Decision Support Systems', 'credit_hour' => 3],
            ['code' => 'IS310', 'name' => 'Enterprise Resource Planning', 'credit_hour' => 3],
            ['code' => 'IS320', 'name' => 'Supply Chain Management Systems', 'credit_hour' => 3],
            ['code' => 'IS330', 'name' => 'Customer Relationship Management', 'credit_hour' => 3],
            ['code' => 'IS340', 'name' => 'Business Analytics', 'credit_hour' => 3],

            // Multimedia (MM) Courses  
            ['code' => 'MM110', 'name' => 'Introduction to Multimedia', 'credit_hour' => 3],
            ['code' => 'MM120', 'name' => 'Digital Design Fundamentals', 'credit_hour' => 3],
            ['code' => 'MM210', 'name' => 'Digital Image Processing', 'credit_hour' => 3],
            ['code' => 'MM220', 'name' => 'Animation Principles', 'credit_hour' => 4],
            ['code' => 'MM230', 'name' => 'Audio and Video Production', 'credit_hour' => 4],
            ['code' => 'MM240', 'name' => '3D Modeling and Animation', 'credit_hour' => 4],
            ['code' => 'MM310', 'name' => 'Interactive Multimedia', 'credit_hour' => 3],
            ['code' => 'MM320', 'name' => 'Game Design', 'credit_hour' => 3],
            ['code' => 'MM330', 'name' => 'Virtual Reality Development', 'credit_hour' => 3],
            ['code' => 'MM340', 'name' => 'Multimedia Project', 'credit_hour' => 6],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(
                ['code' => $course['code']],
                $course
            );
        }

        $this->command->info('Courses seeded successfully. Total: ' . count($courses));
    }
}