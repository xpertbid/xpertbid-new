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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->text('description');
            $table->string('vehicle_type'); // car, motorcycle, truck, bus, etc.
            $table->string('listing_type'); // sale, rent, both
            $table->string('make'); // Toyota, Honda, BMW, etc.
            $table->string('model');
            $table->integer('year');
            $table->string('variant')->nullable();
            $table->string('body_type')->nullable(); // sedan, SUV, hatchback, etc.
            $table->string('fuel_type')->nullable(); // petrol, diesel, electric, hybrid
            $table->string('transmission')->nullable(); // manual, automatic, CVT
            $table->integer('mileage')->nullable();
            $table->string('mileage_unit')->default('km'); // km, miles
            $table->string('color')->nullable();
            $table->integer('doors')->nullable();
            $table->integer('seats')->nullable();
            $table->decimal('engine_size', 5, 2)->nullable(); // in liters
            $table->string('engine_power')->nullable(); // horsepower
            $table->decimal('price', 12, 2);
            $table->decimal('rent_price', 12, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('condition')->default('used'); // new, used, certified
            $table->string('vehicle_status')->default('available'); // available, sold, rented, pending
            $table->string('vin_number')->nullable();
            $table->string('registration_number')->nullable();
            $table->date('registration_date')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->json('features')->nullable(); // AC, power steering, etc.
            $table->json('images')->nullable();
            $table->json('documents')->nullable(); // registration, insurance, etc.
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->decimal('commission_rate', 5, 2)->default(2.50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
