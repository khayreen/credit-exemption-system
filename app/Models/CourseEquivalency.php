<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEquivalency extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'diploma_course_code', 'diploma_course_name', 'diploma_credit_hour', 'diploma_institution',
        'degree_course_code', 'degree_course_name', 'degree_credit_hour', 'program_code', 'match_percentage', 'approved_by_user_id', 'notes'
    ];

    public function degreeCourse() { return $this->belongsTo(Course::class, 'degree_course_code', 'code'); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by_user_id'); }
}
