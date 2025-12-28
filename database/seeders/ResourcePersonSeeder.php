<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ResourcePerson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ResourcePersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resourcePersons = [
            [
                'name' => 'Nor Aimuni Binti Md Rashid',
                'email' => 'aimuni.rashid@uitm.edu.my',
                'staff_id' => 'RP001',
                'department' => 'Faculty of Computer and Mathematical Sciences',
                'expertise_area' => 'Computer Science',
                'assigned_programs' => ['CDCS251'],
            ],
            [
                'name' => 'Fadzlin Binti Ahmadon',
                'email' => 'fadzlin.ahmadon@uitm.edu.my',
                'staff_id' => 'RP002',
                'department' => 'Faculty of Computer and Mathematical Sciences',
                'expertise_area' => 'Computer Science',
                'assigned_programs' => ['CDCS230'],
            ],
            [
                'name' => 'Ts. Nurul Najwa Binti Abdul Rahid',
                'email' => 'najwa.rahid@uitm.edu.my',
                'staff_id' => 'RP003',
                'department' => 'Faculty of Computer and Mathematical Sciences',
                'expertise_area' => 'Computer Science',
                'assigned_programs' => ['CDCS255'],
            ],
            [
                'name' => 'Noor Afni Binti Deraman',
                'email' => 'afni.deraman@uitm.edu.my',
                'staff_id' => 'RP004',
                'department' => 'Faculty of Computer and Mathematical Sciences',
                'expertise_area' => 'Computer Science',
                'assigned_programs' => ['CDCS266'],
            ],
            [
                'name' => 'Norshahidatul Hasana Binti Ishak',
                'email' => 'shahidatul.ishak@uitm.edu.my',
                'staff_id' => 'RP005',
                'department' => 'Faculty of Computer and Mathematical Sciences',
                'expertise_area' => 'Computer Science',
                'assigned_programs' => ['CDCS253'],
            ],
        ];

        foreach ($resourcePersons as $rpData) {
            // Create User account
            $user = User::create([
                'name' => $rpData['name'],
                'email' => $rpData['email'],
                'password' => Hash::make('password123'),
                'role' => 'resource_person',
                'current_role' => 'resource_person',
                'email_verified_at' => now(),
            ]);

            // Create ResourcePerson profile
            ResourcePerson::create([
                'user_id' => $user->id,
                'staff_id' => $rpData['staff_id'],
                'department' => $rpData['department'],
                'expertise_area' => $rpData['expertise_area'],
                'assigned_programs' => $rpData['assigned_programs'],
            ]);

            $this->command->info("Created Resource Person: {$rpData['name']} (" . implode(', ', $rpData['assigned_programs']) . ")");
        }
    }
}
