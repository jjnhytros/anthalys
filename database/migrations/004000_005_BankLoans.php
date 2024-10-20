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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 24, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->decimal('total_amount', 24, 2);
            $table->decimal('balance', 24, 2);
            $table->integer('term')->comment('Durata del prestito in mesi');
            $table->enum('status', ['pending', 'active', 'completed', 'default'])->default('pending');
            $table->date('next_payment_due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->decimal('payment_amount', 24, 2);
            $table->date('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
        Schema::dropIfExists('loans');
    }
};
