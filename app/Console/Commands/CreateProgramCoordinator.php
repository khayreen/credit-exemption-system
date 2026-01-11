<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\ProgramCoordinator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateProgramCoordinator extends Command
{
    protected $signature = 'pc:create
                          {name : The full name of the Program Coordinator}
                          {email : The email address}
                          {programs : Comma-separated list of program codes (e.g., CDCS251,CDCS255)}
                          {--password= : Optional password (will be generated if not provided)}
                          {--no-email : Do not send email verification}';

    protected $description = 'Create a new Program Coordinator user and bypass HEA approval';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $programsString = $this->argument('programs');
        $password = $this->option('password') ?? Str::random(12);
        $sendEmail = !$this->option('no-email');

        // Parse program codes
        $programCodes = array_map('trim', explode(',', $programsString));

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
        $this->info("Creating Program Coordinator Account:");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->line("Name:     {$name}");
        $this->line("Email:    {$email}");
        $this->line("Password: {$password}");
        $this->line("Role:     Program Coordinator");
        $this->line("Programs: " . implode(', ', $programCodes));

        if ($sendEmail) {
            $this->line("Email:    ✅ Will be sent to {$email}");
        } else {
            $this->line("Email:    ⏭️  Skipped (--no-email flag)");
        }

        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        if (!$this->confirm('Proceed with creation?', true)) {
            $this->info('Operation cancelled.');
            return 0;
        }

        try {
            $user = DB::transaction(function() use ($name, $email, $password, $programCodes) {
                // Create user
                $user = User::create([
                    'id' => Str::uuid(),
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'program_coordinator',
                    'current_role' => 'program_coordinator',
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                    'email_verified_at' => now(), // Auto-verify email
                ]);

                // Create Program Coordinator record
                ProgramCoordinator::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'name' => $name,
                    'email' => $email,
                    'program_codes' => $programCodes,
                ]);

                return $user;
            });

            $this->info("✅ Program Coordinator created successfully!");

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
            $this->error("❌ Failed to create Program Coordinator: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }
}
