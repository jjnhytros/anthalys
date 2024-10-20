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
        Schema::create('reputations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
            $table->foreignId('rated_character_id')->constrained('characters')->cascadeOnDelete();
            $table->integer('rating')->comment('Valutazione, ad esempio da 1 a 5');
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->unique(['character_id', 'rated_character_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reputations');
    }
};
