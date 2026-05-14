<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PlatformSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    private const CACHE_KEY = 'platform_settings_map_v2';

    public static function flushCachedMap(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * @return array<string, string|null>
     */
    public static function cachedMap(): array
    {
        return Cache::remember(self::CACHE_KEY, 300, function () {
            return static::query()->pluck('value', 'key')->all();
        });
    }

    public static function getValue(string $key, ?string $default = null): ?string
    {
        $map = static::cachedMap();
        if (! array_key_exists($key, $map)) {
            return $default;
        }

        $v = $map[$key];

        return $v === null ? $default : $v;
    }

    public static function getBool(string $key, bool $default = false): bool
    {
        $v = static::getValue($key);

        if ($v === null) {
            return $default;
        }

        return in_array(strtolower(trim($v)), ['1', 'true', 'yes', 'on'], true);
    }

    public static function setValue(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        static::flushCachedMap();
    }
}
