<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $role;
    public $programs;

    public int $timeout = 60;
    public int $tries = 3;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $role
     * @param array $programs
     */
    public function __construct(User $user, string $role, array $programs = [])
    {
        $this->user = $user;
        $this->role = $role;
        $this->programs = $programs;
        \$this->onQueue('emails');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Account Approved - UiTM Credit Exemption System')
                    ->view('emails.user-approved');
    }
}
