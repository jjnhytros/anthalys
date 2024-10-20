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
        Schema::create('marketplace_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->index();
            $table->foreignId('buyer_id')->nullable()->constrained('characters')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('characters')->cascadeOnDelete();
            $table->index(['buyer_id', 'seller_id']);
            $table->decimal('price', 12, 2);
            $table->timestamps();
        });

        Schema::create('marketplace_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_events');
        Schema::dropIfExists('marketplace_transactions');
    }
};
