<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalLecturer extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['user_id', 'name', 'email', 'institution_name', 'phone_number'];
    public function user() { return $this->belongsTo(User::class); }
}
