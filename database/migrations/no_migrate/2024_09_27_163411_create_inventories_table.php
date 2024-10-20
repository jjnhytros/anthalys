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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete(); // Collega l'inventario alla tabella items
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete(); // Collega la risorsa
            $table->enum('type', ['material', 'food', 'equipment'])->default('material');
            $table->string('location')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('weight', 8, 2)->default(0);
            $table->integer('freshness')->default(100); // Freshness percentage (from 100 to 0)
            $table->date('expiration_date')->nullable(); // Expiration date of the item
            $table->decimal('value', 12, 2)->default(0); // Valore dell'oggetto, potrebbe essere differente dal valore base
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
