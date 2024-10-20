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
        Schema::create('trade_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_square_id')->constrained('map_squares')->cascadeOnDelete();
            $table->foreignId('to_square_id')->constrained('map_squares')->cascadeOnDelete();
            $table->string('resource_type');
            $table->integer('quantity');
            $table->integer('duration');  // Durata in giorni o mesi
            $table->enum('status', ['active', 'completed'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_agreements');
    }
};
