<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'specification_type',
        'name',
        'value',
        'unit',
        'description',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    /**
     * Get the vehicle that owns the specification.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Scope to get specifications by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('specification_type', $type);
    }

    /**
     * Scope to get featured specifications.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get specifications ordered by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get predefined vehicle specification types.
     */
    public static function getSpecificationTypes(): array
    {
        return [
            'engine' => [
                'Engine Type', 'Engine Size', 'Cylinders', 'Horsepower',
                'Torque', 'Fuel Type', 'Fuel Capacity', 'Fuel Economy',
                'Transmission', 'Drive Type', 'Engine Code'
            ],
            'performance' => [
                'Top Speed', '0-60 mph', '0-100 km/h', 'Quarter Mile',
                'Braking Distance', 'Acceleration', 'Handling Rating'
            ],
            'dimensions' => [
                'Length', 'Width', 'Height', 'Wheelbase', 'Ground Clearance',
                'Curb Weight', 'Gross Weight', 'Cargo Capacity',
                'Seating Capacity', 'Headroom', 'Legroom'
            ],
            'safety' => [
                'Airbags', 'ABS', 'Traction Control', 'Stability Control',
                'Blind Spot Monitoring', 'Lane Departure Warning',
                'Forward Collision Warning', 'Automatic Emergency Braking',
                'Rearview Camera', 'Parking Sensors', 'Safety Rating'
            ],
            'comfort' => [
                'Air Conditioning', 'Heating', 'Seat Material',
                'Power Seats', 'Heated Seats', 'Ventilated Seats',
                'Memory Seats', 'Sunroof', 'Power Windows',
                'Power Locks', 'Cruise Control', 'Steering Wheel Material'
            ],
            'entertainment' => [
                'Audio System', 'Speakers', 'Navigation System',
                'Bluetooth', 'USB Ports', 'Auxiliary Input',
                'CD Player', 'Radio', 'Satellite Radio',
                'Apple CarPlay', 'Android Auto', 'WiFi Hotspot'
            ],
            'exterior' => [
                'Paint Color', 'Paint Type', 'Wheel Size', 'Wheel Type',
                'Tire Type', 'Headlights', 'Taillights', 'Fog Lights',
                'Mirrors', 'Roof Type', 'Door Count', 'Window Tinting'
            ],
            'interior' => [
                'Interior Color', 'Dashboard Material', 'Steering Wheel',
                'Gauges', 'Center Console', 'Cup Holders', 'Storage',
                'Cargo Space', 'Seat Configuration', 'Interior Lighting'
            ]
        ];
    }

    /**
     * Get predefined vehicle specifications for seeding.
     */
    public static function getPredefinedSpecifications(): array
    {
        return [
            // Engine Specifications
            ['name' => 'Engine Type', 'specification_type' => 'engine', 'unit' => '', 'is_featured' => true],
            ['name' => 'Engine Size', 'specification_type' => 'engine', 'unit' => 'L', 'is_featured' => true],
            ['name' => 'Cylinders', 'specification_type' => 'engine', 'unit' => '', 'is_featured' => true],
            ['name' => 'Horsepower', 'specification_type' => 'engine', 'unit' => 'hp', 'is_featured' => true],
            ['name' => 'Torque', 'specification_type' => 'engine', 'unit' => 'lb-ft', 'is_featured' => true],
            ['name' => 'Fuel Type', 'specification_type' => 'engine', 'unit' => '', 'is_featured' => true],
            ['name' => 'Transmission', 'specification_type' => 'engine', 'unit' => '', 'is_featured' => true],
            ['name' => 'Drive Type', 'specification_type' => 'engine', 'unit' => '', 'is_featured' => true],

            // Performance Specifications
            ['name' => 'Top Speed', 'specification_type' => 'performance', 'unit' => 'mph', 'is_featured' => true],
            ['name' => '0-60 mph', 'specification_type' => 'performance', 'unit' => 'sec', 'is_featured' => true],
            ['name' => 'Fuel Economy', 'specification_type' => 'performance', 'unit' => 'mpg', 'is_featured' => true],

            // Dimension Specifications
            ['name' => 'Length', 'specification_type' => 'dimensions', 'unit' => 'in', 'is_featured' => false],
            ['name' => 'Width', 'specification_type' => 'dimensions', 'unit' => 'in', 'is_featured' => false],
            ['name' => 'Height', 'specification_type' => 'dimensions', 'unit' => 'in', 'is_featured' => false],
            ['name' => 'Wheelbase', 'specification_type' => 'dimensions', 'unit' => 'in', 'is_featured' => false],
            ['name' => 'Curb Weight', 'specification_type' => 'dimensions', 'unit' => 'lbs', 'is_featured' => true],
            ['name' => 'Seating Capacity', 'specification_type' => 'dimensions', 'unit' => 'people', 'is_featured' => true],
            ['name' => 'Cargo Capacity', 'specification_type' => 'dimensions', 'unit' => 'cu ft', 'is_featured' => true],

            // Safety Specifications
            ['name' => 'Airbags', 'specification_type' => 'safety', 'unit' => '', 'is_featured' => true],
            ['name' => 'ABS', 'specification_type' => 'safety', 'unit' => '', 'is_featured' => false],
            ['name' => 'Traction Control', 'specification_type' => 'safety', 'unit' => '', 'is_featured' => false],
            ['name' => 'Stability Control', 'specification_type' => 'safety', 'unit' => '', 'is_featured' => false],
            ['name' => 'Safety Rating', 'specification_type' => 'safety', 'unit' => 'stars', 'is_featured' => true],

            // Comfort Specifications
            ['name' => 'Air Conditioning', 'specification_type' => 'comfort', 'unit' => '', 'is_featured' => false],
            ['name' => 'Heated Seats', 'specification_type' => 'comfort', 'unit' => '', 'is_featured' => false],
            ['name' => 'Power Seats', 'specification_type' => 'comfort', 'unit' => '', 'is_featured' => false],
            ['name' => 'Sunroof', 'specification_type' => 'comfort', 'unit' => '', 'is_featured' => false],
            ['name' => 'Cruise Control', 'specification_type' => 'comfort', 'unit' => '', 'is_featured' => false],

            // Entertainment Specifications
            ['name' => 'Audio System', 'specification_type' => 'entertainment', 'unit' => '', 'is_featured' => false],
            ['name' => 'Navigation System', 'specification_type' => 'entertainment', 'unit' => '', 'is_featured' => false],
            ['name' => 'Bluetooth', 'specification_type' => 'entertainment', 'unit' => '', 'is_featured' => false],
            ['name' => 'Apple CarPlay', 'specification_type' => 'entertainment', 'unit' => '', 'is_featured' => false],
            ['name' => 'Android Auto', 'specification_type' => 'entertainment', 'unit' => '', 'is_featured' => false],
        ];
    }
}