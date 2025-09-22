<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'countries',
        'states',
        'cities',
        'postal_codes',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'countries' => 'array',
        'states' => 'array',
        'cities' => 'array',
        'postal_codes' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the shipping methods for this zone.
     */
    public function shippingMethods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class);
    }

    /**
     * Get the tenant that owns the shipping zone.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope for active zones
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if a location is covered by this zone
     */
    public function coversLocation($country, $state = null, $city = null, $postalCode = null)
    {
        // Check country
        if ($this->countries && !in_array($country, $this->countries)) {
            return false;
        }

        // Check state
        if ($state && $this->states && !in_array($state, $this->states)) {
            return false;
        }

        // Check city
        if ($city && $this->cities && !in_array($city, $this->cities)) {
            return false;
        }

        // Check postal code
        if ($postalCode && $this->postal_codes) {
            $covered = false;
            foreach ($this->postal_codes as $range) {
                if (is_array($range) && count($range) === 2) {
                    // Range format: [start, end]
                    if ($postalCode >= $range[0] && $postalCode <= $range[1]) {
                        $covered = true;
                        break;
                    }
                } else {
                    // Single postal code
                    if ($postalCode === $range) {
                        $covered = true;
                        break;
                    }
                }
            }
            if (!$covered) {
                return false;
            }
        }

        return true;
    }
}
