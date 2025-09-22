<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use App\Models\ShippingMethod;
use App\Models\Carrier;
use App\Models\PickupPoint;
use App\Models\VendorShippingSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    /**
     * Get shipping zones
     */
    public function getZones(Request $request)
    {
        try {
            $zones = ShippingZone::with('shippingMethods')
                ->where('tenant_id', $request->tenant_id)
                ->active()
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $zones
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching shipping zones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get shipping methods for a zone
     */
    public function getMethods(Request $request, $zoneId)
    {
        try {
            $methods = ShippingMethod::where('shipping_zone_id', $zoneId)
                ->active()
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $methods
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching shipping methods: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate shipping cost
     */
    public function calculateShipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'destination' => 'required|array',
            'destination.country' => 'required|string',
            'destination.state' => 'nullable|string',
            'destination.city' => 'nullable|string',
            'destination.postal_code' => 'nullable|string',
            'order_amount' => 'required|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $destination = $request->destination;
            $orderAmount = $request->order_amount;
            $weight = $request->weight ?? 0;
            $vendorId = $request->vendor_id;

            // Find applicable shipping zone
            $zone = ShippingZone::where('tenant_id', $request->tenant_id)
                ->active()
                ->get()
                ->first(function ($zone) use ($destination) {
                    return $zone->coversLocation(
                        $destination['country'],
                        $destination['state'] ?? null,
                        $destination['city'] ?? null,
                        $destination['postal_code'] ?? null
                    );
                });

            if (!$zone) {
                return response()->json([
                    'success' => false,
                    'message' => 'No shipping zone found for the destination'
                ], 404);
            }

            // Get available shipping methods
            $methods = ShippingMethod::where('shipping_zone_id', $zone->id)
                ->active()
                ->get();

            $shippingOptions = [];

            foreach ($methods as $method) {
                $cost = $method->calculateCost($orderAmount, $weight, null, $vendorId);
                
                $shippingOptions[] = [
                    'id' => $method->id,
                    'name' => $method->name,
                    'method_type' => $method->method_type,
                    'cost' => $cost,
                    'free_shipping_threshold' => $method->free_shipping_threshold,
                    'estimated_delivery' => $this->getEstimatedDelivery($method),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'zone' => $zone,
                    'shipping_options' => $shippingOptions
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating shipping: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pickup points
     */
    public function getPickupPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = PickupPoint::where('tenant_id', $request->tenant_id)
                ->active();

            if ($request->city) {
                $query->where('city', $request->city);
            }
            if ($request->state) {
                $query->where('state', $request->state);
            }
            if ($request->country) {
                $query->where('country', $request->country);
            }

            $pickupPoints = $query->orderBy('sort_order')->get();

            return response()->json([
                'success' => true,
                'data' => $pickupPoints
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pickup points: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get carriers
     */
    public function getCarriers(Request $request)
    {
        try {
            $carriers = Carrier::where('tenant_id', $request->tenant_id)
                ->active()
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $carriers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching carriers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor shipping settings
     */
    public function getVendorSettings(Request $request, $vendorId)
    {
        try {
            $settings = VendorShippingSettings::where('vendor_id', $vendorId)
                ->where('tenant_id', $request->tenant_id)
                ->first();

            if (!$settings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor shipping settings not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching vendor shipping settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get estimated delivery time
     */
    private function getEstimatedDelivery($method)
    {
        $settings = $method->settings ?? [];
        return $settings['estimated_delivery'] ?? '3-5 business days';
    }
}
