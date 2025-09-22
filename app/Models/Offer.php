<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'offerable_type',
        'offerable_id',
        'offer_amount',
        'currency',
        'message',
        'status',
        'personal_details',
        'additional_info',
        'expires_at',
        'responded_at',
        'responded_by',
        'response_message',
    ];

    protected $casts = [
        'personal_details' => 'array',
        'additional_info' => 'array',
        'expires_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Get the tenant that owns the offer.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the user that made the offer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who responded to the offer.
     */
    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Get the offerable model (Property or Vehicle).
     */
    public function offerable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the attachments for this offer.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(OfferAttachment::class);
    }

    /**
     * Get the communications for this offer.
     */
    public function communications(): HasMany
    {
        return $this->hasMany(OfferCommunication::class);
    }

    /**
     * Scope to get offers by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get pending offers.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get accepted offers.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope to get rejected offers.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope to get expired offers.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere(function ($q) {
                        $q->where('expires_at', '<', now())
                          ->where('status', 'pending');
                    });
    }

    /**
     * Scope to get non-expired offers.
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Check if the offer is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if the offer is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    /**
     * Check if the offer is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if the offer is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Accept the offer.
     */
    public function accept($respondedBy = null, $message = null): void
    {
        $this->update([
            'status' => 'accepted',
            'responded_at' => now(),
            'responded_by' => $respondedBy,
            'response_message' => $message,
        ]);
    }

    /**
     * Reject the offer.
     */
    public function reject($respondedBy = null, $message = null): void
    {
        $this->update([
            'status' => 'rejected',
            'responded_at' => now(),
            'responded_by' => $respondedBy,
            'response_message' => $message,
        ]);
    }

    /**
     * Withdraw the offer.
     */
    public function withdraw(): void
    {
        $this->update(['status' => 'withdrawn']);
    }

    /**
     * Get formatted offer amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->offer_amount, 2) . ' ' . $this->currency;
    }

    /**
     * Get offer status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-warning',
            'accepted' => 'bg-success',
            'rejected' => 'bg-danger',
            'expired' => 'bg-secondary',
            'withdrawn' => 'bg-info',
            default => 'bg-secondary',
        };
    }

    /**
     * Get offer status text.
     */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'expired' => 'Expired',
            'withdrawn' => 'Withdrawn',
            default => 'Unknown',
        };
    }
}