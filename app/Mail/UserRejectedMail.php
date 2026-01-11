<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $rejectionReason;

    public int $timeout = 60;
    public int $tries = 3;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $rejectionReason
     */
    public function __construct(User $user, string $rejectionReason)
    {
        $this->user = $user;
        $this->rejectionReason = $rejectionReason;
        \$this->onQueue('emails');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Registration Review - UiTM Credit Exemption System')
                    ->view('emails.user-rejected');
    }
}
