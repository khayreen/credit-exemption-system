<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\HeaPersonnel;
use App\Mail\HeaAccountCreatedMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateHeaUser extends Command
{
    protected $signature = 'hea:create
                          {name : The full name of the HEA personnel}
                          {email : The email address}
                          {--password= : Optional password (will be generated if not provided)}
                          {--no-email : Do not send email notification}';

    protected $description = 'Create a new HEA personnel user and send credentials via email';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->option('password') ?? Str::random(12);
        $sendEmail = !$this->option('no-email');

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
        $this->info("Creating HEA Personnel Account:");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->line("Name:     {$name}");
        $this->line("Email:    {$email}");
        $this->line("Password: {$password}");
        $this->line("Role:     HEA Personnel");

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
            $user = DB::transaction(function() use ($name, $email, $password) {
                // Create user
                $user = User::create([
                    'id' => Str::uuid(),
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'hea_personnel', // Legacy field for backward compatibility
                    'current_role' => 'hea_personnel',
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                ]);

                // Create HEA personnel record
                HeaPersonnel::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                ]);

                return $user;
            });

            $this->info("✅ HEA personnel created successfully!");

            // Send email verification notification
            if ($sendEmail) {
                $this->line("");
                $this->line("📧 Sending email verification...");

                try {
                    $user->sendEmailVerificationNotification();
                    $this->info("✅ Verification email sent to {$email}");
                    $this->warn("⚠️  User must verify email and set up 2FA before logging in.");
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
            $this->error("❌ Failed to create HEA user: " . $e->getMessage());
            return 1;
        }
    }
}
