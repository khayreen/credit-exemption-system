<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityEvent extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'event_type',
        'user_id',
        'ip_address',
        'request_data',
        'route',
        'blocked',
        'details',
    ];

    protected $casts = [
        'blocked' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log a security event
     */
    public static function log(
        string $eventType,
        ?string $details = null,
        bool $blocked = true,
        ?array $requestData = null
    ): self {
        return self::create([
            'event_type' => $eventType,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'route' => request()->path(),
            'blocked' => $blocked,
            'details' => $details,
            'request_data' => $requestData ? json_encode($requestData) : null,
        ]);
    }

    /**
     * Scope for specific event type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Get event type label
     */
    public function getEventTypeLabelAttribute(): string
    {
        return match($this->event_type) {
            'sql_injection' => 'SQL Injection Attempt',
            'xss_attempt' => 'XSS Attempt',
            'csrf_failure' => 'CSRF Token Failure',
            'suspicious_input' => 'Suspicious Input',
            'brute_force' => 'Brute Force Attack',
            default => 'Other Security Event',
        };
    }

    /**
     * Get badge color based on event type
     */
    public function getBadgeColorAttribute(): string
    {
        return match($this->event_type) {
            'sql_injection', 'xss_attempt' => 'danger',
            'brute_force' => 'warning',
            'csrf_failure' => 'info',
            default => 'secondary',
        };
    }
}
