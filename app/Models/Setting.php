<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::getAllCached();

        if (!isset($settings[$key])) {
            return $default;
        }

        return static::castValue($settings[$key]['value'], $settings[$key]['type']);
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value, string $type = 'string'): void
    {
        if ($type === 'encrypted' && !empty($value)) {
            $value = Crypt::encryptString($value);
        }

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );

        static::flushCache();
    }

    /**
     * Get all settings from cache or database.
     */
    public static function getAllCached(): array
    {
        return Cache::rememberForever('app_settings', function () {
            return static::all()->keyBy('key')->map(function ($setting) {
                return [
                    'value' => $setting->value,
                    'type' => $setting->type,
                ];
            })->toArray();
        });
    }

    /**
     * Get all settings as a simple key => castValue array.
     */
    public static function getAllAsKeyValue(): array
    {
        $settings = static::getAllCached();
        $result = [];

        foreach ($settings as $key => $data) {
            $result[$key] = static::castValue($data['value'], $data['type']);
        }

        return $result;
    }

    /**
     * Flush the settings cache.
     */
    public static function flushCache(): void
    {
        Cache::forget('app_settings');
    }

    /**
     * Cast a raw value to its proper PHP type.
     */
    protected static function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'integer' => (int) $value,
            'float' => (float) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'encrypted' => !empty($value) ? static::safeDecrypt($value) : $value,
            default => $value,
        };
    }

    /**
     * Safely decrypt a value, returning the raw value if decryption fails.
     */
    protected static function safeDecrypt(string $value): string
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }
}
