<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tag;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CachedProductController extends Controller
{
    /**
     * Display a listing of products with caching
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'category' => $request->get('category'),
            'brand' => $request->get('brand'),
            'status' => $request->get('status'),
            'sort' => $request->get('sort', 'created_at'),
            'order' => $request->get('order', 'desc'),
        ];

        // Generate cache key based on filters
        $cacheKey = CacheService::generateFilterKey(CacheService::PRODUCTS_PREFIX . 'index', $filters);
        
        $data = CacheService::remember($cacheKey, CacheService::PRODUCTS_TTL, function () use ($filters) {
            $query = Product::with(['category', 'brand', 'tags']);

            // Apply filters
            if (!empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('sku', 'like', '%' . $filters['search'] . '%');
                });
            }

            if (!empty($filters['category'])) {
                $query->where('category_id', $filters['category']);
            }

            if (!empty($filters['brand'])) {
                $query->where('brand_id', $filters['brand']);
            }

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Apply sorting
            $query->orderBy($filters['sort'], $filters['order']);

            return [
                'products' => $query->paginate(20),
                'total_count' => $query->count(),
                'filters' => $filters
            ];
        });

        // Get cached filter options
        $filterOptions = CacheService::remember(CacheService::PRODUCTS_PREFIX . 'filter_options', CacheService::CATEGORIES_TTL, function () {
            return [
                'categories' => Category::where('is_active', true)->orderBy('name')->get(),
                'brands' => Brand::where('status', true)->orderBy('name')->get(),
                'statuses' => ['active', 'inactive', 'draft', 'archived']
            ];
        });

        return view('admin.products.index', array_merge($data, $filterOptions));
    }

    /**
     * Display the specified product with caching
     */
    public function show($id)
    {
        $product = CacheService::remember(CacheService::PRODUCTS_PREFIX . 'show:' . $id, CacheService::PRODUCTS_TTL, function () use ($id) {
            return Product::with(['category', 'brand', 'tags', 'images'])
                          ->findOrFail($id);
        });

        return view('admin.products.show', compact('product'));
    }

    /**
     * Store a newly created product and invalidate cache
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'sku' => 'required|string|unique:products',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:active,inactive,draft',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $product = Product::create($validated);

        // Handle tags
        if (!empty($validated['tags'])) {
            $product->tags()->attach($validated['tags']);
        }

        // Invalidate related caches
        $this->invalidateProductCaches();

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product created successfully!');
    }

    /**
     * Update the specified product and invalidate cache
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:active,inactive,draft',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $product->update($validated);

        // Handle tags
        if (isset($validated['tags'])) {
            $product->tags()->sync($validated['tags']);
        }

        // Invalidate related caches
        $this->invalidateProductCaches($id);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product and invalidate cache
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        // Invalidate related caches
        $this->invalidateProductCaches($id);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product deleted successfully!');
    }

    /**
     * Get product statistics with caching
     */
    public function stats()
    {
        $stats = CacheService::remember(CacheService::PRODUCTS_PREFIX . 'stats', CacheService::STATS_TTL, function () {
            return [
                'total_products' => Product::count(),
                'active_products' => Product::where('status', 'active')->count(),
                'inactive_products' => Product::where('status', 'inactive')->count(),
                'draft_products' => Product::where('status', 'draft')->count(),
                'products_with_sale_price' => Product::whereNotNull('sale_price')->count(),
                'average_price' => Product::avg('price'),
                'total_value' => Product::sum('price'),
                'products_by_category' => Product::select('categories.name', DB::raw('count(*) as count'))
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->groupBy('categories.name')
                    ->get(),
                'products_by_brand' => Product::select('brands.name', DB::raw('count(*) as count'))
                    ->join('brands', 'products.brand_id', '=', 'brands.id')
                    ->groupBy('brands.name')
                    ->get(),
            ];
        });

        return response()->json($stats);
    }

    /**
     * Search products with caching
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $cacheKey = CacheService::PRODUCTS_PREFIX . 'search:' . md5($query);
        
        $products = CacheService::remember($cacheKey, CacheService::PRODUCTS_TTL, function () use ($query) {
            return Product::where('name', 'like', '%' . $query . '%')
                          ->orWhere('sku', 'like', '%' . $query . '%')
                          ->orWhere('description', 'like', '%' . $query . '%')
                          ->with(['category', 'brand'])
                          ->limit(10)
                          ->get();
        });

        return response()->json($products);
    }

    /**
     * Invalidate product-related caches
     */
    private function invalidateProductCaches($productId = null)
    {
        // Clear index caches
        CacheService::invalidateByPattern(CacheService::PRODUCTS_PREFIX . 'index*');
        CacheService::invalidateByPattern(CacheService::PRODUCTS_PREFIX . 'search*');
        CacheService::invalidateByPattern(CacheService::PRODUCTS_PREFIX . 'stats*');
        
        // Clear specific product cache
        if ($productId) {
            CacheService::invalidateModel(CacheService::PRODUCTS_PREFIX, $productId);
            CacheService::invalidateByPattern(CacheService::PRODUCTS_PREFIX . 'show:' . $productId);
        }
        
        // Clear dashboard stats
        CacheService::invalidateByPattern(CacheService::STATS_PREFIX . '*');
    }
}
