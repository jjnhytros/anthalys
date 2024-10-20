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
        Schema::create('negotiations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_offer_id')->constrained('job_offers')->cascadeOnDelete(); // Relazione con l'offerta di lavoro
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete(); // Chi fa l'offerta
            $table->decimal('salary_offered', 10, 2); // Salario offerto durante la negoziazione
            $table->text('message')->nullable(); // Messaggio opzionale inviato con l'offerta
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending'); // Stato dell'offerta
            $table->timestamps(); // Timestamp di creazione e aggiornamento
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negotiations');
    }
};
