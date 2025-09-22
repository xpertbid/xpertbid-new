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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('type')->default('english'); // english, reserve, buy_now, private
            $table->decimal('starting_price', 10, 2);
            $table->decimal('reserve_price', 10, 2)->nullable();
            $table->decimal('buy_now_price', 10, 2)->nullable();
            $table->decimal('current_bid', 10, 2)->nullable();
            $table->decimal('bid_increment', 10, 2)->default(1.00);
            $table->integer('bid_count')->default(0);
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, active, ended, cancelled
            $table->boolean('is_featured')->default(false);
            $table->boolean('auto_extend')->default(false); // anti-sniping
            $table->integer('extend_minutes')->default(5);
            $table->json('images')->nullable();
            $table->json('terms_conditions')->nullable();
            $table->string('winner_id')->nullable(); // user_id of winner
            $table->timestamp('won_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
