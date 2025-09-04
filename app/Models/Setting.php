<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group'
    ];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = Cache::remember("setting_{$key}", 3600, function () use ($key) {
            return static::where('key', $key)->first();
        });

        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general'): bool
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => (string) $value,
                'type' => $type,
                'group' => $group
            ]
        );

        // Clear cache
        Cache::forget("setting_{$key}");

        return $setting ? true : false;
    }

    /**
     * Get settings by group
     */
    public static function getGroup(string $group): array
    {
        $settings = Cache::remember("settings_group_{$group}", 3600, function () use ($group) {
            return static::where('group', $group)->get();
        });

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = static::castValue($setting->value, $setting->type);
        }

        return $result;
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            case 'string':
            default:
                return $value;
        }
    }

    /**
     * Get the site logo URL
     */
    public static function getLogoUrl(): ?string
    {
        $logoPath = static::get('site_logo', null);

        if ($logoPath && file_exists(public_path('storage/' . $logoPath))) {
            return asset('storage/' . $logoPath);
        }

        // Return null if logo not found, so default icon + text can be used
        return null;
    }

    /**
     * Get the site name
     */
    public static function getSiteName(): string
    {
        return static::get('site_name', 'PharmaCare');
    }
}
