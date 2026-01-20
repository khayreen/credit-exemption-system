<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DegreeCourseSyllabus extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'degree_course_syllabi';

    protected $fillable = [
        'course_code',
        'course_name',
        'credit_hours',
        'program_code',
        'syllabus_file_path',
        'syllabus_file_original_name',
        'digital_signature',
        'description',
        'academic_session',
        'uploaded_by',
        'is_active',
    ];

    protected $casts = [
        'credit_hours' => 'decimal:1',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who uploaded this syllabus.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope to get only active syllabi.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by program code.
     */
    public function scopeForProgram($query, string $programCode)
    {
        return $query->where('program_code', $programCode);
    }

    /**
     * Scope to filter by course code prefix (e.g., 'CS' for CS110, CS232, etc.).
     */
    public function scopeWithCodePrefix($query, string $prefix)
    {
        return $query->where('course_code', 'LIKE', $prefix . '%');
    }

    /**
     * Get the full storage path of the syllabus file.
     */
    public function getFullFilePathAttribute(): string
    {
        return storage_path('app/' . $this->syllabus_file_path);
    }

    /**
     * Check if the syllabus file exists.
     */
    public function fileExists(): bool
    {
        return file_exists($this->full_file_path);
    }
}
