<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create product attributes
        $attributes = [
            [
                'tenant_id' => 1,
                'name' => 'Color',
                'slug' => 'color',
                'type' => 'color',
                'description' => 'Product color selection',
                'is_required' => false,
                'is_filterable' => true,
                'is_variation_attribute' => true,
                'sort_order' => 1,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Size',
                'slug' => 'size',
                'type' => 'select',
                'description' => 'Product size selection',
                'is_required' => false,
                'is_filterable' => true,
                'is_variation_attribute' => true,
                'sort_order' => 2,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Material',
                'slug' => 'material',
                'type' => 'select',
                'description' => 'Product material type',
                'is_required' => false,
                'is_filterable' => true,
                'is_variation_attribute' => false,
                'sort_order' => 3,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Brand',
                'slug' => 'brand',
                'type' => 'select',
                'description' => 'Product brand',
                'is_required' => false,
                'is_filterable' => true,
                'is_variation_attribute' => false,
                'sort_order' => 4,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($attributes as $attribute) {
            $attributeId = DB::table('product_attributes')->insertGetId($attribute);

            // Create attribute values based on attribute type
            if ($attribute['name'] === 'Color') {
                $colorValues = [
                    ['value' => 'Red', 'slug' => 'red', 'color_code' => '#FF0000', 'sort_order' => 1],
                    ['value' => 'Blue', 'slug' => 'blue', 'color_code' => '#0000FF', 'sort_order' => 2],
                    ['value' => 'Green', 'slug' => 'green', 'color_code' => '#00FF00', 'sort_order' => 3],
                    ['value' => 'Yellow', 'slug' => 'yellow', 'color_code' => '#FFFF00', 'sort_order' => 4],
                    ['value' => 'Black', 'slug' => 'black', 'color_code' => '#000000', 'sort_order' => 5],
                    ['value' => 'White', 'slug' => 'white', 'color_code' => '#FFFFFF', 'sort_order' => 6],
                    ['value' => 'Purple', 'slug' => 'purple', 'color_code' => '#800080', 'sort_order' => 7],
                    ['value' => 'Orange', 'slug' => 'orange', 'color_code' => '#FFA500', 'sort_order' => 8],
                ];

                foreach ($colorValues as $value) {
                    DB::table('product_attribute_values')->insert([
                        'product_attribute_id' => $attributeId,
                        'value' => $value['value'],
                        'slug' => $value['slug'],
                        'color_code' => $value['color_code'],
                        'sort_order' => $value['sort_order'],
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } elseif ($attribute['name'] === 'Size') {
                $sizeValues = [
                    ['value' => 'XS', 'slug' => 'xs', 'sort_order' => 1],
                    ['value' => 'S', 'slug' => 's', 'sort_order' => 2],
                    ['value' => 'M', 'slug' => 'm', 'sort_order' => 3],
                    ['value' => 'L', 'slug' => 'l', 'sort_order' => 4],
                    ['value' => 'XL', 'slug' => 'xl', 'sort_order' => 5],
                    ['value' => 'XXL', 'slug' => 'xxl', 'sort_order' => 6],
                    ['value' => 'XXXL', 'slug' => 'xxxl', 'sort_order' => 7],
                ];

                foreach ($sizeValues as $value) {
                    DB::table('product_attribute_values')->insert([
                        'product_attribute_id' => $attributeId,
                        'value' => $value['value'],
                        'slug' => $value['slug'],
                        'sort_order' => $value['sort_order'],
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } elseif ($attribute['name'] === 'Material') {
                $materialValues = [
                    ['value' => 'Cotton', 'slug' => 'cotton', 'sort_order' => 1],
                    ['value' => 'Polyester', 'slug' => 'polyester', 'sort_order' => 2],
                    ['value' => 'Wool', 'slug' => 'wool', 'sort_order' => 3],
                    ['value' => 'Silk', 'slug' => 'silk', 'sort_order' => 4],
                    ['value' => 'Leather', 'slug' => 'leather', 'sort_order' => 5],
                    ['value' => 'Denim', 'slug' => 'denim', 'sort_order' => 6],
                    ['value' => 'Linen', 'slug' => 'linen', 'sort_order' => 7],
                ];

                foreach ($materialValues as $value) {
                    DB::table('product_attribute_values')->insert([
                        'product_attribute_id' => $attributeId,
                        'value' => $value['value'],
                        'slug' => $value['slug'],
                        'sort_order' => $value['sort_order'],
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}