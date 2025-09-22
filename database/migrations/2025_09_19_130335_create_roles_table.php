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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name'); // 'individual', 'vendor', 'vendor_team_member', 'admin', 'admin_team_member'
            $table->string('display_name'); // 'Individual', 'Vendor', 'Vendor Team Member', 'Admin', 'Admin Team Member'
            $table->text('description')->nullable();
            $table->string('user_type'); // 'individual', 'vendor', 'admin'
            $table->string('level')->default('standard'); // 'standard', 'team_member', 'super_admin'
            $table->json('restrictions')->nullable(); // Additional restrictions/permissions
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false); // System roles cannot be deleted
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'name']);
            $table->index(['tenant_id', 'user_type']);
            $table->index(['tenant_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};