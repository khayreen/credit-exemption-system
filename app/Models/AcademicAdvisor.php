<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicAdvisor extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'academic_advisors';
    protected $fillable = [
        'user_id',
        'staff_id',
        'department',
        'specialization',
        'assigned_programs',
        'program_groups',
        'name',
        'email',
    ];

    protected $casts = [
        'assigned_programs' => 'array',
        'program_groups' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
