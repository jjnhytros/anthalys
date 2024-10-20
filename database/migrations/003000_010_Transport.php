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
        Schema::create('drones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_warehouse_id')->constrained('warehouses')->cascadeOnDelete(); // Magazzino assegnato
            $table->string('type'); // Es. "delivery", "surveillance", etc.
            $table->integer('battery_life'); // Durata della batteria in ore
            $table->integer('capacity'); // Capacità di carico del drone
            $table->string('status'); // Stato del drone (es. "active", "charging", "repairing")
            $table->timestamps();
        });

        Schema::create('robots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_warehouse_id')->constrained('warehouses')->cascadeOnDelete(); // Magazzino assegnato
            $table->string('type'); // Es. "storage", "maintenance", etc.
            $table->integer('battery_life'); // Durata della batteria in ore
            $table->integer('capacity'); // Capacità di carico del robot
            $table->string('status'); // Stato del robot (es. "active", "repairing")
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robots');
        Schema::dropIfExists('drones');
    }
};
