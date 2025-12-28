<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingEquivalencyMapping extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     */
    protected $table = 'pending_equivalency_mappings';

    /**
     * Indicates if the model's ID is auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'resource_person_user_id',
        'program_code',
        'diploma_course_code',
        'diploma_course_name',
        'diploma_credit_hour',
        'diploma_institution',
        'degree_course_code',
        'degree_course_name',
        'degree_credit_hour',
        'match_percentage',
        'notes',
        'status',
        'added_by_coordinator_id',
        'added_at',
        'rejection_reason',
        'course_equivalency_request_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'diploma_credit_hour' => 'decimal:1',
        'degree_credit_hour' => 'decimal:1',
        'match_percentage' => 'decimal:2',
        'added_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_ADDED = 'added';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get the resource person who created this mapping
     */
    public function resourcePerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resource_person_user_id');
    }

    /**
     * Get the program coordinator who added or rejected this mapping
     */
    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by_coordinator_id');
    }

    /**
     * Get the student's equivalency request that prompted this mapping (if any)
     */
    public function courseEquivalencyRequest(): BelongsTo
    {
        return $this->belongsTo(CourseEquivalencyRequest::class);
    }

    /**
     * Scope: Pending mappings only
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Added mappings only
     */
    public function scopeAdded($query)
    {
        return $query->where('status', self::STATUS_ADDED);
    }

    /**
     * Scope: Rejected mappings only
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope: Filter by program code
     */
    public function scopeForProgram($query, string $programCode)
    {
        return $query->where('program_code', $programCode);
    }

    /**
     * Scope: Filter by resource person
     */
    public function scopeByResourcePerson($query, string $userId)
    {
        return $query->where('resource_person_user_id', $userId);
    }

    /**
     * Mark mapping as added to equivalency list
     */
    public function markAsAdded(string $coordinatorId): bool
    {
        return $this->update([
            'status' => self::STATUS_ADDED,
            'added_by_coordinator_id' => $coordinatorId,
            'added_at' => now(),
        ]);
    }

    /**
     * Mark mapping as rejected
     */
    public function markAsRejected(string $coordinatorId, string $reason): bool
    {
        return $this->update([
            'status' => self::STATUS_REJECTED,
            'added_by_coordinator_id' => $coordinatorId,
            'added_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Check if mapping is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if mapping has been added
     */
    public function isAdded(): bool
    {
        return $this->status === self::STATUS_ADDED;
    }

    /**
     * Check if mapping was rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_ADDED => 'success',
            self::STATUS_REJECTED => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get formatted status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending Review',
            self::STATUS_ADDED => 'Added to List',
            self::STATUS_REJECTED => 'Rejected',
            default => ucfirst($this->status),
        };
    }
}
