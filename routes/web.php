<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

// Test route for admin login
Route::get('/test-admin', function () {
    return view('admin.auth.login');
});

// General Authentication Routes
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');

Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Frontend KYC Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/kyc', [App\Http\Controllers\KycController::class, 'index'])->name('kyc.index');
    Route::get('/kyc/create', [App\Http\Controllers\KycController::class, 'create'])->name('kyc.create');
    Route::post('/kyc', [App\Http\Controllers\KycController::class, 'store'])->name('kyc.store');
    Route::get('/kyc/{kycDocument}', [App\Http\Controllers\KycController::class, 'show'])->name('kyc.show');
    Route::get('/kyc/{kycDocument}/edit', [App\Http\Controllers\KycController::class, 'edit'])->name('kyc.edit');
    Route::put('/kyc/{kycDocument}', [App\Http\Controllers\KycController::class, 'update'])->name('kyc.update');
    Route::post('/kyc/{kycDocument}/upload-document', [App\Http\Controllers\KycController::class, 'uploadDocument'])->name('kyc.upload-document');
    Route::delete('/kyc/{kycDocument}/remove-document', [App\Http\Controllers\KycController::class, 'removeDocument'])->name('kyc.remove-document');
});

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    // Login Routes
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
    
    // Registration Routes
    Route::get('/register', [App\Http\Controllers\Admin\AuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [App\Http\Controllers\Admin\AuthController::class, 'register']);
    
    // Google OAuth Routes
    Route::get('/auth/google', [App\Http\Controllers\Admin\AuthController::class, 'redirectToGoogle'])->name('admin.google.redirect');
    Route::get('/auth/google/callback', [App\Http\Controllers\Admin\AuthController::class, 'handleGoogleCallback'])->name('admin.google.callback');
    
    // Password Reset Routes
    Route::get('/forgot-password', [App\Http\Controllers\Admin\AuthController::class, 'showForgotPasswordForm'])->name('admin.forgot-password');
    Route::post('/forgot-password', [App\Http\Controllers\Admin\AuthController::class, 'forgotPassword']);
    
    // Logout Route
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');
});

// Admin Dashboard Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function () {
        // Use cached dashboard stats
        $cacheService = new \App\Services\CacheService();
        $stats = $cacheService->cacheDashboardStats();
        $recentActivities = $cacheService->cacheRecentActivities(10);
        
        return view('admin.dashboard', compact('stats', 'recentActivities'));
    })->name('admin.dashboard');
    
    // KYC Management Routes
    Route::resource('admin/kyc', App\Http\Controllers\Admin\KycController::class)->names([
        'index' => 'admin.kyc.index',
        'create' => 'admin.kyc.create',
        'store' => 'admin.kyc.store',
        'show' => 'admin.kyc.show',
        'edit' => 'admin.kyc.edit',
        'update' => 'admin.kyc.update',
        'destroy' => 'admin.kyc.destroy'
    ]);
    Route::post('/admin/kyc/{kycDocument}/approve', [App\Http\Controllers\Admin\KycController::class, 'approve'])->name('admin.kyc.approve');
    Route::post('/admin/kyc/{kycDocument}/reject', [App\Http\Controllers\Admin\KycController::class, 'reject'])->name('admin.kyc.reject');
    Route::post('/admin/kyc/{kycDocument}/under-review', [App\Http\Controllers\Admin\KycController::class, 'underReview'])->name('admin.kyc.under-review');
    Route::post('/admin/kyc/{kycDocument}/upload-document', [App\Http\Controllers\Admin\KycController::class, 'uploadDocument'])->name('admin.kyc.upload-document');
    Route::delete('/admin/kyc/{kycDocument}/remove-document', [App\Http\Controllers\Admin\KycController::class, 'removeDocument'])->name('admin.kyc.remove-document');
    Route::get('/admin/kyc-stats', [App\Http\Controllers\Admin\KycController::class, 'getStats'])->name('admin.kyc.stats');
});

Route::get('/admin/tenants', function () {
    $tenants = DB::table('tenants')->get();
    return view('admin.tenants', compact('tenants'));
});

Route::get('/admin/users', function () {
    $users = DB::table('users')->get();
    return view('admin.users', compact('users'));
});

Route::get('/admin/users/create', function () {
    return view('admin.users.create');
});

Route::delete('/admin/users/{id}', function ($id) {
    DB::table('users')->where('id', $id)->delete();
    return redirect('/admin/users')->with('success', 'User deleted successfully!');
});

Route::post('/admin/users', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'phone' => 'nullable|string|max:20',
        'role' => 'required|string|in:user,admin,vendor',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'is_active' => 'required|boolean',
        'email_verified' => 'required|boolean',
    ]);
    
    // Handle avatar upload
    $avatarPath = null;
    if ($request->hasFile('avatar')) {
        $avatarPath = $request->file('avatar')->store('user-avatars', 'public');
    }
    
    // Create the user
    $user = DB::table('users')->insertGetId([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'phone' => $request->phone,
        'role' => $request->role,
        'avatar' => $avatarPath,
        'is_active' => $request->boolean('is_active'),
        'email_verified_at' => $request->boolean('email_verified') ? now() : null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect('/admin/users')->with('success', 'User created successfully!');
});

Route::get('/admin/vendors', function () {
    $vendors = DB::table('vendors')->get();
    return view('admin.vendors', compact('vendors'));
});

// Products Routes - Main Dashboard (removed duplicate)

// Simple/Variation Products Routes
Route::get('/admin/products/simple/create', function () {
    return view('admin.products.simple.create-new');
});

Route::post('/admin/products/simple', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'sku' => 'required|string|max:255',
        'status' => 'required|string',
    ]);
    
    // Create the product
    $product = DB::table('products')->insertGetId([
        'name' => $request->name,
        'description' => $request->description,
        'short_description' => $request->short_description ?? '',
        'price' => $request->price,
        'sku' => $request->sku,
        'status' => $request->status,
        'product_type' => 'simple',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect('/admin/products')->with('success', 'Product created successfully!');
});

Route::get('/admin/products/simple/{id}/edit', function ($id) {
    return view('admin.products.simple.edit', compact('id'));
});

// Digital Products Routes
Route::get('/admin/products/digital/create', function () {
    return view('admin.products.digital.create');
});

Route::get('/admin/products/digital/{id}/edit', function ($id) {
    return view('admin.products.digital.edit', compact('id'));
});

// Auction Products Routes
Route::get('/admin/products/auction/create', function () {
    return view('admin.products.auction.create');
});

Route::get('/admin/products/auction/{id}/edit', function ($id) {
    return view('admin.products.auction.edit', compact('id'));
});

// Wholesale Products Routes
Route::get('/admin/products/wholesale/create', function () {
    return view('admin.products.wholesale.create');
});

Route::get('/admin/products/wholesale/{id}/edit', function ($id) {
    return view('admin.products.wholesale.edit', compact('id'));
});

// Properties Routes
Route::get('/admin/properties', function () {
    $properties = DB::table('properties')->get();
    return view('admin.properties', compact('properties'));
});

Route::get('/admin/properties/create', function () {
    return view('admin.properties.create-new');
});

Route::post('/admin/properties', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'property_type' => 'required|string',
        'listing_type' => 'required|string',
        'price' => 'required|numeric|min:0',
        'location' => 'required|string|max:255',
        'status' => 'required|string',
    ]);
    
    // Create the property
    $property = DB::table('properties')->insertGetId([
        'title' => $request->title,
        'description' => $request->description,
        'short_description' => $request->short_description ?? '',
        'property_type' => $request->property_type,
        'listing_type' => $request->listing_type,
        'price' => $request->price,
        'location' => $request->location,
        'status' => $request->status,
        'featured' => $request->has('featured') ? 1 : 0,
        'verified' => $request->has('verified') ? 1 : 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect('/admin/properties')->with('success', 'Property created successfully!');
});

Route::delete('/admin/properties/{id}', function ($id) {
    DB::table('properties')->where('id', $id)->delete();
    return redirect('/admin/properties')->with('success', 'Property deleted successfully!');
});

Route::get('/admin/properties/{id}/edit', function ($id) {
    return view('admin.properties.edit-new', compact('id'));
});

// Vehicles Routes
Route::get('/admin/vehicles', function () {
    $vehicles = DB::table('vehicles')->get();
    return view('admin.vehicles', compact('vehicles'));
});

Route::get('/admin/vehicles/create', function () {
    return view('admin.vehicles.create-new');
});

Route::post('/admin/vehicles', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'vehicle_type' => 'required|string',
        'price' => 'required|numeric|min:0',
        'location' => 'required|string|max:255',
        'status' => 'required|string',
    ]);
    
    // Create the vehicle
    $vehicle = DB::table('vehicles')->insertGetId([
        'title' => $request->title,
        'description' => $request->description,
        'short_description' => $request->short_description ?? '',
        'vehicle_type' => $request->vehicle_type,
        'price' => $request->price,
        'location' => $request->location,
        'status' => $request->status,
        'featured' => $request->has('featured') ? 1 : 0,
        'verified' => $request->has('verified') ? 1 : 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect('/admin/vehicles')->with('success', 'Vehicle created successfully!');
});

Route::delete('/admin/vehicles/{id}', function ($id) {
    DB::table('vehicles')->where('id', $id)->delete();
    return redirect('/admin/vehicles')->with('success', 'Vehicle deleted successfully!');
});

Route::get('/admin/vehicles/{id}/edit', function ($id) {
    return view('admin.vehicles.edit-new', compact('id'));
});

// Auctions Routes
Route::get('/admin/auctions', function () {
    $auctions = DB::table('auctions')->get();
    return view('admin.auctions', compact('auctions'));
});

Route::get('/admin/auctions/create', function () {
    return view('admin.auctions.create');
});

Route::post('/admin/auctions', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'starting_price' => 'required|numeric|min:0',
        'reserve_price' => 'nullable|numeric|min:0',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'status' => 'required|string',
    ]);
    
    // Create the auction
    $auction = DB::table('auctions')->insertGetId([
        'title' => $request->title,
        'description' => $request->description,
        'starting_price' => $request->starting_price,
        'reserve_price' => $request->reserve_price,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => $request->status,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect('/admin/auctions')->with('success', 'Auction created successfully!');
});

Route::delete('/admin/auctions/{id}', function ($id) {
    DB::table('auctions')->where('id', $id)->delete();
    return redirect('/admin/auctions')->with('success', 'Auction deleted successfully!');
});

Route::get('/admin/auctions/{id}/edit', function ($id) {
    $auction = DB::table('auctions')->where('id', $id)->first();
    return view('admin.auctions.edit', compact('id', 'auction'));
});

// Vendors Routes
Route::get('/admin/vendors/create', function () {
    return view('admin.vendors.create');
});

Route::post('/admin/vendors', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:vendors',
        'phone' => 'nullable|string|max:20',
        'website' => 'nullable|url',
        'address' => 'nullable|string',
        'description' => 'nullable|string',
        'is_active' => 'required|boolean',
    ]);
    
    // Create the vendor
    $vendor = DB::table('vendors')->insertGetId([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'website' => $request->website,
        'address' => $request->address,
        'description' => $request->description,
        'is_active' => $request->boolean('is_active'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect('/admin/vendors')->with('success', 'Vendor created successfully!');
});

Route::delete('/admin/vendors/{id}', function ($id) {
    DB::table('vendors')->where('id', $id)->delete();
    return redirect('/admin/vendors')->with('success', 'Vendor deleted successfully!');
});

Route::get('/admin/vendors/{id}/edit', function ($id) {
    return view('admin.vendors.edit', compact('id'));
});

// Brands Routes
Route::get('/admin/brands', function () {
    $brands = DB::table('brands')->where('status', true)->get();
    return view('admin.brands', compact('brands'));
});

Route::get('/admin/brands/create', function () {
    return view('admin.brands.create');
});

Route::post('/admin/brands', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'status' => 'required|boolean',
    ]);
    
    // Handle logo upload
    $logoPath = null;
    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('brand-logos', 'public');
    }
    
    // Create the brand
    $brand = DB::table('brands')->insertGetId([
        'name' => $request->name,
        'description' => $request->description,
        'logo' => $logoPath,
        'status' => $request->boolean('status'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect('/admin/brands')->with('success', 'Brand created successfully!');
});

Route::delete('/admin/brands/{id}', function ($id) {
    DB::table('brands')->where('id', $id)->delete();
    return redirect('/admin/brands')->with('success', 'Brand deleted successfully!');
});

Route::get('/admin/brands/{id}/edit', function ($id) {
    return view('admin.brands.edit', compact('id'));
});

// Tags Routes
Route::get('/admin/tags', function () {
    $tags = DB::table('tags')->where('status', true)->get();
    return view('admin.tags', compact('tags'));
});

Route::get('/admin/tags/create', function () {
    return view('admin.tags.create');
});

Route::post('/admin/tags', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'color' => 'nullable|string|max:7',
        'status' => 'required|boolean',
    ]);
    
    // Create the tag
    $tag = DB::table('tags')->insertGetId([
        'name' => $request->name,
        'description' => $request->description,
        'color' => $request->color ?? '#007bff',
        'status' => $request->boolean('status'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect('/admin/tags')->with('success', 'Tag created successfully!');
});

Route::delete('/admin/tags/{id}', function ($id) {
    DB::table('tags')->where('id', $id)->delete();
    return redirect('/admin/tags')->with('success', 'Tag deleted successfully!');
});

Route::get('/admin/tags/{id}/edit', function ($id) {
    return view('admin.tags.edit', compact('id'));
});

// Categories Routes
Route::get('/admin/categories', function () {
    $categories = DB::table('categories')->where('is_active', true)->get();
    return view('admin.categories', compact('categories'));
});

Route::get('/admin/categories/create', function () {
    return view('admin.categories.create');
});

Route::post('/admin/categories', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'parent_id' => 'nullable|exists:categories,id',
        'is_active' => 'required|boolean',
    ]);
    
    // Create the category
    $category = DB::table('categories')->insertGetId([
        'name' => $request->name,
        'description' => $request->description,
        'parent_id' => $request->parent_id,
        'is_active' => $request->boolean('is_active'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect('/admin/categories')->with('success', 'Category created successfully!');
});

Route::delete('/admin/categories/{id}', function ($id) {
    DB::table('categories')->where('id', $id)->delete();
    return redirect('/admin/categories')->with('success', 'Category deleted successfully!');
});

Route::get('/admin/categories/{id}/edit', function ($id) {
    $category = DB::table('categories')->where('id', $id)->first();
    return view('admin.categories.edit', compact('id', 'category'));
});

// Languages Routes
Route::get('/admin/languages', function () {
    $languages = DB::table('languages')->orderBy('name')->get();
    return view('admin.languages', compact('languages'));
});

Route::get('/admin/languages/create', function () {
    return view('admin.languages.create');
});

Route::post('/admin/languages', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:5|unique:languages',
        'native_name' => 'required|string|max:255',
        'is_active' => 'required|boolean',
        'is_default' => 'required|boolean',
    ]);
    
    // If this is set as default, unset other defaults
    if ($request->boolean('is_default')) {
        DB::table('languages')->update(['is_default' => false]);
    }
    
    // Create the language
    $language = DB::table('languages')->insertGetId([
        'name' => $request->name,
        'code' => $request->code,
        'native_name' => $request->native_name,
        'is_active' => $request->boolean('is_active'),
        'is_default' => $request->boolean('is_default'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect('/admin/languages')->with('success', 'Language created successfully!');
});

Route::delete('/admin/languages/{id}', function ($id) {
    DB::table('languages')->where('id', $id)->delete();
    return redirect('/admin/languages')->with('success', 'Language deleted successfully!');
});

Route::get('/admin/languages/{id}/edit', function ($id) {
    $language = DB::table('languages')->where('id', $id)->first();
    return view('admin.languages.edit', compact('id', 'language'));
});

// Currencies Routes
Route::get('/admin/currencies', function () {
    $currencies = DB::table('currencies')->orderBy('name')->get();
    return view('admin.currencies', compact('currencies'));
});

Route::get('/admin/currencies/create', function () {
    return view('admin.currencies.create');
});

Route::post('/admin/currencies', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:3|unique:currencies',
        'symbol' => 'required|string|max:10',
        'rate' => 'required|numeric|min:0',
        'is_active' => 'required|boolean',
    ]);
    
    // Create the currency
    $currency = DB::table('currencies')->insertGetId([
        'name' => $request->name,
        'code' => $request->code,
        'symbol' => $request->symbol,
        'rate' => $request->rate,
        'is_active' => $request->boolean('is_active'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect('/admin/currencies')->with('success', 'Currency created successfully!');
});

Route::delete('/admin/currencies/{id}', function ($id) {
    DB::table('currencies')->where('id', $id)->delete();
    return redirect('/admin/currencies')->with('success', 'Currency deleted successfully!');
});

Route::get('/admin/currencies/{id}/edit', function ($id) {
    $currency = DB::table('currencies')->where('id', $id)->first();
    if (!$currency) {
        abort(404, 'Currency not found');
    }
    return view('admin.currencies.edit', compact('id', 'currency'));
});

// Tax Routes
Route::get('/admin/tax', [App\Http\Controllers\Admin\TaxController::class, 'index'])->name('admin.tax.index');

// Shipping Carrier Routes (MUST come before generic shipping routes)
Route::get('/admin/shipping/carriers', [App\Http\Controllers\Admin\ShippingController::class, 'carriers'])->name('admin.shipping.carriers');
Route::get('/admin/shipping/carriers/create', [App\Http\Controllers\Admin\ShippingController::class, 'createCarrier'])->name('admin.shipping.create-carrier');
Route::post('/admin/shipping/carriers', [App\Http\Controllers\Admin\ShippingController::class, 'storeCarrier'])->name('admin.shipping.store-carrier');
Route::get('/admin/shipping/carriers/{id}/edit', [App\Http\Controllers\Admin\ShippingController::class, 'editCarrier'])->name('admin.shipping.edit-carrier');
Route::put('/admin/shipping/carriers/{id}', [App\Http\Controllers\Admin\ShippingController::class, 'updateCarrier'])->name('admin.shipping.update-carrier');
Route::delete('/admin/shipping/carriers/{id}', [App\Http\Controllers\Admin\ShippingController::class, 'destroyCarrier'])->name('admin.shipping.destroy-carrier');
Route::get('/admin/shipping/carriers/configure/{carrier}', [App\Http\Controllers\Admin\ShippingController::class, 'configureCarrier'])->name('admin.shipping.configure-carrier');
Route::post('/admin/shipping/carriers/configure/{carrier}', [App\Http\Controllers\Admin\ShippingController::class, 'storeCarrierConfig'])->name('admin.shipping.store-carrier-config');
Route::post('/admin/shipping/carriers/toggle/{carrier}', [App\Http\Controllers\Admin\ShippingController::class, 'toggleCarrier'])->name('admin.shipping.toggle-carrier');

// Shipping Routes (Generic routes come after specific ones)
Route::get('/admin/shipping', [App\Http\Controllers\Admin\ShippingController::class, 'index'])->name('admin.shipping.index');
Route::get('/admin/shipping/create', [App\Http\Controllers\Admin\ShippingController::class, 'create'])->name('admin.shipping.create');
Route::post('/admin/shipping', [App\Http\Controllers\Admin\ShippingController::class, 'store'])->name('admin.shipping.store');
Route::get('/admin/shipping/{id}', [App\Http\Controllers\Admin\ShippingController::class, 'show'])->name('admin.shipping.show');
Route::get('/admin/shipping/{id}/edit', [App\Http\Controllers\Admin\ShippingController::class, 'edit'])->name('admin.shipping.edit');
Route::put('/admin/shipping/{id}', [App\Http\Controllers\Admin\ShippingController::class, 'update'])->name('admin.shipping.update');
Route::delete('/admin/shipping/{id}', [App\Http\Controllers\Admin\ShippingController::class, 'destroy'])->name('admin.shipping.destroy');

// Debug route for carriers
Route::get('/debug/carriers', function() {
    $carriers = App\Models\Carrier::where('tenant_id', 1)->get();
    return response()->json([
        'count' => $carriers->count(),
        'carriers' => $carriers->map(function($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'code' => $c->code,
                'tenant_id' => $c->tenant_id,
                'is_active' => $c->is_active,
                'is_integrated' => $c->is_integrated,
                'base_rate' => $c->base_rate,
            ];
        })
    ]);
});

// Media upload route for rich text editor
Route::post('/admin/media/upload', function(\Illuminate\Http\Request $request) {
    $request->validate([
        'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp|max:10240' // 10MB max
    ]);
    
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('public/media', $filename);
        
        return response()->json([
            'location' => asset('storage/media/' . $filename)
        ]);
    }
    
    return response()->json(['error' => 'No file uploaded'], 400);
})->name('admin.media.upload');

// Test route for rich text editor
Route::get('/admin/test-rich-editor', function() {
    return view('admin.test-rich-editor');
})->name('admin.test-rich-editor');

// Test route for simple editor
Route::get('/admin/test-simple-editor', function() {
    return view('admin.test-simple-editor');
})->name('admin.test-simple-editor');

// Test route for both editors
Route::get('/admin/test-both-editors', function() {
    return view('admin.test-both-editors');
})->name('admin.test-both-editors');

// Test route for Shopify-style editor
Route::get('/admin/test-shopify-editor', function() {
    return view('admin.test-shopify-editor');
})->name('admin.test-shopify-editor');

// Translation Manager Component Route
Route::get('/admin/components/translation-manager/{model}/{id}', function ($model, $id) {
    return view('admin.components.translation-manager', compact('model', 'id'));
});

// Pages Routes
Route::resource('admin/pages', App\Http\Controllers\Admin\PageController::class);

// Blog Routes (moved to actual data display above)

// Blog Categories Routes
Route::resource('admin/blog-categories', App\Http\Controllers\Admin\BlogCategoryController::class);

// Roles & Permissions Routes
Route::resource('admin/roles', App\Http\Controllers\Admin\RoleController::class);
Route::get('/admin/permissions', [App\Http\Controllers\Admin\RoleController::class, 'permissions'])->name('admin.permissions');


// Payment Gateway Routes
Route::get('/admin/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('admin.payments.index');
Route::get('/admin/payments/{gateway}/configure', [App\Http\Controllers\Admin\PaymentController::class, 'configure'])->name('admin.payments.configure');
Route::post('/admin/payments/{gateway}/configure', [App\Http\Controllers\Admin\PaymentController::class, 'storeConfig'])->name('admin.payments.store-config');
Route::post('/admin/payments/{gateway}/toggle', [App\Http\Controllers\Admin\PaymentController::class, 'toggleStatus'])->name('admin.payments.toggle');

// Affiliate & Referral Routes
Route::get('/admin/affiliates', [App\Http\Controllers\Admin\AffiliateController::class, 'index'])->name('admin.affiliates.index');
Route::get('/admin/affiliates/create', [App\Http\Controllers\Admin\AffiliateController::class, 'create'])->name('admin.affiliates.create');
Route::post('/admin/affiliates', [App\Http\Controllers\Admin\AffiliateController::class, 'store'])->name('admin.affiliates.store');
Route::get('/admin/affiliates/{id}', [App\Http\Controllers\Admin\AffiliateController::class, 'show'])->name('admin.affiliates.show');
Route::get('/admin/affiliates/{id}/edit', [App\Http\Controllers\Admin\AffiliateController::class, 'edit'])->name('admin.affiliates.edit');
Route::put('/admin/affiliates/{id}', [App\Http\Controllers\Admin\AffiliateController::class, 'update'])->name('admin.affiliates.update');
Route::delete('/admin/affiliates/{id}', [App\Http\Controllers\Admin\AffiliateController::class, 'destroy'])->name('admin.affiliates.destroy');
Route::get('/admin/affiliates/programs', [App\Http\Controllers\Admin\AffiliateController::class, 'programs'])->name('admin.affiliates.programs');
Route::get('/admin/affiliates/commissions', [App\Http\Controllers\Admin\AffiliateController::class, 'commissions'])->name('admin.affiliates.commissions');

// Order Management Routes
Route::get('/admin/orders', function () {
    $orders = DB::table('orders')
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
        ->leftJoin('vendors', 'orders.vendor_id', '=', 'vendors.id')
        ->select('orders.*', 'users.name as customer_name', 'users.email as customer_email', 'vendors.business_name as vendor_name')
        ->orderBy('orders.created_at', 'desc')
        ->paginate(20);
    return view('admin.orders.index', compact('orders'));
})->name('admin.orders.index');

Route::get('/admin/orders/{id}', function ($id) {
    $order = DB::table('orders')
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
        ->leftJoin('vendors', 'orders.vendor_id', '=', 'vendors.id')
        ->select('orders.*', 'users.name as customer_name', 'users.email as customer_email', 'vendors.business_name as vendor_name')
        ->where('orders.id', $id)
        ->first();
    
    if (!$order) {
        abort(404, 'Order not found');
    }
    
    $orderItems = DB::table('order_items')
        ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
        ->leftJoin('vendors', 'order_items.vendor_id', '=', 'vendors.id')
        ->select('order_items.*', 'products.name as product_name', 'products.sku as product_sku', 'vendors.business_name as vendor_name')
        ->where('order_items.order_id', $id)
        ->get();
    
    $payments = DB::table('payments')
        ->where('order_id', $id)
        ->get();
    
    return view('admin.orders.show', compact('order', 'orderItems', 'payments'));
})->name('admin.orders.show');

Route::put('/admin/orders/{id}/status', function (\Illuminate\Http\Request $request, $id) {
    $request->validate([
        'status' => 'required|string|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
        'notes' => 'nullable|string'
    ]);
    
    $order = DB::table('orders')->where('id', $id)->first();
    if (!$order) {
        abort(404, 'Order not found');
    }
    
    $updateData = ['status' => $request->status];
    
    // Update timestamps based on status
    if ($request->status === 'confirmed' && !$order->confirmed_at) {
        $updateData['confirmed_at'] = now();
    } elseif ($request->status === 'shipped' && !$order->shipped_at) {
        $updateData['shipped_at'] = now();
        $updateData['tracking_number'] = 'TRK-' . strtoupper(Str::random(10));
    } elseif ($request->status === 'delivered' && !$order->delivered_at) {
        $updateData['delivered_at'] = now();
    }
    
    if ($request->notes) {
        $updateData['notes'] = $request->notes;
    }
    
    DB::table('orders')->where('id', $id)->update($updateData);
    
    return redirect()->back()->with('success', 'Order status updated successfully!');
})->name('admin.orders.update-status');

// Products Routes - Show actual products data
Route::get('/admin/products', function () {
    $products = DB::table('products')
        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->leftJoin('vendors', 'products.vendor_id', '=', 'vendors.id')
        ->select('products.*', 'categories.name as category_name', 'vendors.business_name as vendor_name')
        ->orderBy('products.created_at', 'desc')
        ->paginate(20);
    return view('admin.products.index', compact('products'));
})->name('admin.products.index');

// Blog Routes - Show actual blog data
Route::get('/admin/blogs', function () {
    $blogs = DB::table('pages')
        ->leftJoin('blog_categories', 'pages.blog_category_id', '=', 'blog_categories.id')
        ->leftJoin('users', 'pages.author_id', '=', 'users.id')
        ->select('pages.*', 'blog_categories.name as category_name', 'users.name as author_name')
        ->where('pages.page_type', 'blog')
        ->orderBy('pages.created_at', 'desc')
        ->paginate(20);
    return view('admin.blogs.index', compact('blogs'));
})->name('admin.blogs.index');

// Cache Management Routes
Route::get('/admin/cache', [App\Http\Controllers\Admin\CacheController::class, 'index'])->name('admin.cache.index');
Route::post('/admin/cache/clear-all', [App\Http\Controllers\Admin\CacheController::class, 'clearAll'])->name('admin.cache.clear-all');
Route::post('/admin/cache/clear-module', [App\Http\Controllers\Admin\CacheController::class, 'clearModule'])->name('admin.cache.clear-module');
Route::post('/admin/cache/warm-up', [App\Http\Controllers\Admin\CacheController::class, 'warmUp'])->name('admin.cache.warm-up');
Route::post('/admin/cache/clear-application', [App\Http\Controllers\Admin\CacheController::class, 'clearApplication'])->name('admin.cache.clear-application');
Route::post('/admin/cache/optimize', [App\Http\Controllers\Admin\CacheController::class, 'optimize'])->name('admin.cache.optimize');
Route::get('/admin/cache/stats', [App\Http\Controllers\Admin\CacheController::class, 'stats'])->name('admin.cache.stats');
Route::get('/admin/cache/redis-info', [App\Http\Controllers\Admin\CacheController::class, 'redisInfo'])->name('admin.cache.redis-info');
Route::get('/admin/cache/keys', [App\Http\Controllers\Admin\CacheController::class, 'getKeys'])->name('admin.cache.keys');
Route::post('/admin/cache/delete-key', [App\Http\Controllers\Admin\CacheController::class, 'deleteKey'])->name('admin.cache.delete-key');
