<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function getSetting($key, $default = null)
    {
        return self::where('key', $key)->value('value') ?? $default;
    }

    public static function setSetting($key, $value)
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    // In your Setting model
    public static function updateSettings(array $settings)
    {
        foreach ($settings as $key => $value) {
            self::setSetting($key, $value); // Use the existing setSetting method
        }
    }

}
