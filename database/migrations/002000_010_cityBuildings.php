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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('map_square_id')->constrained('map_squares')->cascadeOnDelete();
            $table->string('name');  // Nome dell'edificio
            $table->string('type');  // Tipo di edificio (es. "shop", "bank", "hospital")
            $table->text('description')->nullable();  // Descrizione dell'edificio
            $table->boolean('is_main_structure')->default(false); // Se è la struttura principale della cella
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
