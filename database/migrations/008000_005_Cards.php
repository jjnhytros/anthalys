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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->enum('suit', ['hearts', 'diamonds', 'clubs', 'spades', 'moons', 'stars']);
            $table->enum('color', ['red', 'black', 'yellow']);
            $table->string('value'); // can be 2-12, H, J, W, K, T, A
            $table->boolean('is_joker')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
