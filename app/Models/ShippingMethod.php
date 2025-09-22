<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingMethod extends Model
{
    protected $fillable = [
        'tenant_id',
        'shipping_zone_id',
        'name',
        'method_type',
        'description',
        'cost',
        'free_shipping_threshold',
        'settings',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the shipping zone that owns the method.
     */
    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class);
    }

    /**
     * Get the tenant that owns the shipping method.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope for active methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Calculate shipping cost for an order
     */
    public function calculateCost($orderAmount = 0, $weight = 0, $dimensions = null, $vendorId = null)
    {
        // Check if free shipping threshold is met
        if ($this->free_shipping_threshold && $orderAmount >= $this->free_shipping_threshold) {
            return 0;
        }

        $cost = $this->cost;

        // Apply method-specific calculations
        switch ($this->method_type) {
            case 'flat_rate':
                return $cost;

            case 'weight_based':
                return $cost * $weight;

            case 'carrier_based':
                // This would integrate with carrier APIs
                return $this->getCarrierRate($weight, $dimensions);

            case 'vendor_based':
                return $this->getVendorRate($vendorId, $orderAmount);

            case 'product_based':
                return $this->getProductBasedRate($orderAmount);

            default:
                return $cost;
        }
    }

    /**
     * Get carrier-based shipping rate
     */
    private function getCarrierRate($weight, $dimensions)
    {
        // This would integrate with carrier APIs like DHL, FedEx
        // For now, return base cost
        return $this->cost;
    }

    /**
     * Get vendor-based shipping rate
     */
    private function getVendorRate($vendorId, $orderAmount)
    {
        if (!$vendorId) {
            return $this->cost;
        }

        $vendorSettings = VendorShippingSettings::where('vendor_id', $vendorId)->first();
        if ($vendorSettings && isset($vendorSettings->custom_rates[$this->id])) {
            return $vendorSettings->custom_rates[$this->id];
        }

        return $this->cost;
    }

    /**
     * Get product-based shipping rate
     */
    private function getProductBasedRate($orderAmount)
    {
        // This would calculate based on product shipping settings
        return $this->cost;
    }
}
