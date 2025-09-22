<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Language extends Model
{

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'native_name',
        'direction',
        'is_active',
        'is_default',
        'flag_url',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];


    /**
     * Scope for active languages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the default language
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->first();
    }

    /**
     * Set as default language
     */
    public function setAsDefault()
    {
        // Remove default from other languages
        static::where('is_default', true)->update(['is_default' => false]);
        
        // Set this as default
        $this->update(['is_default' => true]);
    }
}
