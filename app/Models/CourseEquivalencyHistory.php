<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEquivalencyHistory extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'course_equivalency_history';

    protected $fillable = [
        'equivalency_list_id',
        'original_equivalency_id',
        'program_code',
        'diploma_course_code',
        'diploma_course_name',
        'diploma_credit_hour',
        'diploma_institution',
        'degree_course_code',
        'degree_course_name',
        'degree_credit_hour',
        'match_percentage',
        'is_eligible',
        'action',
        'archived_by_user_id',
        'archived_at',
        'archive_reason',
    ];

    protected $casts = [
        'is_eligible' => 'boolean',
        'archived_at' => 'datetime',
        'match_percentage' => 'decimal:2',
    ];

    /**
     * Action constants
     */
    const ACTION_DELETED = 'deleted';
    const ACTION_REPLACED = 'replaced';
    const ACTION_ARCHIVED = 'archived';

    /**
     * Get the equivalency list this history belongs to
     */
    public function equivalencyList()
    {
        return $this->belongsTo(EquivalencyList::class);
    }

    /**
     * Get the user who archived this
     */
    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by_user_id');
    }

    /**
     * Get the original equivalency (if it still exists)
     */
    public function originalEquivalency()
    {
        return $this->belongsTo(CourseEquivalency::class, 'original_equivalency_id');
    }
}
