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
        Schema::create('crafting_recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id'); // Oggetto creato
            $table->json('resources_required'); // Risorse necessarie per creare l'oggetto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crafting_recipes');
    }
};
