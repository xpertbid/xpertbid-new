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
        Schema::create('carriers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name'); // DHL, FedEx, UPS, etc.
            $table->string('code')->unique(); // dhl, fedex, ups
            $table->string('logo_url')->nullable();
            $table->text('description')->nullable();
            $table->json('api_settings')->nullable(); // API credentials and settings
            $table->json('supported_countries')->nullable();
            $table->json('supported_services')->nullable(); // Express, Standard, Economy
            $table->boolean('is_active')->default(true);
            $table->boolean('is_integrated')->default(false); // Has API integration
            $table->decimal('base_rate', 10, 2)->default(0);
            $table->json('rate_calculation')->nullable(); // Rate calculation rules
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carriers');
    }
};
