<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateWithdrawal extends Model
{
    protected $fillable = [
        'tenant_id',
        'affiliate_id',
        'amount',
        'payment_method',
        'payment_details',
        'status',
        'rejection_reason',
        'notes',
        'processed_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the affiliate that owns the withdrawal.
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Get the tenant that owns the withdrawal.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope for pending withdrawals
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved withdrawals
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for paid withdrawals
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for rejected withdrawals
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Approve withdrawal
     */
    public function approve()
    {
        $this->update([
            'status' => 'approved',
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark withdrawal as paid
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Reject withdrawal
     */
    public function reject($reason)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    /**
     * Get payment method details
     */
    public function getPaymentMethodDetails()
    {
        switch ($this->payment_method) {
            case 'bank_transfer':
                return [
                    'bank_name' => $this->payment_details['bank_name'] ?? '',
                    'account_number' => $this->payment_details['account_number'] ?? '',
                    'routing_number' => $this->payment_details['routing_number'] ?? '',
                ];
            case 'paypal':
                return [
                    'email' => $this->payment_details['email'] ?? '',
                ];
            case 'check':
                return [
                    'address' => $this->payment_details['address'] ?? '',
                ];
            default:
                return $this->payment_details;
        }
    }

    /**
     * Check if withdrawal can be processed
     */
    public function canBeProcessed()
    {
        return $this->status === 'approved' && 
               $this->affiliate->pending_earnings >= $this->amount;
    }
}
