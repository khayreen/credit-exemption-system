<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $rejectionReason;

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
