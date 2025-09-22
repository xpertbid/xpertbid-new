<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorShippingSettings extends Model
{
    protected $fillable = [
        'tenant_id',
        'vendor_id',
        'shipping_policy',
        'free_shipping_enabled',
        'free_shipping_threshold',
        'handling_fee',
        'shipping_methods',
        'excluded_zones',
        'custom_rates',
        'is_active',
    ];

    protected $casts = [
        'free_shipping_enabled' => 'boolean',
        'free_shipping_threshold' => 'decimal:2',
        'handling_fee' => 'decimal:2',
        'shipping_methods' => 'array',
        'excluded_zones' => 'array',
        'custom_rates' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the tenant that owns the settings.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the vendor that owns the settings.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Check if free shipping is available for order amount
     */
    public function isFreeShippingAvailable($orderAmount)
    {
        return $this->free_shipping_enabled && 
               $this->free_shipping_threshold && 
               $orderAmount >= $this->free_shipping_threshold;
    }

    /**
     * Get custom shipping rate for a method
     */
    public function getCustomRate($methodId)
    {
        return $this->custom_rates[$methodId] ?? null;
    }

    /**
     * Check if a shipping zone is excluded
     */
    public function isZoneExcluded($zoneId)
    {
        return in_array($zoneId, $this->excluded_zones ?? []);
    }

    /**
     * Get available shipping methods for this vendor
     */
    public function getAvailableMethods()
    {
        if (!$this->shipping_methods) {
            return ShippingMethod::active()->get();
        }

        return ShippingMethod::whereIn('id', $this->shipping_methods)
                           ->active()
                           ->get();
    }
}
