<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SemesterReminderNotification extends Notification
{
    public string $semester;
    public string $assignedProgram;
    public User $sender;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $semester, string $assignedProgram, User $sender)
    {
        $this->semester = $semester;
        $this->assignedProgram = $assignedProgram;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $programName = $this->getProgramName($this->assignedProgram);

        return (new MailMessage)
            ->subject("Action Required: Submit CS110 Equivalency List for {$this->assignedProgram} - {$this->semester}")
            ->greeting("Dear {$notifiable->name},")
            ->line("This is a reminder from the Higher Education Authority (HEA) regarding the upcoming semester.")
            ->line("**Action Required:**")
            ->line("Please submit the updated CS110 Course Equivalency List for your assigned program:")
            ->line("- **Program Code:** {$this->assignedProgram}")
            ->line("- **Program Name:** {$programName}")
            ->line("- **Semester:** {$this->semester}")
            ->line("**What you need to do:**")
            ->line("1. Review the current equivalency list for any changes")
            ->line("2. Update course mappings if necessary")
            ->line("3. Submit the list for HEA endorsement")
            ->line("Even if there are no changes from the previous semester, please submit the list to confirm it remains current.")
            ->action('Go to My CS110 Lists', url('/resource-person/cs110-lists'))
            ->line("If you have any questions, please contact the HEA office.")
            ->salutation("Best regards,\n{$this->sender->name}\nHigher Education Authority");
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'semester_reminder',
            'semester' => $this->semester,
            'program_code' => $this->assignedProgram,
            'program_name' => $this->getProgramName($this->assignedProgram),
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'message' => "Reminder: Submit CS110 Equivalency List for {$this->assignedProgram} ({$this->semester})",
            'action_url' => '/resource-person/cs110-lists',
        ];
    }

    /**
     * Get program name by code
     */
    private function getProgramName(string $code): string
    {
        $programNames = [
            'CDCS230' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN)',
            'CDCS251' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) PENGKOMPUTERAN NETSENTRIK',
            'CDCS253' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) PENGKOMPUTERAN MULTIMEDIA',
            'CDCS255' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) RANGKAIAN KOMPUTER',
            'CDCS266' => 'SARJANA MUDA SISTEM MAKLUMAT (KEPUJIAN) KEJURUTERAAN SISTEM MAKLUMAT',
        ];

        return $programNames[$code] ?? $code;
    }
}
