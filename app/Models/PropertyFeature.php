<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'feature_type',
        'name',
        'description',
        'icon',
        'image',
        'metadata',
        'is_available',
        'sort_order',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_available' => 'boolean',
    ];

    /**
     * Get the property that owns the feature.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Scope to get features by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('feature_type', $type);
    }

    /**
     * Scope to get available features.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope to get features ordered by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get predefined property amenity types.
     */
    public static function getAmenityTypes(): array
    {
        return [
            'interior' => [
                'Air Conditioning', 'Central Heating', 'Fireplace', 'Hardwood Floors',
                'Carpet', 'Tile Floors', 'Marble Floors', 'Granite Countertops',
                'Stainless Steel Appliances', 'Built-in Wardrobes', 'Walk-in Closet',
                'Laundry Room', 'Pantry', 'Study Room', 'Home Office'
            ],
            'exterior' => [
                'Balcony', 'Terrace', 'Garden', 'Swimming Pool', 'Jacuzzi',
                'BBQ Area', 'Outdoor Kitchen', 'Patio', 'Deck', 'Garage',
                'Parking Space', 'Driveway', 'Fence', 'Security Gate',
                'Landscaping', 'Outdoor Lighting'
            ],
            'security' => [
                'Security System', 'CCTV Cameras', 'Intercom', 'Security Guards',
                'Gated Community', 'Access Control', 'Alarm System',
                'Safe Room', 'Security Doors', 'Window Bars'
            ],
            'utilities' => [
                'Electricity', 'Water Supply', 'Gas Connection', 'Internet',
                'Cable TV', 'Telephone', 'Generator', 'Solar Panels',
                'Water Heater', 'Water Tank', 'Sewage System'
            ],
            'recreation' => [
                'Gym', 'Fitness Center', 'Swimming Pool', 'Tennis Court',
                'Basketball Court', 'Playground', 'Clubhouse', 'Cinema Room',
                'Game Room', 'Library', 'Spa', 'Sauna'
            ]
        ];
    }

    /**
     * Get predefined facility types.
     */
    public static function getFacilityTypes(): array
    {
        return [
            'transportation' => [
                'Metro Station', 'Bus Stop', 'Taxi Stand', 'Airport',
                'Train Station', 'Highway Access', 'Public Transport'
            ],
            'education' => [
                'School', 'University', 'College', 'Kindergarten',
                'Library', 'Training Center', 'Language School'
            ],
            'healthcare' => [
                'Hospital', 'Clinic', 'Pharmacy', 'Dental Clinic',
                'Medical Center', 'Emergency Services', 'Veterinary'
            ],
            'shopping' => [
                'Shopping Mall', 'Supermarket', 'Grocery Store', 'Market',
                'Department Store', 'Convenience Store', 'Shopping Center'
            ],
            'entertainment' => [
                'Cinema', 'Theater', 'Restaurant', 'Cafe', 'Park',
                'Recreation Center', 'Sports Complex', 'Museum'
            ]
        ];
    }
}