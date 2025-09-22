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
        // Add listing types to properties table
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'listing_type')) {
                $table->string('listing_type')->default('buy'); // 'buy', 'offer', 'auction'
            }
            if (!Schema::hasColumn('properties', 'price')) {
                $table->decimal('price', 15, 2)->nullable(); // For buy listings
            }
            if (!Schema::hasColumn('properties', 'min_offer_price')) {
                $table->decimal('min_offer_price', 15, 2)->nullable(); // For offer listings
            }
            if (!Schema::hasColumn('properties', 'max_offer_price')) {
                $table->decimal('max_offer_price', 15, 2)->nullable(); // For offer listings
            }
            if (!Schema::hasColumn('properties', 'starting_price')) {
                $table->decimal('starting_price', 15, 2)->nullable(); // For auction listings
            }
            if (!Schema::hasColumn('properties', 'reserve_price')) {
                $table->decimal('reserve_price', 15, 2)->nullable(); // For auction listings
            }
            if (!Schema::hasColumn('properties', 'is_negotiable')) {
                $table->boolean('is_negotiable')->default(false); // For buy/offer listings
            }
            if (!Schema::hasColumn('properties', 'offer_requirements')) {
                $table->json('offer_requirements')->nullable(); // Requirements for offer listings
            }
            if (!Schema::hasColumn('properties', 'offer_deadline')) {
                $table->timestamp('offer_deadline')->nullable(); // Deadline for offers
            }
            if (!Schema::hasColumn('properties', 'show_price')) {
                $table->boolean('show_price')->default(true); // Whether to show price publicly
            }
        });

        // Add listing types to vehicles table
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'listing_type')) {
                $table->string('listing_type')->default('buy'); // 'buy', 'offer', 'auction'
            }
            if (!Schema::hasColumn('vehicles', 'price')) {
                $table->decimal('price', 15, 2)->nullable(); // For buy listings
            }
            if (!Schema::hasColumn('vehicles', 'min_offer_price')) {
                $table->decimal('min_offer_price', 15, 2)->nullable(); // For offer listings
            }
            if (!Schema::hasColumn('vehicles', 'max_offer_price')) {
                $table->decimal('max_offer_price', 15, 2)->nullable(); // For offer listings
            }
            if (!Schema::hasColumn('vehicles', 'starting_price')) {
                $table->decimal('starting_price', 15, 2)->nullable(); // For auction listings
            }
            if (!Schema::hasColumn('vehicles', 'reserve_price')) {
                $table->decimal('reserve_price', 15, 2)->nullable(); // For auction listings
            }
            if (!Schema::hasColumn('vehicles', 'is_negotiable')) {
                $table->boolean('is_negotiable')->default(false); // For buy/offer listings
            }
            if (!Schema::hasColumn('vehicles', 'offer_requirements')) {
                $table->json('offer_requirements')->nullable(); // Requirements for offer listings
            }
            if (!Schema::hasColumn('vehicles', 'offer_deadline')) {
                $table->timestamp('offer_deadline')->nullable(); // Deadline for offers
            }
            if (!Schema::hasColumn('vehicles', 'show_price')) {
                $table->boolean('show_price')->default(true); // Whether to show price publicly
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'listing_type',
                'price',
                'min_offer_price',
                'max_offer_price',
                'starting_price',
                'reserve_price',
                'is_negotiable',
                'offer_requirements',
                'offer_deadline',
                'show_price',
            ]);
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'listing_type',
                'price',
                'min_offer_price',
                'max_offer_price',
                'starting_price',
                'reserve_price',
                'is_negotiable',
                'offer_requirements',
                'offer_deadline',
                'show_price',
            ]);
        });
    }
};