<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferCommunication extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'user_id',
        'message',
        'message_type',
        'attachments',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the offer that owns the communication.
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    /**
     * Get the user that sent the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope to get read messages.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope to get messages by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('message_type', $type);
    }

    /**
     * Scope to get text messages.
     */
    public function scopeText($query)
    {
        return $query->where('message_type', 'text');
    }

    /**
     * Scope to get system messages.
     */
    public function scopeSystem($query)
    {
        return $query->where('message_type', 'system');
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Check if message has attachments.
     */
    public function hasAttachments(): bool
    {
        return !empty($this->attachments);
    }

    /**
     * Get message type badge class.
     */
    public function getMessageTypeBadgeClassAttribute(): string
    {
        return match ($this->message_type) {
            'text' => 'bg-primary',
            'image' => 'bg-info',
            'document' => 'bg-warning',
            'system' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    /**
     * Get message type text.
     */
    public function getMessageTypeTextAttribute(): string
    {
        return match ($this->message_type) {
            'text' => 'Text',
            'image' => 'Image',
            'document' => 'Document',
            'system' => 'System',
            default => 'Unknown',
        };
    }

    /**
     * Get formatted message with attachments info.
     */
    public function getFormattedMessageAttribute(): string
    {
        $message = $this->message;
        
        if ($this->hasAttachments()) {
            $attachmentCount = count($this->attachments);
            $message .= " [{$attachmentCount} attachment(s)]";
        }
        
        return $message;
    }
}