<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourcePerson extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resource_persons'; // Add this line

    protected $fillable = ['user_id', 'staff_id', 'department', 'expertise_area', 'assigned_programs'];

    protected $casts = [
        'assigned_programs' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }
}