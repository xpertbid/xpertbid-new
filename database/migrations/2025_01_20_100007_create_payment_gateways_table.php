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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name'); // PayPal, Stripe, Razorpay, etc.
            $table->string('code')->unique(); // paypal, stripe, razorpay
            $table->string('type')->default('online'); // online, offline, wallet
            $table->text('description')->nullable();
            $table->string('logo_url')->nullable();
            $table->json('settings')->nullable(); // API keys, webhooks, etc.
            $table->json('supported_currencies')->nullable();
            $table->json('supported_countries')->nullable();
            $table->decimal('transaction_fee', 5, 4)->default(0); // Fee percentage
            $table->decimal('fixed_fee', 10, 2)->default(0); // Fixed fee
            $table->boolean('is_active')->default(true);
            $table->boolean('is_test_mode')->default(true);
            $table->integer('sort_order')->default(0);
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
        Schema::dropIfExists('payment_gateways');
    }
};
