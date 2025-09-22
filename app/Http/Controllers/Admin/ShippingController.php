<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrier;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    /**
     * Display the shipping management page.
     */
    public function index()
    {
        // Mock data for shipping zones
        $shippingZones = [
            [
                'id' => 1,
                'name' => 'North America',
                'countries' => 'USA, Canada, Mexico',
                'methods_count' => 3,
                'status' => 'active',
                'created_at' => '2024-01-15',
            ],
            [
                'id' => 2,
                'name' => 'Europe',
                'countries' => 'UK, Germany, France, Italy',
                'methods_count' => 2,
                'status' => 'active',
                'created_at' => '2024-01-10',
            ],
            [
                'id' => 3,
                'name' => 'Asia Pacific',
                'countries' => 'Japan, Australia, Singapore',
                'methods_count' => 2,
                'status' => 'active',
                'created_at' => '2024-01-12',
            ],
        ];

        $stats = [
            'total_zones' => count($shippingZones),
            'active_zones' => count(array_filter($shippingZones, fn($z) => $z['status'] === 'active')),
            'total_methods' => array_sum(array_column($shippingZones, 'methods_count')),
            'avg_cost' => 12.50,
        ];

        return view('admin.shipping.index', compact('shippingZones', 'stats'));
    }

    /**
     * Show the form for creating a new shipping zone.
     */
    public function create()
    {
        return view('admin.shipping.create');
    }

    /**
     * Store a newly created shipping zone.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'countries' => 'required|array',
            'shipping_method' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // In real implementation, you'd save to database
        // ShippingZone::create($request->all());

        return redirect()->route('admin.shipping.index')
                        ->with('success', 'Shipping zone created successfully.');
    }

    /**
     * Display the specified shipping zone.
     */
    public function show($id)
    {
        // Mock shipping zone data
        $shippingZone = [
            'id' => $id,
            'name' => 'North America',
            'countries' => 'USA, Canada, Mexico',
            'methods_count' => 3,
            'status' => 'active',
            'created_at' => '2024-01-15',
        ];

        return view('admin.shipping.show', compact('shippingZone'));
    }

    /**
     * Show the form for editing the specified shipping zone.
     */
    public function edit($id)
    {
        // Mock shipping zone data
        $shippingZone = [
            'id' => $id,
            'name' => 'North America',
            'countries' => ['US', 'CA', 'MX'],
            'shipping_method' => 'flat_rate',
            'cost' => 9.99,
            'description' => 'Standard shipping for North American customers',
            'is_active' => true,
        ];

        return view('admin.shipping.edit', compact('shippingZone'));
    }

    /**
     * Update the specified shipping zone.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'countries' => 'required|array',
            'shipping_method' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // In real implementation, you'd update in database
        // $shippingZone = ShippingZone::findOrFail($id);
        // $shippingZone->update($request->all());

        return redirect()->route('admin.shipping.index')
                        ->with('success', 'Shipping zone updated successfully.');
    }

    /**
     * Remove the specified shipping zone.
     */
    public function destroy($id)
    {
        // In real implementation, you'd delete from database
        // ShippingZone::findOrFail($id)->delete();

        return redirect()->route('admin.shipping.index')
                        ->with('success', 'Shipping zone deleted successfully.');
    }

    /**
     * Display shipping carriers management page.
     */
    public function carriers()
    {
        // Get carriers from database for current tenant
        $carriers = Carrier::where('tenant_id', 1) // Default tenant for now
            ->orderBy('sort_order')
            ->get()
            ->map(function ($carrier) {
                return [
                    'id' => $carrier->id,
                    'name' => $carrier->name,
                    'slug' => $carrier->code,
                    'type' => $this->getCarrierType($carrier),
                    'api_status' => $carrier->is_integrated ? 'Connected' : 'Pending',
                    'tracking' => 'Available',
                    'status' => $carrier->is_active ? 'active' : 'inactive',
                    'base_rate' => $carrier->base_rate,
                    'created_at' => $carrier->created_at->format('Y-m-d'),
                ];
            });

        $stats = [
            'total_carriers' => $carriers->count(),
            'active_carriers' => $carriers->where('status', 'active')->count(),
            'connected_apis' => $carriers->where('api_status', 'Connected')->count(),
            'tracking_available' => $carriers->where('tracking', 'Available')->count(),
        ];

        return view('admin.shipping.carriers', compact('carriers', 'stats'));
    }

    /**
     * Show the form for configuring a shipping carrier.
     */
    public function configureCarrier($carrier)
    {
        $carrierModel = Carrier::where('code', $carrier)
            ->where('tenant_id', 1) // Default tenant for now
            ->first();
        
        if (!$carrierModel) {
            abort(404, 'Shipping carrier not found');
        }

        $carrierData = [
            'id' => $carrierModel->id,
            'name' => $carrierModel->name,
            'slug' => $carrierModel->code,
            'type' => $this->getCarrierType($carrierModel),
            'api_status' => $carrierModel->is_integrated ? 'Connected' : 'Pending',
            'tracking' => 'Available',
            'status' => $carrierModel->is_active ? 'active' : 'inactive',
            'api_settings' => $carrierModel->api_settings ?? [],
        ];
        
        return view('admin.shipping.configure-carrier', compact('carrier', 'carrierData'));
    }

    /**
     * Store shipping carrier configuration.
     */
    public function storeCarrierConfig(Request $request, $carrier)
    {
        $carrierModel = Carrier::where('code', $carrier)
            ->where('tenant_id', 1) // Default tenant for now
            ->first();
        
        if (!$carrierModel) {
            abort(404, 'Shipping carrier not found');
        }

        $request->validate([
            'test_mode' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Prepare API settings based on carrier type
        $apiSettings = $carrierModel->api_settings ?? [];
        
        if ($carrier === 'dhl') {
            $request->validate([
                'dhl_api_key' => 'required|string',
                'dhl_api_secret' => 'required|string',
                'dhl_account_number' => 'nullable|string',
            ]);
            
            $apiSettings = array_merge($apiSettings, [
                'api_key' => $request->dhl_api_key,
                'api_secret' => $request->dhl_api_secret,
                'account_number' => $request->dhl_account_number,
                'test_mode' => $request->test_mode,
            ]);
        } elseif ($carrier === 'fedex') {
            $request->validate([
                'fedex_api_key' => 'required|string',
                'fedex_api_secret' => 'required|string',
                'fedex_account_number' => 'nullable|string',
                'fedex_meter_number' => 'nullable|string',
            ]);
            
            $apiSettings = array_merge($apiSettings, [
                'api_key' => $request->fedex_api_key,
                'api_secret' => $request->fedex_api_secret,
                'account_number' => $request->fedex_account_number,
                'meter_number' => $request->fedex_meter_number,
                'test_mode' => $request->test_mode,
            ]);
        } elseif ($carrier === 'ups') {
            $request->validate([
                'ups_access_key' => 'required|string',
                'ups_username' => 'required|string',
                'ups_password' => 'required|string',
                'ups_account_number' => 'nullable|string',
            ]);
            
            $apiSettings = array_merge($apiSettings, [
                'access_key' => $request->ups_access_key,
                'username' => $request->ups_username,
                'password' => $request->ups_password,
                'account_number' => $request->ups_account_number,
                'test_mode' => $request->test_mode,
            ]);
        } elseif ($carrier === 'usps') {
            $request->validate([
                'usps_user_id' => 'required|string',
                'usps_password' => 'nullable|string',
            ]);
            
            $apiSettings = array_merge($apiSettings, [
                'user_id' => $request->usps_user_id,
                'password' => $request->usps_password,
                'test_mode' => $request->test_mode,
            ]);
        } else {
            $request->validate([
                'api_key' => 'required|string',
                'api_secret' => 'required|string',
                'account_number' => 'nullable|string',
            ]);
            
            $apiSettings = array_merge($apiSettings, [
                'api_key' => $request->api_key,
                'api_secret' => $request->api_secret,
                'account_number' => $request->account_number,
                'test_mode' => $request->test_mode,
            ]);
        }

        // Update carrier configuration
        $carrierModel->update([
            'api_settings' => $apiSettings,
            'is_active' => $request->boolean('is_active'),
            'is_integrated' => !empty($apiSettings['api_key']),
        ]);
        
        return redirect()->route('admin.shipping.carriers')
                        ->with('success', ucfirst($carrier) . ' carrier configuration saved successfully.');
    }

    /**
     * Toggle shipping carrier status.
     */
    public function toggleCarrier($carrier)
    {
        $carrierModel = Carrier::where('code', $carrier)
            ->where('tenant_id', 1) // Default tenant for now
            ->first();
        
        if (!$carrierModel) {
            abort(404, 'Shipping carrier not found');
        }

        $carrierModel->update([
            'is_active' => !$carrierModel->is_active
        ]);
        
        $status = $carrierModel->is_active ? 'enabled' : 'disabled';
        
        return redirect()->route('admin.shipping.carriers')
                        ->with('success', ucfirst($carrier) . ' carrier ' . $status . ' successfully.');
    }

    /**
     * Show the form for creating a new carrier.
     */
    public function createCarrier()
    {
        return view('admin.shipping.create-carrier');
    }

    /**
     * Store a newly created shipping carrier.
     */
    public function storeCarrier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:carriers,code',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
            'base_rate' => 'required|numeric|min:0',
            'supported_countries' => 'nullable|array',
            'supported_services' => 'nullable|array',
            'is_active' => 'boolean',
            'is_integrated' => 'boolean',
        ]);

        $carrier = Carrier::create([
            'tenant_id' => 1, // Default tenant for now
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'logo_url' => $request->logo_url,
            'api_settings' => [],
            'supported_countries' => $request->supported_countries ?? [],
            'supported_services' => $request->supported_services ?? ['standard'],
            'is_active' => $request->boolean('is_active'),
            'is_integrated' => $request->boolean('is_integrated'),
            'base_rate' => $request->base_rate,
            'rate_calculation' => [
                'weight_rate' => 2.50,
                'distance_rate' => 0.15,
            ],
            'sort_order' => Carrier::max('sort_order') + 1,
        ]);

        return redirect()->route('admin.shipping.carriers')
                        ->with('success', 'Shipping carrier created successfully.');
    }

    /**
     * Show the form for editing the specified carrier.
     */
    public function editCarrier($id)
    {
        $carrier = Carrier::where('id', $id)
            ->where('tenant_id', 1) // Default tenant for now
            ->firstOrFail();

        return view('admin.shipping.edit-carrier', compact('carrier'));
    }

    /**
     * Update the specified carrier.
     */
    public function updateCarrier(Request $request, $id)
    {
        $carrier = Carrier::where('id', $id)
            ->where('tenant_id', 1) // Default tenant for now
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:carriers,code,' . $id,
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
            'base_rate' => 'required|numeric|min:0',
            'supported_countries' => 'nullable|array',
            'supported_services' => 'nullable|array',
            'is_active' => 'boolean',
            'is_integrated' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $carrier->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'logo_url' => $request->logo_url,
            'supported_countries' => $request->supported_countries ?? [],
            'supported_services' => $request->supported_services ?? ['standard'],
            'is_active' => $request->boolean('is_active'),
            'is_integrated' => $request->boolean('is_integrated'),
            'base_rate' => $request->base_rate,
            'sort_order' => $request->sort_order ?? $carrier->sort_order,
        ]);

        return redirect()->route('admin.shipping.carriers')
                        ->with('success', 'Carrier updated successfully.');
    }

    /**
     * Remove the specified carrier.
     */
    public function destroyCarrier($id)
    {
        $carrier = Carrier::where('id', $id)
            ->where('tenant_id', 1) // Default tenant for now
            ->firstOrFail();

        $carrierName = $carrier->name;
        $carrier->delete();

        return redirect()->route('admin.shipping.carriers')
                        ->with('success', "Carrier '{$carrierName}' deleted successfully.");
    }

    /**
     * Get shipping zones.
     */
    public function getShippingZones()
    {
        // Mock data for now - replace with actual database queries
        $shippingZones = [
            [
                'id' => 1,
                'name' => 'North America',
                'countries' => 'USA, Canada, Mexico',
                'methods_count' => 3,
                'status' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'Europe',
                'countries' => 'UK, Germany, France, Italy',
                'methods_count' => 2,
                'status' => 'active'
            ],
            [
                'id' => 3,
                'name' => 'Asia Pacific',
                'countries' => 'Japan, Australia, Singapore',
                'methods_count' => 2,
                'status' => 'active'
            ],
            [
                'id' => 4,
                'name' => 'Rest of World',
                'countries' => 'All other countries',
                'methods_count' => 1,
                'status' => 'active'
            ]
        ];

        return response()->json($shippingZones);
    }

    /**
     * Get shipping methods.
     */
    public function getShippingMethods()
    {
        // Mock data for now - replace with actual database queries
        $shippingMethods = [
            [
                'id' => 1,
                'name' => 'Standard Shipping',
                'type' => 'Flat Rate',
                'zone' => 'North America',
                'cost' => 9.99,
                'delivery_time' => '5-7 days',
                'status' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'Express Shipping',
                'type' => 'Flat Rate',
                'zone' => 'North America',
                'cost' => 19.99,
                'delivery_time' => '2-3 days',
                'status' => 'active'
            ],
            [
                'id' => 3,
                'name' => 'DHL Express',
                'type' => 'Carrier Based',
                'zone' => 'Europe',
                'cost' => 'Calculated',
                'delivery_time' => '1-2 days',
                'status' => 'active'
            ],
            [
                'id' => 4,
                'name' => 'FedEx Ground',
                'type' => 'Carrier Based',
                'zone' => 'North America',
                'cost' => 'Calculated',
                'delivery_time' => '3-5 days',
                'status' => 'active'
            ]
        ];

        return response()->json($shippingMethods);
    }

    /**
     * Get shipping carriers.
     */
    public function getShippingCarriers()
    {
        // Mock data for now - replace with actual database queries
        $carriers = [
            [
                'id' => 1,
                'name' => 'DHL Express',
                'type' => 'International',
                'api_status' => 'Connected',
                'tracking' => 'Available',
                'status' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'FedEx',
                'type' => 'Domestic & International',
                'api_status' => 'Connected',
                'tracking' => 'Available',
                'status' => 'active'
            ],
            [
                'id' => 3,
                'name' => 'UPS',
                'type' => 'Domestic & International',
                'api_status' => 'Pending',
                'tracking' => 'Available',
                'status' => 'inactive'
            ],
            [
                'id' => 4,
                'name' => 'USPS',
                'type' => 'Domestic',
                'api_status' => 'Connected',
                'tracking' => 'Available',
                'status' => 'active'
            ]
        ];

        return response()->json($carriers);
    }

    /**
     * Calculate shipping cost.
     */
    public function calculateShipping(Request $request)
    {
        $weight = $request->input('weight', 0);
        $dimensions = $request->input('dimensions', []);
        $destination = $request->input('destination');
        $method = $request->input('method', 'standard');

        // Mock shipping calculation - replace with actual logic
        $baseCost = 9.99;
        $weightCost = $weight * 0.5;
        $totalCost = $baseCost + $weightCost;

        return response()->json([
            'method' => $method,
            'cost' => $totalCost,
            'delivery_time' => '5-7 days',
            'carrier' => 'Standard Shipping'
        ]);
    }

    /**
     * Create a new shipping zone.
     */
    public function storeShippingZone(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'countries' => 'required|array',
            'countries.*' => 'string|max:255'
        ]);

        // Add logic to create shipping zone in database
        // For now, just return success
        return response()->json(['message' => 'Shipping zone created successfully']);
    }

    /**
     * Create a new shipping method.
     */
    public function storeShippingMethod(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:flat_rate,carrier_based,product_based',
            'zone_id' => 'required|integer',
            'cost' => 'required|numeric|min:0',
            'delivery_time' => 'required|string|max:255'
        ]);

        // Add logic to create shipping method in database
        // For now, just return success
        return response()->json(['message' => 'Shipping method created successfully']);
    }

    /**
     * Create a new shipping carrier.
     */
    public function storeShippingCarrier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'api_key' => 'nullable|string|max:255',
            'api_secret' => 'nullable|string|max:255'
        ]);

        // Add logic to create shipping carrier in database
        // For now, just return success
        return response()->json(['message' => 'Shipping carrier created successfully']);
    }

    /**
     * Get carrier type based on supported countries
     */
    private function getCarrierType($carrier)
    {
        $supportedCountries = $carrier->supported_countries ?? [];
        
        if (empty($supportedCountries)) {
            return 'Domestic';
        }
        
        $domesticCountries = ['US', 'CA', 'MX']; // Add more domestic countries as needed
        $hasDomestic = !empty(array_intersect($supportedCountries, $domesticCountries));
        $hasInternational = count($supportedCountries) > count(array_intersect($supportedCountries, $domesticCountries));
        
        if ($hasDomestic && $hasInternational) {
            return 'Domestic & International';
        } elseif ($hasDomestic) {
            return 'Domestic';
        } else {
            return 'International';
        }
    }
}
