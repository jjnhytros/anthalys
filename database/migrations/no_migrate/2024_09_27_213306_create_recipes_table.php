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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('effects'); // Effetti della ricetta (es. energia, felicità)
            $table->timestamps();
        });
        Schema::create('ingredient_recipe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // Quantità dell'ingrediente necessario
            $table->timestamps();
        });
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('objekt_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
        });
        Schema::create('recipe_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->foreignId('resource_id')->constrained('resources')->onDelete('cascade');
            $table->integer('quantity'); // Quantità necessaria di questa risorsa per la ricetta
            $table->timestamps();
        });
        Schema::create('recipe_markets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 12, 2);
            $table->integer('availability')->default(10); // Disponibilità della ricetta
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recipe_markets');
        Schema::dropIfExists('recipe_resources');
        Schema::dropIfExists('recipe_ingredients');
        Schema::dropIfExists('ingredient_recipe');
        Schema::dropIfExists('recipes');
    }
};
