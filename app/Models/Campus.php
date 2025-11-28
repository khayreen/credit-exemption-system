<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name', 'code', 'state', 'address', 'is_main_campus'
    ];

    protected $casts = [
        'is_main_campus' => 'boolean'
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
