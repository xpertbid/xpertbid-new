<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Category;
use App\Models\Brand;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CachedAuctionController extends Controller
{
    /**
     * Display a listing of auctions with caching
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'category' => $request->get('category'),
            'status' => $request->get('status'),
            'sort' => $request->get('sort', 'created_at'),
            'order' => $request->get('order', 'desc'),
        ];

        // Generate cache key based on filters
        $cacheKey = CacheService::generateFilterKey(CacheService::AUCTIONS_PREFIX . 'index', $filters);
        
        $data = CacheService::remember($cacheKey, CacheService::AUCTIONS_TTL, function () use ($filters) {
            $query = Auction::with(['category', 'bids']);

            // Apply filters
            if (!empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%');
                });
            }

            if (!empty($filters['category'])) {
                $query->where('category_id', $filters['category']);
            }

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Apply sorting
            $query->orderBy($filters['sort'], $filters['order']);

            return [
                'auctions' => $query->paginate(20),
                'total_count' => $query->count(),
                'filters' => $filters
            ];
        });

        // Get cached filter options
        $filterOptions = CacheService::remember(CacheService::AUCTIONS_PREFIX . 'filter_options', CacheService::CATEGORIES_TTL, function () {
            return [
                'categories' => Category::where('is_active', true)->orderBy('name')->get(),
                'statuses' => ['active', 'inactive', 'completed', 'cancelled']
            ];
        });

        return view('admin.auctions.index', array_merge($data, $filterOptions));
    }

    /**
     * Display the specified auction with caching
     */
    public function show($id)
    {
        $auction = CacheService::remember(CacheService::AUCTIONS_PREFIX . 'show:' . $id, CacheService::AUCTIONS_TTL, function () use ($id) {
            return Auction::with(['category', 'bids.user', 'images'])
                          ->findOrFail($id);
        });

        return view('admin.auctions.show', compact('auction'));
    }

    /**
     * Store a newly created auction and invalidate cache
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'starting_price' => 'required|numeric|min:0',
            'reserve_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive,completed,cancelled',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
        ]);

        $auction = Auction::create($validated);

        // Invalidate related caches
        $this->invalidateAuctionCaches();

        return redirect()->route('admin.auctions.index')
                        ->with('success', 'Auction created successfully!');
    }

    /**
     * Update the specified auction and invalidate cache
     */
    public function update(Request $request, $id)
    {
        $auction = Auction::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'starting_price' => 'required|numeric|min:0',
            'reserve_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive,completed,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $auction->update($validated);

        // Invalidate related caches
        $this->invalidateAuctionCaches($id);

        return redirect()->route('admin.auctions.index')
                        ->with('success', 'Auction updated successfully!');
    }

    /**
     * Remove the specified auction and invalidate cache
     */
    public function destroy($id)
    {
        $auction = Auction::findOrFail($id);
        $auction->delete();

        // Invalidate related caches
        $this->invalidateAuctionCaches($id);

        return redirect()->route('admin.auctions.index')
                        ->with('success', 'Auction deleted successfully!');
    }

    /**
     * Get auction statistics with caching
     */
    public function stats()
    {
        $stats = CacheService::remember(CacheService::AUCTIONS_PREFIX . 'stats', CacheService::STATS_TTL, function () {
            return [
                'total_auctions' => Auction::count(),
                'active_auctions' => Auction::where('status', 'active')->count(),
                'completed_auctions' => Auction::where('status', 'completed')->count(),
                'cancelled_auctions' => Auction::where('status', 'cancelled')->count(),
                'total_bids' => DB::table('bids')->count(),
                'average_starting_price' => Auction::avg('starting_price'),
                'highest_bid' => DB::table('bids')->max('amount'),
                'auctions_by_category' => Auction::select('categories.name', DB::raw('count(*) as count'))
                    ->join('categories', 'auctions.category_id', '=', 'categories.id')
                    ->groupBy('categories.name')
                    ->get(),
                'recent_auctions' => Auction::latest()->take(5)->get(),
                'ending_soon' => Auction::where('status', 'active')
                    ->where('end_date', '>', now())
                    ->where('end_date', '<=', now()->addDays(7))
                    ->orderBy('end_date')
                    ->take(5)
                    ->get(),
            ];
        });

        return response()->json($stats);
    }

    /**
     * Get active auctions with caching
     */
    public function active()
    {
        $auctions = CacheService::remember(CacheService::AUCTIONS_PREFIX . 'active', CacheService::AUCTIONS_TTL, function () {
            return Auction::where('status', 'active')
                          ->where('start_date', '<=', now())
                          ->where('end_date', '>', now())
                          ->with(['category', 'bids'])
                          ->orderBy('end_date')
                          ->get();
        });

        return response()->json($auctions);
    }

    /**
     * Get ending soon auctions with caching
     */
    public function endingSoon()
    {
        $auctions = CacheService::remember(CacheService::AUCTIONS_PREFIX . 'ending_soon', CacheService::AUCTIONS_TTL, function () {
            return Auction::where('status', 'active')
                          ->where('end_date', '>', now())
                          ->where('end_date', '<=', now()->addHours(24))
                          ->with(['category', 'bids'])
                          ->orderBy('end_date')
                          ->get();
        });

        return response()->json($auctions);
    }

    /**
     * Search auctions with caching
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $cacheKey = CacheService::AUCTIONS_PREFIX . 'search:' . md5($query);
        
        $auctions = CacheService::remember($cacheKey, CacheService::AUCTIONS_TTL, function () use ($query) {
            return Auction::where('title', 'like', '%' . $query . '%')
                          ->orWhere('description', 'like', '%' . $query . '%')
                          ->with(['category'])
                          ->limit(10)
                          ->get();
        });

        return response()->json($auctions);
    }

    /**
     * Invalidate auction-related caches
     */
    private function invalidateAuctionCaches($auctionId = null)
    {
        // Clear index caches
        CacheService::invalidateByPattern(CacheService::AUCTIONS_PREFIX . 'index*');
        CacheService::invalidateByPattern(CacheService::AUCTIONS_PREFIX . 'search*');
        CacheService::invalidateByPattern(CacheService::AUCTIONS_PREFIX . 'stats*');
        CacheService::invalidateByPattern(CacheService::AUCTIONS_PREFIX . 'active*');
        CacheService::invalidateByPattern(CacheService::AUCTIONS_PREFIX . 'ending_soon*');
        
        // Clear specific auction cache
        if ($auctionId) {
            CacheService::invalidateModel(CacheService::AUCTIONS_PREFIX, $auctionId);
            CacheService::invalidateByPattern(CacheService::AUCTIONS_PREFIX . 'show:' . $auctionId);
        }
        
        // Clear dashboard stats
        CacheService::invalidateByPattern(CacheService::STATS_PREFIX . '*');
    }
}
