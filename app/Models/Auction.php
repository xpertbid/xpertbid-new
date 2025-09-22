<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Auction extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('videos')
            ->acceptsMimeTypes(['video/mp4', 'video/avi', 'video/mov']);

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    /**
     * Register media conversions
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(400)
            ->height(400)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->width(800)
            ->height(800)
            ->sharpen(10)
            ->nonQueued();
    }

    /**
     * Get the category for the auction.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the bids for the auction.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
}
