<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tenants = DB::table('tenants')->get();
            return response()->json([
                'success' => true,
                'data' => $tenants
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching tenants: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:tenants,domain',
            'subdomain' => 'required|string|unique:tenants,subdomain',
            'subscription_plan' => 'required|string|in:basic,premium,enterprise',
            'vendor_limit' => 'required|integer|min:1',
            'product_limit' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tenant = DB::table('tenants')->insertGetId([
                'name' => $request->name,
                'domain' => $request->domain,
                'subdomain' => $request->subdomain,
                'subscription_plan' => $request->subscription_plan,
                'vendor_limit' => $request->vendor_limit,
                'product_limit' => $request->product_limit,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tenant created successfully',
                'data' => ['id' => $tenant]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating tenant: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $tenant = DB::table('tenants')->where('id', $id)->first();
            
            if (!$tenant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tenant not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $tenant
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching tenant: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'domain' => 'sometimes|required|string|unique:tenants,domain,' . $id,
            'subdomain' => 'sometimes|required|string|unique:tenants,subdomain,' . $id,
            'subscription_plan' => 'sometimes|required|string|in:basic,premium,enterprise',
            'vendor_limit' => 'sometimes|required|integer|min:1',
            'product_limit' => 'sometimes|required|integer|min:1',
            'status' => 'sometimes|required|string|in:active,suspended,pending',
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
                'name', 'domain', 'subdomain', 'subscription_plan', 
                'vendor_limit', 'product_limit', 'status'
            ]);
            $updateData['updated_at'] = now();

            $updated = DB::table('tenants')->where('id', $id)->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tenant updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tenant not found or no changes made'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating tenant: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = DB::table('tenants')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tenant deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tenant not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting tenant: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update tenant status
     */
    public function updateStatus(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:active,suspended,pending'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updated = DB::table('tenants')->where('id', $id)->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tenant status updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tenant not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating tenant status: ' . $e->getMessage()
            ], 500);
        }
    }
}
