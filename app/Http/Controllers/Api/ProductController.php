<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = DB::table('products')
                ->leftJoin('vendors', 'products.vendor_id', '=', 'vendors.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->select('products.*', 'vendors.business_name', 'categories.name as category_name', 'brands.name as brand_name');

            // Filter by product type if specified
            if ($request->has('type')) {
                $query->where('products.product_type', $request->type);
            }

            $products = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product counts by type
     */
    public function getProductCounts()
    {
        try {
            $counts = DB::table('products')
                ->select('product_type', DB::raw('count(*) as count'))
                ->groupBy('product_type')
                ->pluck('count', 'product_type')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'simple' => $counts['simple'] ?? 0,
                    'variation' => $counts['variation'] ?? 0,
                    'digital' => $counts['digital'] ?? 0,
                    'auction' => $counts['auction'] ?? 0,
                    'wholesale' => $counts['wholesale'] ?? 0,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product counts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Required fields
            'tenant_id' => 'required|exists:tenants,id',
            'vendor_id' => 'required|exists:vendors,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:500',
            'description' => 'required|string',
            'sku' => 'required|string|unique:products,sku|max:100',
            'price' => 'required|numeric|min:0|max:999999999.9999',
            'product_type' => 'required|in:simple,variation,digital,auction,wholesale',
            
            // Optional basic fields
            'short_description' => 'nullable|string|max:1000',
            'slug' => 'nullable|string|unique:products,slug|max:500',
            'barcode' => 'nullable|string|max:100',
            'mpn' => 'nullable|string|max:100',
            'gtin' => 'nullable|string|max:50',
            'isbn' => 'nullable|string|max:20',
            
            // Pricing fields
            'sale_price' => 'nullable|numeric|min:0|max:999999999.9999',
            'compare_price' => 'nullable|numeric|min:0|max:999999999.9999',
            'cost_price' => 'nullable|numeric|min:0|max:999999999.9999',
            'msrp' => 'nullable|numeric|min:0|max:999999999.9999',
            'wholesale_price' => 'nullable|numeric|min:0|max:999999999.9999',
            
            // Tax fields
            'tax_class' => 'nullable|string|max:50',
            'tax_status' => 'nullable|in:taxable,shipping_only,none',
            
            // Inventory fields
            'quantity' => 'nullable|integer|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'min_quantity' => 'nullable|integer|min:1',
            'max_quantity' => 'nullable|integer|min:1',
            'track_quantity' => 'nullable|boolean',
            'manage_stock' => 'nullable|boolean',
            'stock_status' => 'nullable|in:in_stock,out_of_stock,on_backorder,discontinued',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'allow_backorder' => 'nullable|boolean',
            'backorders' => 'nullable|in:no,notify,yes',
            
            // Physical properties
            'weight' => 'nullable|numeric|min:0|max:999999.999',
            'length' => 'nullable|numeric|min:0|max:999999.999',
            'width' => 'nullable|numeric|min:0|max:999999.999',
            'height' => 'nullable|numeric|min:0|max:999999.999',
            'volume' => 'nullable|numeric|min:0|max:999999.999',
            
            // Shipping fields
            'requires_shipping' => 'nullable|boolean',
            'shipping_class' => 'nullable|string|max:100',
            'free_shipping' => 'nullable|boolean',
            'separate_shipping' => 'nullable|boolean',
            
            // Digital product fields
            'type' => 'nullable|in:physical,digital,service',
            'is_digital' => 'nullable|boolean',
            'is_downloadable' => 'nullable|boolean',
            'download_limit' => 'nullable|integer|min:-1',
            'download_expiry' => 'nullable|integer|min:-1',
            'downloadable_files' => 'nullable|array',
            
            // Status and visibility
            'status' => 'nullable|in:draft,pending,published,private,archived,trash',
            'visibility' => 'nullable|in:visible,catalog,search,hidden',
            'catalog_visibility' => 'nullable|in:visible,catalog,search,hidden',
            'is_featured' => 'nullable|boolean',
            
            // Date fields
            'date_on_sale_from' => 'nullable|date',
            'date_on_sale_to' => 'nullable|date|after_or_equal:date_on_sale_from',
            
            // Media fields
            'images' => 'nullable|array',
            'featured_image' => 'nullable|string|max:500',
            'gallery_images' => 'nullable|array',
            'video_url' => 'nullable|url|max:500',
            'video_embed_code' => 'nullable|string',
            
            // Product attributes
            'attributes' => 'nullable|array',
            'custom_fields' => 'nullable|array',
            'specifications' => 'nullable|array',
            
            // Variations
            'is_variable' => 'nullable|boolean',
            'variation_attributes' => 'nullable|array',
            'default_attributes' => 'nullable|array',
            
            // Product type
            'product_type' => 'nullable|in:simple,grouped,external,variable,bundle,digital,subscription',
            'parent_id' => 'nullable|exists:products,id',
            'grouped_products' => 'nullable|array',
            'cross_sells' => 'nullable|array',
            'upsells' => 'nullable|array',
            
            // External product
            'external_url' => 'nullable|url|max:500',
            'button_text' => 'nullable|string|max:100',
            
            // Reviews
            'enable_reviews' => 'nullable|boolean',
            
            // Purchase notes
            'purchase_note' => 'nullable|string',
            
            // Restrictions
            'membership_required' => 'nullable|boolean',
            'age_restriction' => 'nullable|integer|min:0|max:120',
            'purchase_limit' => 'nullable|integer|min:1',
            
            // Multi-language
            'language' => 'nullable|string|max:10',
            'translation_group' => 'nullable|string|max:36',
            
            // SEO fields
            'seo_title' => 'nullable|string|max:500',
            'seo_description' => 'nullable|string|max:1000',
            'seo_keywords' => 'nullable|string|max:500',
            'seo_focus_keyword' => 'nullable|string|max:100',
            'canonical_url' => 'nullable|url|max:500',
            'og_title' => 'nullable|string|max:500',
            'og_description' => 'nullable|string|max:1000',
            'og_image' => 'nullable|string|max:500',
            'schema_markup' => 'nullable|array',
            
            // Vendor specific
            'vendor_sku' => 'nullable|string|max:100',
            'vendor_notes' => 'nullable|string',
            
            // Brand
            'brand_id' => 'nullable|exists:brands,id',
            
            // New Product Type Fields
            'unit' => 'nullable|string|max:50',
            'unit_value' => 'nullable|numeric|min:0',
            'thumbnail_image' => 'nullable|url|max:500',
            'gallery_images' => 'nullable|array',
            'videos' => 'nullable|array',
            'video_thumbnails' => 'nullable|array',
            'youtube_url' => 'nullable|url|max:500',
            'youtube_shorts_url' => 'nullable|url|max:500',
            'pdf_specification' => 'nullable|url|max:500',
            
            // Auction Specific Fields
            'reserve_price' => 'nullable|numeric|min:0',
            'product_country' => 'nullable|string|max:100',
            
            // Wholesale Specific Fields
            'wholesale_price' => 'nullable|numeric|min:0',
            'min_wholesale_quantity' => 'nullable|integer|min:1',
            'max_wholesale_quantity' => 'nullable|integer|min:1',
            
            // Digital Product Fields
            'digital_files' => 'nullable|array',
            'download_limit' => 'nullable|integer|min:-1',
            'download_expiry_days' => 'nullable|integer|min:1',
            
            // Enhanced SEO Fields
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_image' => 'nullable|url|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            
            // Frequently Bought Together
            'frequently_bought_together' => 'nullable|array',
            
            // Product Status Enhancements
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate UUID if not provided
            $uuid = $request->uuid ?? \Illuminate\Support\Str::uuid()->toString();
            
            // Generate slug if not provided
            $slug = $request->slug ?? \Illuminate\Support\Str::slug($request->name);
            
            $product = DB::table('products')->insertGetId([
                'tenant_id' => $request->tenant_id,
                'vendor_id' => $request->vendor_id,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'uuid' => $uuid,
                'name' => $request->name,
                'slug' => $slug,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'mpn' => $request->mpn,
                'gtin' => $request->gtin,
                'isbn' => $request->isbn,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'compare_price' => $request->compare_price,
                'cost_price' => $request->cost_price,
                'msrp' => $request->msrp,
                'wholesale_price' => $request->wholesale_price,
                
                // New Product Type Fields
                'product_type' => $request->product_type,
                'unit' => $request->unit,
                'unit_value' => $request->unit_value,
                'thumbnail_image' => $request->thumbnail_image,
                'gallery_images' => $request->gallery_images ? json_encode($request->gallery_images) : null,
                'videos' => $request->videos ? json_encode($request->videos) : null,
                'video_thumbnails' => $request->video_thumbnails ? json_encode($request->video_thumbnails) : null,
                'youtube_url' => $request->youtube_url,
                'youtube_shorts_url' => $request->youtube_shorts_url,
                'pdf_specification' => $request->pdf_specification,
                
                // Auction Specific Fields
                'reserve_price' => $request->reserve_price,
                'product_country' => $request->product_country,
                
                // Wholesale Specific Fields
                'min_wholesale_quantity' => $request->min_wholesale_quantity,
                'max_wholesale_quantity' => $request->max_wholesale_quantity,
                
                // Digital Product Fields
                'digital_files' => $request->digital_files ? json_encode($request->digital_files) : null,
                'download_limit' => $request->download_limit ?? -1,
                'download_expiry_days' => $request->download_expiry_days,
                
                // Enhanced SEO Fields
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_image' => $request->meta_image,
                'meta_keywords' => $request->meta_keywords,
                
                // Frequently Bought Together
                'frequently_bought_together' => $request->frequently_bought_together ? json_encode($request->frequently_bought_together) : null,
                
                // Product Status Enhancements
                'is_featured' => $request->is_featured ?? false,
                'is_trending' => $request->is_trending ?? false,
                'is_bestseller' => $request->is_bestseller ?? false,
                'tax_class' => $request->tax_class ?? 'standard',
                'tax_status' => $request->tax_status ?? 'taxable',
                'quantity' => $request->quantity ?? $request->stock_quantity ?? 0,
                'stock_quantity' => $request->stock_quantity ?? $request->quantity ?? 0,
                'min_quantity' => $request->min_quantity ?? 1,
                'max_quantity' => $request->max_quantity,
                'track_quantity' => $request->track_quantity ?? true,
                'manage_stock' => $request->manage_stock ?? true,
                'stock_status' => $request->stock_status ?? 'in_stock',
                'low_stock_threshold' => $request->low_stock_threshold ?? 5,
                'allow_backorder' => $request->allow_backorder ?? false,
                'backorders' => $request->backorders ?? 'no',
                'weight' => $request->weight,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'volume' => $request->volume,
                'requires_shipping' => $request->requires_shipping ?? true,
                'shipping_class' => $request->shipping_class,
                'free_shipping' => $request->free_shipping ?? false,
                'separate_shipping' => $request->separate_shipping ?? false,
                'type' => $request->type ?? 'physical',
                'is_digital' => $request->is_digital ?? false,
                'is_downloadable' => $request->is_downloadable ?? false,
                'download_limit' => $request->download_limit ?? -1,
                'download_expiry' => $request->download_expiry ?? -1,
                'downloadable_files' => $request->downloadable_files ? json_encode($request->downloadable_files) : null,
                'status' => $request->status ?? 'draft',
                'visibility' => $request->visibility ?? 'visible',
                'catalog_visibility' => $request->catalog_visibility ?? 'visible',
                'is_featured' => $request->is_featured ?? false,
                'date_on_sale_from' => $request->date_on_sale_from,
                'date_on_sale_to' => $request->date_on_sale_to,
                'images' => $request->images ? json_encode($request->images) : null,
                'featured_image' => $request->featured_image,
                'gallery_images' => $request->gallery_images ? json_encode($request->gallery_images) : null,
                'video_url' => $request->video_url,
                'video_embed_code' => $request->video_embed_code,
                'attributes' => $request->attributes ? json_encode($request->attributes) : null,
                'custom_fields' => $request->custom_fields ? json_encode($request->custom_fields) : null,
                'specifications' => $request->specifications ? json_encode($request->specifications) : null,
                'is_variable' => $request->is_variable ?? false,
                'variation_attributes' => $request->variation_attributes ? json_encode($request->variation_attributes) : null,
                'default_attributes' => $request->default_attributes ? json_encode($request->default_attributes) : null,
                'product_type' => $request->product_type ?? 'simple',
                'parent_id' => $request->parent_id,
                'grouped_products' => $request->grouped_products ? json_encode($request->grouped_products) : null,
                'cross_sells' => $request->cross_sells ? json_encode($request->cross_sells) : null,
                'upsells' => $request->upsells ? json_encode($request->upsells) : null,
                'external_url' => $request->external_url,
                'button_text' => $request->button_text,
                'enable_reviews' => $request->enable_reviews ?? true,
                'purchase_note' => $request->purchase_note,
                'membership_required' => $request->membership_required ?? false,
                'age_restriction' => $request->age_restriction,
                'purchase_limit' => $request->purchase_limit,
                'language' => $request->language ?? 'en',
                'translation_group' => $request->translation_group,
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'seo_keywords' => $request->seo_keywords,
                'seo_focus_keyword' => $request->seo_focus_keyword,
                'canonical_url' => $request->canonical_url,
                'og_title' => $request->og_title,
                'og_description' => $request->og_description,
                'og_image' => $request->og_image,
                'schema_markup' => $request->schema_markup ? json_encode($request->schema_markup) : null,
                'vendor_sku' => $request->vendor_sku,
                'vendor_notes' => $request->vendor_notes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => ['id' => $product]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = DB::table('products')
                ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.*', 'vendors.business_name', 'categories.name as category_name')
                ->where('products.id', $id)
                ->first();
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'sku' => 'sometimes|required|string|unique:products,sku,' . $id,
            'price' => 'sometimes|required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
            'product_type' => 'sometimes|required|in:physical,digital',
            'status' => 'sometimes|required|in:active,inactive,draft',
            'is_featured' => 'boolean',
            'requires_shipping' => 'boolean',
            'track_inventory' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = $request->only([
                'name', 'description', 'sku', 'price', 'compare_price', 'cost_price',
                'stock_quantity', 'min_stock_level', 'weight', 'dimensions',
                'product_type', 'status', 'is_featured', 'requires_shipping', 'track_inventory',
                'seo_title', 'seo_description', 'seo_keywords'
            ]);
            
            if ($request->has('images')) {
                $updateData['images'] = json_encode($request->images);
            }
            
            if ($request->has('attributes')) {
                $updateData['attributes'] = json_encode($request->attributes);
            }
            
            $updateData['updated_at'] = now();

            $updated = DB::table('products')->where('id', $id)->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found or no changes made'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = DB::table('products')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product status
     */
    public function updateStatus(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive,draft'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updated = DB::table('products')->where('id', $id)->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product status updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product inventory
     */
    public function updateInventory(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [
                'stock_quantity' => $request->stock_quantity,
                'updated_at' => now()
            ];

            if ($request->has('min_stock_level')) {
                $updateData['min_stock_level'] = $request->min_stock_level;
            }

            $updated = DB::table('products')->where('id', $id)->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product inventory updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product inventory: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get low stock products
     */
    public function getLowStock()
    {
        try {
            $products = DB::table('products')
                ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.*', 'vendors.business_name', 'categories.name as category_name')
                ->whereRaw('products.stock_quantity <= products.min_stock_level')
                ->where('products.track_inventory', true)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching low stock products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update products
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id',
            'action' => 'required|in:activate,deactivate,delete,update_price,update_stock',
            'value' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $productIds = $request->product_ids;
            $action = $request->action;
            $value = $request->value;

            $updateData = ['updated_at' => now()];

            switch ($action) {
                case 'activate':
                    $updateData['status'] = 'active';
                    break;
                case 'deactivate':
                    $updateData['status'] = 'inactive';
                    break;
                case 'update_price':
                    if (!$value) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Price value is required for price update'
                        ], 422);
                    }
                    $updateData['price'] = $value;
                    break;
                case 'update_stock':
                    if (!$value) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stock value is required for stock update'
                        ], 422);
                    }
                    $updateData['stock_quantity'] = $value;
                    break;
            }

            if ($action === 'delete') {
                $deleted = DB::table('products')->whereIn('id', $productIds)->delete();
                return response()->json([
                    'success' => true,
                    'message' => "{$deleted} products deleted successfully"
                ]);
            } else {
                $updated = DB::table('products')->whereIn('id', $productIds)->update($updateData);
                return response()->json([
                    'success' => true,
                    'message' => "{$updated} products updated successfully"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk update: ' . $e->getMessage()
            ], 500);
        }
    }
}
