<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wiki_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('wiki_categories')->nullOnDelete();
            $table->string('title', 255)->unique();
            $table->string('slug', 255)->unique();
            $table->longText('content');
            $table->longText('html_content');
            $table->string(column: 'code_language')->nullable();
            $table->boolean('render_infobox')->nullable()->default(true);
            $table->boolean('auto_infobox')->nullable()->default(false);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('character_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('character_id'); // Collega il character che esegue l'attività
            $table->unsignedBigInteger('article_id');   // Collega l'articolo coinvolto nell'attività
            $table->string('action');                   // Specifica l'azione (es. 'view', 'like')
            $table->timestamps();                       // Per tracciare quando è avvenuta l'attività

            // Definisci le chiavi esterne
            $table->foreign('character_id')->references('id')->on('characters')->cascadeOnDelete();
            $table->foreign('article_id')->references('id')->on('wiki_articles')->cascadeOnDelete();
        });

        Schema::create('character_article_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('time_spent')->default(0); // Tempo in secondi
            $table->timestamps();
        });

        Schema::create('wiki_weights', function (Blueprint $table) {
            $table->id();
            $table->string('action')->unique(); // Azioni come 'view', 'like', ecc.
            $table->float('weight');            // Peso associato all'azione
            $table->timestamps();
        });

        // Inserisci i pesi di default
        DB::table('wiki_weights')->insert([
            ['action' => 'view', 'weight' => 1],
            ['action' => 'like', 'weight' => 3],
            ['action' => 'comment', 'weight' => 2],
            ['action' => 'time_spent', 'weight' => 0.1],
        ]);

        Schema::create('wiki_article_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('wiki_articles')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('wiki_categories')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wiki_article_category');
        Schema::dropIfExists('wiki_weights');
        Schema::dropIfExists('character_article_interactions');
        Schema::dropIfExists('character_activities');
        Schema::dropIfExists('wiki_articles');
    }
};
