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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
            $table->decimal('amount', 24, 2);
            $table->string('types'); // Tipi di investimenti (stringa con valori separati da virgole)
            $table->decimal('current_value', 12, 2); // Valore attuale dell'investimento
            $table->decimal('return_rate', 5, 2)->default(0.00); // Tasso di rendimento
            $table->integer('duration');
            $table->enum('status', ['active', 'completed', 'failed'])->default('active');
            $table->timestamp('stipulated_at'); // Data dell'investimento
            $table->timestamp('completed_at')->nullable(); // Data di completamento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
