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
        Schema::create('warehouse_matrices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('level_id'); // Collegamento al livello del magazzino
            $table->integer('x'); // Coordinata X della cella
            $table->integer('y'); // Coordinata Y della cella
            $table->jsonb('value'); // Valore della cella (ad esempio, item e quantità)
            $table->timestamps();

            // Relazioni con le altre tabelle
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->cascadeOnDelete();
            $table->foreign('level_id')->references('id')->on('warehouse_levels')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_matrix');
    }
};
