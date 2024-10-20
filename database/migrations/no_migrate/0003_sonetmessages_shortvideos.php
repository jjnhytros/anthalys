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
        Schema::create('sonet_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('characters')->cascadeOnDelete();
            $table->text('content')->nullable();
            $table->string('media_path')->nullable();
            $table->enum('media_type', ['image', 'video', 'audio'])->nullable();
            $table->timestamps();
        });

        Schema::create('short_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->string('video_path');
            $table->string('audio_path')->nullable();
            $table->string('description')->nullable();
            $table->enum('privacy', ['public', 'friends', 'private'])->default('public');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_videos');
        Schema::dropIfExists('sonet_messages');
    }
};
