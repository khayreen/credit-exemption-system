<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalLecturerSubmission extends Model
{
    use HasUuids;

    protected $fillable = [
        'external_lecturer_request_id',
        'institution_name',
        'course_code',
        'course_name',
        'credit_hours',
        'justification_notes',
        'syllabus_file_path',
        'syllabus_file_original_name',
        'digital_signature'
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(ExternalLecturerRequest::class, 'external_lecturer_request_id');
    }
}
