<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'category',
        'question',
        'answer',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active FAQ items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Get all FAQ items grouped by category
     */
    public static function getGroupedByCategory(): \Illuminate\Support\Collection
    {
        return self::active()
            ->ordered()
            ->get()
            ->groupBy('category');
    }

    /**
     * Get unique categories
     */
    public static function getCategories(): \Illuminate\Support\Collection
    {
        return self::whereNotNull('category')
            ->distinct()
            ->pluck('category');
    }
}
