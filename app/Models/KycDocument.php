<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kyc_type',
        'status',
        'full_name',
        'email',
        'phone_number',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'business_name',
        'ntn_number',
        'business_registration_number',
        'business_address',
        'business_type',
        'tax_number',
        'property_type',
        'property_location',
        'property_size',
        'property_ownership',
        'property_description',
        'vehicle_type',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'vehicle_vin',
        'vehicle_registration_number',
        'auction_type',
        'item_category',
        'item_description',
        'item_condition',
        'estimated_value',
        'documents',
        'additional_info',
        'rejection_reason',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'documents' => 'array',
        'additional_info' => 'array',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('kyc_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Accessors & Mutators
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'under_review' => 'info',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusTextAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getKycTypeTextAttribute()
    {
        return ucfirst($this->kyc_type);
    }

    // Helper Methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isUnderReview()
    {
        return $this->status === 'under_review';
    }

    public function getRequiredFields()
    {
        $requiredFields = [
            'user' => ['full_name', 'email', 'phone_number'],
            'vendor' => ['full_name', 'email', 'phone_number', 'business_name', 'ntn_number', 'business_address'],
            'property' => ['full_name', 'email', 'phone_number'],
            'vehicle' => ['full_name', 'email', 'phone_number'],
            'auction' => ['full_name', 'email', 'phone_number'],
        ];

        return $requiredFields[$this->kyc_type] ?? [];
    }

    public function getOptionalFields()
    {
        $optionalFields = [
            'user' => ['address', 'city', 'state', 'country', 'postal_code'],
            'vendor' => ['address', 'city', 'state', 'country', 'postal_code', 'business_type', 'tax_number', 'business_registration_number'],
            'property' => ['address', 'city', 'state', 'country', 'postal_code', 'property_type', 'property_location', 'property_size', 'property_ownership', 'property_description'],
            'vehicle' => ['address', 'city', 'state', 'country', 'postal_code', 'vehicle_type', 'vehicle_make', 'vehicle_model', 'vehicle_year', 'vehicle_vin', 'vehicle_registration_number'],
            'auction' => ['address', 'city', 'state', 'country', 'postal_code', 'auction_type', 'item_category', 'item_description', 'item_condition', 'estimated_value'],
        ];

        return $optionalFields[$this->kyc_type] ?? [];
    }

    public function getDocumentCount()
    {
        return is_array($this->documents) ? count($this->documents) : 0;
    }

    public function addDocument($filePath, $originalName, $fileType)
    {
        $documents = $this->documents ?? [];
        $documents[] = [
            'path' => $filePath,
            'original_name' => $originalName,
            'file_type' => $fileType,
            'uploaded_at' => now()->toISOString(),
        ];
        
        $this->update(['documents' => $documents]);
    }

    public function removeDocument($index)
    {
        $documents = $this->documents ?? [];
        if (isset($documents[$index])) {
            unset($documents[$index]);
            $this->update(['documents' => array_values($documents)]);
            return true;
        }
        return false;
    }
}