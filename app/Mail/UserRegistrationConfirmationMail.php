<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegistrationConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $status;

    /**
     * The number of seconds before the job times out.
     */
    public int $timeout = 60;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $status - 'approved', 'pending_hea', 'pending_admin'
     */
    public function __construct(User $user, string $status)
    {
        $this->user = $user;
        $this->status = $status;
        $this->onQueue('emails');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = match($this->status) {
            'approved' => 'Registration Successful - UiTM Credit Exemption System',
            'pending_hea' => 'Registration Submitted - Pending HEA Approval',
            'pending_admin' => 'HEA Registration Submitted - Pending Admin Approval',
            default => 'Registration Confirmation',
        };

        return $this->subject($subject)
                    ->view('emails.user-registration-confirmation');
    }
}
