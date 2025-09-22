<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $vendors = DB::table('vendors')->get();
            return response()->json([
                'success' => true,
                'data' => $vendors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching vendors: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'user_id' => 'required|exists:users,id',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|in:individual,company,corporation',
            'store_name' => 'required|string|unique:vendors,store_name',
            'store_slug' => 'required|string|unique:vendors,store_slug',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vendor = DB::table('vendors')->insertGetId([
                'tenant_id' => $request->tenant_id,
                'user_id' => $request->user_id,
                'business_name' => $request->business_name,
                'business_type' => $request->business_type,
                'store_name' => $request->store_name,
                'store_slug' => $request->store_slug,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'commission_rate' => $request->commission_rate ?? 5.00,
                'status' => 'pending',
                'tier' => 'bronze',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vendor created successfully',
                'data' => ['id' => $vendor]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $vendor = DB::table('vendors')->where('id', $id)->first();
            
            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $vendor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'sometimes|required|string|max:255',
            'business_type' => 'sometimes|required|in:individual,company,corporation',
            'store_name' => 'sometimes|required|string|unique:vendors,store_name,' . $id,
            'store_slug' => 'sometimes|required|string|unique:vendors,store_slug,' . $id,
            'contact_email' => 'sometimes|required|email',
            'contact_phone' => 'nullable|string|max:20',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'sometimes|required|in:pending,approved,rejected,suspended',
            'tier' => 'sometimes|required|in:bronze,silver,gold,platinum',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = $request->only([
                'business_name', 'business_type', 'store_name', 'store_slug',
                'contact_email', 'contact_phone', 'commission_rate', 'status', 'tier'
            ]);
            $updateData['updated_at'] = now();

            $updated = DB::table('vendors')->where('id', $id)->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vendor updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found or no changes made'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = DB::table('vendors')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vendor deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update vendor status
     */
    public function updateStatus(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected,suspended'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updated = DB::table('vendors')->where('id', $id)->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vendor status updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating vendor status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify vendor
     */
    public function verify(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'verified' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [
                'verified' => $request->verified,
                'verified_at' => $request->verified ? now() : null,
                'updated_at' => now()
            ];

            $updated = DB::table('vendors')->where('id', $id)->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vendor verification updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating vendor verification: ' . $e->getMessage()
            ], 500);
        }
    }
}
