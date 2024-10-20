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
        Schema::create('days', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
        });

        Schema::create('months', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->decimal('multiplier', 5, 2)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('months');
        Schema::dropIfExists('days');
    }
};
