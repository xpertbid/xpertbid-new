<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = BlogCategory::with(['parent', 'children'])
                                 ->orderBy('sort_order')
                                 ->orderBy('name')
                                 ->get();
        
        return view('admin.blog-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = BlogCategory::active()->ordered()->get();
        return view('admin.blog-categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:blog_categories,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        BlogCategory::create([
            'tenant_id' => 1, // Default tenant for now
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('blog-categories.index')
                        ->with('success', 'Blog category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogCategory $blogCategory)
    {
        $parentCategories = BlogCategory::active()
                                       ->where('id', '!=', $blogCategory->id)
                                       ->ordered()
                                       ->get();
        
        return view('admin.blog-categories.edit', compact('blogCategory', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogCategory $blogCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $blogCategory->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:blog_categories,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $blogCategory->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('blog-categories.index')
                        ->with('success', 'Blog category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogCategory $blogCategory)
    {
        // Check if category has children
        if ($blogCategory->children()->count() > 0) {
            return redirect()->route('blog-categories.index')
                            ->with('error', 'Cannot delete category with subcategories.');
        }

        $blogCategory->delete();

        return redirect()->route('blog-categories.index')
                        ->with('success', 'Blog category deleted successfully.');
    }
}