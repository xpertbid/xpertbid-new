<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    protected $fillable = [
        'tenant_id',
        'affiliate_id',
        'referred_user_id',
        'referral_code',
        'referral_type',
        'status',
        'ip_address',
        'user_agent',
        'metadata',
        'converted_at',
        'expires_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'converted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the affiliate that owns the referral.
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Get the referred user.
     */
    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    /**
     * Get the tenant that owns the referral.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope for converted referrals
     */
    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }

    /**
     * Scope for pending referrals
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for expired referrals
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere(function($q) {
                        $q->where('status', 'pending')
                          ->where('expires_at', '<', now());
                    });
    }

    /**
     * Check if referral is expired
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Mark referral as converted
     */
    public function markAsConverted($userId = null)
    {
        $this->update([
            'status' => 'converted',
            'referred_user_id' => $userId,
            'converted_at' => now(),
        ]);

        // Update affiliate stats
        $this->affiliate->increment('total_sales');
    }

    /**
     * Mark referral as expired
     */
    public function markAsExpired()
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Get referral URL
     */
    public function getReferralUrl($baseUrl)
    {
        return $baseUrl . '?ref=' . $this->referral_code;
    }

    /**
     * Track referral activity
     */
    public function trackActivity($action, $data = [])
    {
        $metadata = $this->metadata ?? [];
        $metadata['activities'][] = [
            'action' => $action,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ];

        $this->update(['metadata' => $metadata]);
    }
}
