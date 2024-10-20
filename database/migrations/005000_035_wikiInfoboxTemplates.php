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
        Schema::create('wiki_infobox_templates', function (Blueprint $table) {
            $table->id();
            $table->string('type', 255)->unique();  // Tipo di infobox (es. city, person, etc.)
            $table->text('content');  // Contenuto HTML del template
            $table->json('optional_fields')->nullable(); // Campi opzionali

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wiki_infobox_templates');
    }
};
