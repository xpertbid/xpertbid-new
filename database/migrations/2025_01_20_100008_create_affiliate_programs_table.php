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
        Schema::create('affiliate_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('general'); // general, product_specific, category_specific
            $table->decimal('commission_rate', 5, 4)->default(0); // Commission percentage
            $table->decimal('fixed_commission', 10, 2)->default(0); // Fixed commission amount
            $table->string('commission_type')->default('percentage'); // percentage, fixed, hybrid
            $table->decimal('minimum_payout', 10, 2)->default(0);
            $table->integer('cookie_duration')->default(30); // Days
            $table->json('terms_conditions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_approval')->default(true);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->index(['tenant_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_programs');
    }
};
