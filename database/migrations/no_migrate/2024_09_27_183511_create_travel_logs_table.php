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
        Schema::create('travel_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_region_id')->constrained('regions')->cascadeOnDelete();
            $table->foreignId('to_region_id')->constrained('regions')->cascadeOnDelete();
            $table->timestamp('departure_time');
            $table->timestamp('arrival_time')->nullable();
            $table->string('weather')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_logs');
    }
};
