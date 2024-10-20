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
        Schema::create('character_markets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete(); // Collegamento al personaggio
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete(); // Collegamento alla risorsa
            $table->decimal('price', 12, 2); // Prezzo della risorsa
            $table->integer('quantity'); // Quantità disponibile
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_markets');
    }
};
