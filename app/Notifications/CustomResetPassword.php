<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends ResetPassword
{
    use Queueable;

    public function __construct($token)
    {
        parent::__construct($token);
    }

    public function toMail($notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Reset Your UiTM Credit System Password')
            ->greeting('Hello!')
            ->line('You are receiving this email because we received a password reset request for your UiTM Credit Exemption System account.')
            ->line('**Account Details:**')
            ->line('Email: ' . $notifiable->email)
            ->line('Request Time: ' . now()->format('d/m/Y H:i:s'))
            ->line('')
            ->action('Reset Password', $resetUrl)
            ->line('')
            ->line('⚠️ **Security Information:**')
            ->line('• This password reset link will expire in **60 minutes**')
            ->line('• The link can only be used **once**')
            ->line('• If you did not request this reset, no further action is required')
            ->line('• For security reasons, please do not share this link with anyone')
            ->line('• Always ensure you are on the official UiTM domain when entering your credentials')
            ->line('')
            ->line('If you continue to experience issues, please contact the system administrator.')
            ->salutation('Best regards,')
            ->salutation('UiTM Credit Exemption System Team')
            ->salutation('Universiti Teknologi MARA');
    }

    protected function resetUrl($notifiable)
    {
        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}