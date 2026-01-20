<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'route',
        'method',
        'ip_address',
        'status',
        'denial_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for denied access
     */
    public function scopeDenied($query)
    {
        return $query->where('status', 'denied');
    }

    /**
     * Log an access attempt
     */
    public static function log(string $route, string $method, string $status, ?string $denialReason = null, ?string $userId = null): self
    {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'route' => $route,
            'method' => $method,
            'ip_address' => request()->ip(),
            'status' => $status,
            'denial_reason' => $denialReason,
        ]);
    }
}
