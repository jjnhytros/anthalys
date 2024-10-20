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
        Schema::create('event_logs_archive', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->nullable()->constrained('characters')->cascadeOnDelete();
            $table->string('event_type')->nullable();  // Tipo di evento (es. health_crisis, promotion, robbery)
            $table->json('character_attributes')->nullable();  // Stato degli attributi del personaggio al momento dell'evento
            $table->json('event_context')->nullable();  // Contesto (posizione, stato della città, ecc.)
            $table->string('category')->nullable();  // Categoria dell'evento (es. 'construction', 'urban_development')
            $table->json('details')->nullable();  // Dettagli specifici dell'evento (tipo di costruzione, iniziatore, ecc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_logs');
    }
};
