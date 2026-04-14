<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'label', 'group'];

    /**
     * Get a setting value by key with caching.
     */
    public static function get(string $key, $default = null)
    {
        $setting = Cache::remember("setting_{$key}", 3600, function () use ($key) {
            return self::where('key', $key)->first();
        });

        if (!$setting) return $default;

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value by key and clear cache.
     */
    public static function set(string $key, $value)
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            $setting->update(['value' => $value]);
            Cache::forget("setting_{$key}");
        }
    }

    /**
     * Cast the string value to the appropriate type.
     */
    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'decimal', 'float' => (float) $value,
            'integer', 'int'   => (int) $value,
            'boolean', 'bool'  => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json'             => json_decode($value, true),
            default            => $value,
        };
    }
}
