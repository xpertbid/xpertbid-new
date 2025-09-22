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
        // Create vehicle specifications table
        Schema::create('vehicle_specifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->string('specification_type'); // 'engine', 'performance', 'fuel', 'transmission', 'dimensions', 'safety', 'comfort', 'entertainment'
            $table->string('name');
            $table->string('value');
            $table->string('unit')->nullable(); // 'cc', 'hp', 'km/h', 'L', 'kg', etc.
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->index(['vehicle_id', 'specification_type']);
            $table->index(['specification_type', 'is_featured']);
        });

        // Create vehicle features table
        Schema::create('vehicle_features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->string('feature_type'); // 'interior', 'exterior', 'safety', 'technology', 'comfort'
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->index(['vehicle_id', 'feature_type']);
            $table->index(['feature_type', 'is_available']);
        });

        // Create vehicle images table for multiple images
        Schema::create('vehicle_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->string('image_type'); // 'exterior', 'interior', 'engine', 'dashboard', '360_view'
            $table->string('image_url');
            $table->string('thumbnail_url')->nullable();
            $table->string('alt_text')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->index(['vehicle_id', 'image_type']);
            $table->index(['image_type', 'is_featured']);
        });

        // Create vehicle documents table
        Schema::create('vehicle_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->string('document_type'); // 'registration', 'insurance', 'inspection', 'warranty', 'manual'
            $table->string('document_name');
            $table->string('document_url');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('issuing_authority')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->index(['vehicle_id', 'document_type']);
            $table->index(['document_type', 'is_verified']);
        });

        // Create vehicle history table (accidents, maintenance, etc.)
        Schema::create('vehicle_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->string('event_type'); // 'accident', 'maintenance', 'repair', 'modification', 'service'
            $table->string('title');
            $table->text('description');
            $table->date('event_date');
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('service_provider')->nullable();
            $table->string('location')->nullable();
            $table->json('metadata')->nullable(); // Additional details
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->index(['vehicle_id', 'event_type']);
            $table->index(['event_type', 'event_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_history');
        Schema::dropIfExists('vehicle_documents');
        Schema::dropIfExists('vehicle_images');
        Schema::dropIfExists('vehicle_features');
        Schema::dropIfExists('vehicle_specifications');
    }
};