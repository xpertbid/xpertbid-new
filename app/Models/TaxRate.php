<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRate extends Model
{
    protected $fillable = [
        'tenant_id',
        'tax_class_id',
        'name',
        'country_code',
        'state_code',
        'city',
        'postal_code',
        'rate',
        'tax_type',
        'is_compound',
        'is_shipping_taxable',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'is_compound' => 'boolean',
        'is_shipping_taxable' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the tax class that owns the rate.
     */
    public function taxClass(): BelongsTo
    {
        return $this->belongsTo(TaxClass::class);
    }

    /**
     * Get the tenant that owns the tax rate.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope for active tax rates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Calculate tax amount
     */
    public function calculateTax($amount, $isShipping = false)
    {
        if ($isShipping && !$this->is_shipping_taxable) {
            return 0;
        }

        return $amount * $this->rate;
    }

    /**
     * Get formatted tax rate
     */
    public function getFormattedRateAttribute()
    {
        return number_format($this->rate * 100, 2) . '%';
    }

    /**
     * Get location description
     */
    public function getLocationDescriptionAttribute()
    {
        $location = $this->country_code;
        
        if ($this->state_code) {
            $location .= ', ' . $this->state_code;
        }
        
        if ($this->city) {
            $location .= ', ' . $this->city;
        }
        
        if ($this->postal_code) {
            $location .= ' ' . $this->postal_code;
        }
        
        return $location;
    }
}
