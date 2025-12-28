<?php

namespace App\Models;

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
}
