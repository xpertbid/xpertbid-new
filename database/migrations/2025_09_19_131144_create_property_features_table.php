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
        Schema::create('property_features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->string('feature_type'); // 'amenity', 'facility', 'nearby', '360_view', 'other'
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // FontAwesome icon class
            $table->string('image')->nullable(); // Feature image
            $table->json('metadata')->nullable(); // Additional data (distance, rating, etc.)
            $table->boolean('is_available')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->index(['property_id', 'feature_type']);
            $table->index(['feature_type', 'is_available']);
        });

        // Create property amenities table for predefined amenities
        Schema::create('property_amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('category'); // 'interior', 'exterior', 'security', 'utilities', 'recreation'
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['category', 'is_active']);
        });

        // Create property facilities table for predefined facilities
        Schema::create('property_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('category'); // 'transportation', 'education', 'healthcare', 'shopping', 'entertainment'
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['category', 'is_active']);
        });

        // Create property nearby places table
        Schema::create('property_nearby', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->string('place_name');
            $table->string('place_type'); // 'school', 'hospital', 'mall', 'restaurant', 'park', 'station'
            $table->decimal('distance', 8, 2); // Distance in km
            $table->string('distance_unit')->default('km');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->decimal('rating', 3, 2)->nullable(); // 0.00 to 5.00
            $table->json('metadata')->nullable(); // Additional info
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->index(['property_id', 'place_type']);
            $table->index(['place_type', 'distance']);
        });

        // Create property 360 views table
        Schema::create('property_360_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('view_type'); // 'interior', 'exterior', 'garden', 'balcony', 'rooftop'
            $table->string('media_type'); // 'image', 'video', '360_image', '360_video'
            $table->string('media_url');
            $table->string('thumbnail_url')->nullable();
            $table->json('coordinates')->nullable(); // For 360 views
            $table->integer('duration')->nullable(); // For videos (in seconds)
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->index(['property_id', 'view_type']);
            $table->index(['media_type', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_360_views');
        Schema::dropIfExists('property_nearby');
        Schema::dropIfExists('property_facilities');
        Schema::dropIfExists('property_amenities');
        Schema::dropIfExists('property_features');
    }
};