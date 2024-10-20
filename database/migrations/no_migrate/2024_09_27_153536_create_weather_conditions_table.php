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
        Schema::create('weather_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // es. sole, pioggia, neve, ecc.
            $table->integer('probability'); // Probabilità di accadere (0-100%)
            $table->string('season'); // Primavera, Estate, Autunno, Inverno
            $table->json('effects')->nullable(); // Effetti sul personaggio o sulle azioni
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_conditions');
    }
};
