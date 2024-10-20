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
        Schema::create('sonet_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['public', 'private', 'invite-only'])->default('public');
            $table->foreignId('created_by')->constrained('characters')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sonet_room_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('sonet_rooms')->cascadeOnDelete();
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
            $table->enum('role', ['admin', 'moderator', 'member'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
        });

        Schema::create('sonet_room_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('sonet_rooms')->cascadeOnDelete();
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
            $table->foreignId('reply_to')->index()->nullable()->constrained('sonet_messages')->nullOnDelete();
            $table->text('message');
            $table->enum('type', ['text', 'image', 'video', 'audio'])->default('text');
            $table->string('media_url')->nullable();
            $table->boolean('edited')->default(false);
            $table->json('attachments')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sonet_room_messages');
        Schema::dropIfExists('sonet_room_members');
        Schema::dropIfExists('sonet_rooms');
    }
};
