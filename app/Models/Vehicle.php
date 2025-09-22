<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Vehicle extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'tenant_id',
        'vendor_id',
        'sales_agent_id',
        'title',
        'description',
        'vehicle_type',
        'listing_type',
        'price',
        'min_offer_price',
        'max_offer_price',
        'starting_price',
        'reserve_price',
        'is_negotiable',
        'offer_requirements',
        'offer_deadline',
        'show_price',
        'make',
        'model',
        'year',
        'variant',
        'body_type',
        'fuel_type',
        'transmission',
        'mileage',
        'mileage_unit',
        'color',
        'doors',
        'seats',
        'engine_size',
        'engine_power',
        'rent_price',
        'currency',
        'condition',
        'vehicle_status',
        'vin_number',
        'registration_number',
        'registration_date',
        'insurance_expiry',
        'features',
        'images',
        'documents',
        'is_featured',
        'is_verified',
        'commission_rate',
    ];

    protected $casts = [
        'features' => 'array',
        'images' => 'array',
        'documents' => 'array',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'registration_date' => 'date',
        'insurance_expiry' => 'date',
    ];

    /**
     * Register media collections for vehicles
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png']);

        $this->addMediaCollection('videos')
            ->acceptsMimeTypes(['video/mp4', 'video/avi', 'video/mov', 'video/wmv']);
    }

    /**
     * Register media conversions for vehicle images
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(200)
            ->sharpen(10)
            ->performOnCollections('images');

        $this->addMediaConversion('medium')
            ->width(600)
            ->height(400)
            ->sharpen(10)
            ->performOnCollections('images');

        $this->addMediaConversion('large')
            ->width(1200)
            ->height(800)
            ->sharpen(10)
            ->performOnCollections('images');

        $this->addMediaConversion('video_thumb')
            ->width(400)
            ->height(225)
            ->sharpen(10)
            ->performOnCollections('videos');
    }

    /**
     * Get the translations for the vehicle.
     */
    public function translations()
    {
        return $this->hasMany(VehicleTranslation::class);
    }

    /**
     * Get the category for the vehicle.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand for the vehicle.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the specifications for the vehicle.
     */
    public function specifications()
    {
        return $this->hasMany(VehicleSpecification::class);
    }

    /**
     * Get the features for the vehicle.
     */
    public function features()
    {
        return $this->hasMany(VehicleFeature::class);
    }

    /**
     * Get the images for the vehicle.
     */
    public function images()
    {
        return $this->hasMany(VehicleImage::class);
    }

    /**
     * Get the documents for the vehicle.
     */
    public function documents()
    {
        return $this->hasMany(VehicleDocument::class);
    }

    /**
     * Get the history for the vehicle.
     */
    public function history()
    {
        return $this->hasMany(VehicleHistory::class);
    }

    /**
     * Get the offers for this vehicle.
     */
    public function offers()
    {
        return $this->morphMany(Offer::class, 'offerable');
    }

    /**
     * Get pending offers for this vehicle.
     */
    public function pendingOffers()
    {
        return $this->offers()->where('status', 'pending');
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
     * Get translated title
     */
    public function getTranslatedTitleAttribute()
    {
        $translation = $this->translation();
        return $translation ? $translation->title : $this->title;
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
        return $translation ? $translation->meta_title : null;
    }

    /**
     * Get translated meta description
     */
    public function getTranslatedMetaDescriptionAttribute()
    {
        $translation = $this->translation();
        return $translation ? $translation->meta_description : null;
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
     * Scope for vehicles with translations in specific locale
     */
    public function scopeWithTranslation($query, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $query->with(['translations' => function($q) use ($locale) {
            $q->where('locale', $locale);
        }]);
    }
}
