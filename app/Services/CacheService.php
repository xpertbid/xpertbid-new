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
}