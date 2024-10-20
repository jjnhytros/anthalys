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
        Schema::create('bus_lines', function (Blueprint $table) {
            $table->id();
            $table->string('line_name');  // Nome della linea (es. "Linea 1", "Linea 2")
            $table->json('stops');  // Lista di fermate nella griglia, memorizzate come coordinate
            $table->json('path')->nullable();  // Percorso della linea come array di coordinate
            $table->timestamps();
        });
        Schema::create('tram_lines', function (Blueprint $table) {
            $table->id();
            $table->string('line_name');  // Nome della linea (es. "Linea 1", "Linea 2")
            $table->json('stops');  // Lista di fermate nella griglia, memorizzate come coordinate
            $table->json('path')->nullable();  // Percorso della linea come array di coordinate
            $table->timestamps();
        });
        Schema::create('metro_lines', function (Blueprint $table) {
            $table->id();
            $table->string('line_name');  // Nome della linea (es. "Metro A", "Metro B")
            $table->json('stops');  // Lista di fermate nella griglia, memorizzate come coordinate
            $table->json('path')->nullable();  // Percorso della linea come array di coordinate
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metro_lines');
        Schema::dropIfExists('bus_lines');
    }
};
