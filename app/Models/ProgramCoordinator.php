<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramCoordinator extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'program_codes',
        'program_category',
    ];

    protected $casts = [
        'program_codes' => 'array',
    ];

    /**
     * Get the user associated with this program coordinator
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get course equivalency requests handled by this coordinator
     */
    public function courseEquivalencyRequests(): HasMany
    {
        return $this->hasMany(CourseEquivalencyRequest::class, 'coordinator_id');
    }

    /**
     * Check if this coordinator handles a specific program code
     */
    public function handlesProgram(string $programCode): bool
    {
        return in_array($programCode, $this->program_codes);
    }

    /**
     * Get the appropriate coordinator for a given program code
     */
    public static function getCoordinatorForProgram(string $programCode): ?self
    {
        return self::all()->first(function ($coordinator) use ($programCode) {
            return $coordinator->handlesProgram($programCode);
        });
    }
}
