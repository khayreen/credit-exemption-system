<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\ResourcePerson;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateResourcePerson extends Command
{
    protected $signature = 'rp:create
                          {name : The full name of the Resource Person}
                          {email : The email address}
                          {programs : Comma-separated list of program codes (e.g., CDCS251,CDCS255)}
                          {--staff-id= : Staff ID (will be auto-generated if not provided)}
                          {--department=Faculty of Computer and Mathematical Sciences : Department name}
                          {--expertise=Computer Science : Expertise area}
                          {--password= : Optional password (will be generated if not provided)}
                          {--no-email : Do not send email verification}';

    protected $description = 'Create a new Resource Person user and bypass HEA approval';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $programsString = $this->argument('programs');
        $password = $this->option('password') ?? Str::random(12);
        $sendEmail = !$this->option('no-email');
        $department = $this->option('department');
        $expertise = $this->option('expertise');

        // Parse program codes
        $programCodes = array_map('trim', explode(',', $programsString));

        // Generate staff ID if not provided
        $staffId = $this->option('staff-id');
        if (!$staffId) {
            // Auto-generate based on existing count
            $count = ResourcePerson::count() + 1;
            $staffId = 'RP' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Invalid email address: {$email}");
            return 1;
        }

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("❌ User with email {$email} already exists!");
            return 1;
        }

        // Show what will be created
        $this->info("Creating Resource Person Account:");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->line("Name:       {$name}");
        $this->line("Email:      {$email}");
        $this->line("Password:   {$password}");
        $this->line("Staff ID:   {$staffId}");
        $this->line("Department: {$department}");
        $this->line("Expertise:  {$expertise}");
        $this->line("Role:       Resource Person");
        $this->line("Programs:   " . implode(', ', $programCodes));

        if ($sendEmail) {
            $this->line("Email:      ✅ Will be sent to {$email}");
        } else {
            $this->line("Email:      ⏭️  Skipped (--no-email flag)");
        }

        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        if (!$this->confirm('Proceed with creation?', true)) {
            $this->info('Operation cancelled.');
            return 0;
        }

        try {
            $user = DB::transaction(function() use ($name, $email, $password, $programCodes, $staffId, $department, $expertise) {
                // Create user
                $user = User::create([
                    'id' => Str::uuid(),
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'resource_person',
                    'current_role' => 'resource_person',
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                    'email_verified_at' => now(), // Auto-verify email
                ]);

                // Create Resource Person record
                ResourcePerson::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'staff_id' => $staffId,
                    'department' => $department,
                    'expertise_area' => $expertise,
                    'assigned_programs' => $programCodes,
                ]);

                return $user;
            });

            $this->info("✅ Resource Person created successfully!");

            // Send email verification notification if requested
            if ($sendEmail) {
                $this->line("");
                $this->line("📧 Sending email verification...");

                try {
                    $user->sendEmailVerificationNotification();
                    $this->info("✅ Verification email sent to {$email}");
                    $this->warn("⚠️  User must set up 2FA before logging in.");
                    $this->line("Temporary password: {$password}");
                } catch (\Exception $e) {
                    $this->error("❌ Failed to send verification email: " . $e->getMessage());
                    $this->warn("⚠️  Please manually share credentials:");
                    $this->line("Email: {$email}");
                    $this->line("Password: {$password}");
                }
            } else {
                $this->line("");
                $this->warn("⚠️  Email not sent. Please manually share credentials:");
                $this->line("Email: {$email}");
                $this->line("Password: {$password}");
                $this->warn("⚠️  User must verify email and set up 2FA manually.");
            }

            $this->line("");
            $this->info("🔗 Login URL: " . url('/login'));
            $this->line("");

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Failed to create Resource Person: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }
}
