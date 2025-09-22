<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyTranslation extends Model
{
    protected $fillable = [
        'property_id',
        'locale',
        'title',
        'description',
        'short_description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'features',
        'amenities',
        'neighborhood_info',
        'school_district',
        'transportation',
        'nearby_attractions',
    ];

    protected $casts = [
        'features' => 'array',
        'amenities' => 'array',
    ];

    /**
     * Get the property that owns the translation.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Scope for specific locale
     */
    public function scopeLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }
}
