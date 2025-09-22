<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxClass extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the tax rates for this class.
     */
    public function taxRates(): HasMany
    {
        return $this->hasMany(TaxRate::class);
    }

    /**
     * Get the tenant that owns the tax class.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope for active tax classes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get applicable tax rate for a location
     */
    public function getTaxRateForLocation($country, $state = null, $city = null, $postalCode = null)
    {
        $query = $this->taxRates()
                     ->where('country_code', $country)
                     ->where('is_active', true);

        if ($state) {
            $query->where(function($q) use ($state) {
                $q->where('state_code', $state)
                  ->orWhereNull('state_code');
            });
        }

        if ($city) {
            $query->where(function($q) use ($city) {
                $q->where('city', $city)
                  ->orWhereNull('city');
            });
        }

        if ($postalCode) {
            $query->where(function($q) use ($postalCode) {
                $q->where('postal_code', $postalCode)
                  ->orWhereNull('postal_code');
            });
        }

        return $query->orderBy('priority', 'desc')->first();
    }
}
