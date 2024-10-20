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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
            $table->string('title');          // Titolo della missione
            $table->text('description');      // Descrizione della missione
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamp('assigned_at')->nullable();  // Data di assegnazione della missione
            $table->timestamp('completed_at')->nullable(); // Data di completamento (se completata)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};
