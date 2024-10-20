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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id')->nullable(); // Proprietario attuale
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->foreign('owner_id')->references('id')->on('characters')->nullOnDelete();
            $table->string('address');
            $table->integer('size');
            $table->integer('rooms');
            $table->decimal('value', 12, 2);
            $table->date('bills_due')->nullable();
            $table->decimal('price', 12, 2)->nullable(); // Prezzo di acquisto
            $table->decimal('rent_price', 12, 2)->nullable(); // Prezzo di affitto
            $table->enum('status', ['available', 'sold', 'rented'])->default('available'); // Stato della proprietà
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
