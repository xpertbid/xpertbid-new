<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'type',
        'description',
        'logo_url',
        'settings',
        'supported_currencies',
        'supported_countries',
        'transaction_fee',
        'fixed_fee',
        'is_active',
        'is_test_mode',
        'sort_order',
    ];

    protected $casts = [
        'settings' => 'array',
        'supported_currencies' => 'array',
        'supported_countries' => 'array',
        'transaction_fee' => 'decimal:4',
        'fixed_fee' => 'decimal:2',
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean',
    ];

    /**
     * Get the tenant that owns the payment gateway.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope for active gateways
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for online gateways
     */
    public function scopeOnline($query)
    {
        return $query->where('type', 'online');
    }

    /**
     * Check if gateway supports a currency
     */
    public function supportsCurrency($currency)
    {
        return !$this->supported_currencies || in_array($currency, $this->supported_currencies);
    }

    /**
     * Check if gateway supports a country
     */
    public function supportsCountry($country)
    {
        return !$this->supported_countries || in_array($country, $this->supported_countries);
    }

    /**
     * Calculate gateway fee
     */
    public function calculateFee($amount)
    {
        $fee = $this->fixed_fee;
        $fee += $amount * $this->transaction_fee;
        return $fee;
    }

    /**
     * Get API credentials
     */
    public function getApiCredentials()
    {
        return $this->settings['credentials'] ?? [];
    }

    /**
     * Get webhook URL
     */
    public function getWebhookUrl()
    {
        return $this->settings['webhook_url'] ?? null;
    }

    /**
     * Process payment
     */
    public function processPayment($amount, $currency, $paymentData)
    {
        // This would integrate with actual payment gateway APIs
        // For now, return a mock response
        return [
            'success' => true,
            'transaction_id' => 'mock_' . uniqid(),
            'gateway_response' => 'Payment processed successfully',
            'amount' => $amount,
            'currency' => $currency,
            'fee' => $this->calculateFee($amount),
        ];
    }
}
