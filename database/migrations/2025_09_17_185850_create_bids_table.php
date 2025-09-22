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
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('max_amount', 10, 2)->nullable(); // for proxy bidding
            $table->boolean('is_proxy_bid')->default(false);
            $table->boolean('is_winning_bid')->default(false);
            $table->boolean('is_outbid')->default(false);
            $table->string('status')->default('active'); // active, outbid, winning, cancelled
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('bid_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
