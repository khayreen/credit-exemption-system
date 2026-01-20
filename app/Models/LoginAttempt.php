<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'user_agent',
        'status',
        'failure_reason',
        'attempted_at',
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for failed attempts
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for successful attempts
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for recent attempts (within specified minutes)
     */
    public function scopeRecent($query, int $minutes = 15)
    {
        return $query->where('attempted_at', '>=', now()->subMinutes($minutes));
    }

    /**
     * Get failed attempts count for an email within specified minutes
     */
    public static function getFailedAttemptsCount(string $email, int $minutes = 15): int
    {
        return self::where('email', $email)
            ->failed()
            ->recent($minutes)
            ->count();
    }

    /**
     * Log a login attempt
     */
    public static function log(string $email, string $status, ?string $failureReason = null, ?string $userId = null): self
    {
        return self::create([
            'user_id' => $userId,
            'email' => $email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => $status,
            'failure_reason' => $failureReason,
            'attempted_at' => now(),
        ]);
    }
}
