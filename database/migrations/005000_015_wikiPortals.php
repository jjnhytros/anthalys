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
        Schema::create('wiki_portals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->text('description');
            $table->string('cover_image')->nullable(); // Immagine di copertina
            $table->timestamps();
        });

        Schema::create('wiki_article_portal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portal_id')->constrained('wiki_portals')->cascadeOnDelete(); // Cambiato da 'portals' a 'wiki_portals'
            $table->foreignId('article_id')->constrained('wiki_articles')->cascadeOnDelete(); // Cambiato da 'articles' a 'wiki_articles'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wiki_article_portal');
        Schema::dropIfExists('wiki_portals');
    }
};
