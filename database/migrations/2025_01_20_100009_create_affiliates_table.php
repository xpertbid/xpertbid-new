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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('affiliate_program_id');
            $table->string('affiliate_code')->unique();
            $table->string('status')->default('pending'); // pending, approved, rejected, suspended
            $table->text('application_data')->nullable(); // Store application form data
            $table->text('rejection_reason')->nullable();
            $table->decimal('total_earnings', 10, 2)->default(0);
            $table->decimal('total_paid', 10, 2)->default(0);
            $table->decimal('pending_earnings', 10, 2)->default(0);
            $table->integer('total_referrals')->default(0);
            $table->integer('total_sales')->default(0);
            $table->json('payment_methods')->nullable(); // Bank details, PayPal, etc.
            $table->json('settings')->nullable(); // Affiliate-specific settings
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('affiliate_program_id')->references('id')->on('affiliate_programs')->onDelete('cascade');
            $table->unique(['tenant_id', 'user_id', 'affiliate_program_id']);
            $table->index(['tenant_id', 'status']);
            $table->index(['affiliate_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
