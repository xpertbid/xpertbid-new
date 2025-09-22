<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CachedProductController extends Controller
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Display a listing of the resource with caching
     */
    public function index(Request $request)
    {
        $cacheKey = 'products_list_' . md5(serialize($request->all()));
        
        return $this->cacheService->remember($cacheKey, 60, function () use ($request) {
            try {
                $query = DB::table('products')
                    ->leftJoin('vendors', 'products.vendor_id', '=', 'vendors.id')
                    ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                    ->select('products.*', 'vendors.business_name', 'categories.name as category_name', 'brands.name as brand_name');

                // Apply filters
                if ($request->has('type')) {
                    $query->where('products.product_type', $request->type);
                }

                if ($request->has('category_id')) {
                    $query->where('products.category_id', $request->category_id);
                }

                if ($request->has('brand_id')) {
                    $query->where('products.brand_id', $request->brand_id);
                }

                if ($request->has('vendor_id')) {
                    $query->where('products.vendor_id', $request->vendor_id);
                }

                if ($request->has('status')) {
                    $query->where('products.status', $request->status);
                }

                // Pagination
                $perPage = $request->get('per_page', 15);
                $products = $query->paginate($perPage);

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
        });
    }

    /**
     * Display the specified resource with caching
     */
    public function show(string $id)
    {
        $cachedProduct = $this->cacheService->getProduct($id);
        
        if ($cachedProduct) {
            return response()->json([
                'success' => true,
                'data' => $cachedProduct,
                'from_cache' => true
            ]);
        }

        try {
            $product = DB::table('products')
                ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->select('products.*', 'vendors.business_name', 'categories.name as category_name', 'brands.name as brand_name')
                ->where('products.id', $id)
                ->first();
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Cache the product
            $this->cacheService->putProduct($id, $product);

            return response()->json([
                'success' => true,
                'data' => $product,
                'from_cache' => false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured products with caching
     */
    public function featured()
    {
        return $this->cacheService->remember('featured_products', 120, function () {
            try {
                $products = DB::table('products')
                    ->leftJoin('vendors', 'products.vendor_id', '=', 'vendors.id')
                    ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                    ->select('products.*', 'vendors.business_name', 'categories.name as category_name', 'brands.name as brand_name')
                    ->where('products.is_featured', true)
                    ->where('products.status', 'published')
                    ->orderBy('products.created_at', 'desc')
                    ->limit(12)
                    ->get();

                return response()->json([
                    'success' => true,
                    'data' => $products
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching featured products: ' . $e->getMessage()
                ], 500);
            }
        });
    }

    /**
     * Get trending products with caching
     */
    public function trending()
    {
        return $this->cacheService->remember('trending_products', 60, function () {
            try {
                $products = DB::table('products')
                    ->leftJoin('vendors', 'products.vendor_id', '=', 'vendors.id')
                    ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                    ->select('products.*', 'vendors.business_name', 'categories.name as category_name', 'brands.name as brand_name')
                    ->where('products.is_trending', true)
                    ->where('products.status', 'published')
                    ->orderBy('products.updated_at', 'desc')
                    ->limit(8)
                    ->get();

                return response()->json([
                    'success' => true,
                    'data' => $products
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching trending products: ' . $e->getMessage()
                ], 500);
            }
        });
    }

    /**
     * Search products with caching
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $cacheKey = 'products_search_' . md5($query . serialize($request->except('q')));
        
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required'
            ], 400);
        }

        return $this->cacheService->remember($cacheKey, 30, function () use ($request, $query) {
            try {
                $products = DB::table('products')
                    ->leftJoin('vendors', 'products.vendor_id', '=', 'vendors.id')
                    ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                    ->select('products.*', 'vendors.business_name', 'categories.name as category_name', 'brands.name as brand_name')
                    ->where(function ($q) use ($query) {
                        $q->where('products.name', 'like', "%{$query}%")
                          ->orWhere('products.description', 'like', "%{$query}%")
                          ->orWhere('products.sku', 'like', "%{$query}%")
                          ->orWhere('brands.name', 'like', "%{$query}%")
                          ->orWhere('categories.name', 'like', "%{$query}%");
                    })
                    ->where('products.status', 'published')
                    ->orderBy('products.is_featured', 'desc')
                    ->orderBy('products.created_at', 'desc')
                    ->limit(50)
                    ->get();

                return response()->json([
                    'success' => true,
                    'data' => $products,
                    'query' => $query
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error searching products: ' . $e->getMessage()
                ], 500);
            }
        });
    }

    /**
     * Clear product cache
     */
    public function clearCache(string $id = null)
    {
        try {
            if ($id) {
                $this->cacheService->forgetProduct($id);
                return response()->json([
                    'success' => true,
                    'message' => 'Product cache cleared successfully'
                ]);
            } else {
                // Clear all product-related cache
                $this->cacheService->clearByPattern(self::PRODUCT_PREFIX . '*');
                return response()->json([
                    'success' => true,
                    'message' => 'All product cache cleared successfully'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cache: ' . $e->getMessage()
            ], 500);
        }
    }
}
