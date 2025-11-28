<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AcademicAdvisor;
use Illuminate\Support\Facades\Hash;

class AcademicAdvisorTestSeeder extends Seeder
{
    public function run()
    {
        // Actual FSKM Academic Advisors with their assigned groups
        $advisors = [
            // CS230 - 3 advisors
            [
                'name' => 'Suhanah Rosnan',
                'email' => 'suhanah.rosnan@gmail.com',
                'staff_id' => 'STAFF2301',
                'group' => 'CS2301B',
                'program_code' => 'CS230',
                'program_name' => 'Bachelor of Computer Science (Hons.)'
            ],
            [
                'name' => 'Fadhlina Izzah Saman',
                'email' => 'fadhlina.izzah@gmail.com',
                'staff_id' => 'STAFF2302',
                'group' => 'CS2303B',
                'program_code' => 'CS230',
                'program_name' => 'Bachelor of Computer Science (Hons.)'
            ],
            [
                'name' => 'Nurazian Mior Dahalan',
                'email' => 'nurazian.mior@gmail.com',
                'staff_id' => 'STAFF2303',
                'group' => 'CS2303C',
                'program_code' => 'CS230',
                'program_name' => 'Bachelor of Computer Science (Hons.)'
            ],
            // CS240 - 1 advisor
            [
                'name' => 'Zainal Fikri',
                'email' => 'zainal.fikri@gmail.com',
                'staff_id' => 'STAFF2401',
                'group' => 'CS2403A',
                'program_code' => 'CS240',
                'program_name' => 'Bachelor of Information Technology (Hons.)'
            ],
            // CS251 - 1 advisor
            [
                'name' => 'Asrol Arshad',
                'email' => 'asrol.arshad@gmail.com',
                'staff_id' => 'STAFF2511',
                'group' => 'CS2513A',
                'program_code' => 'CS251',
                'program_name' => 'Bachelor of Computer Science (Hons.) Netcentric Computing'
            ],
            // CS253 - 2 advisors
            [
                'name' => 'Syamsul Ariffin Yahaya',
                'email' => 'syamsul.ariffin@gmail.com',
                'staff_id' => 'STAFF2531',
                'group' => 'CS2531A',
                'program_code' => 'CS253',
                'program_name' => 'Bachelor of Computer Science (Hons.) Multimedia Computing'
            ],
            [
                'name' => 'Siti Nuramalina Johari',
                'email' => 'siti.nuramalina@gmail.com',
                'staff_id' => 'STAFF2532',
                'group' => 'CS2533B',
                'program_code' => 'CS253',
                'program_name' => 'Bachelor of Computer Science (Hons.) Multimedia Computing'
            ],
            // CS255 - 2 advisors
            [
                'name' => 'Prof. Madya Ts. Dr. Alya Geogiana',
                'email' => 'alya.geogiana@gmail.com',
                'staff_id' => 'STAFF2551',
                'group' => 'CS2551A',
                'program_code' => 'CS255',
                'program_name' => 'Bachelor of Computer Science (Hons.) Computer Networks'
            ],
            [
                'name' => 'Nor Adora Endut',
                'email' => 'nor.adora@gmail.com',
                'staff_id' => 'STAFF2552',
                'group' => 'CS2553B',
                'program_code' => 'CS255',
                'program_name' => 'Bachelor of Computer Science (Hons.) Computer Networks'
            ],
            // CS259 - 1 advisor
            [
                'name' => 'Dr. Raihah',
                'email' => 'raihah@gmail.com',
                'staff_id' => 'STAFF2591',
                'group' => 'CS2593A',
                'program_code' => 'CS259',
                'program_name' => 'Bachelor of Information Systems (Hons.) Intelligent Systems Engineering'
            ],
            // CS264 - 1 advisor
            [
                'name' => 'Norzatul Bazamah',
                'email' => 'norzatul.bazamah@gmail.com',
                'staff_id' => 'STAFF2641',
                'group' => 'CS2643A',
                'program_code' => 'CS264',
                'program_name' => 'Bachelor of Information Systems (Hons.) Business Computing'
            ],
            // CS266 - 1 advisor
            [
                'name' => 'Prof. Madya Dr. Ismadi Md Badarudin',
                'email' => 'ismadi.badarudin@gmail.com',
                'staff_id' => 'STAFF2661',
                'group' => 'CS2663A',
                'program_code' => 'CS266',
                'program_name' => 'Bachelor of Information Systems (Hons.) Information Systems Engineering'
            ],
            // CS270 - 1 advisor
            [
                'name' => 'Dr. Nor Masri Sahri',
                'email' => 'nor.masri@gmail.com',
                'staff_id' => 'STAFF2701',
                'group' => 'CS2703A',
                'program_code' => 'CS270',
                'program_name' => 'Bachelor of Computer Science (Hons.) Mobile Computing'
            ],
        ];

        echo "\n🔧 Creating FSKM Academic Advisor Accounts...\n";
        echo "==========================================\n\n";

        foreach ($advisors as $advisorData) {
            // Check if user already exists
            $existingUser = User::where('email', $advisorData['email'])->first();

            if ($existingUser) {
                echo "⚠️  Skipped: {$advisorData['name']} (email already exists)\n";
                continue;
            }

            // Create User
            $user = User::create([
                'name' => $advisorData['name'],
                'email' => $advisorData['email'],
                'password' => Hash::make('password123'),
                'role' => 'academic_advisor',
                'email_verified_at' => now(), // Auto-verify for testing
            ]);

            // Create Academic Advisor profile
            AcademicAdvisor::create([
                'user_id' => $user->id,
                'staff_id' => $advisorData['staff_id'],
                'department' => 'Fakulti Sains Komputer dan Matematik',
                'specialization' => $advisorData['program_name'],
            ]);

            echo "✓ Created: {$advisorData['name']} ({$advisorData['email']}) - Group: {$advisorData['group']}\n";
        }

        echo "\n==========================================\n";
        echo "✅ Successfully created " . count($advisors) . " academic advisor accounts!\n";
        echo "📧 All accounts use password: password123\n\n";

        echo "📋 Summary by Course:\n";
        echo "   CS230: 3 advisors (CS2301B, CS2303B, CS2303C)\n";
        echo "   CS240: 1 advisor  (CS2403A)\n";
        echo "   CS251: 1 advisor  (CS2513A)\n";
        echo "   CS253: 2 advisors (CS2531A, CS2533B)\n";
        echo "   CS255: 2 advisors (CS2551A, CS2553B)\n";
        echo "   CS259: 1 advisor  (CS2593A)\n";
        echo "   CS264: 1 advisor  (CS2643A)\n";
        echo "   CS266: 1 advisor  (CS2663A)\n";
        echo "   CS270: 1 advisor  (CS2703A)\n";
        echo "==========================================\n\n";
    }
}
