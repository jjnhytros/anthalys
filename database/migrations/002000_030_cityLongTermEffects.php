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
        Schema::create('long_term_effects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('map_square_id')->nullable()->constrained('map_squares')->cascadeOnDelete();
            $table->foreignId('character_id')->nullable()->constrained('characters')->cascadeOnDelete();
            $table->string('effect_type');  // Tipo di effetto (es. "economic_decline", "reputation_loss")
            $table->integer('duration');  // Durata dell'effetto in giorni
            $table->integer('remaining_days');  // Giorni rimanenti per l'effetto
            $table->json('impact');  // Dati sugli impatti dell'effetto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('long_term_effects');
    }
};
