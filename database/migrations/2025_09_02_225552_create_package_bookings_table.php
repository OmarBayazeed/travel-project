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
        Schema::create('package_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('client_id');
            $table->string('full_name')->nullable();
            $table->string('phone');
            $table->string('nationality');
            $table->string('special_requests')->nullable();
            $table->integer('number_of_guests')->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->enum('currency', ['EGP', 'USD'])->default('USD');
            $table->string('payment_status')->default('pending');
            $table->timestamps();
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_bookings');
    }
};
