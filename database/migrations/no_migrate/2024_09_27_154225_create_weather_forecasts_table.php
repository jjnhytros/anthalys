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
        Schema::create('weather_forecasts', function (Blueprint $table) {
            $table->id();
            $table->integer('day'); // Giorno della previsione
            $table->integer('month'); // Mese della previsione
            $table->string('weather_type'); // Tipo di meteo previsto
            $table->integer('accuracy')->default(96); // Percentuale di accuratezza predefinita al 90%
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_forecasts');
    }
};
