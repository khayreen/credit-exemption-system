<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class HeaRegistrationNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public int $timeout = 60;
    public int $tries = 3;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->onQueue('emails');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('🚨 New HEA Personnel Registration Request')
                    ->view('emails.admin.hea-registration-notification');
    }
}
