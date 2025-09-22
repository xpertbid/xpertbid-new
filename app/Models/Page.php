<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'page_type',
        'blog_category_id',
        'meta_data',
        'featured_image',
        'status',
        'is_featured',
        'sort_order',
        'author_id',
        'published_at',
    ];

    protected $casts = [
        'meta_data' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the tenant that owns the page.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the author that owns the page.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the blog category for this page (if it's a blog post).
     */
    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class);
    }

    /**
     * Get the comments for this page.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    /**
     * Get approved comments for this page.
     */
    public function approvedComments(): HasMany
    {
        return $this->hasMany(BlogComment::class)->where('status', 'approved');
    }

    /**
     * Scope to get only published pages.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to get only blog posts.
     */
    public function scopeBlogPosts($query)
    {
        return $query->where('page_type', 'blog_post');
    }

    /**
     * Scope to get only regular pages.
     */
    public function scopePages($query)
    {
        return $query->where('page_type', 'page');
    }

    /**
     * Scope to get featured pages.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the URL for this page.
     */
    public function getUrlAttribute(): string
    {
        if ($this->page_type === 'blog_post') {
            return "/blog/{$this->slug}";
        }
        return "/pages/{$this->slug}";
    }

    /**
     * Get the excerpt or truncated content.
     */
    public function getExcerptAttribute($value): string
    {
        if ($value) {
            return $value;
        }
        
        return \Str::limit(strip_tags($this->content), 150);
    }
}