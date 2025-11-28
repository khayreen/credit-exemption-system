<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueuedVerifyEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // Set queue connection and options
        $this->onQueue('default');
        $this->tries = 3; // Retry 3 times if it fails
        $this->timeout = 60; // Timeout after 60 seconds
    }
}
