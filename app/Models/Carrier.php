<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'logo_url',
        'description',
        'api_settings',
        'supported_countries',
        'supported_services',
        'is_active',
        'is_integrated',
        'base_rate',
        'rate_calculation',
        'sort_order',
    ];

    protected $casts = [
        'api_settings' => 'array',
        'supported_countries' => 'array',
        'supported_services' => 'array',
        'rate_calculation' => 'array',
        'is_active' => 'boolean',
        'is_integrated' => 'boolean',
        'base_rate' => 'decimal:2',
    ];

    /**
     * Get the tenant that owns the carrier.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope for active carriers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for integrated carriers
     */
    public function scopeIntegrated($query)
    {
        return $query->where('is_integrated', true);
    }

    /**
     * Calculate shipping rate using carrier API
     */
    public function calculateRate($origin, $destination, $weight, $dimensions, $service = 'standard')
    {
        if (!$this->is_integrated) {
            return $this->base_rate;
        }

        // This would integrate with actual carrier APIs
        // For now, return a calculated rate based on weight and distance
        $rate = $this->base_rate;
        
        if ($this->rate_calculation) {
            $rate += $weight * ($this->rate_calculation['weight_rate'] ?? 0);
            $rate += $this->calculateDistanceRate($origin, $destination);
        }

        return $rate;
    }

    /**
     * Calculate distance-based rate
     */
    private function calculateDistanceRate($origin, $destination)
    {
        // This would use geolocation APIs to calculate distance
        // For now, return a fixed rate
        return 5.00;
    }

    /**
     * Get available services for a country
     */
    public function getAvailableServices($countryCode)
    {
        if (!$this->supported_countries || in_array($countryCode, $this->supported_countries)) {
            return $this->supported_services ?? ['standard', 'express'];
        }

        return [];
    }
}
