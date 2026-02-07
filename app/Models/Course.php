<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    public $incrementing = false; // Because the primary key 'code' is a string
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $fillable = ['code', 'name', 'credit_hour', 'program_code', 'semester', 'type'];
}
