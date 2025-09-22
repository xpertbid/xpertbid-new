<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Currency extends Model
{

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'symbol',
        'symbol_position',
        'decimal_places',
        'exchange_rate',
        'is_active',
        'is_default',
        'last_updated',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'exchange_rate' => 'decimal:6',
        'decimal_places' => 'integer',
    ];


    /**
     * Scope for active currencies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the default currency
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->first();
    }

    /**
     * Set as default currency
     */
    public function setAsDefault()
    {
        // Remove default from other currencies
        static::where('is_default', true)->update(['is_default' => false]);
        
        // Set this as default
        $this->update(['is_default' => true]);
    }

    /**
     * Format amount with currency symbol
     */
    public function formatAmount($amount)
    {
        $formattedAmount = number_format($amount, $this->decimal_places);
        
        if ($this->symbol_position === 'before') {
            return $this->symbol . ' ' . $formattedAmount;
        } else {
            return $formattedAmount . ' ' . $this->symbol;
        }
    }

    /**
     * Convert amount from default currency
     */
    public function convertFromDefault($amount)
    {
        $defaultCurrency = static::getDefault();
        if (!$defaultCurrency || $this->id === $defaultCurrency->id) {
            return $amount;
        }
        
        return $amount * $this->exchange_rate;
    }

    /**
     * Convert amount to default currency
     */
    public function convertToDefault($amount)
    {
        $defaultCurrency = static::getDefault();
        if (!$defaultCurrency || $this->id === $defaultCurrency->id) {
            return $amount;
        }
        
        return $amount / $this->exchange_rate;
    }
}
