<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleTranslation extends Model
{
    protected $fillable = [
        'vehicle_id',
        'locale',
        'title',
        'description',
        'short_description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'features',
        'specifications',
        'warranty_info',
        'service_history',
        'accident_history',
        'maintenance_records',
    ];

    protected $casts = [
        'features' => 'array',
        'specifications' => 'array',
    ];

    /**
     * Get the vehicle that owns the translation.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Scope for specific locale
     */
    public function scopeLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }
}
