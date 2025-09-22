<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAmenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'category',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get active amenities.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get amenities by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get amenities ordered by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get predefined amenities for seeding.
     */
    public static function getPredefinedAmenities(): array
    {
        return [
            // Interior Amenities
            ['name' => 'Air Conditioning', 'slug' => 'air-conditioning', 'category' => 'interior', 'icon' => 'fas fa-snowflake'],
            ['name' => 'Central Heating', 'slug' => 'central-heating', 'category' => 'interior', 'icon' => 'fas fa-fire'],
            ['name' => 'Fireplace', 'slug' => 'fireplace', 'category' => 'interior', 'icon' => 'fas fa-fire'],
            ['name' => 'Hardwood Floors', 'slug' => 'hardwood-floors', 'category' => 'interior', 'icon' => 'fas fa-square'],
            ['name' => 'Marble Floors', 'slug' => 'marble-floors', 'category' => 'interior', 'icon' => 'fas fa-square'],
            ['name' => 'Granite Countertops', 'slug' => 'granite-countertops', 'category' => 'interior', 'icon' => 'fas fa-square'],
            ['name' => 'Stainless Steel Appliances', 'slug' => 'stainless-steel-appliances', 'category' => 'interior', 'icon' => 'fas fa-blender'],
            ['name' => 'Built-in Wardrobes', 'slug' => 'built-in-wardrobes', 'category' => 'interior', 'icon' => 'fas fa-tshirt'],
            ['name' => 'Walk-in Closet', 'slug' => 'walk-in-closet', 'category' => 'interior', 'icon' => 'fas fa-tshirt'],
            ['name' => 'Laundry Room', 'slug' => 'laundry-room', 'category' => 'interior', 'icon' => 'fas fa-tshirt'],
            ['name' => 'Home Office', 'slug' => 'home-office', 'category' => 'interior', 'icon' => 'fas fa-laptop'],

            // Exterior Amenities
            ['name' => 'Balcony', 'slug' => 'balcony', 'category' => 'exterior', 'icon' => 'fas fa-home'],
            ['name' => 'Terrace', 'slug' => 'terrace', 'category' => 'exterior', 'icon' => 'fas fa-home'],
            ['name' => 'Garden', 'slug' => 'garden', 'category' => 'exterior', 'icon' => 'fas fa-seedling'],
            ['name' => 'Swimming Pool', 'slug' => 'swimming-pool', 'category' => 'exterior', 'icon' => 'fas fa-swimming-pool'],
            ['name' => 'Jacuzzi', 'slug' => 'jacuzzi', 'category' => 'exterior', 'icon' => 'fas fa-hot-tub'],
            ['name' => 'BBQ Area', 'slug' => 'bbq-area', 'category' => 'exterior', 'icon' => 'fas fa-fire'],
            ['name' => 'Outdoor Kitchen', 'slug' => 'outdoor-kitchen', 'category' => 'exterior', 'icon' => 'fas fa-utensils'],
            ['name' => 'Garage', 'slug' => 'garage', 'category' => 'exterior', 'icon' => 'fas fa-car'],
            ['name' => 'Parking Space', 'slug' => 'parking-space', 'category' => 'exterior', 'icon' => 'fas fa-parking'],
            ['name' => 'Landscaping', 'slug' => 'landscaping', 'category' => 'exterior', 'icon' => 'fas fa-tree'],

            // Security Amenities
            ['name' => 'Security System', 'slug' => 'security-system', 'category' => 'security', 'icon' => 'fas fa-shield-alt'],
            ['name' => 'CCTV Cameras', 'slug' => 'cctv-cameras', 'category' => 'security', 'icon' => 'fas fa-video'],
            ['name' => 'Intercom', 'slug' => 'intercom', 'category' => 'security', 'icon' => 'fas fa-microphone'],
            ['name' => 'Security Guards', 'slug' => 'security-guards', 'category' => 'security', 'icon' => 'fas fa-user-shield'],
            ['name' => 'Gated Community', 'slug' => 'gated-community', 'category' => 'security', 'icon' => 'fas fa-lock'],
            ['name' => 'Alarm System', 'slug' => 'alarm-system', 'category' => 'security', 'icon' => 'fas fa-bell'],

            // Utilities Amenities
            ['name' => 'Electricity', 'slug' => 'electricity', 'category' => 'utilities', 'icon' => 'fas fa-bolt'],
            ['name' => 'Water Supply', 'slug' => 'water-supply', 'category' => 'utilities', 'icon' => 'fas fa-tint'],
            ['name' => 'Gas Connection', 'slug' => 'gas-connection', 'category' => 'utilities', 'icon' => 'fas fa-fire'],
            ['name' => 'Internet', 'slug' => 'internet', 'category' => 'utilities', 'icon' => 'fas fa-wifi'],
            ['name' => 'Cable TV', 'slug' => 'cable-tv', 'category' => 'utilities', 'icon' => 'fas fa-tv'],
            ['name' => 'Generator', 'slug' => 'generator', 'category' => 'utilities', 'icon' => 'fas fa-bolt'],
            ['name' => 'Solar Panels', 'slug' => 'solar-panels', 'category' => 'utilities', 'icon' => 'fas fa-solar-panel'],

            // Recreation Amenities
            ['name' => 'Gym', 'slug' => 'gym', 'category' => 'recreation', 'icon' => 'fas fa-dumbbell'],
            ['name' => 'Fitness Center', 'slug' => 'fitness-center', 'category' => 'recreation', 'icon' => 'fas fa-running'],
            ['name' => 'Tennis Court', 'slug' => 'tennis-court', 'category' => 'recreation', 'icon' => 'fas fa-table-tennis'],
            ['name' => 'Basketball Court', 'slug' => 'basketball-court', 'category' => 'recreation', 'icon' => 'fas fa-basketball-ball'],
            ['name' => 'Playground', 'slug' => 'playground', 'category' => 'recreation', 'icon' => 'fas fa-child'],
            ['name' => 'Clubhouse', 'slug' => 'clubhouse', 'category' => 'recreation', 'icon' => 'fas fa-home'],
            ['name' => 'Cinema Room', 'slug' => 'cinema-room', 'category' => 'recreation', 'icon' => 'fas fa-film'],
            ['name' => 'Game Room', 'slug' => 'game-room', 'category' => 'recreation', 'icon' => 'fas fa-gamepad'],
            ['name' => 'Library', 'slug' => 'library', 'category' => 'recreation', 'icon' => 'fas fa-book'],
            ['name' => 'Spa', 'slug' => 'spa', 'category' => 'recreation', 'icon' => 'fas fa-spa'],
            ['name' => 'Sauna', 'slug' => 'sauna', 'category' => 'recreation', 'icon' => 'fas fa-hot-tub'],
        ];
    }
}