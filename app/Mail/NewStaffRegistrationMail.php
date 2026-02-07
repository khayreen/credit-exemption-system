<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewStaffRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $registeredUser;
    public User $heaUser;

    /**
     * Create a new message instance.
     */
    public function __construct(User $registeredUser, User $heaUser)
    {
        $this->registeredUser = $registeredUser;
        $this->heaUser = $heaUser;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $roleName = $this->formatRoleName($this->registeredUser->requested_role);
        $programInfo = $this->getProgramInfo();

        return $this->subject("New Staff Registration: {$this->registeredUser->name} ({$roleName})")
                    ->view('emails.hea.new-staff-registration')
                    ->with([
                        'roleName' => $roleName,
                        'programInfo' => $programInfo,
                    ]);
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
    public function getProgramInfo(): string
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
