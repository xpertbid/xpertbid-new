<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $units = DB::table('units')
                ->where('status', true)
                ->orderBy('type', 'asc')
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $units
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching units: ' . $e->getMessage()
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
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'type' => 'required|in:weight,volume,length,area,count',
            'conversion_factor' => 'required|numeric|min:0',
            'base_unit' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'is_base_unit' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $unit = DB::table('units')->insertGetId([
                'tenant_id' => $request->tenant_id,
                'name' => $request->name,
                'symbol' => $request->symbol,
                'type' => $request->type,
                'conversion_factor' => $request->conversion_factor,
                'base_unit' => $request->base_unit,
                'description' => $request->description,
                'is_base_unit' => $request->is_base_unit ?? false,
                'status' => $request->status ?? true,
                'sort_order' => $request->sort_order ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Unit created successfully',
                'data' => ['id' => $unit]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating unit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $unit = DB::table('units')
                ->where('id', $id)
                ->where('status', true)
                ->first();
            
            if (!$unit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $unit
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching unit: ' . $e->getMessage()
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
            'symbol' => 'sometimes|required|string|max:10',
            'type' => 'sometimes|required|in:weight,volume,length,area,count',
            'conversion_factor' => 'sometimes|required|numeric|min:0',
            'base_unit' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'is_base_unit' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
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
                'name', 'symbol', 'type', 'conversion_factor', 'base_unit',
                'description', 'is_base_unit', 'status', 'sort_order'
            ]);
            
            $updateData['updated_at'] = now();

            $updated = DB::table('units')
                ->where('id', $id)
                ->where('status', true)
                ->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unit updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit not found or no changes made'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating unit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = DB::table('units')
                ->where('id', $id)
                ->where('status', true)
                ->update(['deleted_at' => now()]);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unit deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting unit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get units by type
     */
    public function getByType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:weight,volume,length,area,count'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $units = DB::table('units')
                ->where('type', $request->type)
                ->where('status', true)
                ->where('status', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $units
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching units by type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get base units
     */
    public function getBaseUnits()
    {
        try {
            $units = DB::table('units')
                ->where('is_base_unit', true)
                ->where('status', true)
                ->where('status', true)
                ->orderBy('type', 'asc')
                ->orderBy('name', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $units
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching base units: ' . $e->getMessage()
            ], 500);
        }
    }
}