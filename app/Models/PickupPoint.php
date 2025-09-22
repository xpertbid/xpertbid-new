<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupPoint extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'phone',
        'email',
        'description',
        'operating_hours',
        'handling_fee',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'handling_fee' => 'decimal:2',
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the tenant that owns the pickup point.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope for active pickup points
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for pickup points in a specific location
     */
    public function scopeInLocation($query, $city, $state = null, $country = null)
    {
        $query->where('city', $city);
        
        if ($state) {
            $query->where('state', $state);
        }
        
        if ($country) {
            $query->where('country', $country);
        }
        
        return $query;
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) {
            $address .= ', ' . $this->city;
        }
        if ($this->state) {
            $address .= ', ' . $this->state;
        }
        if ($this->country) {
            $address .= ', ' . $this->country;
        }
        if ($this->postal_code) {
            $address .= ' ' . $this->postal_code;
        }
        
        return $address;
    }

    /**
     * Check if pickup point is open at given time
     */
    public function isOpenAt($datetime = null)
    {
        if (!$this->operating_hours) {
            return true; // Assume always open if no hours specified
        }

        $datetime = $datetime ?: now();
        $dayOfWeek = strtolower($datetime->format('l')); // monday, tuesday, etc.
        
        if (!isset($this->operating_hours[$dayOfWeek])) {
            return false;
        }

        $hours = $this->operating_hours[$dayOfWeek];
        if ($hours['closed'] ?? false) {
            return false;
        }

        $currentTime = $datetime->format('H:i');
        $openTime = $hours['open'] ?? '00:00';
        $closeTime = $hours['close'] ?? '23:59';

        return $currentTime >= $openTime && $currentTime <= $closeTime;
    }

    /**
     * Get distance from a given location
     */
    public function getDistanceFrom($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        // Haversine formula to calculate distance
        $earthRadius = 6371; // Earth's radius in kilometers

        $latDiff = deg2rad($latitude - $this->latitude);
        $lonDiff = deg2rad($longitude - $this->longitude);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos(deg2rad($this->latitude)) * cos(deg2rad($latitude)) *
             sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
