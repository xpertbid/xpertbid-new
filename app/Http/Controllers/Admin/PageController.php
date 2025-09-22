<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::with(['author', 'updater'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
        
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        // Handle image upload
        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            $featuredImagePath = $request->file('featured_image')->store('page-images', 'public');
        }

        $page = Page::create([
            'tenant_id' => 1, // Default tenant for now
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content ?? '',
            'featured_image' => $featuredImagePath,
            'page_type' => 'page',
            'status' => $request->boolean('is_published') ? 'published' : 'draft',
            'published_at' => $request->boolean('is_published') ? now() : null,
            'author_id' => auth()->id() ?? 1, // Default to user ID 1 if not authenticated
        ]);

        return redirect()->route('pages.index')
                        ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
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
            'published_at' => $request->boolean('is_published') && !$page->published_at ? now() : $page->published_at,
        ];

        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($page->featured_image && \Storage::disk('public')->exists($page->featured_image)) {
                \Storage::disk('public')->delete($page->featured_image);
            }
            $updateData['featured_image'] = $request->file('featured_image')->store('page-images', 'public');
        }

        $page->update($updateData);

        return redirect()->route('pages.index')
                        ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('pages.index')
                        ->with('success', 'Page deleted successfully.');
    }
}