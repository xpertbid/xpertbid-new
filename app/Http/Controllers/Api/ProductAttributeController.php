<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $attributes = DB::table('product_attributes')
                ->where('status', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $attributes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product attributes: ' . $e->getMessage()
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
            'slug' => 'nullable|string|unique:product_attributes,slug|max:255',
            'type' => 'required|in:text,select,multiselect,color,image',
            'description' => 'nullable|string',
            'is_required' => 'nullable|boolean',
            'is_filterable' => 'nullable|boolean',
            'is_variation_attribute' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate slug if not provided
            $slug = $request->slug ?? \Illuminate\Support\Str::slug($request->name);
            
            $attribute = DB::table('product_attributes')->insertGetId([
                'tenant_id' => $request->tenant_id,
                'name' => $request->name,
                'slug' => $slug,
                'type' => $request->type,
                'description' => $request->description,
                'is_required' => $request->is_required ?? false,
                'is_filterable' => $request->is_filterable ?? true,
                'is_variation_attribute' => $request->is_variation_attribute ?? false,
                'sort_order' => $request->sort_order ?? 0,
                'status' => $request->status ?? true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product attribute created successfully',
                'data' => ['id' => $attribute]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product attribute: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $attribute = DB::table('product_attributes')
                ->where('id', $id)
                ->where('status', true)
                ->first();
            
            if (!$attribute) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product attribute not found'
                ], 404);
            }

            // Get attribute values
            $values = DB::table('product_attribute_values')
                ->where('product_attribute_id', $id)
                ->where('status', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('value', 'asc')
                ->get();

            $attribute->values = $values;

            return response()->json([
                'success' => true,
                'data' => $attribute
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product attribute: ' . $e->getMessage()
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
            'slug' => 'sometimes|required|string|unique:product_attributes,slug,' . $id . '|max:255',
            'type' => 'sometimes|required|in:text,select,multiselect,color,image',
            'description' => 'nullable|string',
            'is_required' => 'nullable|boolean',
            'is_filterable' => 'nullable|boolean',
            'is_variation_attribute' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
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
                'name', 'slug', 'type', 'description', 'is_required',
                'is_filterable', 'is_variation_attribute', 'sort_order', 'status'
            ]);
            
            $updateData['updated_at'] = now();

            $updated = DB::table('product_attributes')
                ->where('id', $id)
                ->where('status', true)
                ->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product attribute updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product attribute not found or no changes made'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product attribute: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = DB::table('product_attributes')
                ->where('id', $id)
                ->where('status', true)
                ->update(['deleted_at' => now()]);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product attribute deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product attribute not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product attribute: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add value to attribute
     */
    public function addValue(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'color_code' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'image' => 'nullable|url|max:500',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $slug = $request->slug ?? \Illuminate\Support\Str::slug($request->value);
            
            $valueId = DB::table('product_attribute_values')->insertGetId([
                'product_attribute_id' => $id,
                'value' => $request->value,
                'slug' => $slug,
                'color_code' => $request->color_code,
                'image' => $request->image,
                'description' => $request->description,
                'sort_order' => $request->sort_order ?? 0,
                'status' => $request->status ?? true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Attribute value added successfully',
                'data' => ['id' => $valueId]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding attribute value: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get variation attributes (for product variations)
     */
    public function getVariationAttributes()
    {
        try {
            $attributes = DB::table('product_attributes')
                ->where('is_variation_attribute', true)
                ->where('status', true)
                ->where('status', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            // Get values for each attribute
            foreach ($attributes as $attribute) {
                $attribute->values = DB::table('product_attribute_values')
                    ->where('product_attribute_id', $attribute->id)
                    ->where('status', true)
                    ->where('status', true)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('value', 'asc')
                    ->get();
            }

            return response()->json([
                'success' => true,
                'data' => $attributes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching variation attributes: ' . $e->getMessage()
            ], 500);
        }
    }
}