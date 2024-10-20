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
        Schema::create('anthal_regions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome della regione
            $table->text('description')->nullable(); // Descrizione opzionale della regione
            $table->integer('distance')->default(100);
            $table->integer('travel_cost')->default(10);
            $table->decimal('x_coordinate', 12, 2)->nullable();
            $table->decimal('y_coordinate', 12, 2)->nullable();
            $table->string('weather')->nullable();
            $table->boolean('special_bonus')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
