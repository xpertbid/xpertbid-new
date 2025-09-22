<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateController extends Controller
{
    /**
     * Display a listing of affiliates.
     */
    public function index()
    {
        // Mock data for affiliates - in real implementation, you'd use Eloquent models
        $affiliates = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'status' => 'active',
                'commission_rate' => 5.0,
                'total_earnings' => 1250.00,
                'pending_earnings' => 150.00,
                'referrals_count' => 25,
                'conversion_rate' => 12.5,
                'joined_date' => '2024-01-15',
                'last_activity' => '2024-01-20',
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'status' => 'pending',
                'commission_rate' => 3.0,
                'total_earnings' => 0.00,
                'pending_earnings' => 0.00,
                'referrals_count' => 0,
                'conversion_rate' => 0.0,
                'joined_date' => '2024-01-18',
                'last_activity' => '2024-01-18',
            ],
            [
                'id' => 3,
                'name' => 'Mike Johnson',
                'email' => 'mike@example.com',
                'status' => 'active',
                'commission_rate' => 7.0,
                'total_earnings' => 2100.00,
                'pending_earnings' => 300.00,
                'referrals_count' => 45,
                'conversion_rate' => 18.2,
                'joined_date' => '2024-01-10',
                'last_activity' => '2024-01-19',
            ],
        ];

        $stats = [
            'total_affiliates' => count($affiliates),
            'active_affiliates' => count(array_filter($affiliates, fn($a) => $a['status'] === 'active')),
            'pending_affiliates' => count(array_filter($affiliates, fn($a) => $a['status'] === 'pending')),
            'total_commissions_paid' => array_sum(array_column($affiliates, 'total_earnings')),
            'pending_commissions' => array_sum(array_column($affiliates, 'pending_earnings')),
            'total_referrals' => array_sum(array_column($affiliates, 'referrals_count')),
        ];

        return view('admin.affiliates.index', compact('affiliates', 'stats'));
    }

    /**
     * Show the form for creating a new affiliate.
     */
    public function create()
    {
        return view('admin.affiliates.create');
    }

    /**
     * Store a newly created affiliate.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:affiliates,email',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,pending,inactive',
        ]);

        // In real implementation, you'd save to database
        // Affiliate::create($request->all());

        return redirect()->route('admin.affiliates.index')
                        ->with('success', 'Affiliate created successfully.');
    }

    /**
     * Display the specified affiliate.
     */
    public function show($id)
    {
        // Mock affiliate data
        $affiliate = [
            'id' => $id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'status' => 'active',
            'commission_rate' => 5.0,
            'total_earnings' => 1250.00,
            'pending_earnings' => 150.00,
            'referrals_count' => 25,
            'conversion_rate' => 12.5,
            'joined_date' => '2024-01-15',
            'last_activity' => '2024-01-20',
        ];

        $commissions = [
            [
                'id' => 1,
                'amount' => 50.00,
                'status' => 'paid',
                'order_id' => 'ORD-001',
                'created_at' => '2024-01-15',
            ],
            [
                'id' => 2,
                'amount' => 75.00,
                'status' => 'pending',
                'order_id' => 'ORD-002',
                'created_at' => '2024-01-18',
            ],
        ];

        $referrals = [
            [
                'id' => 1,
                'user_email' => 'user1@example.com',
                'status' => 'converted',
                'commission_earned' => 25.00,
                'created_at' => '2024-01-15',
            ],
            [
                'id' => 2,
                'user_email' => 'user2@example.com',
                'status' => 'pending',
                'commission_earned' => 0.00,
                'created_at' => '2024-01-18',
            ],
        ];

        return view('admin.affiliates.show', compact('affiliate', 'commissions', 'referrals'));
    }

    /**
     * Show the form for editing the specified affiliate.
     */
    public function edit($id)
    {
        // Mock affiliate data
        $affiliate = [
            'id' => $id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'status' => 'active',
            'commission_rate' => 5.0,
        ];

        return view('admin.affiliates.edit', compact('affiliate'));
    }

    /**
     * Update the specified affiliate.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:affiliates,email,' . $id,
            'commission_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,pending,inactive',
        ]);

        // In real implementation, you'd update in database
        // $affiliate = Affiliate::findOrFail($id);
        // $affiliate->update($request->all());

        return redirect()->route('admin.affiliates.index')
                        ->with('success', 'Affiliate updated successfully.');
    }

    /**
     * Remove the specified affiliate.
     */
    public function destroy($id)
    {
        // In real implementation, you'd delete from database
        // Affiliate::findOrFail($id)->delete();

        return redirect()->route('admin.affiliates.index')
                        ->with('success', 'Affiliate deleted successfully.');
    }

    /**
     * Display affiliate programs management.
     */
    public function programs()
    {
        $programs = [
            [
                'id' => 1,
                'name' => 'Standard Affiliate Program',
                'description' => 'Standard commission program for all affiliates',
                'commission_rate' => 5.0,
                'is_active' => true,
                'min_payout' => 50.00,
                'payout_frequency' => 'monthly',
            ],
            [
                'id' => 2,
                'name' => 'Premium Affiliate Program',
                'description' => 'Higher commission program for top performers',
                'commission_rate' => 7.0,
                'is_active' => true,
                'min_payout' => 100.00,
                'payout_frequency' => 'weekly',
            ],
        ];

        return view('admin.affiliates.programs', compact('programs'));
    }

    /**
     * Display commissions management.
     */
    public function commissions()
    {
        $commissions = [
            [
                'id' => 1,
                'affiliate_name' => 'John Doe',
                'amount' => 50.00,
                'status' => 'paid',
                'order_id' => 'ORD-001',
                'created_at' => '2024-01-15',
                'paid_at' => '2024-01-16',
            ],
            [
                'id' => 2,
                'affiliate_name' => 'Jane Smith',
                'amount' => 75.00,
                'status' => 'pending',
                'order_id' => 'ORD-002',
                'created_at' => '2024-01-18',
                'paid_at' => null,
            ],
        ];

        return view('admin.affiliates.commissions', compact('commissions'));
    }
}