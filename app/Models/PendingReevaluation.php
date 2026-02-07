<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingReevaluation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'application_subject_id',
        'course_equivalency_id',
        'assigned_academic_advisor_id',
        'status',
        'original_subject_status',
        'diploma_course_code',
        'degree_course_code',
        'match_percentage',
        'decided_by_user_id',
        'decided_at',
        'decision_notes',
        'expires_at',
    ];

    protected $casts = [
        'match_percentage' => 'decimal:2',
        'decided_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';

    /**
     * Get the application subject that needs re-evaluation.
     */
    public function applicationSubject(): BelongsTo
    {
        return $this->belongsTo(ApplicationSubject::class);
    }

    /**
     * Get the course equivalency that triggered this re-evaluation.
     */
    public function courseEquivalency(): BelongsTo
    {
        return $this->belongsTo(CourseEquivalency::class);
    }

    /**
     * Get the assigned academic advisor.
     */
    public function assignedAcademicAdvisor(): BelongsTo
    {
        return $this->belongsTo(AcademicAdvisor::class, 'assigned_academic_advisor_id');
    }

    /**
     * Get the user who made the decision.
     */
    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by_user_id');
    }

    /**
     * Scope for pending re-evaluations.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for non-expired re-evaluations.
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for re-evaluations by program code.
     */
    public function scopeForProgram($query, string $programCode)
    {
        return $query->whereHas('applicationSubject.exemptionApplication', function ($q) use ($programCode) {
            $q->where('current_program_code', $programCode);
        });
    }

    /**
     * Scope for re-evaluations assigned to a specific academic advisor.
     */
    public function scopeAssignedTo($query, $academicAdvisorId)
    {
        return $query->where('assigned_academic_advisor_id', $academicAdvisorId);
    }

    /**
     * Scope for unassigned re-evaluations (any AA can review).
     */
    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_academic_advisor_id');
    }

    /**
     * Check if this re-evaluation is still actionable.
     */
    public function isActionable(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Mark as approved.
     */
    public function approve(User $user, ?string $notes = null): bool
    {
        if (!$this->isActionable()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_APPROVED,
            'decided_by_user_id' => $user->id,
            'decided_at' => now(),
            'decision_notes' => $notes,
        ]);

        return true;
    }

    /**
     * Mark as rejected.
     */
    public function reject(User $user, ?string $notes = null): bool
    {
        if (!$this->isActionable()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'decided_by_user_id' => $user->id,
            'decided_at' => now(),
            'decision_notes' => $notes,
        ]);

        return true;
    }

    /**
     * Get the student associated with this re-evaluation.
     */
    public function getStudentAttribute()
    {
        return $this->applicationSubject?->exemptionApplication?->student;
    }

    /**
     * Get the exemption application associated with this re-evaluation.
     */
    public function getExemptionApplicationAttribute()
    {
        return $this->applicationSubject?->exemptionApplication;
    }
}
