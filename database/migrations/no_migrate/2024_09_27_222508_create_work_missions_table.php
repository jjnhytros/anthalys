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
        Schema::create('work_missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profession_id')->constrained()->cascadeOnDelete();  // Riferimento alla professione
            $table->string('objective');  // Obiettivo della missione (es. "Produrre 10 oggetti")
            $table->integer('target');  // Quantità necessaria per completare la missione
            $table->string('reward_type');  // Tipo di ricompensa (es. "denaro", "risorse", "esperienza", "competenze")
            $table->integer('reward_amount');  // Quantità della ricompensa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_missions');
    }
};
