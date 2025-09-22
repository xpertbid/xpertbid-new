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
        Schema::create('kyc_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('kyc_type', ['user', 'vendor', 'property', 'vehicle', 'auction'])->default('user');
            $table->enum('status', ['pending', 'approved', 'rejected', 'under_review'])->default('pending');
            
            // Personal Information
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            
            // Vendor Specific Fields
            $table->string('business_name')->nullable();
            $table->string('ntn_number')->nullable();
            $table->string('business_registration_number')->nullable();
            $table->text('business_address')->nullable();
            $table->string('business_type')->nullable();
            $table->string('tax_number')->nullable();
            
            // Property Specific Fields
            $table->string('property_type')->nullable();
            $table->string('property_location')->nullable();
            $table->string('property_size')->nullable();
            $table->string('property_ownership')->nullable();
            $table->text('property_description')->nullable();
            
            // Vehicle Specific Fields
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_year')->nullable();
            $table->string('vehicle_vin')->nullable();
            $table->string('vehicle_registration_number')->nullable();
            
            // Auction Specific Fields
            $table->string('auction_type')->nullable();
            $table->string('item_category')->nullable();
            $table->text('item_description')->nullable();
            $table->string('item_condition')->nullable();
            $table->string('estimated_value')->nullable();
            
            // Document Storage
            $table->json('documents')->nullable(); // Store document paths and metadata
            $table->json('additional_info')->nullable(); // Store any additional custom fields
            
            // Review Information
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'kyc_type']);
            $table->index(['status', 'kyc_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_documents');
    }
};