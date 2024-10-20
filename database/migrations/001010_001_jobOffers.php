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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete(); // Chi pubblica l'offerta
            $table->string('title'); // Titolo del lavoro
            $table->text('description'); // Descrizione dell'offerta di lavoro
            $table->string('location'); // Luogo di lavoro
            $table->decimal('salary', 12, 2); // Salario offerto
            $table->enum('job_type', ['freelance', 'part-time', 'full_time']); // Tipo di lavoro: freelance o assunzione a lungo termine
            $table->json('required_skills'); // Competenze richieste (archiviato come JSON)
            $table->boolean('negotiable')->default(false); // Se è possibile negoziare le condizioni
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
