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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('characters')->cascadeOnDelete();
            $table->foreignId('recipient_id')->constrained('characters')->cascadeOnDelete();
            $table->string('subject');
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->string('type')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_message')->default(true);
            $table->boolean('is_notification')->default(false); // Indica se il messaggio è una notifica
            $table->boolean('is_email')->default(false); // Indica se il messaggio è una email
            $table->boolean('is_archived')->default(false); // Indica se il messaggio è una email
            $table->enum('status', ['sent', 'unread', 'read', 'archived'])->default('unread');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
