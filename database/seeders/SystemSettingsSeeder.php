<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Terms and Conditions
        \App\Models\SystemSetting::set(
            'terms_and_conditions',
            "By using the UiTM Credit Exemption System, you agree to the following terms and conditions:\n\n" .
            "1. All information provided must be accurate and complete.\n" .
            "2. Students are responsible for submitting valid transcripts and supporting documents.\n" .
            "3. Credit exemption decisions are final and subject to academic approval.\n" .
            "4. The university reserves the right to verify all submitted documents.\n" .
            "5. Processing times may vary depending on application complexity.\n\n" .
            "For more information, please contact the Academic Office.",
            'textarea',
            'Terms and conditions displayed to students in their dashboard'
        );

        // Academic Calendar URL
        \App\Models\SystemSetting::set(
            'academic_calendar_url',
            'https://uitm.edu.my/index.php/en/academic-calendar',
            'url',
            'URL link for the academic calendar page'
        );

        // System Information
        \App\Models\SystemSetting::set(
            'system_name',
            'UiTM Credit Exemption System',
            'text',
            'Name of the system displayed in headers'
        );

        \App\Models\SystemSetting::set(
            'system_version',
            '1.0.0',
            'text',
            'Current system version'
        );
    }
}
