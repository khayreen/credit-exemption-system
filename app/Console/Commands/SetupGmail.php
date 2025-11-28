<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupGmail extends Command
{
    protected $signature = 'mail:setup-gmail';
    protected $description = 'Interactive Gmail SMTP setup with App Password';

    public function handle()
    {
        $this->info('');
        $this->info('=== Gmail SMTP Setup for UiTM Credit Exemption System ===');
        $this->info('');

        // Step 1: Check if 2FA is enabled
        $this->line('📋 <fg=cyan>Step 1: Enable 2-Step Verification</>');
        $this->line('   Go to: https://myaccount.google.com/signinoptions/two-step-verification');
        $this->info('');

        if (!$this->confirm('Have you enabled 2-Step Verification?', false)) {
            $this->warn('⚠️  Please enable 2-Step Verification first, then run this command again.');
            $this->line('   Link: https://myaccount.google.com/signinoptions/two-step-verification');
            return 1;
        }

        // Step 2: Generate App Password
        $this->info('');
        $this->line('📋 <fg=cyan>Step 2: Generate App Password</>');
        $this->line('   Go to: https://myaccount.google.com/apppasswords');
        $this->line('   - Select app: Mail');
        $this->line('   - Select device: Windows Computer');
        $this->line('   - Click Generate');
        $this->line('   - Copy the 16-character password');
        $this->info('');

        $email = $this->ask('What is your Gmail address?', 'kaiarenne00@gmail.com');

        $this->info('');
        $appPassword = $this->secret('Paste your 16-character App Password (spaces will be removed)');

        if (empty($appPassword)) {
            $this->error('❌ App Password cannot be empty!');
            return 1;
        }

        // Remove spaces from app password
        $appPassword = str_replace(' ', '', $appPassword);

        if (strlen($appPassword) !== 16) {
            $this->error('❌ App Password must be exactly 16 characters (got ' . strlen($appPassword) . ')');
            $this->line('   Make sure you copied the entire password and removed all spaces.');
            return 1;
        }

        // Step 3: Update .env file
        $this->info('');
        $this->line('📝 Updating .env file...');

        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        // Remove old MAIL configuration
        $envContent = preg_replace('/MAIL_MAILER=.*\n/', '', $envContent);
        $envContent = preg_replace('/MAIL_HOST=.*\n/', '', $envContent);
        $envContent = preg_replace('/MAIL_PORT=.*\n/', '', $envContent);
        $envContent = preg_replace('/MAIL_USERNAME=.*\n/', '', $envContent);
        $envContent = preg_replace('/MAIL_PASSWORD=.*\n/', '', $envContent);
        $envContent = preg_replace('/MAIL_ENCRYPTION=.*\n/', '', $envContent);
        $envContent = preg_replace('/MAIL_FROM_ADDRESS=.*\n/', '', $envContent);
        $envContent = preg_replace('/MAIL_FROM_NAME=.*\n/', '', $envContent);

        // Add new MAIL configuration
        $mailConfig = <<<EOT

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=$email
MAIL_PASSWORD=$appPassword
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=$email
MAIL_FROM_NAME="UiTM Credit System"
EOT;

        // Find a good place to insert (after REDIS or QUEUE settings)
        if (strpos($envContent, 'REDIS_PORT') !== false) {
            $envContent = preg_replace('/(REDIS_PORT=.*\n)/', "$1$mailConfig\n", $envContent);
        } else {
            $envContent .= $mailConfig;
        }

        File::put($envPath, $envContent);

        $this->info('✅ .env file updated successfully!');

        // Step 4: Clear config cache
        $this->info('');
        $this->line('🔄 Clearing configuration cache...');
        $this->call('config:clear');

        // Step 5: Test email
        $this->info('');
        if ($this->confirm('Would you like to send a test email now?', true)) {
            $this->line('📧 Sending test email to ' . $email . '...');

            try {
                \Illuminate\Support\Facades\Mail::raw(
                    '🎉 Congratulations! Your Gmail SMTP is now configured correctly!

This is a test email from your UiTM Credit Exemption System.

✅ Gmail SMTP is working perfectly
✅ All verification emails will now be delivered to real Gmail inboxes
✅ Your beautiful UiTM-branded email design is ready

Next steps:
1. Register a new user at http://credit_system.test/register
2. Check your Gmail inbox for the verification email
3. Enjoy your professional UiTM-branded email design!

---
UiTM Credit Exemption System
Powered by Gmail',
                    function($message) use ($email) {
                        $message->to($email)
                                ->subject('✅ Gmail SMTP Test - UiTM Credit Exemption System');
                    }
                );

                $this->info('');
                $this->info('✅ SUCCESS! Test email sent!');
                $this->info('📬 Check your Gmail inbox: ' . $email);
                $this->info('');
                $this->line('🎯 Next step: Register a new user to see the verification email design!');

            } catch (\Exception $e) {
                $this->error('');
                $this->error('❌ Failed to send test email!');
                $this->error('Error: ' . $e->getMessage());
                $this->info('');
                $this->line('💡 Common fixes:');
                $this->line('   1. Make sure your App Password is correct (16 characters, no spaces)');
                $this->line('   2. Verify 2-Step Verification is enabled');
                $this->line('   3. Try generating a new App Password');
                $this->line('   4. Check: https://myaccount.google.com/notifications for blocked sign-ins');
                return 1;
            }
        }

        $this->info('');
        $this->info('=== Setup Complete! ===');
        $this->info('');
        $this->line('Your Gmail SMTP is now configured. All emails will be sent via Gmail.');
        $this->info('');

        return 0;
    }
}
