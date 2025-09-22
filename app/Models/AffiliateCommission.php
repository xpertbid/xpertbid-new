<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateCommission extends Model
{
    protected $fillable = [
        'tenant_id',
        'affiliate_id',
        'referral_id',
        'order_id',
        'product_id',
        'category_id',
        'commission_type',
        'order_amount',
        'commission_rate',
        'commission_amount',
        'status',
        'notes',
        'approved_at',
        'paid_at',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'commission_rate' => 'decimal:4',
        'commission_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the affiliate that owns the commission.
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Get the referral.
     */
    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    /**
     * Get the order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the tenant that owns the commission.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope for pending commissions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved commissions
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for paid commissions
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Approve commission
     */
    public function approve()
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Update affiliate pending earnings
        $this->affiliate->updateEarnings($this->commission_amount);
    }

    /**
     * Mark commission as paid
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update affiliate paid earnings
        $this->affiliate->markEarningsAsPaid($this->commission_amount);
    }

    /**
     * Cancel commission
     */
    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $reason,
        ]);
    }

    /**
     * Get formatted commission amount
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->commission_amount, 2);
    }

    /**
     * Get commission description
     */
    public function getDescriptionAttribute()
    {
        $description = ucfirst($this->commission_type) . ' Commission';
        
        if ($this->product) {
            $description .= ' - ' . $this->product->name;
        } elseif ($this->category) {
            $description .= ' - ' . $this->category->name;
        }
        
        return $description;
    }
}
