<?php

namespace App\Notifications;

use App\Models\ExemptionApplication;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationReviewReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected ExemptionApplication $application;
    protected User $sender;

    /**
     * Create a new notification instance.
     */
    public function __construct(ExemptionApplication $application, User $sender)
    {
        $this->application = $application;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $studentName = $this->application->student_name;
        $matricNo = $this->application->matric_no;
        $programCode = $this->application->current_program_code;
        $studentGroup = $this->application->student_group;
        $submittedDate = $this->application->created_at->format('d M Y, h:i A');

        return (new MailMessage)
            ->subject('Reminder: Credit Exemption Application Pending Review')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('This is a reminder that a credit exemption application is pending your review.')
            ->line('**Application Details:**')
            ->line('- **Student Name:** ' . $studentName)
            ->line('- **Matric No:** ' . $matricNo)
            ->line('- **Program:** ' . $programCode)
            ->line('- **Group:** ' . $studentGroup)
            ->line('- **Submitted:** ' . $submittedDate)
            ->action('Review Application', route('academic_advisor.dashboard'))
            ->line('Please review this application at your earliest convenience.')
            ->salutation('Regards, HEA Personnel');
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'application_review_reminder',
            'title' => 'Application Review Reminder',
            'message' => 'Please review the credit exemption application from ' . $this->application->student_name . ' (' . $this->application->matric_no . ')',
            'application_id' => $this->application->id,
            'student_name' => $this->application->student_name,
            'matric_no' => $this->application->matric_no,
            'program_code' => $this->application->current_program_code,
            'student_group' => $this->application->student_group,
            'submitted_at' => $this->application->created_at->toIso8601String(),
            'sent_by' => $this->sender->name,
            'sent_by_id' => $this->sender->id,
        ];
    }
}
