<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'audit_trail'; // Explicitly define table name
    protected $fillable = ['user_id', 'action', 'target_entity', 'target_id', 'ip_address', 'details'];
    public function user() { return $this->belongsTo(User::class); }
}
