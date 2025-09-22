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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Kilogram", "Liter", "Piece"
            $table->string('symbol', 10); // e.g., "kg", "L", "pcs"
            $table->string('type')->default('weight'); // weight, volume, length, area, count
            $table->decimal('conversion_factor', 10, 6)->default(1.000000); // Base unit conversion
            $table->string('base_unit', 10)->nullable(); // Base unit symbol
            $table->text('description')->nullable();
            $table->boolean('is_base_unit')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('symbol');
            $table->index('type');
            $table->index('is_base_unit');
            $table->index('status');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};