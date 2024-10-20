<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('npc_reputations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('npc_id');
            $table->foreign('npc_id')->references('id')->on('characters')->cascadeOnDelete();
            $table->integer('reputation_score')->default(0);
            $table->integer('tasks_completed')->default(0);
            $table->integer('interactions')->default(0);
            $table->integer('feedback_received')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('npc_reputations');
    }
};
