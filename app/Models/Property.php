<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Property extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'tenant_id',
        'vendor_id',
        'estate_agent_id',
        'title',
        'description',
        'property_type',
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
        'rent_price',
        'currency',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'bedrooms',
        'bathrooms',
        'area_sqft',
        'lot_size',
        'year_built',
        'property_status',
        'amenities',
        'facilities',
        'images',
        'floor_plans',
        'virtual_tour',
        'is_featured',
        'is_verified',
        'commission_rate',
    ];

    protected $casts = [
        'amenities' => 'array',
        'facilities' => 'array',
        'images' => 'array',
        'floor_plans' => 'array',
        'virtual_tour' => 'array',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
    ];

    /**
     * Register media collections for properties
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

        $this->addMediaCollection('floor_plans')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf']);

        $this->addMediaCollection('virtual_tour')
            ->acceptsMimeTypes(['video/mp4', 'video/avi', 'video/mov', 'video/wmv']);
    }

    /**
     * Register media conversions for property images
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

        $this->addMediaConversion('floor_plan_thumb')
            ->width(400)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('floor_plans');
    }

    /**
     * Get the translations for the property.
     */
    public function translations()
    {
        return $this->hasMany(PropertyTranslation::class);
    }

    /**
     * Get the features for the property.
     */
    public function features()
    {
        return $this->hasMany(PropertyFeature::class);
    }

    /**
     * Get the amenities for the property.
     */
    public function amenities()
    {
        return $this->features()->where('feature_type', 'amenity');
    }

    /**
     * Get the facilities for the property.
     */
    public function facilities()
    {
        return $this->features()->where('feature_type', 'facility');
    }

    /**
     * Get the nearby places for the property.
     */
    public function nearbyPlaces()
    {
        return $this->hasMany(PropertyNearby::class);
    }

    /**
     * Get the 360 views for the property.
     */
    public function views360()
    {
        return $this->hasMany(Property360View::class);
    }

    /**
     * Get the offers for this property.
     */
    public function offers()
    {
        return $this->morphMany(Offer::class, 'offerable');
    }

    /**
     * Get pending offers for this property.
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
     * Get translated amenities
     */
    public function getTranslatedAmenitiesAttribute()
    {
        $translation = $this->translation();
        return $translation ? $translation->amenities : [];
    }

    /**
     * Scope for properties with translations in specific locale
     */
    public function scopeWithTranslation($query, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $query->with(['translations' => function($q) use ($locale) {
            $q->where('locale', $locale);
        }]);
    }
}
