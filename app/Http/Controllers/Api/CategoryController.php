<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = DB::table('categories')
                ->where('is_active', true);
            
            // Filter by level if provided
            if ($request->has('level')) {
                $query->where('level', $request->level);
            }
            
            // Filter by parent if provided
            if ($request->has('parent_id')) {
                $query->where('parent_id', $request->parent_id);
            }
            
            $categories = $query->orderBy('level', 'asc')
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching categories: ' . $e->getMessage()
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
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url|max:500',
            'banner_image' => 'nullable|url|max:500',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'listing_fee' => 'nullable|numeric|min:0',
            'final_value_fee' => 'nullable|numeric|min:0|max:100',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
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
            
            // Determine level based on parent
            $level = 1; // Default to category level
            $path = $request->name;
            
            if ($request->parent_id) {
                $parent = DB::table('categories')->where('id', $request->parent_id)->first();
                if ($parent) {
                    $level = $parent->level + 1;
                    $path = $parent->path ? $parent->path . '/' . $request->name : $request->name;
                }
            }
            
            $category = DB::table('categories')->insertGetId([
                'tenant_id' => $request->tenant_id,
                'parent_id' => $request->parent_id,
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'image' => $request->image,
                'banner_image' => $request->banner_image,
                'icon' => $request->icon,
                'color' => $request->color,
                'sort_order' => $request->sort_order ?? 0,
                'is_featured' => $request->is_featured ?? false,
                'status' => $request->status ?? true,
                'level' => $level,
                'path' => $path,
                'commission_rate' => $request->commission_rate,
                'listing_fee' => $request->listing_fee ?? 0.00,
                'final_value_fee' => $request->final_value_fee,
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'seo_keywords' => $request->seo_keywords,
                'canonical_url' => $request->canonical_url,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update parent's subcategory count
            if ($request->parent_id) {
                DB::table('categories')
                    ->where('id', $request->parent_id)
                    ->increment('subcategory_count');
            }

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => ['id' => $category]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = DB::table('categories')
                ->where('id', $id)
                ->first();
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Get subcategories
            $subcategories = DB::table('categories')
                ->where('parent_id', $id)
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            $category->subcategories = $subcategories;

            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|unique:categories,slug,' . $id . '|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url|max:500',
            'banner_image' => 'nullable|url|max:500',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'listing_fee' => 'nullable|numeric|min:0',
            'final_value_fee' => 'nullable|numeric|min:0|max:100',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
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
                'parent_id', 'name', 'slug', 'description', 'image', 'banner_image',
                'icon', 'color', 'sort_order', 'is_featured', 'status',
                'commission_rate', 'listing_fee', 'final_value_fee',
                'seo_title', 'seo_description', 'seo_keywords', 'canonical_url'
            ]);
            
            // Update level and path if parent changes
            if ($request->has('parent_id')) {
                $level = 1;
                $path = $request->name ?? DB::table('categories')->where('id', $id)->value('name');
                
                if ($request->parent_id) {
                    $parent = DB::table('categories')->where('id', $request->parent_id)->first();
                    if ($parent) {
                        $level = $parent->level + 1;
                        $path = $parent->path ? $parent->path . '/' . $path : $path;
                    }
                }
                
                $updateData['level'] = $level;
                $updateData['path'] = $path;
            }
            
            $updateData['updated_at'] = now();

            $updated = DB::table('categories')
                ->where('id', $id)
                ->where('is_active', true)
                ->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found or no changes made'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Check if category has subcategories
            $subcategoriesCount = DB::table('categories')
                ->where('parent_id', $id)
                ->where('is_active', true)
                ->count();
            
            if ($subcategoriesCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with subcategories. Please delete subcategories first.'
                ], 400);
            }

            $deleted = DB::table('categories')
                ->where('id', $id)
                ->where('is_active', true)
                ->update(['deleted_at' => now()]);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update category status
     */
    public function updateStatus(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updated = DB::table('categories')
                ->where('id', $id)
                ->where('is_active', true)
                ->update([
                    'status' => $request->status,
                    'updated_at' => now()
                ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category status updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating category status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get category tree
     */
    public function getTree()
    {
        try {
            $categories = DB::table('categories')
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            // Build tree structure
            $tree = $this->buildTree($categories->toArray());

            return response()->json([
                'success' => true,
                'data' => $tree
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching category tree: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Build category tree recursively
     */
    private function buildTree($categories, $parentId = null)
    {
        $tree = [];
        
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $category->children = $this->buildTree($categories, $category->id);
                $tree[] = $category;
            }
        }
        
        return $tree;
    }
}