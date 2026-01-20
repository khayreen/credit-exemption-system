<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquivalencyList extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'program_code',
        'program_name',
        'semester',
        'target_semester',
        'academic_year',
        'category',
        'source_institution',
        'status',
        'created_by_user_id',
        'submitted_at',
        'submission_notes',
        'reviewed_by_user_id',
        'reviewed_at',
        'review_notes',
        'endorsed_by_user_id',
        'endorsed_at',
        'endorsement_notes',
        'published_by_user_id',
        'published_at',
        'is_active',
        'image_path',
        'total_equivalencies',
        'eligible_count',
        'not_eligible_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'endorsed_at' => 'datetime',
        'published_at' => 'datetime',
        'total_equivalencies' => 'integer',
        'eligible_count' => 'integer',
        'not_eligible_count' => 'integer',
    ];

    /**
     * Status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_ENDORSED = 'endorsed';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ARCHIVED = 'archived';

    /**
     * Category constants
     */
    const CATEGORY_INTERNAL = 'internal';
    const CATEGORY_EXTERNAL = 'external';

    /**
     * Get the creator (Resource Person or Program Coordinator) of this list
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the reviewer (HEA Personnel) of this list
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    /**
     * Get the endorser (HEA Personnel) of this list
     */
    public function endorser()
    {
        return $this->belongsTo(User::class, 'endorsed_by_user_id');
    }

    /**
     * Get the publisher (HEA Personnel) of this list
     */
    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by_user_id');
    }

    /**
     * Get the HEA personnel who endorsed this list
     */
    public function endorsedBy()
    {
        return $this->belongsTo(User::class, 'endorsed_by_user_id');
    }

    /**
     * Get all course equivalencies in this list
     */
    public function courseEquivalencies()
    {
        return $this->hasMany(CourseEquivalency::class, 'equivalency_list_id')
            ->orderBy('diploma_course_code', 'asc');
    }

    /**
     * Get the history of course equivalencies for this list
     */
    public function courseEquivalencyHistory()
    {
        return $this->hasMany(CourseEquivalencyHistory::class, 'equivalency_list_id');
    }

    /**
     * Scope: Filter by category
     */
    public function scopeInternal($query)
    {
        return $query->where('category', self::CATEGORY_INTERNAL);
    }

    public function scopeExternal($query)
    {
        return $query->where('category', self::CATEGORY_EXTERNAL);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', self::STATUS_UNDER_REVIEW);
    }

    public function scopeEndorsed($query)
    {
        return $query->where('status', self::STATUS_ENDORSED);
    }

    public function scopePublished($query)
    {
        // Lists revert to draft status after publishing for continuous editing
        // So we check published_at instead of status to identify published lists
        return $query->whereNotNull('published_at');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeArchived($query)
    {
        return $query->where('status', self::STATUS_ARCHIVED);
    }

    /**
     * Scope: Filter pending lists (awaiting HEA action)
     * Includes submitted and under_review statuses
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_SUBMITTED, self::STATUS_UNDER_REVIEW]);
    }

    /**
     * Scope: Filter active lists
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by program code
     */
    public function scopeForProgram($query, $programCode)
    {
        return $query->where('program_code', $programCode);
    }

    /**
     * Scope: Filter by semester
     */
    public function scopeForSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * Check if the list is internal (CS110)
     */
    public function isInternal(): bool
    {
        return $this->category === self::CATEGORY_INTERNAL;
    }

    /**
     * Check if the list is external
     */
    public function isExternal(): bool
    {
        return $this->category === self::CATEGORY_EXTERNAL;
    }

    /**
     * Get the source display name
     */
    public function getSourceDisplayAttribute(): string
    {
        if ($this->isInternal()) {
            return 'CS110 (UiTM Diploma)';
        }
        return $this->source_institution ?? 'Unknown';
    }

    /**
     * Get the category display name
     */
    public function getCategoryDisplayAttribute(): string
    {
        return $this->isInternal() ? 'Internal' : 'External';
    }

    /**
     * Get the category icon
     */
    public function getCategoryIconAttribute(): string
    {
        return $this->isInternal() ? '🏠' : '🌐';
    }

    /**
     * Get total mappings (alias for total_equivalencies)
     */
    public function getTotalMappingsAttribute(): int
    {
        return $this->total_equivalencies ?? 0;
    }

    /**
     * Get the status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_SUBMITTED => 'info',
            self::STATUS_UNDER_REVIEW => 'primary',
            self::STATUS_ENDORSED => 'success',
            self::STATUS_PUBLISHED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_ARCHIVED => 'dark',
            default => 'secondary',
        };
    }

    /**
     * Get the status display name
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMITTED => 'Submitted to HEA',
            self::STATUS_UNDER_REVIEW => 'Under HEA Review',
            self::STATUS_ENDORSED => 'Endorsed by HEA',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_ARCHIVED => 'Archived',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * Submit the list to HEA (Resource Person action)
     *
     * @param User $resourcePerson The resource person submitting
     * @param string $targetSemester The target semester for publication (e.g., "2025/2026-1")
     * @param string|null $notes Optional submission notes
     */
    public function submit(User $resourcePerson, string $targetSemester, ?string $notes = null): bool
    {
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_SUBMITTED,
            'target_semester' => $targetSemester,
            'academic_year' => explode('-', $targetSemester)[0], // Extract academic year from semester
            'submitted_at' => now(),
            'submission_notes' => $notes,
        ]);

        return true;
    }

    /**
     * Start reviewing the list (HEA action)
     */
    public function startReview(User $heaPersonnel): bool
    {
        if ($this->status !== self::STATUS_SUBMITTED) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_UNDER_REVIEW,
            'reviewed_by_user_id' => $heaPersonnel->id,
            'reviewed_at' => now(),
        ]);

        return true;
    }

    /**
     * Endorse the list (HEA action)
     */
    public function endorse(User $heaPersonnel, ?string $notes = null): bool
    {
        if (!in_array($this->status, [self::STATUS_SUBMITTED, self::STATUS_UNDER_REVIEW])) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_ENDORSED,
            'endorsed_by_user_id' => $heaPersonnel->id,
            'endorsed_at' => now(),
            'endorsement_notes' => $notes,
        ]);

        return true;
    }

    /**
     * Reject the list (HEA action)
     */
    public function reject(User $heaPersonnel, string $reason): bool
    {
        if (!in_array($this->status, [self::STATUS_SUBMITTED, self::STATUS_UNDER_REVIEW])) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'reviewed_by_user_id' => $heaPersonnel->id,
            'reviewed_at' => now(),
            'review_notes' => $reason,
        ]);

        return true;
    }

    /**
     * Publish the list (HEA action after endorsement)
     *
     * NEW ARCHITECTURE:
     * - Sets semester to target_semester for publication record
     * - Creates a snapshot of current state (published as PDF for that semester)
     * - Immediately reverts to DRAFT status for continuous editing
     * - List remains active and editable for next semester's updates
     */
    public function publish(User $heaPersonnel): bool
    {
        if ($this->status !== self::STATUS_ENDORSED) {
            return false;
        }

        // Set semester from target_semester for publication record
        $publishedSemester = $this->target_semester;

        // Update this list
        $this->update([
            'status' => self::STATUS_PUBLISHED,
            'semester' => $publishedSemester, // Record which semester it was published for
            'published_by_user_id' => $heaPersonnel->id,
            'published_at' => now(),
            'is_active' => true,
        ]);

        // Mark all equivalencies as published
        $this->courseEquivalencies()->update(['is_published' => true]);

        // TODO: Generate PDF for this semester here
        // $this->generatePDF($publishedSemester);

        // CRITICAL: Immediately revert to DRAFT for continuous editing
        // This allows Resource Person to start editing for next semester
        $this->update([
            'status' => self::STATUS_DRAFT,
            'target_semester' => null, // Clear target semester
            // Keep semester, published_at, published_by, endorsed_by for historical record
            // Keep endorsement data to maintain historical record of who endorsed the list
            // Clear submission/review data for next cycle
            'submitted_at' => null,
            'submission_notes' => null,
            'reviewed_by_user_id' => null,
            'reviewed_at' => null,
            'review_notes' => null,
            // NOTE: endorsed_by_user_id, endorsed_at, endorsement_notes are KEPT for historical tracking
        ]);

        return true;
    }

    /**
     * Revert to draft (Resource Person action after rejection)
     */
    public function revertToDraft(): bool
    {
        if ($this->status !== self::STATUS_REJECTED) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_DRAFT,
            'reviewed_by_user_id' => null,
            'reviewed_at' => null,
            'review_notes' => null,
        ]);

        return true;
    }

    /**
     * Archive the list (deactivate for old semesters)
     */
    public function archive(): bool
    {
        if ($this->status !== self::STATUS_PUBLISHED) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_ARCHIVED,
            'is_active' => false,
        ]);

        return true;
    }

    /**
     * Update statistics based on course equivalencies
     */
    public function updateStatistics(): void
    {
        $total = $this->courseEquivalencies()->count();
        $eligible = $this->courseEquivalencies()->where('is_eligible', true)->count();

        $this->update([
            'total_equivalencies' => $total,
            'eligible_count' => $eligible,
            'not_eligible_count' => $total - $eligible,
        ]);
    }

    /**
     * Check if the list can be edited
     * Only draft and rejected lists can be edited
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REJECTED]);
    }

    /**
     * Check if the list can be submitted
     */
    public function canBeSubmitted(): bool
    {
        return $this->status === self::STATUS_DRAFT && $this->total_equivalencies > 0;
    }

    /**
     * Check if the list can be reviewed (HEA)
     */
    public function canBeReviewed(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    /**
     * Check if the list can be endorsed (HEA)
     */
    public function canBeEndorsed(): bool
    {
        return in_array($this->status, [self::STATUS_SUBMITTED, self::STATUS_UNDER_REVIEW]);
    }

    /**
     * Check if the list can be published (HEA)
     */
    public function canBePublished(): bool
    {
        return $this->status === self::STATUS_ENDORSED;
    }

    /**
     * Check if the list is in draft status
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Check if the list is submitted
     */
    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    /**
     * Check if the list is under review
     */
    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    /**
     * Check if the list is endorsed
     */
    public function isEndorsed(): bool
    {
        return $this->status === self::STATUS_ENDORSED;
    }

    /**
     * Check if the list is published
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Check if the list is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if the list is archived
     */
    public function isArchived(): bool
    {
        return $this->status === self::STATUS_ARCHIVED;
    }
}
