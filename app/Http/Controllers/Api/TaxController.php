<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaxClass;
use App\Models\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxController extends Controller
{
    /**
     * Get tax classes
     */
    public function getTaxClasses(Request $request)
    {
        try {
            $taxClasses = TaxClass::where('tenant_id', $request->tenant_id)
                ->active()
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $taxClasses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching tax classes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tax rates for a class
     */
    public function getTaxRates(Request $request, $taxClassId)
    {
        try {
            $taxRates = TaxRate::where('tax_class_id', $taxClassId)
                ->where('tenant_id', $request->tenant_id)
                ->active()
                ->orderBy('priority', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $taxRates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching tax rates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate tax for an order
     */
    public function calculateTax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'tax_class_id' => 'required|exists:tax_classes,id',
            'location' => 'required|array',
            'location.country' => 'required|string',
            'location.state' => 'nullable|string',
            'location.city' => 'nullable|string',
            'location.postal_code' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'tax_inclusive' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $taxClass = TaxClass::find($request->tax_class_id);
            $location = $request->location;
            $amount = $request->amount;
            $shippingAmount = $request->shipping_amount ?? 0;
            $taxInclusive = $request->tax_inclusive ?? false;

            // Get applicable tax rate
            $taxRate = $taxClass->getTaxRateForLocation(
                $location['country'],
                $location['state'] ?? null,
                $location['city'] ?? null,
                $location['postal_code'] ?? null
            );

            if (!$taxRate) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'tax_rate' => 0,
                        'tax_amount' => 0,
                        'shipping_tax_amount' => 0,
                        'total_tax_amount' => 0,
                        'tax_inclusive' => $taxInclusive,
                        'net_amount' => $amount,
                        'gross_amount' => $amount,
                    ]
                ]);
            }

            // Calculate tax amounts
            $productTaxAmount = $taxRate->calculateTax($amount);
            $shippingTaxAmount = $taxRate->calculateTax($shippingAmount, true);
            $totalTaxAmount = $productTaxAmount + $shippingTaxAmount;

            if ($taxInclusive) {
                // Tax is included in the amount
                $netAmount = $amount / (1 + $taxRate->rate);
                $grossAmount = $amount;
            } else {
                // Tax is added to the amount
                $netAmount = $amount;
                $grossAmount = $amount + $totalTaxAmount;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'tax_rate' => $taxRate->rate,
                    'tax_rate_formatted' => $taxRate->formatted_rate,
                    'tax_amount' => $productTaxAmount,
                    'shipping_tax_amount' => $shippingTaxAmount,
                    'total_tax_amount' => $totalTaxAmount,
                    'tax_inclusive' => $taxInclusive,
                    'net_amount' => $netAmount,
                    'gross_amount' => $grossAmount,
                    'tax_location' => $taxRate->location_description,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating tax: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tax rates by location
     */
    public function getTaxRatesByLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'country' => 'required|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $taxRates = TaxRate::where('tenant_id', $request->tenant_id)
                ->where('country_code', $request->country)
                ->active()
                ->when($request->state, function ($query, $state) {
                    return $query->where(function ($q) use ($state) {
                        $q->where('state_code', $state)
                          ->orWhereNull('state_code');
                    });
                })
                ->when($request->city, function ($query, $city) {
                    return $query->where(function ($q) use ($city) {
                        $q->where('city', $city)
                          ->orWhereNull('city');
                    });
                })
                ->when($request->postal_code, function ($query, $postalCode) {
                    return $query->where(function ($q) use ($postalCode) {
                        $q->where('postal_code', $postalCode)
                          ->orWhereNull('postal_code');
                    });
                })
                ->with('taxClass')
                ->orderBy('priority', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $taxRates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching tax rates: ' . $e->getMessage()
            ], 500);
        }
    }
}
