<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProgramCoordinator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProgramCoordinatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only run this seeder in local/testing environments
        if (!app()->environment('local', 'testing')) {
            $this->command->warn('⚠️  ProgramCoordinatorSeeder skipped - only runs in local/testing environments');
            return;
        }

        $coordinators = [
            [
                'name' => 'Ts. Dr. Edzreena Edza Binti Odzaly',
                'email' => 'edzreena.odzaly@uitm.edu.my',
                'program_codes' => ['CDCS255', 'CDCS251', 'CDCS266'],
            ],
            [
                'name' => 'Dr. Siti Feirusz Binti Ahmad Fesol',
                'email' => 'feirusz.fesol@uitm.edu.my',
                'program_codes' => ['CDCS230', 'CDCS253'],
            ],
        ];

        foreach ($coordinators as $coordinatorData) {
            // Create User account
            $user = User::create([
                'name' => $coordinatorData['name'],
                'email' => $coordinatorData['email'],
                'password' => Hash::make('password123'),
                'role' => 'program_coordinator',
                'current_role' => 'program_coordinator',
                'email_verified_at' => now(),
            ]);

            // Create ProgramCoordinator profile
            ProgramCoordinator::create([
                'user_id' => $user->id,
                'name' => $coordinatorData['name'],
                'email' => $coordinatorData['email'],
                'program_codes' => $coordinatorData['program_codes'],
            ]);

            $this->command->info("Created Program Coordinator: {$coordinatorData['name']} (" . implode(', ', $coordinatorData['program_codes']) . ")");
        }
    }
}
