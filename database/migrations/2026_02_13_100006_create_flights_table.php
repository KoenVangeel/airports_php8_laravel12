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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->dateTime('etd');
            $table->dateTime('eta');
            $table->foreignId('from_airport_id');
            $table->foreignId('to_airport_id');
            $table->foreignId('carrier_id');
            $table->foreignId('flightstatus_id');
            $table->string('gate');
            $table->boolean('boarding')->default(false);
            $table->double('price');
            $table->timestamps();

            $table->foreign('from_airport_id')->references('id')->on('airports')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('to_airport_id')->references('id')->on('airports')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('carrier_id')->references('id')->on('carriers')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('flightstatus_id')->references('id')->on('flightstatuses')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
