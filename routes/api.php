<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'service' => 'XpertBid API',
        'version' => '1.0.0'
    ]);
});
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductAttributeController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\Api\ShippingController;
use App\Http\Controllers\Api\TaxController;
use App\Http\Controllers\Api\PaymentGatewayController;
use App\Http\Controllers\Api\AffiliateController;
use App\Http\Controllers\Api\CachedProductController;
use App\Http\Controllers\Api\CachedAuctionController;
use App\Http\Controllers\Api\EnhancedMediaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('register', [App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('forgot-password', [App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [App\Http\Controllers\Api\AuthController::class, 'resetPassword']);
    
    // Social Login Routes
    Route::post('social-login', [App\Http\Controllers\Api\SocialAuthController::class, 'socialLogin']);
    Route::get('google/redirect', [App\Http\Controllers\Api\SocialAuthController::class, 'googleRedirect']);
    Route::get('google/callback', [App\Http\Controllers\Api\SocialAuthController::class, 'googleCallback']);
    Route::get('facebook/redirect', [App\Http\Controllers\Api\SocialAuthController::class, 'facebookRedirect']);
    Route::get('facebook/callback', [App\Http\Controllers\Api\SocialAuthController::class, 'facebookCallback']);
});

// Protected Authentication Routes
Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::get('me', [App\Http\Controllers\Api\AuthController::class, 'me']);
    Route::put('profile', [App\Http\Controllers\Api\AuthController::class, 'updateProfile']);
    Route::post('change-password', [App\Http\Controllers\Api\AuthController::class, 'changePassword']);
    Route::post('logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('logout-all', [App\Http\Controllers\Api\AuthController::class, 'logoutAll']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Cached Public Routes (No Authentication Required)
Route::prefix('cached')->group(function () {
    // Cached Product Routes
    Route::get('products', [CachedProductController::class, 'index']);
    Route::get('products/{id}', [CachedProductController::class, 'show']);
    Route::get('products/featured', [CachedProductController::class, 'featured']);
    Route::get('products/trending', [CachedProductController::class, 'trending']);
    Route::get('products/search', [CachedProductController::class, 'search']);
    
    // Cached Auction Routes
    Route::get('auctions/active', [CachedAuctionController::class, 'active']);
    Route::get('auctions/{id}', [CachedAuctionController::class, 'show']);
    Route::get('auctions/featured', [CachedAuctionController::class, 'featured']);
    Route::get('auctions/ending-soon', [CachedAuctionController::class, 'endingSoon']);
    Route::get('auctions/statistics', [CachedAuctionController::class, 'statistics']);
});

// Enhanced Media Routes (No Authentication Required for Public Uploads)
Route::prefix('media')->group(function () {
    Route::post('upload', [EnhancedMediaController::class, 'uploadFiles']);
    Route::post('delete', [EnhancedMediaController::class, 'deleteFiles']);
    Route::get('info', [EnhancedMediaController::class, 'getFileInfo']);
    Route::get('stats', [EnhancedMediaController::class, 'getStorageStats']);
    Route::post('cleanup', [EnhancedMediaController::class, 'cleanupTempFiles']);
    Route::post('signed-url', [EnhancedMediaController::class, 'generateSignedUrl']);
});

// API Routes for Admin Dashboard
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    // Tenants API
    Route::apiResource('tenants', TenantController::class);
    Route::get('tenants/{id}/status', [TenantController::class, 'updateStatus']);
    
    // Users API
    Route::apiResource('users', UserController::class);
    Route::get('users/{id}/status', [UserController::class, 'updateStatus']);
    
    // Vendors API
    Route::apiResource('vendors', VendorController::class);
    Route::get('vendors/{id}/status', [VendorController::class, 'updateStatus']);
    Route::get('vendors/{id}/verify', [VendorController::class, 'verify']);
    
    // Products API
    Route::get('products/counts', [ProductController::class, 'getProductCounts']);
    Route::get('products/low-stock', [ProductController::class, 'getLowStock']);
    Route::post('products/bulk-update', [ProductController::class, 'bulkUpdate']);
    Route::apiResource('products', ProductController::class);
    Route::get('products/{id}/status', [ProductController::class, 'updateStatus']);
    Route::get('products/{id}/inventory', [ProductController::class, 'updateInventory']);
    
    // Properties API
    Route::apiResource('properties', PropertyController::class);
    Route::get('properties/{id}/status', [PropertyController::class, 'updateStatus']);
    
    // Vehicles API
    Route::apiResource('vehicles', VehicleController::class);
    Route::get('vehicles/{id}/status', [VehicleController::class, 'updateStatus']);
    
    // Auctions API
    Route::apiResource('auctions', AuctionController::class);
    Route::get('auctions/{id}/status', [AuctionController::class, 'updateStatus']);
    Route::get('auctions/{id}/bids', [AuctionController::class, 'getBids']);
    Route::post('auctions/{id}/bid', [AuctionController::class, 'placeBid']);
    
    // Brands API
    Route::apiResource('brands', BrandController::class);
    Route::get('brands/{id}/status', [BrandController::class, 'updateStatus']);
    
    // Tags API
    Route::apiResource('tags', TagController::class);
    Route::get('tags/{id}/status', [TagController::class, 'updateStatus']);
    Route::get('products/{productId}/tags', [TagController::class, 'getProductTags']);
    Route::post('products/{productId}/tags', [TagController::class, 'attachToProduct']);
    
    // Categories API
    Route::apiResource('categories', CategoryController::class);
    Route::get('categories/{id}/status', [CategoryController::class, 'updateStatus']);
    Route::get('categories/hierarchy/tree', [CategoryController::class, 'getHierarchy']);
    
    // Product Attributes API
    Route::apiResource('product-attributes', ProductAttributeController::class);
    Route::post('product-attributes/{id}/values', [ProductAttributeController::class, 'addValue']);
    Route::get('product-attributes/variation-attributes', [ProductAttributeController::class, 'getVariationAttributes']);
    
    // Units API
    Route::apiResource('units', UnitController::class);
    Route::get('units/by-type/{type}', [UnitController::class, 'getByType']);
    Route::get('units/base-units', [UnitController::class, 'getBaseUnits']);
    
    // Media API
    Route::post('media/upload', [MediaController::class, 'upload']);
    Route::get('media', [MediaController::class, 'getMedia']);
    Route::delete('media/{id}', [MediaController::class, 'deleteMedia']);
    Route::put('media/order', [MediaController::class, 'updateOrder']);
    Route::get('media/collections', [MediaController::class, 'getCollections']);
    
    // Languages API
    Route::apiResource('languages', LanguageController::class);
    Route::get('languages/{id}/status', [LanguageController::class, 'updateStatus']);
    Route::get('languages/{id}/set-default', [LanguageController::class, 'setDefault']);
    Route::get('languages/active/list', [LanguageController::class, 'getActive']);
    
    // Currencies API
    Route::apiResource('currencies', CurrencyController::class);
    Route::get('currencies/{id}/status', [CurrencyController::class, 'updateStatus']);
    Route::get('currencies/{id}/set-default', [CurrencyController::class, 'setDefault']);
    Route::get('currencies/active/list', [CurrencyController::class, 'getActive']);
    Route::post('currencies/convert', [CurrencyController::class, 'convert']);
    Route::put('currencies/exchange-rates', [CurrencyController::class, 'updateExchangeRates']);
    
    // Translation API
    Route::get('translations/{model}/{id}', [TranslationController::class, 'getTranslations']);
    Route::post('translations/{model}/{id}', [TranslationController::class, 'storeTranslation']);
    Route::delete('translations/{model}/{id}/{locale}', [TranslationController::class, 'deleteTranslation']);
    Route::get('translations/{model}/{id}/locales', [TranslationController::class, 'getAvailableLocales']);
    Route::put('translations/{model}/{id}/bulk', [TranslationController::class, 'bulkUpdateTranslations']);
    
    // Shipping API
    Route::get('shipping/zones', [ShippingController::class, 'getZones']);
    Route::get('shipping/zones/{zoneId}/methods', [ShippingController::class, 'getMethods']);
    Route::post('shipping/calculate', [ShippingController::class, 'calculateShipping']);
    Route::get('shipping/pickup-points', [ShippingController::class, 'getPickupPoints']);
    Route::get('shipping/carriers', [ShippingController::class, 'getCarriers']);
    Route::get('shipping/vendors/{vendorId}/settings', [ShippingController::class, 'getVendorSettings']);
    
    // Tax API
    Route::get('tax/classes', [TaxController::class, 'getTaxClasses']);
    Route::get('tax/classes/{taxClassId}/rates', [TaxController::class, 'getTaxRates']);
    Route::post('tax/calculate', [TaxController::class, 'calculateTax']);
    Route::get('tax/rates/by-location', [TaxController::class, 'getTaxRatesByLocation']);
    
    // Payment Gateway API
    Route::get('payment/gateways', [PaymentGatewayController::class, 'getGateways']);
    Route::post('payment/process', [PaymentGatewayController::class, 'processPayment']);
    Route::get('payment/gateways/{gatewayId}', [PaymentGatewayController::class, 'getGatewayDetails']);
    Route::post('payment/webhooks/{gatewayCode}', [PaymentGatewayController::class, 'handleWebhook']);
    
    // Affiliate API
    Route::get('affiliate/programs', [AffiliateController::class, 'getPrograms']);
    Route::post('affiliate/register', [AffiliateController::class, 'register']);
    Route::get('affiliate/{affiliateId}/dashboard', [AffiliateController::class, 'getDashboard']);
    Route::get('affiliate/{affiliateId}/commissions', [AffiliateController::class, 'getCommissions']);
    Route::post('affiliate/{affiliateId}/withdraw', [AffiliateController::class, 'requestWithdrawal']);
    Route::get('affiliate/{affiliateId}/withdrawals', [AffiliateController::class, 'getWithdrawals']);
    Route::post('affiliate/track-referral', [AffiliateController::class, 'trackReferral']);
});
