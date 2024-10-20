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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('difficulty_level')->default('beginner');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->foreign('lesson_id')->references('id')->on('lessons')->cascadeOnDelete();
            $table->string('type');
            $table->text('question');
            $table->json('options')->nullable();
            $table->string('correct_answer');
            $table->timestamps();
        });
    }


    // Schema::create('questions', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
    //     $table->text('question_text');
    //     $table->timestamps();
    // });

    // Schema::create('answers', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('question_id')->constrained()->cascadeOnDelete();
    //     $table->text('answer_text');
    //     $table->boolean('is_correct');
    //     $table->timestamps();
    // });

    // Schema::create('progress', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('character_id')->constrained()->cascadeOnDelete();
    //     $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
    //     $table->integer('score');
    //     $table->boolean('completed')->default(false);
    //     $table->timestamps();
    // });

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('progress');
        // Schema::dropIfExists('answers');
        // Schema::dropIfExists('questions');
        Schema::dropIfExists('exercises');
        Schema::dropIfExists('lessons');
    }
};
