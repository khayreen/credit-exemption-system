<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ExemptionApplication;

class ApplicationSubmitted extends Notification
{
    use Queueable;

    protected $application;

    public function __construct(ExemptionApplication $application)
    {
        $this->application = $application;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Credit Exemption Application Submitted')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your credit exemption application has been successfully submitted.')
                    ->line('Application ID: ' . $this->application->id)
                    ->line('You can track the status of your application on your dashboard.')
                    ->action('View Status', url(route('student.application.status')))
                    ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
