<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id', 'matric_no', 'program_name', 'ic_number', 'campus', 'intake_semester',
        'home_address', 'faculty_id'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function faculty() { return $this->belongsTo(Faculty::class); }
    public function exemptionApplications() { return $this->hasMany(ExemptionApplication::class); }
}
