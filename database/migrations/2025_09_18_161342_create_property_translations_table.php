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
        Schema::create('property_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('locale', 10); // en, es, fr, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('features')->nullable(); // JSON array of features
            $table->text('amenities')->nullable(); // JSON array of amenities
            $table->text('neighborhood_info')->nullable();
            $table->text('school_district')->nullable();
            $table->text('transportation')->nullable();
            $table->text('nearby_attractions')->nullable();
            $table->timestamps();
            
            $table->unique(['property_id', 'locale']);
            $table->index(['locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_translations');
    }
};