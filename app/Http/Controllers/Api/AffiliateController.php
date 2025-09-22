<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AffiliateProgram;
use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliateWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AffiliateController extends Controller
{
    /**
     * Get affiliate programs
     */
    public function getPrograms(Request $request)
    {
        try {
            $programs = AffiliateProgram::where('tenant_id', $request->tenant_id)
                ->active()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $programs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching affiliate programs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register as affiliate
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'user_id' => 'required|exists:users,id',
            'affiliate_program_id' => 'required|exists:affiliate_programs,id',
            'application_data' => 'required|array',
            'application_data.full_name' => 'required|string',
            'application_data.email' => 'required|email',
            'application_data.phone' => 'required|string',
            'application_data.website' => 'nullable|url',
            'application_data.social_media' => 'nullable|array',
            'application_data.marketing_methods' => 'nullable|array',
            'application_data.experience' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if user is already an affiliate for this program
            $existingAffiliate = Affiliate::where('user_id', $request->user_id)
                ->where('affiliate_program_id', $request->affiliate_program_id)
                ->where('tenant_id', $request->tenant_id)
                ->first();

            if ($existingAffiliate) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already registered for this affiliate program'
                ], 400);
            }

            $affiliateProgram = AffiliateProgram::find($request->affiliate_program_id);

            // Generate unique affiliate code
            $affiliateCode = Affiliate::generateAffiliateCode($request->user_id);

            $affiliate = Affiliate::create([
                'tenant_id' => $request->tenant_id,
                'user_id' => $request->user_id,
                'affiliate_program_id' => $request->affiliate_program_id,
                'affiliate_code' => $affiliateCode,
                'status' => $affiliateProgram->requires_approval ? 'pending' : 'approved',
                'application_data' => $request->application_data,
                'approved_at' => $affiliateProgram->requires_approval ? null : now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => $affiliateProgram->requires_approval ? 
                    'Affiliate application submitted successfully. You will be notified once approved.' :
                    'Affiliate registration completed successfully.',
                'data' => [
                    'affiliate_id' => $affiliate->id,
                    'affiliate_code' => $affiliateCode,
                    'status' => $affiliate->status,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error registering affiliate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get affiliate dashboard
     */
    public function getDashboard(Request $request, $affiliateId)
    {
        try {
            $affiliate = Affiliate::with(['affiliateProgram', 'user'])
                ->where('id', $affiliateId)
                ->where('tenant_id', $request->tenant_id)
                ->first();

            if (!$affiliate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Affiliate not found'
                ], 404);
            }

            $stats = $affiliate->getDashboardStats();

            // Get recent commissions
            $recentCommissions = AffiliateCommission::where('affiliate_id', $affiliateId)
                ->with(['order', 'product', 'category'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Get recent referrals
            $recentReferrals = $affiliate->referrals()
                ->with('referredUser')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'affiliate' => $affiliate,
                    'stats' => $stats,
                    'recent_commissions' => $recentCommissions,
                    'recent_referrals' => $recentReferrals,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching affiliate dashboard: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get affiliate commissions
     */
    public function getCommissions(Request $request, $affiliateId)
    {
        try {
            $query = AffiliateCommission::where('affiliate_id', $affiliateId)
                ->with(['order', 'product', 'category']);

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $commissions = $query->orderBy('created_at', 'desc')
                ->paginate($request->per_page ?? 20);

            return response()->json([
                'success' => true,
                'data' => $commissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching commissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Request withdrawal
     */
    public function requestWithdrawal(Request $request, $affiliateId)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:bank_transfer,paypal,check',
            'payment_details' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $affiliate = Affiliate::find($affiliateId);

            if (!$affiliate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Affiliate not found'
                ], 404);
            }

            if (!$affiliate->canWithdraw($request->amount)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance or amount below minimum payout threshold'
                ], 400);
            }

            $withdrawal = AffiliateWithdrawal::create([
                'tenant_id' => $affiliate->tenant_id,
                'affiliate_id' => $affiliateId,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_details' => $request->payment_details,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal request submitted successfully',
                'data' => $withdrawal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error requesting withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get withdrawal history
     */
    public function getWithdrawals(Request $request, $affiliateId)
    {
        try {
            $withdrawals = AffiliateWithdrawal::where('affiliate_id', $affiliateId)
                ->orderBy('created_at', 'desc')
                ->paginate($request->per_page ?? 20);

            return response()->json([
                'success' => true,
                'data' => $withdrawals
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching withdrawals: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track referral
     */
    public function trackReferral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'affiliate_code' => 'required|string',
            'referral_type' => 'required|in:signup,purchase,subscription',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $affiliate = Affiliate::where('affiliate_code', $request->affiliate_code)
                ->where('status', 'approved')
                ->first();

            if (!$affiliate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid affiliate code'
                ], 404);
            }

            $referral = \App\Models\Referral::create([
                'tenant_id' => $affiliate->tenant_id,
                'affiliate_id' => $affiliate->id,
                'referral_code' => $request->affiliate_code,
                'referral_type' => $request->referral_type,
                'status' => 'pending',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => $request->metadata,
                'expires_at' => now()->addDays($affiliate->affiliateProgram->cookie_duration),
            ]);

            // Update affiliate stats
            $affiliate->increment('total_referrals');

            return response()->json([
                'success' => true,
                'message' => 'Referral tracked successfully',
                'data' => [
                    'referral_id' => $referral->id,
                    'affiliate_id' => $affiliate->id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error tracking referral: ' . $e->getMessage()
            ], 500);
        }
    }
}
