<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Page::with(['author'])
                    ->where('page_type', 'blog_post')
                    ->where('status', 'published')
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
        
        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BlogCategory::active()->ordered()->get();
        return view('admin.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug: Log the request data
        \Log::info('Blog creation request data:', $request->all());
        
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        // Handle image upload
        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            $featuredImagePath = $request->file('featured_image')->store('blog-images', 'public');
        }

        $blog = Page::create([
            'tenant_id' => 1, // Default tenant for now
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content ?? '', // Ensure content is never null
            'featured_image' => $featuredImagePath,
            'page_type' => 'blog_post',
            'status' => $request->boolean('is_published') ? 'published' : 'draft',
            'published_at' => $request->boolean('is_published') ? now() : null,
            'author_id' => auth()->id() ?? 1, // Default to user ID 1 if not authenticated
        ]);

        return redirect()->route('blogs.index')
                        ->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $blog)
    {
        $categories = BlogCategory::active()->ordered()->get();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $blog->id,
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        // Handle image upload
        $updateData = [
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content ?? '',
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'status' => $request->boolean('is_published') ? 'published' : 'draft',
            'published_at' => $request->boolean('is_published') && !$blog->published_at ? now() : $blog->published_at,
        ];

        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($blog->featured_image && \Storage::disk('public')->exists($blog->featured_image)) {
                \Storage::disk('public')->delete($blog->featured_image);
            }
            $updateData['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        $blog->update($updateData);

        return redirect()->route('blogs.index')
                        ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $blog)
    {
        $blog->delete();

        return redirect()->route('blogs.index')
                        ->with('success', 'Blog post deleted successfully.');
    }
}