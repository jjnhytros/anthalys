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
        Schema::create('job_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_offer_id')->constrained('job_offers')->cascadeOnDelete(); // Offerta di lavoro a cui si riferisce la recensione
            $table->foreignId('reviewer_id')->constrained('characters')->cascadeOnDelete(); // Chi lascia la recensione
            $table->foreignId('reviewed_id')->constrained('characters')->cascadeOnDelete(); // Chi riceve la recensione
            $table->integer('rating')->unsigned()->default(1); // Punteggio da 1 a 12
            $table->text('review'); // Testo della recensione
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_reviews');
    }
};
