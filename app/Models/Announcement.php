<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'content',
        'type',
        'target_roles',
        'starts_at',
        'ends_at',
        'is_dismissible',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'target_roles' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_dismissible' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for active announcements
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });
    }

    /**
     * Scope for announcements visible to a specific role
     */
    public function scopeForRole($query, string $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->whereNull('target_roles')
                ->orWhereJsonContains('target_roles', 'all')
                ->orWhereJsonContains('target_roles', $role);
        });
    }

    /**
     * Get active announcements for a user's role
     */
    public static function getForUser(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return self::active()
            ->forRole($user->current_role)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get Bootstrap alert class based on type
     */
    public function getAlertClassAttribute(): string
    {
        return match($this->type) {
            'warning' => 'alert-warning',
            'success' => 'alert-success',
            'danger' => 'alert-danger',
            default => 'alert-info',
        };
    }

    /**
     * Get icon based on type
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'warning' => 'bi-exclamation-triangle',
            'success' => 'bi-check-circle',
            'danger' => 'bi-x-circle',
            default => 'bi-info-circle',
        };
    }
}
