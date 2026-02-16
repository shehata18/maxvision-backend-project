<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Setting Model
 *
 * Flexible key-value store for site-wide configuration.
 * Stores settings like site name, contact info, social media links, etc.
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string $type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * Usage examples:
 * - Setting::get('site_name') → "MaxVision LED"
 * - Setting::set('contact_phone', '+1-234-567-8900')
 * - Setting::getAll() → ['site_name' => 'MaxVision LED', 'contact_phone' => '...']
 * - Setting::getByType('social') → collection of social media settings
 */
class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Retrieve a setting value by key.
     *
     * @param string $key The setting key
     * @param mixed $default Default value if setting not found
     * @return mixed The setting value or default
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $setting->value;
    }

    /**
     * Update or create a setting.
     *
     * @param string $key The setting key
     * @param string|null $value The setting value
     * @param string $type The setting type (string, text, json, boolean)
     * @return Setting
     */
    public static function set(string $key, ?string $value, string $type = 'string'): Setting
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }

    /**
     * Get all settings as a key-value array.
     *
     * @return array<string, string|null>
     */
    public static function getAll(): array
    {
        return static::pluck('value', 'key')->toArray();
    }

    /**
     * Get settings filtered by type.
     *
     * @param string $type The type to filter by
     * @return \Illuminate\Database\Eloquent\Collection<int, Setting>
     */
    public static function getByType(string $type)
    {
        return static::where('type', $type)->get();
    }
}
