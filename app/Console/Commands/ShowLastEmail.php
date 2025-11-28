<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ShowLastEmail extends Command
{
    protected $signature = 'mail:show-last';
    protected $description = 'Display the last email from the log file';

    public function handle()
    {
        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            $this->error('No log file found!');
            return 1;
        }

        $content = file_get_contents($logFile);

        // Find the last email in the log
        preg_match_all('/Message-ID:.*?(?=\[2025-|\z)/s', $content, $matches);

        if (empty($matches[0])) {
            $this->error('No emails found in log!');
            return 1;
        }

        $lastEmail = end($matches[0]);

        // Extract key information
        preg_match('/Subject: (.+)/', $lastEmail, $subject);
        preg_match('/To: (.+)/', $lastEmail, $to);

        $this->info('');
        $this->info('=== LAST EMAIL IN LOG ===');
        $this->info('');
        $this->line('<fg=cyan>To:</> ' . ($to[1] ?? 'N/A'));
        $this->line('<fg=cyan>Subject:</> ' . ($subject[1] ?? 'N/A'));
        $this->info('');
        $this->info('=== EMAIL CONTENT ===');
        $this->info('');

        // Find and display the verification URL if present
        if (preg_match('/http:\/\/[^\s]+/', $lastEmail, $url)) {
            $this->line('<fg=green>Verification Link:</> ' . $url[0]);
            $this->info('');
        }

        $this->line($lastEmail);

        return 0;
    }
}
