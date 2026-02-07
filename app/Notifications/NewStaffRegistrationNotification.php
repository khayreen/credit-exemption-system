<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewStaffRegistrationNotification extends Notification
{
    public User $registeredUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $registeredUser)
    {
        $this->registeredUser = $registeredUser;
    }

    /**
     * Get the notification's delivery channels.
     * Only database - email is handled separately by NewStaffRegistrationMail
     * to ensure in-app notifications are always saved even if SMTP fails.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Only database channel - email handled separately for reliability
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $roleName = $this->formatRoleName($this->registeredUser->requested_role);
        $programInfo = $this->getProgramInfo();

        return (new MailMessage)
            ->subject("New Staff Registration: {$this->registeredUser->name} ({$roleName})")
            ->greeting("Hello {$notifiable->name},")
            ->line("A new staff member has registered and requires your approval.")
            ->line("**Applicant Details:**")
            ->line("- **Name:** {$this->registeredUser->name}")
            ->line("- **Email:** {$this->registeredUser->email}")
            ->line("- **Requested Role:** {$roleName}")
            ->line("- **Program Assignment:** {$programInfo}")
            ->line("- **Submitted:** {$this->registeredUser->created_at->format('d M Y, h:i A')}")
            ->action('Review Pending Registrations', url('/hea/users/pending'))
            ->line('Please review this registration at your earliest convenience.')
            ->salutation('Credit Exemption System');
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_staff_registration',
            'user_id' => $this->registeredUser->id,
            'user_name' => $this->registeredUser->name,
            'user_email' => $this->registeredUser->email,
            'requested_role' => $this->registeredUser->requested_role,
            'role_display' => $this->formatRoleName($this->registeredUser->requested_role),
            'program_info' => $this->getProgramInfo(),
            'message' => "New {$this->formatRoleName($this->registeredUser->requested_role)} registration from {$this->registeredUser->name}",
            'action_url' => '/hea/users/pending',
            'registered_at' => $this->registeredUser->created_at->toISOString(),
        ];
    }

    /**
     * Format role name for display
     */
    private function formatRoleName(string $role): string
    {
        return match($role) {
            'academic_advisor' => 'Academic Advisor',
            'program_coordinator' => 'Program Coordinator',
            'resource_person' => 'Resource Person',
            default => ucwords(str_replace('_', ' ', $role)),
        };
    }

    /**
     * Get program assignment info based on role
     */
    private function getProgramInfo(): string
    {
        $requestedPrograms = is_string($this->registeredUser->requested_programs)
            ? json_decode($this->registeredUser->requested_programs, true)
            : $this->registeredUser->requested_programs;

        if (!$requestedPrograms) {
            return 'Not specified';
        }

        return match($this->registeredUser->requested_role) {
            'academic_advisor' => $this->formatAcademicAdvisorPrograms($requestedPrograms),
            'program_coordinator' => $this->formatCoordinatorCategory($requestedPrograms),
            'resource_person' => $requestedPrograms['program'] ?? 'Not specified',
            default => 'Not specified',
        };
    }

    /**
     * Format Academic Advisor program groups
     */
    private function formatAcademicAdvisorPrograms(array $programs): string
    {
        if (empty($programs)) {
            return 'Not specified';
        }

        $formatted = array_map(function ($item) {
            $code = $item['program_code'] ?? 'Unknown';
            $group = $item['group'] ?? 'Unknown';
            return "{$code} (Group {$group})";
        }, $programs);

        return implode(', ', $formatted);
    }

    /**
     * Format Program Coordinator category
     */
    private function formatCoordinatorCategory(array $data): string
    {
        $category = $data['category'] ?? null;

        return match($category) {
            'category_1' => 'Category 1 (CDCS230, CDCS251, CDCS253)',
            'category_2' => 'Category 2 (CDCS255, CDCS266)',
            default => 'Not specified',
        };
    }
}
