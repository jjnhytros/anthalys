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
        Schema::create('wiki_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('old_slug', 255)->unique();
            $table->string('new_slug', 255)->unique();
            $table->enum('type', ['permanent', 'temporary'])->default('permanent');
            $table->unsignedInteger('redirect_count')->default(0);
            $table->timestamps();
        });

        Schema::create('wiki_redirect_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('redirect_id')->constrained('wiki_redirects')->cascadeOnDelete();
            $table->unsignedBigInteger('character_id')->nullable();
            $table->ipAddress('character_ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wiki_redirect_logs');
        Schema::dropIfExists('wiki_redirects');
    }
};
