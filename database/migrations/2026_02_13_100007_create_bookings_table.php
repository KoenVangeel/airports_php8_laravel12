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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id')->constrained()->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('passenger_id')->constrained()->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('seatclass_id')->constrained()->onDelete('restrict')->onUpdate('restrict');
            $table->string('seat')->nullable();
            $table->boolean('checkedin')->default(false);
            $table->boolean('payed')->default(false);
            $table->boolean('boarded')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
