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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->year('year')->nullable();
            $table->string('type')->nullable(); // sedan, SUV, etc.
            $table->tinyInteger('seats')->nullable();
            $table->enum('transmission', ['manual', 'automatic'])->nullable();
            $table->enum('fuel_type', ['petrol', 'diesel', 'hybrid', 'electric'])->nullable();
            $table->decimal('price_per_day', 10, 2)->default(0);
            $table->enum('currency', ['EGP', 'USD'])->default('USD');
            $table->boolean('availability')->default(true);
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_on_offer')->default(false);
            $table->decimal('offer_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
