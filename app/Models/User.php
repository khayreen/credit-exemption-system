<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Notifications\CustomResetPassword;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'two_factor_verified_at',
        'phone_number',
        'password',
        'role',
        'current_role',
        'requested_role',
        'requested_programs',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'google2fa_secret',
        'two_factor_recovery_codes',
        'security_phrase',
    ];

    protected $hidden = [ 'password', 'remember_token', 'google2fa_secret' ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    /**
     * Send the email verification notification with custom template
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    // --- Role Relationships ---
    public function student() { return $this->hasOne(Student::class); }
    public function academicAdvisor() { return $this->hasOne(AcademicAdvisor::class); }
    public function coordinator() { return $this->hasOne(Coordinator::class); }
    public function programCoordinator() { return $this->hasOne(ProgramCoordinator::class); }
    public function resourcePerson() { return $this->hasOne(ResourcePerson::class); }
    public function heaPersonnel() { return $this->hasOne(HeaPersonnel::class); }
    public function externalLecturer() { return $this->hasOne(ExternalLecturer::class); }
    public function admin() { return $this->hasOne(Admin::class); }

    /**
     * Check if user is a system administrator
     */
    public function isAdmin(): bool
    {
        return $this->current_role === 'admin';
    }

    /**
     * Check if account is locked
     */
    public function isLocked(): bool
    {
        return $this->locked_at !== null;
    }

    /**
     * Lock the account
     */
    public function lockAccount(string $reason = 'Too many failed login attempts'): void
    {
        $this->update([
            'locked_at' => now(),
            'lock_reason' => $reason,
        ]);
    }

    /**
     * Unlock the account
     */
    public function unlockAccount(): void
    {
        $this->update([
            'locked_at' => null,
            'lock_reason' => null,
            'failed_login_attempts' => 0,
            'last_failed_login_at' => null,
        ]);
    }

    /**
     * Increment failed login attempts and lock if threshold reached
     */
    public function incrementFailedLogins(int $threshold = 5): bool
    {
        $this->increment('failed_login_attempts');
        $this->update(['last_failed_login_at' => now()]);

        if ($this->failed_login_attempts >= $threshold) {
            $this->lockAccount();
            return true; // Account was locked
        }

        return false;
    }

    /**
     * Reset failed login attempts on successful login
     */
    public function resetFailedLogins(): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'last_failed_login_at' => null,
        ]);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // --- Helper Methods for Approval Workflow ---

    /**
     * Generate a secure approval token for quick approval links
     */
    public function generateApprovalToken(): string
    {
        $token = hash('sha256', $this->id . $this->email . now()->timestamp . config('app.key'));

        // Store token in cache for 24 hours
        cache()->put("approval_token:{$this->id}", $token, now()->addHours(24));

        return $token;
    }

    /**
     * Verify an approval token
     */
    public function verifyApprovalToken(string $token): bool
    {
        $cachedToken = cache()->get("approval_token:{$this->id}");

        if (!$cachedToken) {
            return false;
        }

        return hash_equals($cachedToken, $token);
    }

    /**
     * Check if user is pending approval
     */
    public function isPendingApproval(): bool
    {
        return in_array($this->approval_status, ['pending', 'pending_admin']);
    }

    /**
     * Check if user is approved
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->current_role === $role;
    }

    /**
     * Get requested programs as array
     */
    public function getRequestedProgramsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Set requested programs from array
     */
    public function setRequestedProgramsAttribute($value)
    {
        $this->attributes['requested_programs'] = is_array($value) ? json_encode($value) : $value;
    }

    // --- Role Enum Helpers ---

    /**
     * Get the current role as UserRole enum
     */
    public function getCurrentRoleEnum(): ?UserRole
    {
        return UserRole::tryFromString($this->current_role);
    }

    /**
     * Get the requested role as UserRole enum
     */
    public function getRequestedRoleEnum(): ?UserRole
    {
        return UserRole::tryFromString($this->requested_role);
    }

    /**
     * Get display label for current role
     */
    public function getRoleLabelAttribute(): string
    {
        $role = $this->getCurrentRoleEnum();
        return $role ? $role->label() : 'Unknown';
    }

    /**
     * Get display label for requested role
     */
    public function getRequestedRoleLabelAttribute(): string
    {
        $role = $this->getRequestedRoleEnum();
        return $role ? $role->label() : 'Unknown';
    }

    /**
     * Get badge code for current role
     */
    public function getRoleBadgeAttribute(): string
    {
        $role = $this->getCurrentRoleEnum();
        return $role ? $role->badge() : '?';
    }

    /**
     * Get badge code for requested role
     */
    public function getRequestedRoleBadgeAttribute(): string
    {
        $role = $this->getRequestedRoleEnum();
        return $role ? $role->badge() : '?';
    }

    /**
     * Get Bootstrap color class for role
     */
    public function getRoleColorAttribute(): string
    {
        $role = $this->getCurrentRoleEnum();
        return $role ? $role->color() : 'secondary';
    }

    /**
     * Check if user's role requires HEA approval
     */
    public function requiresHeaApproval(): bool
    {
        $role = $this->getRequestedRoleEnum();
        return $role ? $role->requiresHeaApproval() : false;
    }

    /**
     * Get formatted program info based on role
     */
    public function getFormattedProgramInfoAttribute(): string
    {
        $programs = $this->requested_programs;

        if (empty($programs)) {
            return 'Not specified';
        }

        return match($this->requested_role) {
            UserRole::ACADEMIC_ADVISOR->value => $this->formatAcademicAdvisorPrograms($programs),
            UserRole::COORDINATOR->value => $this->formatCoordinatorCategory($programs),
            UserRole::RESOURCE_PERSON->value => $programs['program'] ?? 'Not specified',
            default => 'Not specified',
        };
    }

    /**
     * Format Academic Advisor program groups for display
     */
    private function formatAcademicAdvisorPrograms(array $programs): string
    {
        if (empty($programs) || !isset($programs[0]['program_code'])) {
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
     * Format Program Coordinator category for display
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
