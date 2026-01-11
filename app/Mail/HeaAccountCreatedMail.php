<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class HeaAccountCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    public int $timeout = 60;
    public int $tries = 3;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $password
     */
    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
        \$this->onQueue('emails');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your HEA Account - UiTM Credit Exemption System')
                    ->view('emails.hea-account-created');
    }
}
