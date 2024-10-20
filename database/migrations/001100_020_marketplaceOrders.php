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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();  // Chi ha fatto l'ordine
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();  // Prodotto ordinato
            $table->integer('quantity');  // Quantità ordinata
            $table->decimal('total_price', 12, 2);  // Prezzo totale dell'ordine
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');  // Stato dell'ordine
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
