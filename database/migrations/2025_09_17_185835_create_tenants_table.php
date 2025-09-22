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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique();
            $table->string('subdomain')->unique();
            $table->string('custom_domain')->nullable();
            $table->string('database_name')->unique();
            $table->string('status')->default('active'); // active, suspended, pending
            $table->string('subscription_plan')->default('basic'); // basic, premium, enterprise
            $table->json('settings')->nullable(); // tenant-specific configurations
            $table->json('limits')->nullable(); // resource limits
            $table->decimal('monthly_fee', 10, 2)->default(0);
            $table->integer('vendor_limit')->default(10);
            $table->integer('product_limit')->default(1000);
            $table->integer('storage_limit_mb')->default(1024);
            $table->integer('bandwidth_limit_mb')->default(10240);
            $table->boolean('white_label')->default(false);
            $table->string('logo_url')->nullable();
            $table->string('primary_color')->default('#007bff');
            $table->string('secondary_color')->default('#6c757d');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
