<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    /**
     * Display the tax management page.
     */
    public function index()
    {
        return view('admin.tax');
    }

    /**
     * Get tax classes.
     */
    public function getTaxClasses()
    {
        // Mock data for now - replace with actual database queries
        $taxClasses = [
            [
                'id' => 1,
                'name' => 'Standard Rate',
                'description' => 'Standard tax rate for most products',
                'rates_count' => 3,
                'status' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'Reduced Rate',
                'description' => 'Reduced tax rate for essential items',
                'rates_count' => 2,
                'status' => 'active'
            ],
            [
                'id' => 3,
                'name' => 'Zero Rate',
                'description' => 'Zero tax rate for exempt products',
                'rates_count' => 1,
                'status' => 'active'
            ],
            [
                'id' => 4,
                'name' => 'Digital Products',
                'description' => 'Special tax rate for digital products',
                'rates_count' => 2,
                'status' => 'active'
            ]
        ];

        return response()->json($taxClasses);
    }

    /**
     * Get tax rates.
     */
    public function getTaxRates()
    {
        // Mock data for now - replace with actual database queries
        $taxRates = [
            [
                'id' => 1,
                'country' => 'United States',
                'state' => 'California',
                'city' => 'Los Angeles',
                'tax_class' => 'Standard Rate',
                'rate' => 8.25,
                'status' => 'active'
            ],
            [
                'id' => 2,
                'country' => 'United States',
                'state' => 'New York',
                'city' => 'New York City',
                'tax_class' => 'Standard Rate',
                'rate' => 8.00,
                'status' => 'active'
            ],
            [
                'id' => 3,
                'country' => 'United Kingdom',
                'state' => 'England',
                'city' => 'London',
                'tax_class' => 'Standard Rate',
                'rate' => 20.00,
                'status' => 'active'
            ],
            [
                'id' => 4,
                'country' => 'Canada',
                'state' => 'Ontario',
                'city' => 'Toronto',
                'tax_class' => 'Standard Rate',
                'rate' => 13.00,
                'status' => 'active'
            ]
        ];

        return response()->json($taxRates);
    }

    /**
     * Calculate tax for a given amount and location.
     */
    public function calculateTax(Request $request)
    {
        $amount = $request->input('amount', 0);
        $country = $request->input('country');
        $state = $request->input('state');
        $city = $request->input('city');

        // Mock tax calculation - replace with actual logic
        $taxRate = 8.25; // Default rate
        $taxAmount = ($amount * $taxRate) / 100;
        $totalAmount = $amount + $taxAmount;

        return response()->json([
            'amount' => $amount,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount
        ]);
    }

    /**
     * Create a new tax class.
     */
    public function storeTaxClass(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        // Add logic to create tax class in database
        // For now, just return success
        return response()->json(['message' => 'Tax class created successfully']);
    }

    /**
     * Create a new tax rate.
     */
    public function storeTaxRate(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'tax_class_id' => 'required|integer',
            'rate' => 'required|numeric|min:0|max:100'
        ]);

        // Add logic to create tax rate in database
        // For now, just return success
        return response()->json(['message' => 'Tax rate created successfully']);
    }
}
