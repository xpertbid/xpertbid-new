<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliate extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'affiliate_program_id',
        'affiliate_code',
        'status',
        'application_data',
        'rejection_reason',
        'total_earnings',
        'total_paid',
        'pending_earnings',
        'total_referrals',
        'total_sales',
        'payment_methods',
        'settings',
        'approved_at',
        'last_activity_at',
    ];

    protected $casts = [
        'application_data' => 'array',
        'payment_methods' => 'array',
        'settings' => 'array',
        'total_earnings' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'pending_earnings' => 'decimal:2',
        'approved_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    /**
     * Get the user that owns the affiliate.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the affiliate program.
     */
    public function affiliateProgram(): BelongsTo
    {
        return $this->belongsTo(AffiliateProgram::class);
    }

    /**
     * Get the tenant that owns the affiliate.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the referrals for this affiliate.
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }

    /**
     * Get the commissions for this affiliate.
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(AffiliateCommission::class);
    }

    /**
     * Get the withdrawals for this affiliate.
     */
    public function withdrawals(): HasMany
    {
        return $this->hasMany(AffiliateWithdrawal::class);
    }

    /**
     * Scope for approved affiliates
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending affiliates
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Generate unique affiliate code
     */
    public static function generateAffiliateCode($userId)
    {
        $prefix = 'AFF';
        $suffix = strtoupper(substr(md5($userId . time()), 0, 6));
        return $prefix . $suffix;
    }

    /**
     * Check if affiliate can withdraw
     */
    public function canWithdraw($amount)
    {
        return $this->status === 'approved' && 
               $this->pending_earnings >= $amount &&
               $amount >= $this->affiliateProgram->minimum_payout;
    }

    /**
     * Get affiliate dashboard stats
     */
    public function getDashboardStats()
    {
        return [
            'total_earnings' => $this->total_earnings,
            'pending_earnings' => $this->pending_earnings,
            'total_paid' => $this->total_paid,
            'total_referrals' => $this->total_referrals,
            'total_sales' => $this->total_sales,
            'conversion_rate' => $this->total_referrals > 0 ? 
                round(($this->total_sales / $this->total_referrals) * 100, 2) : 0,
        ];
    }

    /**
     * Update earnings
     */
    public function updateEarnings($amount)
    {
        $this->increment('total_earnings', $amount);
        $this->increment('pending_earnings', $amount);
        $this->update(['last_activity_at' => now()]);
    }

    /**
     * Mark earnings as paid
     */
    public function markEarningsAsPaid($amount)
    {
        $this->increment('total_paid', $amount);
        $this->decrement('pending_earnings', $amount);
    }
}
