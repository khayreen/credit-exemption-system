<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcademicAdvisor;
use App\Models\User;

class UpdateExistingAcademicAdvisorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Map of academic advisors to their GROUP codes (not program codes) and department info
        // Group structure from student application form:
        // CDCS230: CDCS2301B, CDCS2303B, CDCS2303C
        // CDCS251: CDCS2513A
        // CDCS253: CDCS2531A, CDCS2533B
        // CDCS255: CDCS2551A, CDCS2553B
        // CDCS266: CDCS2663A
        $advisorData = [
            'asrol.arshad@gmail.com' => [
                'programs' => ['CDCS2513A'],  // CS251 has only 1 group
                'department' => 'Fakulti Sains Komputer dan Matematik',
                'specialization' => 'Bachelor of Computer Science (Hons.) Netcentric Computing',
                'staff_id' => 'STAFF2511'
            ],
            'suhanah.rosnan@gmail.com' => [
                'programs' => ['CDCS2301B'],  // CS230 group 1
                'department' => 'Fakulti Sains Komputer dan Matematik',
                'specialization' => 'Bachelor of Computer Science (Hons.)',
                'staff_id' => 'STAFF2301'
            ],
            'fadhlina.izzah@gmail.com' => [
                'programs' => ['CDCS2303B'],  // CS230 group 2
                'department' => 'Fakulti Sains Komputer dan Matematik',
                'specialization' => 'Bachelor of Computer Science (Hons.)',
                'staff_id' => 'STAFF2302'
            ],
            'nurazian.mior@gmail.com' => [
                'programs' => ['CDCS2303C'],  // CS230 group 3
                'department' => 'Fakulti Sains Komputer dan Matematik',
                'specialization' => 'Bachelor of Computer Science (Hons.)',
                'staff_id' => 'STAFF2303'
            ],
            'syamsul.ariffin@gmail.com' => [
                'programs' => ['CDCS2531A'],  // CS253 group 1
                'department' => 'Fakulti Sains Komputer dan Matematik',
                'specialization' => 'Bachelor of Computer Science (Hons.) Multimedia Computing',
                'staff_id' => 'STAFF2531'
            ],
            'siti.nuramalina@gmail.com' => [
                'programs' => ['CDCS2533B'],  // CS253 group 2
                'department' => 'Fakulti Sains Komputer dan Matematik',
                'specialization' => 'Bachelor of Computer Science (Hons.) Multimedia Computing',
                'staff_id' => 'STAFF2532'
            ],
            'alya.geogiana@gmail.com' => [
                'programs' => ['CDCS2551A'],  // CS255 group 1
                'department' => 'Fakulti Sains Komputer dan Matematik',
                'specialization' => 'Bachelor of Computer Science (Hons.) Computer Networks',
                'staff_id' => 'STAFF2551'
            ],
            'nor.adora@gmail.com' => [
                'programs' => ['CDCS2553B'],  // CS255 group 2
                'department' => 'Fakulti Sains Komputer dan Matematik',
                'specialization' => 'Bachelor of Computer Science (Hons.) Computer Networks',
                'staff_id' => 'STAFF2552'
            ],
            'ismadi.badarudin@gmail.com' => [
                'programs' => ['CDCS2663A'],  // CS266 has only 1 group
                'department' => 'Fakulti Sains Komputer dan Matematik',
                'specialization' => 'Bachelor of Information Systems (Hons.) Information Systems Engineering',
                'staff_id' => 'STAFF2661'
            ],
        ];

        echo "\n🔧 Updating/Creating Academic Advisor Profiles with Assigned Programs...\n";
        echo "=====================================================================\n\n";

        foreach ($advisorData as $email => $data) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                echo "⚠️  Skipped: {$email} (user not found)\n";
                continue;
            }

            $advisor = AcademicAdvisor::where('user_id', $user->id)->first();

            if (!$advisor) {
                // Create the advisor profile if it doesn't exist
                $advisor = AcademicAdvisor::create([
                    'user_id' => $user->id,
                    'staff_id' => $data['staff_id'],
                    'department' => $data['department'],
                    'specialization' => $data['specialization'],
                    'assigned_programs' => $data['programs'],
                ]);
                echo "✓ Created profile: {$user->name} - Programs: " . implode(', ', $data['programs']) . "\n";
            } else {
                // Update existing profile with programs
                $advisor->update(['assigned_programs' => $data['programs']]);
                echo "✓ Updated: {$user->name} - Programs: " . implode(', ', $data['programs']) . "\n";
            }
        }

        echo "\n=====================================================================\n";
        echo "✅ Academic advisor profiles and program assignments updated!\n\n";
    }
}
