<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;


    protected $fillable = [
        'tenant_id',
        'vendor_id',
        'name',
        'slug',
        'description',
        'price',
        'compare_price',
        'cost_price',
        'sku',
        'barcode',
        'track_quantity',
        'quantity',
        'low_stock_threshold',
        'weight',
        'dimensions',
        'status',
        'product_type',
        'is_variation',
        'parent_product_id',
        'unit',
        'unit_value',
        'thumbnail_image',
        'gallery_images',
        'videos',
        'video_thumbnails',
        'youtube_url',
        'youtube_shorts_url',
        'pdf_specification',
        'reserve_price',
        'product_country',
        'wholesale_price',
        'min_wholesale_quantity',
        'max_wholesale_quantity',
        'digital_files',
        'download_limit',
        'download_expiry_days',
        'meta_title',
        'meta_description',
        'meta_image',
        'meta_keywords',
        'frequently_bought_together',
        'is_featured',
        'is_trending',
        'is_bestseller',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'videos' => 'array',
        'video_thumbnails' => 'array',
        'digital_files' => 'array',
        'frequently_bought_together' => 'array',
        'dimensions' => 'array',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_bestseller' => 'boolean',
        'track_quantity' => 'boolean',
        'is_variation' => 'boolean',
    ];

    /**
     * Register media collections for products
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

        $this->addMediaCollection('videos')
            ->acceptsMimeTypes(['video/mp4', 'video/avi', 'video/mov', 'video/wmv']);

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);

        $this->addMediaCollection('digital_files')
            ->acceptsMimeTypes(['application/pdf', 'application/zip', 'application/x-rar-compressed']);
    }

    /**
     * Register media conversions for thumbnails
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('thumbnail', 'gallery');

        $this->addMediaConversion('medium')
            ->width(600)
            ->height(600)
            ->sharpen(10)
            ->performOnCollections('thumbnail', 'gallery');

        $this->addMediaConversion('large')
            ->width(1200)
            ->height(1200)
            ->sharpen(10)
            ->performOnCollections('thumbnail', 'gallery');

        // Video thumbnail conversion
        $this->addMediaConversion('video_thumb')
            ->width(400)
            ->height(225)
            ->sharpen(10)
            ->performOnCollections('videos');
    }

    /**
     * Get the translations for the product.
     */
    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    /**
     * Get the translation for a specific locale.
     */
    public function translation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Get translated name
     */
    public function getTranslatedNameAttribute()
    {
        $translation = $this->translation();
        return $translation ? $translation->name : $this->name;
    }

    /**
     * Get translated description
     */
    public function getTranslatedDescriptionAttribute()
    {
        $translation = $this->translation();
        return $translation ? $translation->description : $this->description;
    }

    /**
     * Get translated short description
     */
    public function getTranslatedShortDescriptionAttribute()
    {
        $translation = $this->translation();
        return $translation ? $translation->short_description : null;
    }

    /**
     * Get translated meta title
     */
    public function getTranslatedMetaTitleAttribute()
    {
        $translation = $this->translation();
        return $translation ? $translation->meta_title : $this->meta_title;
    }

    /**
     * Get translated meta description
     */
    public function getTranslatedMetaDescriptionAttribute()
    {
        $translation = $this->translation();
        return $translation ? $translation->meta_description : $this->meta_description;
    }

    /**
     * Get translated features
     */
    public function getTranslatedFeaturesAttribute()
    {
        $translation = $this->translation();
        return $translation ? $translation->features : [];
    }

    /**
     * Get translated specifications
     */
    public function getTranslatedSpecificationsAttribute()
    {
        $translation = $this->translation();
        return $translation ? $translation->specifications : [];
    }

    /**
     * Scope for products with translations in specific locale
     */
    public function scopeWithTranslation($query, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $query->with(['translations' => function($q) use ($locale) {
            $q->where('locale', $locale);
        }]);
    }
}
