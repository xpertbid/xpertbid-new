<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Category;
use App\Models\Brand;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CachedVehicleController extends Controller
{
    /**
     * Display a listing of vehicles with caching
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'category' => $request->get('category'),
            'brand' => $request->get('brand'),
            'status' => $request->get('status'),
            'price_min' => $request->get('price_min'),
            'price_max' => $request->get('price_max'),
            'year_min' => $request->get('year_min'),
            'year_max' => $request->get('year_max'),
            'fuel_type' => $request->get('fuel_type'),
            'transmission' => $request->get('transmission'),
            'sort' => $request->get('sort', 'created_at'),
            'order' => $request->get('order', 'desc'),
        ];

        // Generate cache key based on filters
        $cacheKey = CacheService::generateFilterKey(CacheService::VEHICLES_PREFIX . 'index', $filters);
        
        $data = CacheService::remember($cacheKey, CacheService::VEHICLES_TTL, function () use ($filters) {
            $query = Vehicle::with(['category', 'brand', 'specifications']);

            // Apply filters
            if (!empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('make', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('model', 'like', '%' . $filters['search'] . '%');
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

            if (!empty($filters['price_min'])) {
                $query->where('price', '>=', $filters['price_min']);
            }

            if (!empty($filters['price_max'])) {
                $query->where('price', '<=', $filters['price_max']);
            }

            if (!empty($filters['year_min'])) {
                $query->where('year', '>=', $filters['year_min']);
            }

            if (!empty($filters['year_max'])) {
                $query->where('year', '<=', $filters['year_max']);
            }

            if (!empty($filters['fuel_type'])) {
                $query->where('fuel_type', $filters['fuel_type']);
            }

            if (!empty($filters['transmission'])) {
                $query->where('transmission', $filters['transmission']);
            }

            // Apply sorting
            $query->orderBy($filters['sort'], $filters['order']);

            return [
                'vehicles' => $query->paginate(20),
                'total_count' => $query->count(),
                'filters' => $filters
            ];
        });

        // Get cached filter options
        $filterOptions = CacheService::remember(CacheService::VEHICLES_PREFIX . 'filter_options', CacheService::CATEGORIES_TTL, function () {
            return [
                'categories' => Category::where('is_active', true)->orderBy('name')->get(),
                'brands' => Brand::where('status', true)->orderBy('name')->get(),
                'statuses' => ['active', 'inactive', 'sold', 'archived'],
                'fuel_types' => ['gasoline', 'diesel', 'electric', 'hybrid', 'lpg', 'cng'],
                'transmissions' => ['manual', 'automatic', 'semi-automatic', 'cvt'],
                'price_ranges' => [
                    '0-10000' => 'Under $10K',
                    '10000-25000' => '$10K - $25K',
                    '25000-50000' => '$25K - $50K',
                    '50000-100000' => '$50K - $100K',
                    '100000+' => 'Over $100K'
                ]
            ];
        });

        return view('admin.vehicles.index', array_merge($data, $filterOptions));
    }

    /**
     * Display the specified vehicle with caching
     */
    public function show($id)
    {
        $vehicle = CacheService::remember(CacheService::VEHICLES_PREFIX . 'show:' . $id, CacheService::VEHICLES_TTL, function () use ($id) {
            return Vehicle::with(['category', 'brand', 'specifications', 'images'])
                          ->findOrFail($id);
        });

        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Store a newly created vehicle and invalidate cache
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'required|numeric|min:0',
            'mileage' => 'nullable|integer|min:0',
            'fuel_type' => 'required|in:gasoline,diesel,electric,hybrid,lpg,cng',
            'transmission' => 'required|in:manual,automatic,semi-automatic,cvt',
            'engine_size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:active,inactive,sold,archived',
            'vin' => 'nullable|string|max:17|unique:vehicles',
            'specifications' => 'nullable|array',
        ]);

        $vehicle = Vehicle::create($validated);

        // Handle specifications
        if (!empty($validated['specifications'])) {
            foreach ($validated['specifications'] as $spec) {
                $vehicle->specifications()->create([
                    'name' => $spec['name'],
                    'value' => $spec['value'],
                ]);
            }
        }

        // Invalidate related caches
        $this->invalidateVehicleCaches();

        return redirect()->route('admin.vehicles.index')
                        ->with('success', 'Vehicle created successfully!');
    }

    /**
     * Update the specified vehicle and invalidate cache
     */
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'required|numeric|min:0',
            'mileage' => 'nullable|integer|min:0',
            'fuel_type' => 'required|in:gasoline,diesel,electric,hybrid,lpg,cng',
            'transmission' => 'required|in:manual,automatic,semi-automatic,cvt',
            'engine_size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:active,inactive,sold,archived',
            'vin' => 'nullable|string|max:17|unique:vehicles,vin,' . $id,
            'specifications' => 'nullable|array',
        ]);

        $vehicle->update($validated);

        // Handle specifications
        if (isset($validated['specifications'])) {
            $vehicle->specifications()->delete();
            foreach ($validated['specifications'] as $spec) {
                $vehicle->specifications()->create([
                    'name' => $spec['name'],
                    'value' => $spec['value'],
                ]);
            }
        }

        // Invalidate related caches
        $this->invalidateVehicleCaches($id);

        return redirect()->route('admin.vehicles.index')
                        ->with('success', 'Vehicle updated successfully!');
    }

    /**
     * Remove the specified vehicle and invalidate cache
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        // Invalidate related caches
        $this->invalidateVehicleCaches($id);

        return redirect()->route('admin.vehicles.index')
                        ->with('success', 'Vehicle deleted successfully!');
    }

    /**
     * Get vehicle statistics with caching
     */
    public function stats()
    {
        $stats = CacheService::remember(CacheService::VEHICLES_PREFIX . 'stats', CacheService::STATS_TTL, function () {
            return [
                'total_vehicles' => Vehicle::count(),
                'active_vehicles' => Vehicle::where('status', 'active')->count(),
                'sold_vehicles' => Vehicle::where('status', 'sold')->count(),
                'archived_vehicles' => Vehicle::where('status', 'archived')->count(),
                'average_price' => Vehicle::avg('price'),
                'total_value' => Vehicle::sum('price'),
                'vehicles_by_category' => Vehicle::select('categories.name', DB::raw('count(*) as count'))
                    ->join('categories', 'vehicles.category_id', '=', 'categories.id')
                    ->groupBy('categories.name')
                    ->get(),
                'vehicles_by_brand' => Vehicle::select('brands.name', DB::raw('count(*) as count'))
                    ->join('brands', 'vehicles.brand_id', '=', 'brands.id')
                    ->groupBy('brands.name')
                    ->get(),
                'vehicles_by_fuel_type' => Vehicle::select('fuel_type', DB::raw('count(*) as count'))
                    ->groupBy('fuel_type')
                    ->get(),
                'vehicles_by_transmission' => Vehicle::select('transmission', DB::raw('count(*) as count'))
                    ->groupBy('transmission')
                    ->get(),
                'average_mileage' => Vehicle::avg('mileage'),
                'average_year' => Vehicle::avg('year'),
                'recent_vehicles' => Vehicle::latest()->take(5)->get(),
            ];
        });

        return response()->json($stats);
    }

    /**
     * Get vehicles by make/model with caching
     */
    public function byMakeModel(Request $request)
    {
        $make = $request->get('make', '');
        $model = $request->get('model', '');
        
        if (empty($make) && empty($model)) {
            return response()->json([]);
        }

        $cacheKey = CacheService::VEHICLES_PREFIX . 'make_model:' . md5($make . '_' . $model);
        
        $vehicles = CacheService::remember($cacheKey, CacheService::VEHICLES_TTL, function () use ($make, $model) {
            $query = Vehicle::with(['category', 'brand']);
            
            if (!empty($make)) {
                $query->where('make', 'like', '%' . $make . '%');
            }
            
            if (!empty($model)) {
                $query->where('model', 'like', '%' . $model . '%');
            }
            
            return $query->limit(20)->get();
        });

        return response()->json($vehicles);
    }

    /**
     * Search vehicles with caching
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $cacheKey = CacheService::VEHICLES_PREFIX . 'search:' . md5($query);
        
        $vehicles = CacheService::remember($cacheKey, CacheService::VEHICLES_TTL, function () use ($query) {
            return Vehicle::where('title', 'like', '%' . $query . '%')
                          ->orWhere('description', 'like', '%' . $query . '%')
                          ->orWhere('make', 'like', '%' . $query . '%')
                          ->orWhere('model', 'like', '%' . $query . '%')
                          ->with(['category', 'brand'])
                          ->limit(10)
                          ->get();
        });

        return response()->json($vehicles);
    }

    /**
     * Invalidate vehicle-related caches
     */
    private function invalidateVehicleCaches($vehicleId = null)
    {
        // Clear index caches
        CacheService::invalidateByPattern(CacheService::VEHICLES_PREFIX . 'index*');
        CacheService::invalidateByPattern(CacheService::VEHICLES_PREFIX . 'search*');
        CacheService::invalidateByPattern(CacheService::VEHICLES_PREFIX . 'stats*');
        CacheService::invalidateByPattern(CacheService::VEHICLES_PREFIX . 'make_model*');
        
        // Clear specific vehicle cache
        if ($vehicleId) {
            CacheService::invalidateModel(CacheService::VEHICLES_PREFIX, $vehicleId);
            CacheService::invalidateByPattern(CacheService::VEHICLES_PREFIX . 'show:' . $vehicleId);
        }
        
        // Clear dashboard stats
        CacheService::invalidateByPattern(CacheService::STATS_PREFIX . '*');
    }
}
