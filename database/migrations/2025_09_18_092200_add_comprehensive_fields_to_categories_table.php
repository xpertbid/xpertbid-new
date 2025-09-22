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
        Schema::table('categories', function (Blueprint $table) {
            // Enhanced category fields
            $table->string('color', 7)->nullable()->after('icon'); // Hex color code
            $table->string('banner_image')->nullable()->after('image');
            $table->boolean('status')->default(true)->after('is_featured');
            $table->string('language', 10)->default('en')->after('status');
            $table->string('translation_group', 36)->nullable()->after('language'); // UUID to group translations
            
            // Enhanced SEO fields
            $table->string('seo_title', 255)->nullable()->after('seo_meta');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->string('seo_keywords', 500)->nullable()->after('seo_description');
            $table->string('canonical_url', 500)->nullable()->after('seo_keywords');
            
            // Category level tracking
            $table->integer('level')->default(0)->after('parent_id'); // 0=root, 1=sub, 2=child
            $table->string('path')->nullable()->after('level'); // e.g., "electronics/computers/laptops"
            
            // Indexes
            $table->index('level');
            $table->index('path');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['level']);
            $table->dropIndex(['path']);
            $table->dropIndex(['status']);
            
            // Drop columns
            $table->dropColumn([
                'color', 'banner_image', 'status', 'language', 'translation_group',
                'seo_title', 'seo_description', 'seo_keywords', 'canonical_url',
                'level', 'path'
            ]);
        });
    }
};
