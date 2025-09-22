<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $fillable = [
        'product_id',
        'locale',
        'name',
        'description',
        'short_description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'features',
        'specifications',
        'warranty_info',
        'shipping_info',
        'return_policy',
    ];

    protected $casts = [
        'features' => 'array',
        'specifications' => 'array',
    ];

    /**
     * Get the product that owns the translation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for specific locale
     */
    public function scopeLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }
}
