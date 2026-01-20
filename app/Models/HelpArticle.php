<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HelpArticle extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'content',
        'sort_order',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for active articles
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
     * Get all articles grouped by category
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

    /**
     * Find by slug
     */
    public static function findBySlug(string $slug): ?self
    {
        return self::where('slug', $slug)->first();
    }
}
