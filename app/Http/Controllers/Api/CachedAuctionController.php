<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CachedAuctionController extends Controller
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Display a listing of active auctions with caching
     */
    public function active()
    {
        $cachedAuctions = $this->cacheService->getActiveAuctions();
        
        if ($cachedAuctions) {
            return response()->json([
                'success' => true,
                'data' => $cachedAuctions,
                'from_cache' => true
            ]);
        }

        try {
            $auctions = DB::table('auctions')
                ->join('vendors', 'auctions.vendor_id', '=', 'vendors.id')
                ->join('products', 'auctions.product_id', '=', 'products.id')
                ->select('auctions.*', 'vendors.business_name', 'products.name as product_name', 'products.thumbnail_image')
                ->where('auctions.status', 'active')
                ->where('auctions.end_time', '>', now())
                ->orderBy('auctions.end_time', 'asc')
                ->get();

            // Cache the results
            $this->cacheService->putActiveAuctions($auctions);

            return response()->json([
                'success' => true,
                'data' => $auctions,
                'from_cache' => false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching active auctions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified auction with caching
     */
    public function show(string $id)
    {
        $cachedAuction = $this->cacheService->getAuction($id);
        
        if ($cachedAuction) {
            return response()->json([
                'success' => true,
                'data' => $cachedAuction,
                'from_cache' => true
            ]);
        }

        try {
            $auction = DB::table('auctions')
                ->join('vendors', 'auctions.vendor_id', '=', 'vendors.id')
                ->join('products', 'auctions.product_id', '=', 'products.id')
                ->select('auctions.*', 'vendors.business_name', 'products.name as product_name', 'products.thumbnail_image')
                ->where('auctions.id', $id)
                ->first();
            
            if (!$auction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Auction not found'
                ], 404);
            }

            // Cache the auction
            $this->cacheService->putAuction($id, $auction);

            return response()->json([
                'success' => true,
                'data' => $auction,
                'from_cache' => false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching auction: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured auctions with caching
     */
    public function featured()
    {
        return $this->cacheService->remember('featured_auctions', 5, function () {
            try {
                $auctions = DB::table('auctions')
                    ->join('vendors', 'auctions.vendor_id', '=', 'vendors.id')
                    ->join('products', 'auctions.product_id', '=', 'products.id')
                    ->select('auctions.*', 'vendors.business_name', 'products.name as product_name', 'products.thumbnail_image')
                    ->where('auctions.is_featured', true)
                    ->where('auctions.status', 'active')
                    ->where('auctions.end_time', '>', now())
                    ->orderBy('auctions.end_time', 'asc')
                    ->limit(6)
                    ->get();

                return response()->json([
                    'success' => true,
                    'data' => $auctions
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching featured auctions: ' . $e->getMessage()
                ], 500);
            }
        });
    }

    /**
     * Get ending soon auctions with caching
     */
    public function endingSoon()
    {
        return $this->cacheService->remember('ending_soon_auctions', 5, function () {
            try {
                $auctions = DB::table('auctions')
                    ->join('vendors', 'auctions.vendor_id', '=', 'vendors.id')
                    ->join('products', 'auctions.product_id', '=', 'products.id')
                    ->select('auctions.*', 'vendors.business_name', 'products.name as product_name', 'products.thumbnail_image')
                    ->where('auctions.status', 'active')
                    ->where('auctions.end_time', '>', now())
                    ->where('auctions.end_time', '<=', now()->addHours(24))
                    ->orderBy('auctions.end_time', 'asc')
                    ->limit(10)
                    ->get();

                return response()->json([
                    'success' => true,
                    'data' => $auctions
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching ending soon auctions: ' . $e->getMessage()
                ], 500);
            }
        });
    }

    /**
     * Get auction statistics with caching
     */
    public function statistics()
    {
        return $this->cacheService->remember('auction_statistics', 15, function () {
            try {
                $stats = [
                    'total_auctions' => DB::table('auctions')->count(),
                    'active_auctions' => DB::table('auctions')->where('status', 'active')->count(),
                    'ended_auctions' => DB::table('auctions')->where('status', 'ended')->count(),
                    'total_bids' => DB::table('bids')->count(),
                    'total_bidders' => DB::table('bids')->distinct('user_id')->count(),
                ];

                return response()->json([
                    'success' => true,
                    'data' => $stats
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching auction statistics: ' . $e->getMessage()
                ], 500);
            }
        });
    }

    /**
     * Clear auction cache
     */
    public function clearCache(string $id = null)
    {
        try {
            if ($id) {
                $this->cacheService->forgetAuction($id);
                // Also clear active auctions cache since it might include this auction
                $this->cacheService->forgetActiveAuctions();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Auction cache cleared successfully'
                ]);
            } else {
                // Clear all auction-related cache
                $this->cacheService->clearByPattern(self::AUCTION_PREFIX . '*');
                
                return response()->json([
                    'success' => true,
                    'message' => 'All auction cache cleared successfully'
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
