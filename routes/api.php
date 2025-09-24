<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'service' => 'XpertBid API',
        'version' => '1.0.0'
    ]);
});

// Public API routes for frontend
Route::get('/products', function () {
    try {
        $products = DB::table('products')
            ->leftJoin('vendors', 'products.vendor_id', '=', 'vendors.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'vendors.business_name', 'categories.name as category_name')
            ->where('products.status', 'publish')
            ->get();
        
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

Route::get('/products/featured', function () {
    try {
        $products = DB::table('products')
            ->leftJoin('vendors', 'products.vendor_id', '=', 'vendors.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'vendors.business_name', 'categories.name as category_name')
            ->where('products.status', 'publish')
            ->where('products.is_featured', true)
            ->limit(8)
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

Route::get('/categories', function () {
    try {
        $categories = DB::table('categories')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching categories: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/kyc-types', function () {
    try {
        $kycTypes = [
            'user' => [
                'name' => 'E-commerce User',
                'description' => 'For users who want to buy products',
                'required_fields' => ['full_name', 'email', 'phone_number'],
                'optional_fields' => ['address', 'city', 'state', 'country', 'postal_code']
            ],
            'vendor' => [
                'name' => 'Vendor/Business',
                'description' => 'For businesses who want to sell products',
                'required_fields' => ['full_name', 'email', 'phone_number', 'business_name', 'ntn_number', 'business_address'],
                'optional_fields' => ['address', 'city', 'state', 'country', 'postal_code', 'business_type', 'tax_number', 'business_registration_number']
            ],
            'property' => [
                'name' => 'Property Seller',
                'description' => 'For users who want to list properties',
                'required_fields' => ['full_name', 'email', 'phone_number', 'business_name', 'ntn_number'],
                'optional_fields' => ['address', 'city', 'state', 'country', 'postal_code', 'business_type', 'tax_number']
            ],
            'vehicle' => [
                'name' => 'Vehicle Seller',
                'description' => 'For users who want to sell vehicles',
                'required_fields' => ['full_name', 'email', 'phone_number', 'business_name', 'ntn_number'],
                'optional_fields' => ['address', 'city', 'state', 'country', 'postal_code', 'business_type', 'tax_number']
            ],
            'auction' => [
                'name' => 'Auction Participant',
                'description' => 'For users who want to participate in auctions',
                'required_fields' => ['full_name', 'email', 'phone_number'],
                'optional_fields' => ['address', 'city', 'state', 'country', 'postal_code', 'business_name', 'business_type']
            ]
        ];
        
        return response()->json([
            'success' => true,
            'data' => $kycTypes
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching KYC types: ' . $e->getMessage()
        ], 500);
    }
});

// Additional public API routes for frontend
Route::get('/brands', function () {
    try {
        $brands = DB::table('brands')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching brands: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/auctions', function () {
    try {
        // Get auctions with better error handling
        $auctions = DB::table('auctions')
            ->leftJoin('products', 'auctions.product_id', '=', 'products.id')
            ->leftJoin('vendors', 'auctions.vendor_id', '=', 'vendors.id')
            ->select(
                'auctions.*', 
                'products.name as product_name', 
                'products.images as product_image',
                'products.slug as product_slug',
                'vendors.business_name as seller_name'
            )
            ->where('auctions.status', 'active')
            ->orderBy('auctions.end_time', 'asc')
            ->get();
        
        // Transform the data to ensure all required fields are present
        $transformedAuctions = $auctions->map(function ($auction) {
            return [
                'id' => $auction->id,
                'slug' => $auction->product_slug ?? 'auction-' . $auction->id,
                'product_name' => $auction->product_name ?? 'Auction Item',
                'product_image' => $auction->product_image ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=300&fit=crop',
                'seller_name' => $auction->seller_name ?? 'Unknown Seller',
                'current_bid' => $auction->current_bid ?? 0,
                'reserve_price' => $auction->reserve_price,
                'end_time' => $auction->end_time,
                'bid_count' => $auction->bid_count ?? 0,
                'status' => $auction->status,
                'created_at' => $auction->created_at,
                'updated_at' => $auction->updated_at
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $transformedAuctions
        ]);
    } catch (\Exception $e) {
        \Log::error('Auctions API Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error fetching auctions: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/properties', function () {
    try {
        $properties = DB::table('properties')
            ->leftJoin('users', 'properties.user_id', '=', 'users.id')
            ->select('properties.*', 'users.name as seller_name')
            ->where('properties.status', 'active')
            ->orderBy('properties.created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $properties
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching properties: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/vehicles', function () {
    try {
        $vehicles = DB::table('vehicles')
            ->leftJoin('users', 'vehicles.user_id', '=', 'users.id')
            ->select('vehicles.*', 'users.name as seller_name')
            ->where('vehicles.status', 'active')
            ->orderBy('vehicles.created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $vehicles
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching vehicles: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/blog-posts', function () {
    try {
        $blogPosts = DB::table('pages')
            ->leftJoin('blog_categories', 'pages.blog_category_id', '=', 'blog_categories.id')
            ->leftJoin('users', 'pages.author_id', '=', 'users.id')
            ->select('pages.*', 'blog_categories.name as category_name', 'users.name as author_name')
            ->where('pages.page_type', 'blog')
            ->where('pages.status', 'published')
            ->orderBy('pages.published_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $blogPosts
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching blog posts: ' . $e->getMessage()
        ], 500);
    }
});

// Bid placement endpoint
Route::post('/auctions/{auctionId}/bids', function (\Illuminate\Http\Request $request, $auctionId) {
    try {
        $request->validate([
            'bid_amount' => 'required|numeric|min:1',
            'bidder_name' => 'required|string|max:255',
            'bidder_email' => 'required|email|max:255'
        ]);

        // Get auction details
        $auction = DB::table('auctions')->where('id', $auctionId)->first();
        
        if (!$auction) {
            return response()->json([
                'success' => false,
                'message' => 'Auction not found'
            ], 404);
        }

        if ($auction->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Auction is not active'
            ], 400);
        }

        // Check if bid is higher than current bid
        if ($request->bid_amount <= $auction->current_bid) {
            return response()->json([
                'success' => false,
                'message' => 'Bid amount must be higher than current bid'
            ], 400);
        }

        // Create bid record
        $bidId = DB::table('bids')->insertGetId([
            'auction_id' => $auctionId,
            'bidder_name' => $request->bidder_name,
            'bidder_email' => $request->bidder_email,
            'bid_amount' => $request->bid_amount,
            'bid_time' => now(),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Update auction with new current bid
        DB::table('auctions')->where('id', $auctionId)->update([
            'current_bid' => $request->bid_amount,
            'bid_count' => DB::raw('bid_count + 1'),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bid placed successfully',
            'data' => [
                'bid_id' => $bidId,
                'bid_amount' => $request->bid_amount,
                'bidder_name' => $request->bidder_name,
                'bid_time' => now()->toISOString()
            ]
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Bid placement error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error placing bid: ' . $e->getMessage()
        ], 500);
    }
});

// Get user's bidding history
Route::get('/user/{email}/bids', function ($email) {
    try {
        $bids = DB::table('bids')
            ->leftJoin('auctions', 'bids.auction_id', '=', 'auctions.id')
            ->leftJoin('products', 'auctions.product_id', '=', 'products.id')
            ->select(
                'bids.*',
                'auctions.end_time',
                'auctions.status as auction_status',
                'products.name as product_name',
                'products.featured_image as product_image'
            )
            ->where('bids.bidder_email', $email)
            ->orderBy('bids.bid_time', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bids
        ]);
    } catch (\Exception $e) {
        \Log::error('Get user bids error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error fetching bids: ' . $e->getMessage()
        ], 500);
    }
});

// Get available payment gateways
Route::get('/payment-gateways', function () {
    try {
        $gateways = DB::table('payment_gateways')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Transform the data to include only necessary fields
        $transformedGateways = $gateways->map(function ($gateway) {
            return [
                'id' => $gateway->id,
                'name' => $gateway->name,
                'code' => $gateway->code,
                'type' => $gateway->type,
                'description' => $gateway->description,
                'logo_url' => $gateway->logo_url,
                'transaction_fee' => $gateway->transaction_fee,
                'fixed_fee' => $gateway->fixed_fee,
                'supported_currencies' => json_decode($gateway->supported_currencies, true),
                'supported_countries' => json_decode($gateway->supported_countries, true),
                'is_test_mode' => $gateway->is_test_mode,
                'sort_order' => $gateway->sort_order
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $transformedGateways
        ]);
    } catch (\Exception $e) {
        \Log::error('Payment gateways API error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error fetching payment gateways: ' . $e->getMessage()
        ], 500);
    }
});

// Authentication routes are handled by AuthController

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
