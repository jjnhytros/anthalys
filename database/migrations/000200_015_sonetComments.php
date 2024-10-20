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
        Schema::create('sonet_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sonet_post_id')->constrained('sonet_posts')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('sonet_comments')->nullOnDelete();
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
            $table->string('visibility')->default('public');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
