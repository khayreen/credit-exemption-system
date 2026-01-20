<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'key',
        'label',
        'value',
        'type',
        'updated_by',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get a contact setting value by key
     */
    public static function getValue(string $key, string $default = ''): string
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a contact setting value
     */
    public static function setValue(string $key, string $value, ?string $label = null, string $type = 'text', ?string $updatedBy = null): self
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'label' => $label ?? $key,
                'type' => $type,
                'updated_by' => $updatedBy,
            ]
        );
    }

    /**
     * Get all contact settings as key-value pairs
     */
    public static function getAllAsArray(): array
    {
        return self::pluck('value', 'key')->toArray();
    }
}
