<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    /**
     * Get a setting value by key, with optional caching
     */
    public static function getValue(string $key, $default = null)
    {
        // If settings table does not exist (prior to migration), return default
        if (!Schema::hasTable((new static)->getTable())) {
            return $default;
        }
        return Cache::remember("setting_{$key}", 60, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Update setting and clear cache
     */
    public static function setValue(string $key, $value)
    {
        $model = static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting_{$key}");
        return $model;
    }
}
