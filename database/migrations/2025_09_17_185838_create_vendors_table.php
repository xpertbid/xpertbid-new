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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name');
            $table->string('business_type'); // individual, company, corporation
            $table->string('business_registration_number')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, suspended
            $table->string('tier')->default('bronze'); // bronze, silver, gold, platinum
            $table->string('store_name')->unique();
            $table->string('store_slug')->unique();
            $table->text('store_description')->nullable();
            $table->string('store_logo')->nullable();
            $table->string('store_banner')->nullable();
            $table->json('store_theme')->nullable(); // colors, fonts, layout settings
            $table->json('store_policies')->nullable(); // shipping, return, privacy policies
            $table->json('seo_settings')->nullable(); // meta tags, keywords
            $table->json('social_media')->nullable(); // social media links
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(5.00); // percentage
            $table->decimal('subscription_fee', 10, 2)->default(0);
            $table->string('subscription_plan')->default('basic');
            $table->boolean('verified')->default(false);
            $table->json('verification_documents')->nullable(); // uploaded documents
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
