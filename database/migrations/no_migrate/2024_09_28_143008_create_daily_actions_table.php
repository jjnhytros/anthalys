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
        Schema::create('daily_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->string('action_type');
            $table->integer('energy_cost')->default(0); // Costo in energia
            $table->integer('time_cost')->default(0); // Costo in tempo
            $table->integer('happiness_effect')->default(0); // Effetto sulla felicità
            $table->integer('hunger_effect')->default(0); // Effetto sulla fame
            $table->integer('cleanliness_effect')->default(0); // Effetto sulla pulizia
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_actions');
    }
};
