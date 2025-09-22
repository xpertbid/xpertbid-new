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
        Schema::create('auction_watchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Notification Preferences
            $table->boolean('notify_on_outbid')->default(true);
            $table->boolean('notify_on_ending')->default(true);
            $table->boolean('notify_on_price_change')->default(false);
            $table->integer('notify_minutes_before_end')->default(60);
            
            $table->timestamps();
            
            // Unique constraint and indexes
            $table->unique(['auction_id', 'user_id'], 'unique_watcher');
            $table->index('auction_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_watchers');
    }
};
