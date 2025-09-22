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
        Schema::create('vehicle_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->string('locale', 10); // en, es, fr, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('features')->nullable(); // JSON array of features
            $table->text('specifications')->nullable(); // JSON array of specifications
            $table->text('warranty_info')->nullable();
            $table->text('service_history')->nullable();
            $table->text('accident_history')->nullable();
            $table->text('maintenance_records')->nullable();
            $table->timestamps();
            
            $table->unique(['vehicle_id', 'locale']);
            $table->index(['locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_translations');
    }
};