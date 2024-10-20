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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('characters')->cascadeOnDelete();
            $table->foreignId('opponent_id')->constrained('characters')->cascadeOnDelete();
            $table->enum('status', ['pending', 'active', 'completed', 'declined'])->default('pending');
            $table->enum('challenge_type', ['points', 'posts', 'comments', 'reactions'])->default('points');
            $table->integer('target_value');
            $table->json('progress')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('challenge_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained('challenges')->cascadeOnDelete();
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_participants');
        Schema::dropIfExists('challenges');
    }
};
