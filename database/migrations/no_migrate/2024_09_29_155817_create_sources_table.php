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
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Titolo della fonte
            $table->text('description')->nullable(); // Descrizione della fonte
            $table->string('author')->nullable(); // Autore della fonte
            $table->string('type'); // Tipo di fonte (es: libro, articolo, ecc.)
            $table->date('publication_date')->nullable(); // Data di pubblicazione
            $table->string('url')->nullable(); // URL, se è una fonte online
            $table->timestamps();
        });

        Schema::create('source_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome della categoria
            $table->text('description')->nullable(); // Descrizione della categoria
            $table->timestamps();
        });

        Schema::create('source_category_source', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->constrained('sources')->cascadeOnDelete();
            $table->foreignId('source_category_id')->constrained('source_categories')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
