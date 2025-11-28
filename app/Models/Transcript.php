<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcript extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'exemption_application_id', 
        'original_filename', 
        'file_path', 
        'digital_signature', 
        'file_hash',
        'ocr_status',
        'ocr_processed_at',
        'ocr_raw_text',
        'ocr_error_message',
        'ocr_confidence_score'
    ];
    public function exemptionApplication() { return $this->belongsTo(ExemptionApplication::class); }
}
