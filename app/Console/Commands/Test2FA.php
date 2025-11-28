<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use PragmaRX\Google2FA\Google2FA;

class Test2FA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:2fa {email} {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test 2FA verification for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $code = $this->argument('code');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        if (!$user->google2fa_secret) {
            $this->error("User has no 2FA secret configured.");
            return 1;
        }

        $this->info("Testing 2FA for user: {$user->email}");
        $this->info("Role: {$user->role}");
        $this->info("Secret exists: Yes");
        $this->info("Secret length: " . strlen($user->google2fa_secret));
        $this->info("Code provided: {$code}");
        $this->info("Code length: " . strlen($code));

        // Pad the code with leading zeros
        $paddedCode = str_pad($code, 6, '0', STR_PAD_LEFT);
        $this->info("Padded code: {$paddedCode}");

        // Create Google2FA instance
        $google2fa = new Google2FA();

        // Get current timestamp
        $timestamp = $google2fa->getTimestamp();
        $this->info("Current timestamp: {$timestamp}");

        // Test with different windows
        foreach ([0, 1, 2, 4, 8] as $window) {
            $valid = $google2fa->verifyKey($user->google2fa_secret, $paddedCode, $window);
            $this->line("Window {$window}: " . ($valid ? '✓ VALID' : '✗ INVALID'));
        }

        // Generate current valid code
        $currentCode = $google2fa->getCurrentOtp($user->google2fa_secret);
        $this->info("\nCurrent valid code for this user: {$currentCode}");

        return 0;
    }
}
