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
        Schema::create('character_missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();  // Riferimento al personaggio
            $table->foreignId('work_mission_id')->constrained()->cascadeOnDelete();  // Riferimento alla missione di lavoro
            $table->integer('progress')->default(0);  // Progresso della missione
            $table->boolean('completed')->default(false);  // Stato della missione
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_missions');
    }
};
