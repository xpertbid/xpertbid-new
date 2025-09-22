<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add only missing columns with existence checks
            if (!Schema::hasColumn('products', 'mpn')) {
                $table->string('mpn', 100)->nullable()->after('barcode');
            }
            if (!Schema::hasColumn('products', 'gtin')) {
                $table->string('gtin', 50)->nullable()->after('mpn');
            }
            if (!Schema::hasColumn('products', 'isbn')) {
                $table->string('isbn', 20)->nullable()->after('gtin');
            }
            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 15, 4)->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'msrp')) {
                $table->decimal('msrp', 15, 4)->nullable()->after('cost_price');
            }
            if (!Schema::hasColumn('products', 'wholesale_price')) {
                $table->decimal('wholesale_price', 15, 4)->nullable()->after('msrp');
            }
            if (!Schema::hasColumn('products', 'tax_class')) {
                $table->string('tax_class', 50)->default('standard')->after('wholesale_price');
            }
            if (!Schema::hasColumn('products', 'tax_status')) {
                $table->enum('tax_status', ['taxable', 'shipping_only', 'none'])->default('taxable')->after('tax_class');
            }
            if (!Schema::hasColumn('products', 'manage_stock')) {
                $table->boolean('manage_stock')->default(true)->after('track_quantity');
            }
            if (!Schema::hasColumn('products', 'stock_status')) {
                $table->enum('stock_status', ['in_stock', 'out_of_stock', 'on_backorder', 'discontinued'])->default('in_stock')->after('manage_stock');
            }
            if (!Schema::hasColumn('products', 'low_stock_threshold')) {
                $table->integer('low_stock_threshold')->default(5)->after('stock_status');
            }
            if (!Schema::hasColumn('products', 'backorders')) {
                $table->enum('backorders', ['no', 'notify', 'yes'])->default('no')->after('low_stock_threshold');
            }
            if (!Schema::hasColumn('products', 'volume')) {
                $table->decimal('volume', 8, 3)->nullable()->after('height');
            }
            if (!Schema::hasColumn('products', 'requires_shipping')) {
                $table->boolean('requires_shipping')->default(true)->after('volume');
            }
            if (!Schema::hasColumn('products', 'shipping_class')) {
                $table->string('shipping_class', 100)->nullable()->after('requires_shipping');
            }
            if (!Schema::hasColumn('products', 'free_shipping')) {
                $table->boolean('free_shipping')->default(false)->after('shipping_class');
            }
            if (!Schema::hasColumn('products', 'separate_shipping')) {
                $table->boolean('separate_shipping')->default(false)->after('free_shipping');
            }
            if (!Schema::hasColumn('products', 'is_downloadable')) {
                $table->boolean('is_downloadable')->default(false)->after('is_digital');
            }
            if (!Schema::hasColumn('products', 'download_limit')) {
                $table->integer('download_limit')->default(-1)->after('is_downloadable');
            }
            if (!Schema::hasColumn('products', 'download_expiry')) {
                $table->integer('download_expiry')->default(-1)->after('download_limit');
            }
            if (!Schema::hasColumn('products', 'downloadable_files')) {
                $table->json('downloadable_files')->nullable()->after('download_expiry');
            }
            if (!Schema::hasColumn('products', 'visibility')) {
                $table->enum('visibility', ['visible', 'catalog', 'search', 'hidden'])->default('visible')->after('status');
            }
            if (!Schema::hasColumn('products', 'catalog_visibility')) {
                $table->enum('catalog_visibility', ['visible', 'catalog', 'search', 'hidden'])->default('visible')->after('visibility');
            }
            if (!Schema::hasColumn('products', 'date_on_sale_from')) {
                $table->timestamp('date_on_sale_from')->nullable()->after('catalog_visibility');
            }
            if (!Schema::hasColumn('products', 'date_on_sale_to')) {
                $table->timestamp('date_on_sale_to')->nullable()->after('date_on_sale_from');
            }
            if (!Schema::hasColumn('products', 'featured_image')) {
                $table->string('featured_image', 500)->nullable()->after('images');
            }
            if (!Schema::hasColumn('products', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('featured_image');
            }
            if (!Schema::hasColumn('products', 'video_url')) {
                $table->string('video_url', 500)->nullable()->after('gallery_images');
            }
            if (!Schema::hasColumn('products', 'seo_title')) {
                $table->string('seo_title', 255)->nullable()->after('video_url');
            }
            if (!Schema::hasColumn('products', 'seo_description')) {
                $table->text('seo_description')->nullable()->after('seo_title');
            }
            if (!Schema::hasColumn('products', 'seo_keywords')) {
                $table->string('seo_keywords', 500)->nullable()->after('seo_description');
            }
            if (!Schema::hasColumn('products', 'seo_focus_keyword')) {
                $table->string('seo_focus_keyword', 100)->nullable()->after('seo_keywords');
            }
            if (!Schema::hasColumn('products', 'canonical_url')) {
                $table->string('canonical_url', 500)->nullable()->after('seo_focus_keyword');
            }
            if (!Schema::hasColumn('products', 'meta_tags')) {
                $table->json('meta_tags')->nullable()->after('canonical_url');
            }
            if (!Schema::hasColumn('products', 'language')) {
                $table->string('language', 10)->default('en')->after('meta_tags');
            }
            if (!Schema::hasColumn('products', 'translation_group')) {
                $table->string('translation_group', 36)->nullable()->after('language');
            }
            if (!Schema::hasColumn('products', 'views_count')) {
                $table->integer('views_count')->default(0)->after('translation_group');
            }
            if (!Schema::hasColumn('products', 'sales_count')) {
                $table->integer('sales_count')->default(0)->after('views_count');
            }
            if (!Schema::hasColumn('products', 'wishlist_count')) {
                $table->integer('wishlist_count')->default(0)->after('sales_count');
            }
            if (!Schema::hasColumn('products', 'rating_average')) {
                $table->decimal('rating_average', 3, 2)->default(0.00)->after('wishlist_count');
            }
            if (!Schema::hasColumn('products', 'rating_count')) {
                $table->integer('rating_count')->default(0)->after('rating_average');
            }
            if (!Schema::hasColumn('products', 'review_count')) {
                $table->integer('review_count')->default(0)->after('rating_count');
            }
            if (!Schema::hasColumn('products', 'last_sold_at')) {
                $table->timestamp('last_sold_at')->nullable()->after('review_count');
            }
            if (!Schema::hasColumn('products', 'featured_at')) {
                $table->timestamp('featured_at')->nullable()->after('last_sold_at');
            }
            if (!Schema::hasColumn('products', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('featured_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'mpn', 'gtin', 'isbn', 'sale_price', 'msrp', 'wholesale_price',
                'tax_class', 'tax_status', 'manage_stock', 'stock_status',
                'low_stock_threshold', 'backorders', 'volume', 'requires_shipping',
                'shipping_class', 'free_shipping', 'separate_shipping', 'is_downloadable',
                'download_limit', 'download_expiry', 'downloadable_files', 'visibility',
                'catalog_visibility', 'date_on_sale_from', 'date_on_sale_to',
                'featured_image', 'gallery_images', 'video_url', 'seo_title',
                'seo_description', 'seo_keywords', 'seo_focus_keyword', 'canonical_url',
                'meta_tags', 'language', 'translation_group', 'views_count',
                'sales_count', 'wishlist_count', 'rating_average', 'rating_count',
                'review_count', 'last_sold_at', 'featured_at', 'published_at'
            ]);
        });
    }
};