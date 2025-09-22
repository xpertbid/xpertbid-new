<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    const PREFIX = 'xpertbid:';
    const USER_PREFIX = 'user:';
    const PRODUCT_PREFIX = 'product:';
    const AUCTION_PREFIX = 'auction:';
    const CATEGORY_PREFIX = 'category:';
    
    const DEFAULT_TTL = 60;
    const USER_TTL = 30;
    const PRODUCT_TTL = 120;
    const AUCTION_TTL = 5;
    const CATEGORY_TTL = 1440;

    private function getKey(string $prefix, string $key): string
    {
        return self::PREFIX . $prefix . $key;
    }

    public function put(string $key, $value, int $ttl = self::DEFAULT_TTL): bool
    {
        try {
            return Cache::put($key, $value, now()->addMinutes($ttl));
        } catch (\Exception $e) {
            Log::error('Cache put failed', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function get(string $key, $default = null)
    {
        try {
            return Cache::get($key, $default);
        } catch (\Exception $e) {
            Log::error('Cache get failed', ['key' => $key, 'error' => $e->getMessage()]);
            return $default;
        }
    }

    public function forget(string $key): bool
    {
        try {
            return Cache::forget($key);
        } catch (\Exception $e) {
            Log::error('Cache forget failed', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function remember(string $key, int $ttl, callable $callback)
    {
        try {
            return Cache::remember($key, now()->addMinutes($ttl), $callback);
        } catch (\Exception $e) {
            Log::error('Cache remember failed', ['key' => $key, 'error' => $e->getMessage()]);
            return $callback();
        }
    }

    // Product caching methods
    public function putProduct(int $productId, $productData): bool
    {
        $key = $this->getKey(self::PRODUCT_PREFIX, $productId);
        return $this->put($key, $productData, self::PRODUCT_TTL);
    }

    public function getProduct(int $productId)
    {
        $key = $this->getKey(self::PRODUCT_PREFIX, $productId);
        return $this->get($key);
    }

    public function forgetProduct(int $productId): bool
    {
        $key = $this->getKey(self::PRODUCT_PREFIX, $productId);
        return $this->forget($key);
    }

    // Auction caching methods
    public function putAuction(int $auctionId, $auctionData): bool
    {
        $key = $this->getKey(self::AUCTION_PREFIX, $auctionId);
        return $this->put($key, $auctionData, self::AUCTION_TTL);
    }

    public function getAuction(int $auctionId)
    {
        $key = $this->getKey(self::AUCTION_PREFIX, $auctionId);
        return $this->get($key);
    }

    public function forgetAuction(int $auctionId): bool
    {
        $key = $this->getKey(self::AUCTION_PREFIX, $auctionId);
        return $this->forget($key);
    }

    public function putActiveAuctions($auctions): bool
    {
        $key = $this->getKey(self::AUCTION_PREFIX, 'active');
        return $this->put($key, $auctions, self::AUCTION_TTL);
    }

    public function getActiveAuctions()
    {
        $key = $this->getKey(self::AUCTION_PREFIX, 'active');
        return $this->get($key);
    }

    // Dashboard statistics caching
    public function putDashboardStats($stats): bool
    {
        $key = $this->getKey('dashboard:', 'stats');
        return $this->put($key, $stats, 15);
    }

    public function getDashboardStats()
    {
        $key = $this->getKey('dashboard:', 'stats');
        return $this->get($key);
    }

    // Cache dashboard stats with data generation
    public function cacheDashboardStats()
    {
        $key = $this->getKey('dashboard:', 'stats');
        
        return $this->remember($key, 15, function () {
            try {
                return [
                    'total_users' => \App\Models\User::count(),
                    'total_products' => \App\Models\Product::count(),
                    'total_auctions' => \App\Models\Auction::count(),
                    'total_vendors' => \App\Models\Vendor::count(),
                    'total_properties' => \App\Models\Property::count(),
                    'total_vehicles' => \App\Models\Vehicle::count(),
                    'active_auctions' => \App\Models\Auction::where('status', 'active')->count(),
                    'pending_orders' => 0, // You can add Order model later
                    'total_revenue' => 0, // You can add Payment model later
                    'total_value' => 0, // Total value for revenue display
                    'new_users_today' => \App\Models\User::whereDate('created_at', today())->count(),
                ];
            } catch (\Exception $e) {
                // Return default values if any model doesn't exist yet
                return [
                    'total_users' => 0,
                    'total_products' => 0,
                    'total_auctions' => 0,
                    'total_vendors' => 0,
                    'total_properties' => 0,
                    'total_vehicles' => 0,
                    'active_auctions' => 0,
                    'pending_orders' => 0,
                    'total_revenue' => 0,
                    'total_value' => 0,
                    'new_users_today' => 0,
                ];
            }
        });
    }

    // Cache recent activities
    public function cacheRecentActivities($limit = 10)
    {
        $key = $this->getKey('dashboard:', "activities:{$limit}");
        
        return $this->remember($key, 5, function () use ($limit) {
            try {
                $activities = [];
                
                // Get recent users
                $recentUsers = \App\Models\User::latest()->limit($limit)->get();
                foreach ($recentUsers as $user) {
                    $activities[] = [
                        'type' => 'user',
                        'title' => "New User Registered",
                        'description' => "User {$user->name} has joined the platform",
                        'created_at' => $user->created_at,
                        'icon' => 'user-plus',
                        'color' => 'blue'
                    ];
                }
                
                // Get recent auctions
                $recentAuctions = \App\Models\Auction::latest()->limit($limit)->get();
                foreach ($recentAuctions as $auction) {
                    $activities[] = [
                        'type' => 'auction',
                        'title' => "New Auction Created",
                        'description' => "Auction '{$auction->title}' has been listed",
                        'created_at' => $auction->created_at,
                        'icon' => 'gavel',
                        'color' => 'green'
                    ];
                }
                
                // Get recent products
                $recentProducts = \App\Models\Product::latest()->limit($limit)->get();
                foreach ($recentProducts as $product) {
                    $activities[] = [
                        'type' => 'product',
                        'title' => "New Product Added",
                        'description' => "Product '{$product->name}' has been added to catalog",
                        'created_at' => $product->created_at,
                        'icon' => 'box',
                        'color' => 'orange'
                    ];
                }
                
                // Get recent properties
                $recentProperties = \App\Models\Property::latest()->limit($limit)->get();
                foreach ($recentProperties as $property) {
                    $activities[] = [
                        'type' => 'property',
                        'title' => "New Property Listed",
                        'description' => "Property '{$property->title}' has been added to listings",
                        'created_at' => $property->created_at,
                        'icon' => 'home',
                        'color' => 'purple'
                    ];
                }
                
                // Get recent vehicles
                $recentVehicles = \App\Models\Vehicle::latest()->limit($limit)->get();
                foreach ($recentVehicles as $vehicle) {
                    $activities[] = [
                        'type' => 'vehicle',
                        'title' => "New Vehicle Added",
                        'description' => "Vehicle '{$vehicle->title}' has been added to inventory",
                        'created_at' => $vehicle->created_at,
                        'icon' => 'car',
                        'color' => 'red'
                    ];
                }
                
                // Sort by time and limit
                usort($activities, function($a, $b) {
                    return $b['created_at'] <=> $a['created_at'];
                });
                
                return array_slice($activities, 0, $limit);
            } catch (\Exception $e) {
                // Return empty activities if models don't exist yet
                return [];
            }
        });
    }

    public function clearAll(): bool
    {
        try {
            Cache::flush();
            Log::info('All cache cleared');
            return true;
        } catch (\Exception $e) {
            Log::error('Cache clear all failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get cache statistics
     */
    public static function getCacheStats()
    {
        try {
            // Check if Redis is available
            if (self::isRedisAvailable()) {
                $redis = \Illuminate\Support\Facades\Redis::connection();
                $info = $redis->info();
                
                // Get all cache keys
                $allKeys = $redis->keys('*');
                $xpertbidKeys = $redis->keys(self::PREFIX . '*');
                
                $totalSize = 0;
                $activeKeys = 0;
                
                foreach ($xpertbidKeys as $key) {
                    $ttl = $redis->ttl($key);
                    if ($ttl !== -2) { // Not expired
                        $activeKeys++;
                        $totalSize += strlen($redis->get($key) ?? '');
                    }
                }
                
                return [
                    'total_keys' => count($allKeys),
                    'xpertbid_keys' => count($xpertbidKeys),
                    'active_keys' => $activeKeys,
                    'total_size' => self::formatBytes($totalSize),
                    'redis_version' => $info['redis_version'] ?? 'N/A',
                    'used_memory' => $info['used_memory_human'] ?? 'N/A',
                    'connected_clients' => $info['connected_clients'] ?? 0,
                    'uptime' => $info['uptime_in_seconds'] ?? 0,
                    'cache_driver' => 'Redis',
                ];
            } else {
                // Fallback to Laravel cache stats
                return [
                    'total_keys' => 'N/A',
                    'xpertbid_keys' => 'N/A',
                    'active_keys' => 'N/A',
                    'total_size' => 'N/A',
                    'redis_version' => 'N/A',
                    'used_memory' => 'N/A',
                    'connected_clients' => 0,
                    'uptime' => 0,
                    'cache_driver' => config('cache.default'),
                ];
            }
        } catch (\Exception $e) {
            Log::error('Cache stats failed', ['error' => $e->getMessage()]);
            return [
                'total_keys' => 0,
                'xpertbid_keys' => 0,
                'active_keys' => 0,
                'total_size' => '0 B',
                'redis_version' => 'N/A',
                'used_memory' => 'N/A',
                'connected_clients' => 0,
                'uptime' => 0,
                'cache_driver' => config('cache.default'),
            ];
        }
    }

    /**
     * Clear cache for specific module
     */
    public static function clearModuleCache($prefix)
    {
        try {
            if (self::isRedisAvailable()) {
                $redis = \Illuminate\Support\Facades\Redis::connection();
                $keys = $redis->keys(self::PREFIX . $prefix . '*');
                
                if (!empty($keys)) {
                    $redis->del($keys);
                }
                
                Log::info('Module cache cleared', ['prefix' => $prefix, 'keys_count' => count($keys)]);
                return true;
            } else {
                // Fallback: Clear Laravel cache
                Cache::flush();
                Log::info('Module cache cleared (fallback)', ['prefix' => $prefix]);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Module cache clear failed', ['prefix' => $prefix, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Warm up cache for all modules
     */
    public static function warmUpCache()
    {
        try {
            $cacheService = new self();
            
            // Warm up dashboard stats
            $cacheService->cacheDashboardStats();
            $cacheService->cacheRecentActivities();
            
            // Warm up product cache
            $cacheService->putActiveAuctions([]);
            
            Log::info('Cache warmed up successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Cache warm up failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Check if Redis is available
     */
    private static function isRedisAvailable()
    {
        try {
            // Check if Redis facade is available and connection works
            \Illuminate\Support\Facades\Redis::connection()->ping();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Format bytes to human readable format
     */
    private static function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}