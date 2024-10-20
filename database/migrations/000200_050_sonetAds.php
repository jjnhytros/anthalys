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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->string('content');
            $table->decimal('cost', 24, 2);
            $table->enum('type', ['paid', 'ppv', 'ppc', 'free'])->default('paid'); // Tipologia di annuncio
            $table->unsignedBigInteger('views')->default(0); // Visualizzazioni per PPV
            $table->unsignedBigInteger('clicks')->default(0); // Click per PPC
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('active')->default(true); // Stato attivo o meno

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
