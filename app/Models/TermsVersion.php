<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TermsVersion extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'version',
        'content',
        'effective_date',
        'is_current',
        'created_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'is_current' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the current (active) terms version
     */
    public static function getCurrent(): ?self
    {
        return self::where('is_current', true)->first();
    }

    /**
     * Set this version as current and unset others
     */
    public function setAsCurrent(): void
    {
        DB::transaction(function () {
            self::where('is_current', true)->update(['is_current' => false]);
            $this->update(['is_current' => true]);
        });
    }

    /**
     * Get version history ordered by date
     */
    public static function getHistory(): \Illuminate\Database\Eloquent\Collection
    {
        return self::orderBy('created_at', 'desc')->get();
    }

    /**
     * Generate next version number
     */
    public static function generateNextVersion(): string
    {
        $latest = self::orderBy('created_at', 'desc')->first();

        if (!$latest) {
            return '1.0';
        }

        $parts = explode('.', $latest->version);
        $major = (int) ($parts[0] ?? 1);
        $minor = (int) ($parts[1] ?? 0);

        return $major . '.' . ($minor + 1);
    }
}
