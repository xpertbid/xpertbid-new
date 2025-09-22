<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingZone;
use App\Models\ShippingMethod;
use App\Models\Carrier;
use App\Models\PickupPoint;
use App\Models\VendorShippingSettings;
use App\Models\Vendor;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create shipping zones
        $zones = [
            [
                'tenant_id' => 1,
                'name' => 'United States',
                'slug' => 'united-states',
                'description' => 'Shipping zone for United States',
                'countries' => ['US'],
                'states' => null,
                'cities' => null,
                'postal_codes' => null,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Europe',
                'slug' => 'europe',
                'description' => 'Shipping zone for European countries',
                'countries' => ['GB', 'DE', 'FR', 'IT', 'ES', 'NL', 'BE'],
                'states' => null,
                'cities' => null,
                'postal_codes' => null,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Asia Pacific',
                'slug' => 'asia-pacific',
                'description' => 'Shipping zone for Asia Pacific region',
                'countries' => ['AU', 'JP', 'SG', 'HK', 'IN', 'CN'],
                'states' => null,
                'cities' => null,
                'postal_codes' => null,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($zones as $zoneData) {
            ShippingZone::create($zoneData);
        }

        // Create shipping methods
        $methods = [
            [
                'tenant_id' => 1,
                'shipping_zone_id' => 1,
                'name' => 'Standard Shipping',
                'method_type' => 'flat_rate',
                'description' => 'Standard shipping within 5-7 business days',
                'cost' => 9.99,
                'free_shipping_threshold' => 75.00,
                'settings' => ['estimated_delivery' => '5-7 business days'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'tenant_id' => 1,
                'shipping_zone_id' => 1,
                'name' => 'Express Shipping',
                'method_type' => 'flat_rate',
                'description' => 'Express shipping within 2-3 business days',
                'cost' => 19.99,
                'free_shipping_threshold' => 150.00,
                'settings' => ['estimated_delivery' => '2-3 business days'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'tenant_id' => 1,
                'shipping_zone_id' => 1,
                'name' => 'Overnight Shipping',
                'method_type' => 'carrier_based',
                'description' => 'Overnight delivery via FedEx',
                'cost' => 39.99,
                'free_shipping_threshold' => null,
                'settings' => ['carrier' => 'fedex', 'service' => 'overnight'],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'tenant_id' => 1,
                'shipping_zone_id' => 2,
                'name' => 'Europe Standard',
                'method_type' => 'flat_rate',
                'description' => 'Standard shipping to Europe',
                'cost' => 15.99,
                'free_shipping_threshold' => 100.00,
                'settings' => ['estimated_delivery' => '7-10 business days'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'tenant_id' => 1,
                'shipping_zone_id' => 3,
                'name' => 'Asia Pacific Standard',
                'method_type' => 'flat_rate',
                'description' => 'Standard shipping to Asia Pacific',
                'cost' => 25.99,
                'free_shipping_threshold' => 150.00,
                'settings' => ['estimated_delivery' => '10-14 business days'],
                'is_active' => true,
                'sort_order' => 1,
            ],
        ];

        foreach ($methods as $methodData) {
            ShippingMethod::create($methodData);
        }

        // Create carriers
        $carriers = [
            [
                'tenant_id' => 1,
                'name' => 'FedEx',
                'code' => 'fedex',
                'logo_url' => 'https://example.com/logos/fedex.png',
                'description' => 'FedEx Express shipping services',
                'api_settings' => [
                    'api_key' => 'test_key',
                    'api_secret' => 'test_secret',
                    'account_number' => '123456789',
                ],
                'supported_countries' => ['US', 'CA', 'GB', 'DE', 'FR'],
                'supported_services' => ['standard', 'express', 'overnight'],
                'is_active' => true,
                'is_integrated' => true,
                'base_rate' => 12.99,
                'rate_calculation' => [
                    'weight_rate' => 2.50,
                    'distance_rate' => 0.15,
                ],
                'sort_order' => 1,
            ],
            [
                'tenant_id' => 1,
                'name' => 'DHL',
                'code' => 'dhl',
                'logo_url' => 'https://example.com/logos/dhl.png',
                'description' => 'DHL Express shipping services',
                'api_settings' => [
                    'api_key' => 'test_key',
                    'api_secret' => 'test_secret',
                    'account_number' => '987654321',
                ],
                'supported_countries' => ['US', 'GB', 'DE', 'FR', 'IT', 'ES'],
                'supported_services' => ['standard', 'express', 'express_plus'],
                'is_active' => true,
                'is_integrated' => true,
                'base_rate' => 14.99,
                'rate_calculation' => [
                    'weight_rate' => 2.75,
                    'distance_rate' => 0.18,
                ],
                'sort_order' => 2,
            ],
            [
                'tenant_id' => 1,
                'name' => 'UPS',
                'code' => 'ups',
                'logo_url' => 'https://example.com/logos/ups.png',
                'description' => 'UPS shipping services',
                'api_settings' => [
                    'api_key' => 'test_key',
                    'api_secret' => 'test_secret',
                    'account_number' => '456789123',
                ],
                'supported_countries' => ['US', 'CA', 'MX'],
                'supported_services' => ['ground', 'express', 'next_day'],
                'is_active' => true,
                'is_integrated' => false,
                'base_rate' => 11.99,
                'rate_calculation' => [
                    'weight_rate' => 2.25,
                    'distance_rate' => 0.12,
                ],
                'sort_order' => 3,
            ],
        ];

        foreach ($carriers as $carrierData) {
            Carrier::create($carrierData);
        }

        // Create pickup points
        $pickupPoints = [
            [
                'tenant_id' => 1,
                'name' => 'Downtown Store',
                'address' => '123 Main Street',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'US',
                'postal_code' => '10001',
                'latitude' => 40.7589,
                'longitude' => -73.9851,
                'phone' => '+1-555-0123',
                'email' => 'downtown@xpertbid.com',
                'description' => 'Main downtown location with extended hours',
                'operating_hours' => [
                    'monday' => ['open' => '09:00', 'close' => '21:00', 'closed' => false],
                    'tuesday' => ['open' => '09:00', 'close' => '21:00', 'closed' => false],
                    'wednesday' => ['open' => '09:00', 'close' => '21:00', 'closed' => false],
                    'thursday' => ['open' => '09:00', 'close' => '21:00', 'closed' => false],
                    'friday' => ['open' => '09:00', 'close' => '22:00', 'closed' => false],
                    'saturday' => ['open' => '10:00', 'close' => '20:00', 'closed' => false],
                    'sunday' => ['open' => '11:00', 'close' => '18:00', 'closed' => false],
                ],
                'handling_fee' => 0.00,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Brooklyn Hub',
                'address' => '456 Brooklyn Ave',
                'city' => 'Brooklyn',
                'state' => 'NY',
                'country' => 'US',
                'postal_code' => '11201',
                'latitude' => 40.6892,
                'longitude' => -73.9442,
                'phone' => '+1-555-0456',
                'email' => 'brooklyn@xpertbid.com',
                'description' => 'Brooklyn pickup location',
                'operating_hours' => [
                    'monday' => ['open' => '10:00', 'close' => '20:00', 'closed' => false],
                    'tuesday' => ['open' => '10:00', 'close' => '20:00', 'closed' => false],
                    'wednesday' => ['open' => '10:00', 'close' => '20:00', 'closed' => false],
                    'thursday' => ['open' => '10:00', 'close' => '20:00', 'closed' => false],
                    'friday' => ['open' => '10:00', 'close' => '21:00', 'closed' => false],
                    'saturday' => ['open' => '11:00', 'close' => '19:00', 'closed' => false],
                    'sunday' => ['closed' => true],
                ],
                'handling_fee' => 2.50,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'tenant_id' => 1,
                'name' => 'London Office',
                'address' => '789 Oxford Street',
                'city' => 'London',
                'state' => null,
                'country' => 'GB',
                'postal_code' => 'W1D 2HG',
                'latitude' => 51.5154,
                'longitude' => -0.1419,
                'phone' => '+44-20-7123-4567',
                'email' => 'london@xpertbid.com',
                'description' => 'London pickup location',
                'operating_hours' => [
                    'monday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                    'tuesday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                    'wednesday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                    'thursday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                    'friday' => ['open' => '09:00', 'close' => '17:00', 'closed' => false],
                    'saturday' => ['closed' => true],
                    'sunday' => ['closed' => true],
                ],
                'handling_fee' => 5.00,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($pickupPoints as $pickupData) {
            PickupPoint::create($pickupData);
        }

        // Create vendor shipping settings
        $vendors = Vendor::all();
        foreach ($vendors as $vendor) {
            VendorShippingSettings::create([
                'tenant_id' => $vendor->tenant_id,
                'vendor_id' => $vendor->id,
                'shipping_policy' => 'platform',
                'free_shipping_enabled' => true,
                'free_shipping_threshold' => 50.00,
                'handling_fee' => 0.00,
                'shipping_methods' => [1, 2, 3], // Available method IDs
                'excluded_zones' => [],
                'custom_rates' => [],
                'is_active' => true,
            ]);
        }
    }
}
