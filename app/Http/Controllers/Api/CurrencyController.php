<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    /**
     * Display a listing of currencies
     */
    public function index(Request $request): JsonResponse
    {
        $query = Currency::query();

        // Filter by active status
        if ($request->has('active_only') && $request->active_only) {
            $query->active();
        }

        // Search by name or code
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%");
            });
        }

        $currencies = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $currencies->map(function($currency) {
                return [
                    'id' => $currency->id,
                    'name' => $currency->name,
                    'code' => $currency->code,
                    'symbol' => $currency->symbol,
                    'symbol_position' => $currency->symbol_position,
                    'decimal_places' => $currency->decimal_places,
                    'exchange_rate' => $currency->exchange_rate,
                    'icon_url' => null,
                    'icon_thumb' => null,
                    'is_active' => $currency->is_active,
                    'is_default' => $currency->is_default,
                    'sort_order' => 0,
                    'created_at' => $currency->created_at,
                    'updated_at' => $currency->updated_at,
                ];
            })
        ]);
    }

    /**
     * Store a newly created currency
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:currencies,code',
            'symbol' => 'required|string|max:10',
            'symbol_position' => 'required|in:before,after',
            'decimal_places' => 'required|integer|min:0|max:6',
            'exchange_rate' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $currency = Currency::create($request->all());

            // If this is set as default, remove default from others
            if ($request->is_default) {
                $currency->setAsDefault();
            }

            return response()->json([
                'success' => true,
                'message' => 'Currency created successfully',
                'data' => $currency
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create currency',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified currency
     */
    public function show(Currency $currency): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $currency->id,
                'name' => $currency->name,
                'code' => $currency->code,
                'symbol' => $currency->symbol,
                'symbol_position' => $currency->symbol_position,
                'decimal_places' => $currency->decimal_places,
                'exchange_rate' => $currency->exchange_rate,
                'icon_url' => null,
                'icon_thumb' => null,
                'is_active' => $currency->is_active,
                'is_default' => $currency->is_default,
                'sort_order' => 0,
                'created_at' => $currency->created_at,
                'updated_at' => $currency->updated_at,
            ]
        ]);
    }

    /**
     * Update the specified currency
     */
    public function update(Request $request, Currency $currency): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:10|unique:currencies,code,' . $currency->id,
            'symbol' => 'sometimes|required|string|max:10',
            'symbol_position' => 'sometimes|required|in:before,after',
            'decimal_places' => 'sometimes|required|integer|min:0|max:6',
            'exchange_rate' => 'sometimes|required|numeric|min:0',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $currency->update($request->all());

            // If this is set as default, remove default from others
            if ($request->has('is_default') && $request->is_default) {
                $currency->setAsDefault();
            }

            return response()->json([
                'success' => true,
                'message' => 'Currency updated successfully',
                'data' => $currency
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update currency',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified currency
     */
    public function destroy(Currency $currency): JsonResponse
    {
        try {
            // Don't allow deleting default currency
            if ($currency->is_default) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete default currency'
                ], 400);
            }

            $currency->delete();

            return response()->json([
                'success' => true,
                'message' => 'Currency deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete currency',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update currency status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        try {
            $currency = Currency::findOrFail($id);
            $currency->update(['is_active' => !$currency->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Currency status updated successfully',
                'data' => $currency
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update currency status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set currency as default
     */
    public function setDefault(Request $request, $id): JsonResponse
    {
        try {
            $currency = Currency::findOrFail($id);
            $currency->setAsDefault();

            return response()->json([
                'success' => true,
                'message' => 'Default currency updated successfully',
                'data' => $currency
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set default currency',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active currencies for frontend
     */
    public function getActive(): JsonResponse
    {
        $currencies = Currency::active()->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $currencies->map(function($currency) {
                return [
                    'id' => $currency->id,
                    'name' => $currency->name,
                    'code' => $currency->code,
                    'symbol' => $currency->symbol,
                    'symbol_position' => $currency->symbol_position,
                    'decimal_places' => $currency->decimal_places,
                    'exchange_rate' => $currency->exchange_rate,
                    'icon_url' => null,
                    'is_default' => $currency->is_default,
                ];
            })
        ]);
    }

    /**
     * Convert amount between currencies
     */
    public function convert(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'from_currency' => 'required|string|exists:currencies,code',
            'to_currency' => 'required|string|exists:currencies,code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $fromCurrency = Currency::where('code', $request->from_currency)->first();
            $toCurrency = Currency::where('code', $request->to_currency)->first();

            // Convert to default currency first, then to target currency
            $amountInDefault = $fromCurrency->convertToDefault($request->amount);
            $convertedAmount = $toCurrency->convertFromDefault($amountInDefault);

            return response()->json([
                'success' => true,
                'data' => [
                    'original_amount' => $request->amount,
                    'original_currency' => $request->from_currency,
                    'converted_amount' => $convertedAmount,
                    'target_currency' => $request->to_currency,
                    'formatted_amount' => $toCurrency->formatAmount($convertedAmount),
                    'exchange_rate' => $toCurrency->exchange_rate / $fromCurrency->exchange_rate,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to convert currency',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update exchange rates (bulk update)
     */
    public function updateExchangeRates(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rates' => 'required|array',
            'rates.*.id' => 'required|integer|exists:currencies,id',
            'rates.*.exchange_rate' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($request->rates as $rate) {
                Currency::where('id', $rate['id'])->update([
                    'exchange_rate' => $rate['exchange_rate']
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Exchange rates updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update exchange rates',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
