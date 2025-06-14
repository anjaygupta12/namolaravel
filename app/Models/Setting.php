<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['key', 'value', 'category', 'description'];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
    
    /**
     * Get all settings in a specific category
     *
     * @param string $category
     * @return \Illuminate\Support\Collection
     */
    public static function getCategory($category)
    {
        return self::where('category', $category)->get()->keyBy('key');
    }
    
    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param string $category
     * @param string|null $description
     * @return void
     */
    public static function setValue($key, $value, $category = 'general', $description = null)
    {
        self::updateOrCreate(
            ['key' => $key, 'category' => $category],
            ['value' => $value, 'description' => $description]
        );
    }
    
    /**
     * Set multiple settings at once
     *
     * @param array $settings
     * @param string $category
     * @return void
     */
    public static function setMultiple(array $settings, $category = 'general')
    {
        foreach ($settings as $key => $value) {
            self::setValue($key, $value, $category);
        }
    }
}
