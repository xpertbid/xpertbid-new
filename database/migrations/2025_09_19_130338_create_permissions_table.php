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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 'view_auctions', 'manage_auctions', 'approve_vendors', etc.
            $table->string('display_name'); // 'View Auctions', 'Manage Auctions', 'Approve Vendors'
            $table->text('description')->nullable();
            $table->string('module'); // 'auctions', 'vendors', 'users', 'products', 'kyc', 'payments', etc.
            $table->string('action'); // 'view', 'create', 'edit', 'delete', 'approve', 'reject', etc.
            $table->string('resource')->nullable(); // 'auction', 'vendor', 'user', 'product', etc.
            $table->boolean('is_system')->default(false); // System permissions cannot be deleted
            $table->timestamps();

            $table->unique('name');
            $table->index(['module', 'action']);
            $table->index('resource');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};