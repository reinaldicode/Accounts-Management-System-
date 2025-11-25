<?php
// app/Models/AmsSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmsSetting extends Model
{
    protected $table = 'ams_settings';
    public $timestamps = true;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Helper methods
    public static function get($key, $default = null)
    {
        $setting = self::where('setting_key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->setting_value, $setting->setting_type);
    }

    public static function set($key, $value, $type = 'string')
    {
        return self::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'setting_type' => $type,
            ]
        );
    }

    private static function castValue($value, $type)
    {
        switch ($type) {
            case 'integer':
                return (int) $value;
            case 'boolean':
                return (bool) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    public function getValue()
    {
        return self::castValue($this->setting_value, $this->setting_type);
    }
}