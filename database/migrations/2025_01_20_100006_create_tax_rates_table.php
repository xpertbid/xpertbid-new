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
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('tax_class_id');
            $table->string('name');
            $table->string('country_code', 2); // ISO country code
            $table->string('state_code', 10)->nullable(); // State/province code
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->decimal('rate', 5, 4); // Tax rate (e.g., 0.20 for 20%)
            $table->string('tax_type')->default('vat'); // vat, sales_tax, gst, etc.
            $table->boolean('is_compound')->default(false); // Compound tax
            $table->boolean('is_shipping_taxable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(1);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('tax_class_id')->references('id')->on('tax_classes')->onDelete('cascade');
            $table->index(['tenant_id', 'country_code', 'state_code']);
            $table->index(['tenant_id', 'tax_class_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_rates');
    }
};
