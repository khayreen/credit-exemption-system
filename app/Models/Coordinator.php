<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinator extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['user_id', 'staff_id', 'department'];
    public function user() { return $this->belongsTo(User::class); }
}
