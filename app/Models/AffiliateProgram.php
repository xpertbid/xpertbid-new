<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AffiliateProgram extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'type',
        'commission_rate',
        'fixed_commission',
        'commission_type',
        'minimum_payout',
        'cookie_duration',
        'terms_conditions',
        'is_active',
        'requires_approval',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:4',
        'fixed_commission' => 'decimal:2',
        'cookie_duration' => 'integer',
        'minimum_payout' => 'decimal:2',
        'terms_conditions' => 'array',
        'is_active' => 'boolean',
        'requires_approval' => 'boolean',
    ];

    /**
     * Get the affiliates for this program.
     */
    public function affiliates(): HasMany
    {
        return $this->hasMany(Affiliate::class);
    }

    /**
     * Get the tenant that owns the program.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope for active programs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Calculate commission for an order
     */
    public function calculateCommission($orderAmount, $productId = null, $categoryId = null)
    {
        $commission = 0;

        switch ($this->commission_type) {
            case 'percentage':
                $commission = $orderAmount * $this->commission_rate;
                break;
            case 'fixed':
                $commission = $this->fixed_commission;
                break;
            case 'hybrid':
                $commission = ($orderAmount * $this->commission_rate) + $this->fixed_commission;
                break;
        }

        // Apply program-specific rules
        if ($this->type === 'product_specific' && $productId) {
            $commission = $this->getProductCommission($productId, $commission);
        }

        if ($this->type === 'category_specific' && $categoryId) {
            $commission = $this->getCategoryCommission($categoryId, $commission);
        }

        return max(0, $commission);
    }

    /**
     * Get product-specific commission
     */
    private function getProductCommission($productId, $baseCommission)
    {
        // This would check product-specific commission rates
        return $baseCommission;
    }

    /**
     * Get category-specific commission
     */
    private function getCategoryCommission($categoryId, $baseCommission)
    {
        // This would check category-specific commission rates
        return $baseCommission;
    }

    /**
     * Check if program is available for registration
     */
    public function isAvailableForRegistration()
    {
        return $this->is_active;
    }
}
