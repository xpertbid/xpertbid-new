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
        Schema::create('vendor_shipping_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('shipping_policy')->default('platform'); // platform, vendor, hybrid
            $table->boolean('free_shipping_enabled')->default(false);
            $table->decimal('free_shipping_threshold', 10, 2)->nullable();
            $table->decimal('handling_fee', 10, 2)->default(0);
            $table->json('shipping_methods')->nullable(); // Available shipping methods
            $table->json('excluded_zones')->nullable(); // Zones where vendor doesn't ship
            $table->json('custom_rates')->nullable(); // Custom shipping rates
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->unique(['tenant_id', 'vendor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_shipping_settings');
    }
};
