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
        Schema::create('wiki_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->timestamps();
        });

        Schema::create('wiki_article_tag', function (Blueprint $table) {
            $table->foreignId('wiki_article_id')->constrained('wiki_articles')->cascadeOnDelete();
            $table->foreignId('wiki_tag_id')->constrained('wiki_tags')->cascadeOnDelete();
            $table->primary(['wiki_article_id', 'wiki_tag_id']); // Chiave primaria composta
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wiki_article_tag');
        Schema::dropIfExists('wiki_tags');
    }
};
