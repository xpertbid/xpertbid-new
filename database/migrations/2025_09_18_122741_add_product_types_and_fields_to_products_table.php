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
            // Product Type System (check if columns exist first)
            if (!Schema::hasColumn('products', 'product_type')) {
                $table->enum('product_type', ['simple', 'variation', 'digital', 'auction', 'wholesale'])->default('simple')->after('type');
            }
            if (!Schema::hasColumn('products', 'is_variation')) {
                $table->boolean('is_variation')->default(false)->after('product_type');
            }
            if (!Schema::hasColumn('products', 'parent_product_id')) {
                $table->unsignedBigInteger('parent_product_id')->nullable()->after('is_variation');
            }
            
            // Units System
            if (!Schema::hasColumn('products', 'unit')) {
                $table->string('unit', 50)->nullable()->after('weight');
            }
            if (!Schema::hasColumn('products', 'unit_value')) {
                $table->decimal('unit_value', 10, 3)->nullable()->after('unit'); // e.g., 1.5 for 1.5 kg
            }
            
            // Enhanced Media Fields
            if (!Schema::hasColumn('products', 'thumbnail_image')) {
                $table->string('thumbnail_image', 500)->nullable()->after('images');
            }
            if (!Schema::hasColumn('products', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('thumbnail_image');
            }
            if (!Schema::hasColumn('products', 'videos')) {
                $table->json('videos')->nullable()->after('gallery_images');
            }
            if (!Schema::hasColumn('products', 'video_thumbnails')) {
                $table->json('video_thumbnails')->nullable()->after('videos');
            }
            if (!Schema::hasColumn('products', 'youtube_url')) {
                $table->string('youtube_url', 500)->nullable()->after('video_thumbnails');
            }
            if (!Schema::hasColumn('products', 'youtube_shorts_url')) {
                $table->string('youtube_shorts_url', 500)->nullable()->after('youtube_url');
            }
            if (!Schema::hasColumn('products', 'pdf_specification')) {
                $table->string('pdf_specification', 500)->nullable()->after('youtube_shorts_url');
            }
            
            // Auction Specific Fields
            if (!Schema::hasColumn('products', 'reserve_price')) {
                $table->decimal('reserve_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'product_country')) {
                $table->string('product_country', 100)->nullable()->after('reserve_price');
            }
            
            // Wholesale Specific Fields
            if (!Schema::hasColumn('products', 'wholesale_price')) {
                $table->decimal('wholesale_price', 10, 2)->nullable()->after('reserve_price');
            }
            if (!Schema::hasColumn('products', 'min_wholesale_quantity')) {
                $table->integer('min_wholesale_quantity')->nullable()->after('wholesale_price');
            }
            if (!Schema::hasColumn('products', 'max_wholesale_quantity')) {
                $table->integer('max_wholesale_quantity')->nullable()->after('min_wholesale_quantity');
            }
            
            // Digital Product Fields
            if (!Schema::hasColumn('products', 'digital_files')) {
                $table->json('digital_files')->nullable()->after('pdf_specification');
            }
            if (!Schema::hasColumn('products', 'download_limit')) {
                $table->integer('download_limit')->default(-1)->after('digital_files'); // -1 for unlimited
            }
            if (!Schema::hasColumn('products', 'download_expiry_days')) {
                $table->integer('download_expiry_days')->nullable()->after('download_limit');
            }
            
            // Enhanced SEO Fields
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title', 255)->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('products', 'meta_image')) {
                $table->string('meta_image', 500)->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('products', 'meta_keywords')) {
                $table->string('meta_keywords', 500)->nullable()->after('meta_image');
            }
            
            // Frequently Bought Together
            if (!Schema::hasColumn('products', 'frequently_bought_together')) {
                $table->json('frequently_bought_together')->nullable()->after('meta_keywords');
            }
            
            // Product Status Enhancements
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('frequently_bought_together');
            }
            if (!Schema::hasColumn('products', 'is_trending')) {
                $table->boolean('is_trending')->default(false)->after('is_featured');
            }
            if (!Schema::hasColumn('products', 'is_bestseller')) {
                $table->boolean('is_bestseller')->default(false)->after('is_trending');
            }
            
            // Note: Indexes and foreign keys are handled separately to avoid conflicts
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['parent_product_id']);
            $table->dropIndex(['product_type']);
            $table->dropIndex(['is_variation']);
            $table->dropIndex(['parent_product_id']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['is_trending']);
            $table->dropIndex(['is_bestseller']);
            
            $table->dropColumn([
                'product_type', 'is_variation', 'parent_product_id',
                'unit', 'unit_value', 'thumbnail_image', 'gallery_images',
                'videos', 'video_thumbnails', 'youtube_url', 'youtube_shorts_url',
                'pdf_specification', 'reserve_price', 'product_country',
                'wholesale_price', 'min_wholesale_quantity', 'max_wholesale_quantity',
                'digital_files', 'download_limit', 'download_expiry_days',
                'meta_title', 'meta_description', 'meta_image', 'meta_keywords',
                'frequently_bought_together', 'is_featured', 'is_trending', 'is_bestseller'
            ]);
        });
    }
};