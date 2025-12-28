<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExemptionApplication extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'student_id', 'previous_institution', 'previous_program', 'previous_program_code', 'status',
        'student_name', 'matric_no', 'ic_number', 'home_address',
        'current_program', 'current_program_code', 'student_group', 'current_campus', 'current_faculty',
        'current_semester', 'reviewed_by', 'reviewed_at'
    ];

    public function student() { return $this->belongsTo(Student::class); }
    public function transcript() { return $this->hasOne(Transcript::class); }
    public function applicationSubjects() { return $this->hasMany(ApplicationSubject::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
}
