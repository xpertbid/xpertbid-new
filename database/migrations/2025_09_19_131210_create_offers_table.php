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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id'); // User making the offer
            $table->string('offerable_type'); // 'App\Models\Property' or 'App\Models\Vehicle'
            $table->unsignedBigInteger('offerable_id'); // Property or Vehicle ID
            $table->decimal('offer_amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->text('message')->nullable(); // Offer message/notes
            $table->string('status')->default('pending'); // 'pending', 'accepted', 'rejected', 'expired', 'withdrawn'
            $table->json('personal_details')->nullable(); // Collected personal information
            $table->json('additional_info')->nullable(); // Additional information collected
            $table->timestamp('expires_at')->nullable(); // Offer expiration
            $table->timestamp('responded_at')->nullable(); // When offer was responded to
            $table->unsignedBigInteger('responded_by')->nullable(); // Who responded to the offer
            $table->text('response_message')->nullable(); // Response message from owner
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('responded_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['offerable_type', 'offerable_id']);
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('expires_at');
        });

        // Create offer attachments table for documents/images
        Schema::create('offer_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->string('attachment_type'); // 'document', 'image', 'video', 'other'
            $table->string('file_name');
            $table->string('file_url');
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->index(['offer_id', 'attachment_type']);
        });

        // Create offer communications table for messages between parties
        Schema::create('offer_communications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('user_id'); // Who sent the message
            $table->text('message');
            $table->string('message_type')->default('text'); // 'text', 'image', 'document', 'system'
            $table->json('attachments')->nullable(); // File attachments
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['offer_id', 'created_at']);
            $table->index(['user_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_communications');
        Schema::dropIfExists('offer_attachments');
        Schema::dropIfExists('offers');
    }
};