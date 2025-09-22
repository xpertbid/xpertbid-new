<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheController extends Controller
{
    /**
     * Display cache management dashboard
     */
    public function index()
    {
        $cacheStats = CacheService::getCacheStats();
        
        $moduleStats = [
            'products' => $this->getModuleCacheStats('products'),
            'auctions' => $this->getModuleCacheStats('auctions'),
            'properties' => $this->getModuleCacheStats('properties'),
            'vehicles' => $this->getModuleCacheStats('vehicles'),
        ];

        return view('admin.cache.index', compact('cacheStats', 'moduleStats'));
    }

    /**
     * Clear all cache
     */
    public function clearAll()
    {
        try {
            Cache::flush();
            
            return response()->json([
                'success' => true,
                'message' => 'All cache cleared successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear cache for specific module
     */
    public function clearModule(Request $request)
    {
        $module = $request->get('module');
        
        if (!in_array($module, ['products', 'auctions', 'properties', 'vehicles'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid module specified'
            ], 400);
        }

        try {
            $prefix = $module . ':';
            CacheService::clearModuleCache($prefix);
            
            return response()->json([
                'success' => true,
                'message' => ucfirst($module) . ' cache cleared successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing ' . $module . ' cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Warm up cache for all modules
     */
    public function warmUp()
    {
        try {
            CacheService::warmUpCache();
            
            // Warm up dashboard stats
            CacheService::cacheDashboardStats();
            CacheService::cacheRecentActivities();
            
            return response()->json([
                'success' => true,
                'message' => 'Cache warmed up successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error warming up cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear application cache (config, route, view)
     */
    public function clearApplication()
    {
        try {
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Application cache cleared successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing application cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Optimize application
     */
    public function optimize()
    {
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            
            return response()->json([
                'success' => true,
                'message' => 'Application optimized successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error optimizing application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cache statistics
     */
    public function stats()
    {
        try {
            $stats = CacheService::getCacheStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting cache statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Redis info
     */
    public function redisInfo()
    {
        try {
            $redis = Redis::connection();
            $info = $redis->info();
            
            $relevantInfo = [
                'redis_version' => $info['redis_version'] ?? 'N/A',
                'used_memory' => $info['used_memory_human'] ?? 'N/A',
                'used_memory_peak' => $info['used_memory_peak_human'] ?? 'N/A',
                'connected_clients' => $info['connected_clients'] ?? 0,
                'total_commands_processed' => $info['total_commands_processed'] ?? 0,
                'keyspace_hits' => $info['keyspace_hits'] ?? 0,
                'keyspace_misses' => $info['keyspace_misses'] ?? 0,
                'uptime_in_seconds' => $info['uptime_in_seconds'] ?? 0,
                'keyspace' => $info['keyspace'] ?? [],
            ];
            
            return response()->json([
                'success' => true,
                'data' => $relevantInfo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting Redis info: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cache keys by pattern
     */
    public function getKeys(Request $request)
    {
        $pattern = $request->get('pattern', '*');
        
        try {
            $keys = Redis::keys($pattern);
            
            // Get key info (TTL, type, size)
            $keyInfo = [];
            foreach (array_slice($keys, 0, 100) as $key) { // Limit to 100 keys
                $keyInfo[] = [
                    'key' => $key,
                    'ttl' => Redis::ttl($key),
                    'type' => Redis::type($key),
                    'size' => strlen(Redis::get($key) ?? ''),
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'keys' => $keyInfo,
                    'total_count' => count($keys)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting cache keys: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete specific cache key
     */
    public function deleteKey(Request $request)
    {
        $key = $request->get('key');
        
        if (empty($key)) {
            return response()->json([
                'success' => false,
                'message' => 'Key is required'
            ], 400);
        }

        try {
            Redis::del($key);
            
            return response()->json([
                'success' => true,
                'message' => 'Key deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting key: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get module cache statistics
     */
    private function getModuleCacheStats($module)
    {
        try {
            $pattern = $module . ':*';
            $keys = Redis::keys($pattern);
            
            $totalSize = 0;
            $expiredKeys = 0;
            
            foreach ($keys as $key) {
                $ttl = Redis::ttl($key);
                if ($ttl === -2) {
                    $expiredKeys++;
                } else {
                    $totalSize += strlen(Redis::get($key) ?? '');
                }
            }
            
            return [
                'total_keys' => count($keys),
                'active_keys' => count($keys) - $expiredKeys,
                'expired_keys' => $expiredKeys,
                'total_size' => $this->formatBytes($totalSize),
            ];
        } catch (\Exception $e) {
            return [
                'total_keys' => 0,
                'active_keys' => 0,
                'expired_keys' => 0,
                'total_size' => '0 B',
            ];
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
