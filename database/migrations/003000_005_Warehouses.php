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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->integer('depth'); // Profondità del magazzino in metri
            $table->integer('capacity'); // Capacità massima in unità di prodotto
            $table->integer('current_stock')->default(0); // Quantità attuale di merci
            $table->integer('automation_level')->default(0); // Livello di automazione (0-100%)
            $table->integer('security_level')->default(0); // Livello di sicurezza (0-100%)
            $table->float('energy_consumption')->default(0); // Consumo energetico
            $table->string('location')->nullable(); // Posizione descrittiva o coordinate
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
