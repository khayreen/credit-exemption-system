<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApplicationSubject extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'exemption_application_id',
        'course_code',
        'course_name',
        'credit_hour',
        'grade',
        'status',
        'syllabus_path',
        'notes',
        'extraction_method',
        'ocr_confidence_score',
        'needs_verification',
        'exemption_reason'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get the exemption application that owns this subject.
     */
    public function exemptionApplication(): BelongsTo
    {
        return $this->belongsTo(ExemptionApplication::class);
    }
}
