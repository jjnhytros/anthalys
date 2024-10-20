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
        Schema::create('relationship_names', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('required_existing')->nullable();
            $table->string('override')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });


        Schema::create('relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
            $table->foreignId('related_character_id')->constrained('characters')->cascadeOnDelete();
            $table->foreignId('relationship_name_id')->constrained('relationship_names')->cascadeOnDelete();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('relationship_date')->nullable();
            $table->json('long_term_effect')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate relationships between the same characters
            $table->unique(['character_id', 'related_character_id', 'relationship_name_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relationships');
        Schema::dropIfExists('relationship_names');
    }
};
