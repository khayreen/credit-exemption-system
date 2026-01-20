<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use App\Models\ContactSetting;
use App\Models\TermsVersion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@uitm.edu.my'],
            [
                'id' => Str::uuid(),
                'name' => 'System Administrator',
                'email' => 'admin@uitm.edu.my',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'current_role' => 'admin',
                'requested_role' => 'admin',
                'approval_status' => 'approved',
                'email_verified_at' => now(),
                'two_factor_verified_at' => null, // Will be set up on first login
            ]
        );

        // Create admin profile
        Admin::firstOrCreate(
            ['user_id' => $user->id],
            [
                'id' => Str::uuid(),
                'staff_id' => 'ADMIN001',
                'department' => 'ICT Department',
            ]
        );

        $this->command->info('Admin user created:');
        $this->command->info('Email: admin@uitm.edu.my');
        $this->command->info('Password: Admin@123');
        $this->command->warn('Please change this password after first login!');

        // Seed default contact settings
        $contactDefaults = [
            [
                'key' => 'support_email',
                'label' => 'Support Email',
                'value' => 'support@uitm.edu.my',
                'type' => 'email',
            ],
            [
                'key' => 'admin_email',
                'label' => 'Admin Email',
                'value' => 'admin@uitm.edu.my',
                'type' => 'email',
            ],
            [
                'key' => 'hea_email',
                'label' => 'HEA Department Email',
                'value' => 'hea@uitm.edu.my',
                'type' => 'email',
            ],
            [
                'key' => 'helpdesk_phone',
                'label' => 'Helpdesk Phone',
                'value' => '+603-5521 1000',
                'type' => 'phone',
            ],
            [
                'key' => 'office_hours',
                'label' => 'Office Hours',
                'value' => 'Monday - Friday, 8:30 AM - 5:30 PM',
                'type' => 'text',
            ],
            [
                'key' => 'academic_calendar_url',
                'label' => 'Academic Calendar URL',
                'value' => 'https://hea.uitm.edu.my/index.php/calendars/academic-calendar',
                'type' => 'url',
            ],
        ];

        foreach ($contactDefaults as $contact) {
            ContactSetting::firstOrCreate(
                ['key' => $contact['key']],
                [
                    'id' => Str::uuid(),
                    'label' => $contact['label'],
                    'value' => $contact['value'],
                    'type' => $contact['type'],
                ]
            );
        }

        $this->command->info('Default contact settings created.');

        // Seed initial Terms & Conditions
        $existingTerms = TermsVersion::getCurrent();
        if (!$existingTerms) {
            TermsVersion::create([
                'id' => Str::uuid(),
                'version' => '1.0',
                'content' => $this->getDefaultTermsContent(),
                'effective_date' => now(),
                'is_current' => true,
                'created_by' => $user->id,
            ]);
            $this->command->info('Default Terms & Conditions created.');
        }
    }

    /**
     * Get default terms and conditions content
     */
    private function getDefaultTermsContent(): string
    {
        return <<<'EOT'
UiTM Credit Exemption System - Terms and Conditions

1. ACCEPTANCE OF TERMS
By accessing and using the UiTM Credit Exemption System, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, you must not use this system.

2. USER ACCOUNTS
- Users are responsible for maintaining the confidentiality of their login credentials.
- Users must provide accurate and complete information during registration.
- Users must not share their accounts with others.

3. CREDIT EXEMPTION APPLICATIONS
- All applications must be submitted with accurate information.
- Supporting documents must be authentic and verifiable.
- False information may result in rejection of applications and disciplinary action.

4. PRIVACY AND DATA PROTECTION
- Personal information collected will be used only for the purposes of processing credit exemption applications.
- User data will be protected in accordance with applicable data protection laws.
- Users have the right to request access to their personal data.

5. SYSTEM USAGE
- Users must not attempt to gain unauthorized access to any part of the system.
- Users must not interfere with or disrupt the system's operations.
- The system should be used only for its intended purposes.

6. INTELLECTUAL PROPERTY
- All content within the system is the property of UiTM.
- Users may not copy, modify, or distribute system content without permission.

7. LIMITATION OF LIABILITY
- UiTM shall not be liable for any indirect, incidental, or consequential damages.
- The system is provided "as is" without warranties of any kind.

8. CHANGES TO TERMS
- UiTM reserves the right to modify these terms at any time.
- Users will be notified of significant changes.
- Continued use of the system after changes constitutes acceptance.

9. GOVERNING LAW
These terms shall be governed by the laws of Malaysia.

10. CONTACT
For questions regarding these terms, please contact the HEA office at hea@uitm.edu.my.

Last Updated: January 2026
EOT;
    }
}
