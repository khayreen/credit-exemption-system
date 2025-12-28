<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExternalLecturerRequest extends Model
{
    use HasUuids;

    protected $fillable = [
        'application_subject_id',
        'course_equivalency_request_id',
        'external_lecturer_email',
        'external_lecturer_name',
        'request_notes',
        'status',
        'access_token',
        'token_expires_at',
        'submitted_at'
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function applicationSubject(): BelongsTo
    {
        return $this->belongsTo(ApplicationSubject::class);
    }

    public function courseEquivalencyRequest(): BelongsTo
    {
        return $this->belongsTo(CourseEquivalencyRequest::class);
    }

    public function submission(): HasOne
    {
        return $this->hasOne(ExternalLecturerSubmission::class);
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->token_expires_at);
    }

    public function markAsSubmitted(): void
    {
        $this->update([
            'status' => 'submitted',
            'submitted_at' => now()
        ]);
    }
}
