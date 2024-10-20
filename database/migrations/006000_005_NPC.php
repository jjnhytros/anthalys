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
        Schema::create('warehouse_npc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete(); // Collegamento al magazzino
            $table->string('name');        // Nome dell'NPC
            $table->string('role');        // Ruolo (es. Magazziniere, Supervisore)
            $table->string('status')->default('active'); // Stato (attivo, inattivo)
            $table->integer('skill_level')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_npc');
    }
};
