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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('auction_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type'); // order, auction, subscription
            $table->decimal('order_amount', 10, 2);
            $table->decimal('commission_rate', 5, 2); // percentage
            $table->decimal('commission_amount', 10, 2);
            $table->string('status')->default('pending'); // pending, paid, cancelled
            $table->string('currency', 3)->default('USD');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
