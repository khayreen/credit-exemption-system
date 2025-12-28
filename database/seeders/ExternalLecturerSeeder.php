<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ExternalLecturer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ExternalLecturerSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create test external lecturer user
        $user = User::create([
            'id' => Str::uuid(),
            'name' => 'Dr. Sarah Johnson',
            'email' => 'sarah.johnson@external.edu',
            'phone_number' => '+60123456789',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'external_lecturer',
            'current_role' => 'external_lecturer',
        ]);

        // Create external lecturer profile
        ExternalLecturer::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'name' => 'Dr. Sarah Johnson',
            'email' => 'sarah.johnson@external.edu',
            'institution_name' => 'Metropolitan University College',
            'phone_number' => '+60123456789',
        ]);

        // Create another external lecturer
        $user2 = User::create([
            'id' => Str::uuid(),
            'name' => 'Prof. Ahmad Rahman',
            'email' => 'ahmad.rahman@polytechnic.edu.my',
            'phone_number' => '+60987654321',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'external_lecturer',
            'current_role' => 'external_lecturer',
        ]);

        ExternalLecturer::create([
            'id' => Str::uuid(),
            'user_id' => $user2->id,
            'name' => 'Prof. Ahmad Rahman',
            'email' => 'ahmad.rahman@polytechnic.edu.my',
            'institution_name' => 'Kuala Lumpur Polytechnic',
            'phone_number' => '+60987654321',
        ]);

        echo "Created external lecturer test users:\n";
        echo "1. Dr. Sarah Johnson (sarah.johnson@external.edu) - password: password123\n";
        echo "2. Prof. Ahmad Rahman (ahmad.rahman@polytechnic.edu.my) - password: password123\n";
    }
}