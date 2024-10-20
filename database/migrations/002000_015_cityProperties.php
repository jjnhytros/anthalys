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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->nullable()->constrained('characters')->cascadeOnDelete();
            $table->foreignId('map_square_id')->constrained('map_squares')->cascadeOnDelete();
            $table->string('type'); // Tipo di proprietà (residenziale, commerciale)
            $table->decimal('price', 24, 2); // Prezzo corrente
            $table->integer('families_count')->nullable(); // Numero di famiglie in caso di residenziale
            $table->enum('status', ['owned', 'rented'])->default('owned');  // Se la proprietà è posseduta o affittata
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
