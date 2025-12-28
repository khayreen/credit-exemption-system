<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEquivalency extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'equivalency_list_id',
        'version',
        'is_published',
        'diploma_course_code',
        'diploma_course_name',
        'diploma_credit_hour',
        'diploma_institution',
        'degree_course_code',
        'degree_course_name',
        'degree_credit_hour',
        'program_code',
        'match_percentage',
        'is_eligible',
        'approved_by_user_id',
        'notes',
        'source',
    ];

    protected $casts = [
        'is_eligible' => 'boolean',
        'is_published' => 'boolean',
        'match_percentage' => 'float',
        'version' => 'integer',
    ];

    /**
     * Get the equivalency list this course belongs to
     */
    public function equivalencyList()
    {
        return $this->belongsTo(EquivalencyList::class, 'equivalency_list_id');
    }

    /**
     * Get the degree course
     */
    public function degreeCourse()
    {
        return $this->belongsTo(Course::class, 'degree_course_code', 'code');
    }

    /**
     * Get the user who approved this equivalency
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Scope: Filter by published status
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope: Filter by eligibility
     */
    public function scopeEligible($query)
    {
        return $query->where('is_eligible', true);
    }

    /**
     * Scope: Filter by program code
     */
    public function scopeForProgram($query, $programCode)
    {
        return $query->where('program_code', $programCode);
    }

    /**
     * Scope: Filter by list
     */
    public function scopeInList($query, $listId)
    {
        return $query->where('equivalency_list_id', $listId);
    }
}
