<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CourseEquivalencyRequest extends Model
{
    use HasUuids;

    protected $fillable = [
        'student_id',
        'coordinator_id',
        'diploma_course_code',
        'diploma_course_name',
        'diploma_institution',
        'diploma_program',
        'diploma_credit_hours',
        'diploma_grade',
        'suggested_degree_course_code',
        'suggested_degree_course_name',
        'current_program_code',
        'current_program_name',
        'justification',
        'external_lecturer_name',
        'external_lecturer_email',
        'coordinator_decision',
        'coordinator_notes',
        'coordinator_decided_at',
        'selected_lecturer_name',
        'selected_lecturer_email',
        'syllabus_request_sent_at',
        'syllabus_received_at',
        'status',
        'reviewed_by',
        'reviewer_notes',
        'reviewed_at',
        'approved_degree_course_code',
        'approved_degree_course_name',
        'match_percentage',
    ];

    protected $casts = [
        'diploma_credit_hours' => 'decimal:2',
        'match_percentage' => 'integer',
        'reviewed_at' => 'datetime',
        'coordinator_decided_at' => 'datetime',
        'syllabus_request_sent_at' => 'datetime',
        'syllabus_received_at' => 'datetime',
    ];

    /**
     * Relationship: Belongs to Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Relationship: Belongs to Program Coordinator
     */
    public function coordinator()
    {
        return $this->belongsTo(ProgramCoordinator::class, 'coordinator_id');
    }

    /**
     * Relationship: Belongs to Resource Person (reviewer)
     */
    public function reviewer()
    {
        return $this->belongsTo(ResourcePerson::class, 'reviewed_by');
    }

    /**
     * Relationship: Has one External Lecturer Request
     */
    public function externalLecturerRequest()
    {
        return $this->hasOne(ExternalLecturerRequest::class, 'course_equivalency_request_id');
    }
}
