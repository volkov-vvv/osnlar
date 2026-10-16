<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';

    protected $guarded = false;

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $value = Cache::rememberForever(self::cacheKey($key), function () use ($key) {
            return static::query()->where('key', $key)->value('value');
        });

        return $value ?? $default;
    }

    public static function getBool(string $key, bool $default = false): bool
    {
        $value = self::getValue($key, $default ? '1' : '0');

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function setValue(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value]
        );

        Cache::forget(self::cacheKey($key));
    }

    public static function isLidCaptchaEnabled(): bool
    {
        return self::getBool('lid_captcha_enabled', true);
    }

    public static function bioeconomyDocuments(): array
    {
        return [
            [
                'key' => 'bioeconomy_presentation',
                'input' => 'presentation',
                'title' => 'Презентация проекта',
                'icon' => 'fas fa-file-powerpoint',
            ],
            [
                'key' => 'bioeconomy_order',
                'input' => 'order',
                'title' => 'Приказ Минпромторга от 30.12.2025 №6807',
                'icon' => 'fas fa-scroll',
            ],
            [
                'key' => 'bioeconomy_organizations',
                'input' => 'organizations',
                'title' => 'Список программ',
                'icon' => 'fas fa-clipboard-list',
            ],
        ];
    }

    protected static function cacheKey(string $key): string
    {
        return 'setting.' . $key;
    }
}
