<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
|
| Here you may schedule tasks to be run automatically by the system.
| The Laravel scheduler provides a convenient way to manage cron jobs.
|
*/

// Send semester reminders to Resource Persons
// Runs on June 1st and November 1st at 9:00 AM
Schedule::command('reminders:semester')
    ->monthlyOn(1, '09:00')
    ->when(function () {
        // Only run in June (for Semester 1) and November (for Semester 2)
        return in_array(now()->month, [6, 11]);
    })
    ->description('Send semester preparation reminders to Resource Persons');

// Archive expired equivalency lists
// Runs on January 1st and August 1st at midnight
Schedule::command('lists:archive-expired')
    ->monthlyOn(1, '00:00')
    ->when(function () {
        // Run in August (Sem 1 start) and January (Sem 2 start)
        return in_array(now()->month, [1, 8]);
    })
    ->description('Deactivate equivalency lists from previous semesters');
