<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Category;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CachedPropertyController extends Controller
{
    /**
     * Display a listing of properties with caching
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'category' => $request->get('category'),
            'status' => $request->get('status'),
            'price_min' => $request->get('price_min'),
            'price_max' => $request->get('price_max'),
            'location' => $request->get('location'),
            'sort' => $request->get('sort', 'created_at'),
            'order' => $request->get('order', 'desc'),
        ];

        // Generate cache key based on filters
        $cacheKey = CacheService::generateFilterKey(CacheService::PROPERTIES_PREFIX . 'index', $filters);
        
        $data = CacheService::remember($cacheKey, CacheService::PROPERTIES_TTL, function () use ($filters) {
            $query = Property::with(['category', 'amenities', 'features']);

            // Apply filters
            if (!empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('address', 'like', '%' . $filters['search'] . '%');
                });
            }

            if (!empty($filters['category'])) {
                $query->where('category_id', $filters['category']);
            }

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['price_min'])) {
                $query->where('price', '>=', $filters['price_min']);
            }

            if (!empty($filters['price_max'])) {
                $query->where('price', '<=', $filters['price_max']);
            }

            if (!empty($filters['location'])) {
                $query->where('address', 'like', '%' . $filters['location'] . '%');
            }

            // Apply sorting
            $query->orderBy($filters['sort'], $filters['order']);

            return [
                'properties' => $query->paginate(20),
                'total_count' => $query->count(),
                'filters' => $filters
            ];
        });

        // Get cached filter options
        $filterOptions = CacheService::remember(CacheService::PROPERTIES_PREFIX . 'filter_options', CacheService::CATEGORIES_TTL, function () {
            return [
                'categories' => Category::where('is_active', true)->orderBy('name')->get(),
                'statuses' => ['published', 'draft', 'archived'],
                'price_ranges' => [
                    '0-100000' => 'Under $100K',
                    '100000-500000' => '$100K - $500K',
                    '500000-1000000' => '$500K - $1M',
                    '1000000-5000000' => '$1M - $5M',
                    '5000000+' => 'Over $5M'
                ]
            ];
        });

        return view('admin.properties.index', array_merge($data, $filterOptions));
    }

    /**
     * Display the specified property with caching
     */
    public function show($id)
    {
        $property = CacheService::remember(CacheService::PROPERTIES_PREFIX . 'show:' . $id, CacheService::PROPERTIES_TTL, function () use ($id) {
            return Property::with(['category', 'amenities', 'features', 'images'])
                          ->findOrFail($id);
        });

        return view('admin.properties.show', compact('property'));
    }

    /**
     * Store a newly created property and invalidate cache
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:published,draft,archived',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:property_amenities,id',
            'features' => 'nullable|array',
            'features.*' => 'exists:property_features,id',
        ]);

        $property = Property::create($validated);

        // Handle amenities and features
        if (!empty($validated['amenities'])) {
            $property->amenities()->attach($validated['amenities']);
        }

        if (!empty($validated['features'])) {
            $property->features()->attach($validated['features']);
        }

        // Invalidate related caches
        $this->invalidatePropertyCaches();

        return redirect()->route('admin.properties.index')
                        ->with('success', 'Property created successfully!');
    }

    /**
     * Update the specified property and invalidate cache
     */
    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:published,draft,archived',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:property_amenities,id',
            'features' => 'nullable|array',
            'features.*' => 'exists:property_features,id',
        ]);

        $property->update($validated);

        // Handle amenities and features
        if (isset($validated['amenities'])) {
            $property->amenities()->sync($validated['amenities']);
        }

        if (isset($validated['features'])) {
            $property->features()->sync($validated['features']);
        }

        // Invalidate related caches
        $this->invalidatePropertyCaches($id);

        return redirect()->route('admin.properties.index')
                        ->with('success', 'Property updated successfully!');
    }

    /**
     * Remove the specified property and invalidate cache
     */
    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $property->delete();

        // Invalidate related caches
        $this->invalidatePropertyCaches($id);

        return redirect()->route('admin.properties.index')
                        ->with('success', 'Property deleted successfully!');
    }

    /**
     * Get property statistics with caching
     */
    public function stats()
    {
        $stats = CacheService::remember(CacheService::PROPERTIES_PREFIX . 'stats', CacheService::STATS_TTL, function () {
            return [
                'total_properties' => Property::count(),
                'published_properties' => Property::where('status', 'published')->count(),
                'draft_properties' => Property::where('status', 'draft')->count(),
                'archived_properties' => Property::where('status', 'archived')->count(),
                'average_price' => Property::avg('price'),
                'total_value' => Property::sum('price'),
                'properties_by_category' => Property::select('categories.name', DB::raw('count(*) as count'))
                    ->join('categories', 'properties.category_id', '=', 'categories.id')
                    ->groupBy('categories.name')
                    ->get(),
                'properties_by_location' => Property::select('city', 'state', DB::raw('count(*) as count'))
                    ->groupBy('city', 'state')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get(),
                'price_ranges' => [
                    'under_100k' => Property::where('price', '<', 100000)->count(),
                    '100k_500k' => Property::whereBetween('price', [100000, 500000])->count(),
                    '500k_1m' => Property::whereBetween('price', [500000, 1000000])->count(),
                    '1m_5m' => Property::whereBetween('price', [1000000, 5000000])->count(),
                    'over_5m' => Property::where('price', '>', 5000000)->count(),
                ],
                'recent_properties' => Property::latest()->take(5)->get(),
            ];
        });

        return response()->json($stats);
    }

    /**
     * Get properties by location with caching
     */
    public function byLocation(Request $request)
    {
        $location = $request->get('location', '');
        
        if (empty($location)) {
            return response()->json([]);
        }

        $cacheKey = CacheService::PROPERTIES_PREFIX . 'location:' . md5($location);
        
        $properties = CacheService::remember($cacheKey, CacheService::PROPERTIES_TTL, function () use ($location) {
            return Property::where('address', 'like', '%' . $location . '%')
                          ->orWhere('city', 'like', '%' . $location . '%')
                          ->orWhere('state', 'like', '%' . $location . '%')
                          ->with(['category'])
                          ->limit(20)
                          ->get();
        });

        return response()->json($properties);
    }

    /**
     * Search properties with caching
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $cacheKey = CacheService::PROPERTIES_PREFIX . 'search:' . md5($query);
        
        $properties = CacheService::remember($cacheKey, CacheService::PROPERTIES_TTL, function () use ($query) {
            return Property::where('title', 'like', '%' . $query . '%')
                          ->orWhere('description', 'like', '%' . $query . '%')
                          ->orWhere('address', 'like', '%' . $query . '%')
                          ->with(['category'])
                          ->limit(10)
                          ->get();
        });

        return response()->json($properties);
    }

    /**
     * Invalidate property-related caches
     */
    private function invalidatePropertyCaches($propertyId = null)
    {
        // Clear index caches
        CacheService::invalidateByPattern(CacheService::PROPERTIES_PREFIX . 'index*');
        CacheService::invalidateByPattern(CacheService::PROPERTIES_PREFIX . 'search*');
        CacheService::invalidateByPattern(CacheService::PROPERTIES_PREFIX . 'stats*');
        CacheService::invalidateByPattern(CacheService::PROPERTIES_PREFIX . 'location*');
        
        // Clear specific property cache
        if ($propertyId) {
            CacheService::invalidateModel(CacheService::PROPERTIES_PREFIX, $propertyId);
            CacheService::invalidateByPattern(CacheService::PROPERTIES_PREFIX . 'show:' . $propertyId);
        }
        
        // Clear dashboard stats
        CacheService::invalidateByPattern(CacheService::STATS_PREFIX . '*');
    }
}
