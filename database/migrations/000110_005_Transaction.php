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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->index()->nullable()->constrained('characters')->cascadeOnDelete();
            $table->foreignId('recipient_id')->index()->nullable()->constrained('characters')->cascadeOnDelete();
            $table->decimal('amount', 24, 2);
            // main_type: income/expense per la gestione del conto corrente
            // type: sottogruppo di main_type
            $table->enum('master_type', ['income', 'expense'])->default('other');
            $table->enum('type', ['donation', 'subscription', 'sale', 'other'])->default('other');
            $table->enum('status', ['pending', 'confirmed', 'approved', 'completed'])->index()->default('pending'); // Stato della transazione
            $table->decimal('commission_amount', 24, 2)->nullable(); // Commissione applicata alla transazione
            $table->text('description')->nullable();
            $table->boolean('notification_sent')->default(false);
            $table->timestamps();
        });

        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('athel_transactions')->cascadeOnDelete(); // Collegamento alla transazione
            $table->decimal('government_share', 24, 2); // 24% della commissione al governo (Character 2)
            $table->decimal('bank_share', 24, 2); // 76% della commissione in banca (Character 4)
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('athel_transactions')->cascadeOnDelete(); // Collegamento alla transazione
            $table->text('message')->nullable(); // Messaggio personalizzato
            $table->foreignId('incentive_id')->nullable()->constrained('incentives')->nullOnDelete(); // Incentivo o badge assegnato
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
        Schema::dropIfExists('commissions');
        Schema::dropIfExists('transactions');
    }
};
