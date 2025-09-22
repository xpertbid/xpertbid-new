<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'parent_id',
        'sort_order',
        'is_active',
        'meta_data',
    ];

    protected $casts = [
        'meta_data' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the tenant that owns the category.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(BlogCategory::class, 'parent_id');
    }

    /**
     * Get all descendants (children, grandchildren, etc.).
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get the pages in this category.
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'blog_category_id');
    }

    /**
     * Get published pages in this category.
     */
    public function publishedPages(): HasMany
    {
        return $this->hasMany(Page::class, 'blog_category_id')->where('status', 'published');
    }

    /**
     * Scope to get only active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only root categories (no parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get categories ordered by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get the URL for this category.
     */
    public function getUrlAttribute(): string
    {
        return "/blog/category/{$this->slug}";
    }

    /**
     * Get the count of published pages in this category.
     */
    public function getPagesCountAttribute(): int
    {
        return $this->publishedPages()->count();
    }

    /**
     * Get all categories in a tree structure.
     */
    public static function getTree($tenantId = null)
    {
        $query = static::active()->ordered();
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        $categories = $query->get();
        
        return static::buildTree($categories);
    }

    /**
     * Build a tree structure from flat categories.
     */
    private static function buildTree($categories, $parentId = null)
    {
        $tree = [];
        
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = static::buildTree($categories, $category->id);
                if ($children) {
                    $category->children = $children;
                }
                $tree[] = $category;
            }
        }
        
        return $tree;
    }
}