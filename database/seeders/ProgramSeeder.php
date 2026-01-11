<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            ['code' => 'CDCS230', 'name' => 'Bachelor of Computer Science (Hons.)'],
            ['code' => 'CDCS251', 'name' => 'Bachelor of Computer Science (Hons.) Netcentric Computing'],
            ['code' => 'CDCS253', 'name' => 'Bachelor of Computer Science (Hons.) Multimedia Computing'],
            ['code' => 'CDCS255', 'name' => 'Bachelor of Computer Science (Hons.) Computer Networking'],
            ['code' => 'CDCS266', 'name' => 'Bachelor of Information Systems (Hons.) Information Systems Engineering'],
        ];

        foreach ($programs as $program) {
            Program::updateOrCreate(
                ['code' => $program['code']],
                $program
            );
        }

        $this->command->info('Programs seeded successfully. Total: ' . count($programs));
    }
}
