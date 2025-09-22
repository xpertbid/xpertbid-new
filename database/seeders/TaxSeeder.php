<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxClass;
use App\Models\TaxRate;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create tax classes
        $taxClasses = [
            [
                'tenant_id' => 1,
                'name' => 'Standard Rate',
                'slug' => 'standard-rate',
                'description' => 'Standard tax rate for most products',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Reduced Rate',
                'slug' => 'reduced-rate',
                'description' => 'Reduced tax rate for essential items',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Zero Rate',
                'slug' => 'zero-rate',
                'description' => 'Zero tax rate for exempt items',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Digital Products',
                'slug' => 'digital-products',
                'description' => 'Tax rate for digital products and services',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($taxClasses as $taxClassData) {
            TaxClass::create($taxClassData);
        }

        // Create tax rates
        $taxRates = [
            // United States
            [
                'tenant_id' => 1,
                'tax_class_id' => 1,
                'name' => 'US Sales Tax',
                'country_code' => 'US',
                'state_code' => null,
                'city' => null,
                'postal_code' => null,
                'rate' => 0.08, // 8%
                'tax_type' => 'sales_tax',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 1,
            ],
            [
                'tenant_id' => 1,
                'tax_class_id' => 1,
                'name' => 'California Sales Tax',
                'country_code' => 'US',
                'state_code' => 'CA',
                'city' => null,
                'postal_code' => null,
                'rate' => 0.1025, // 10.25%
                'tax_type' => 'sales_tax',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 2,
            ],
            [
                'tenant_id' => 1,
                'tax_class_id' => 1,
                'name' => 'New York Sales Tax',
                'country_code' => 'US',
                'state_code' => 'NY',
                'city' => null,
                'postal_code' => null,
                'rate' => 0.08, // 8%
                'tax_type' => 'sales_tax',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 2,
            ],
            [
                'tenant_id' => 1,
                'tax_class_id' => 1,
                'name' => 'Texas Sales Tax',
                'country_code' => 'US',
                'state_code' => 'TX',
                'city' => null,
                'postal_code' => null,
                'rate' => 0.0625, // 6.25%
                'tax_type' => 'sales_tax',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 2,
            ],

            // United Kingdom
            [
                'tenant_id' => 1,
                'tax_class_id' => 1,
                'name' => 'UK VAT',
                'country_code' => 'GB',
                'state_code' => null,
                'city' => null,
                'postal_code' => null,
                'rate' => 0.20, // 20%
                'tax_type' => 'vat',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 1,
            ],
            [
                'tenant_id' => 1,
                'tax_class_id' => 2,
                'name' => 'UK Reduced VAT',
                'country_code' => 'GB',
                'state_code' => null,
                'city' => null,
                'postal_code' => null,
                'rate' => 0.05, // 5%
                'tax_type' => 'vat',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 1,
            ],

            // Germany
            [
                'tenant_id' => 1,
                'tax_class_id' => 1,
                'name' => 'German VAT',
                'country_code' => 'DE',
                'state_code' => null,
                'city' => null,
                'postal_code' => null,
                'rate' => 0.19, // 19%
                'tax_type' => 'vat',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 1,
            ],
            [
                'tenant_id' => 1,
                'tax_class_id' => 2,
                'name' => 'German Reduced VAT',
                'country_code' => 'DE',
                'state_code' => null,
                'city' => null,
                'postal_code' => null,
                'rate' => 0.07, // 7%
                'tax_type' => 'vat',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 1,
            ],

            // France
            [
                'tenant_id' => 1,
                'tax_class_id' => 1,
                'name' => 'French VAT',
                'country_code' => 'FR',
                'state_code' => null,
                'city' => null,
                'postal_code' => null,
                'rate' => 0.20, // 20%
                'tax_type' => 'vat',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 1,
            ],
            [
                'tenant_id' => 1,
                'tax_class_id' => 2,
                'name' => 'French Reduced VAT',
                'country_code' => 'FR',
                'state_code' => null,
                'city' => null,
                'postal_code' => null,
                'rate' => 0.055, // 5.5%
                'tax_type' => 'vat',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 1,
            ],

            // Australia
            [
                'tenant_id' => 1,
                'tax_class_id' => 1,
                'name' => 'Australian GST',
                'country_code' => 'AU',
                'state_code' => null,
                'city' => null,
                'postal_code' => null,
                'rate' => 0.10, // 10%
                'tax_type' => 'gst',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 1,
            ],

            // Canada
            [
                'tenant_id' => 1,
                'tax_class_id' => 1,
                'name' => 'Canadian GST',
                'country_code' => 'CA',
                'state_code' => null,
                'city' => null,
                'postal_code' => null,
                'rate' => 0.05, // 5%
                'tax_type' => 'gst',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 1,
            ],
            [
                'tenant_id' => 1,
                'tax_class_id' => 1,
                'name' => 'Ontario HST',
                'country_code' => 'CA',
                'state_code' => 'ON',
                'city' => null,
                'postal_code' => null,
                'rate' => 0.13, // 13%
                'tax_type' => 'hst',
                'is_compound' => false,
                'is_shipping_taxable' => true,
                'is_active' => true,
                'priority' => 2,
            ],

            // Digital Products
            [
                'tenant_id' => 1,
                'tax_class_id' => 4,
                'name' => 'Digital Services Tax',
                'country_code' => 'US',
                'state_code' => null,
                'city' => null,
                'postal_code' => null,
                'rate' => 0.08, // 8%
                'tax_type' => 'digital_tax',
                'is_compound' => false,
                'is_shipping_taxable' => false,
                'is_active' => true,
                'priority' => 1,
            ],
            [
                'tenant_id' => 1,
                'tax_class_id' => 4,
                'name' => 'EU Digital Services Tax',
                'country_code' => 'GB',
                'state_code' => null,
                'city' => null,
                'postal_code' => null,
                'rate' => 0.02, // 2%
                'tax_type' => 'digital_tax',
                'is_compound' => false,
                'is_shipping_taxable' => false,
                'is_active' => true,
                'priority' => 1,
            ],
        ];

        foreach ($taxRates as $taxRateData) {
            TaxRate::create($taxRateData);
        }
    }
}
