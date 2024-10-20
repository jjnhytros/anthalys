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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->index()->constrained('characters')->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->cascadeOnDelete();
            $table->string('name')->index();
            $table->string('type')->index();
            $table->unique(['name', 'owner_id']);
            $table->string('image')->nullable();
            $table->decimal('price', 12, 2); // Prezzo in Athel
            $table->text('description')->nullable();
            $table->integer('demand')->default(50);
            $table->decimal('base_price', 12, 2)->default(value: 0.01);
            $table->boolean('is_craftable')->default(false); // Se l'oggetto è craftabile
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
