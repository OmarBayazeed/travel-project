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
        Schema::create('cruises', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('ship_name')->nullable();
            $table->float('stars')->nullable();
            $table->string('duration')->nullable();
            $table->string('route')->nullable();
            $table->string('departure_day')->nullable();
            $table->string('departure_city')->nullable();
            $table->string('arrival_city')->nullable();
            $table->string('cabin_types')->nullable();
            $table->decimal('price_per_person', 10, 2)->default(0);
            $table->enum('currency', ['EGP', 'USD'])->default('USD');
            $table->string('meals')->nullable();
            $table->string('facilities')->nullable();
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
        Schema::dropIfExists('cruises');
    }
};
