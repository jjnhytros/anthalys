<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ids', function (Blueprint $table) {
            $table->id();
            $table->decimal('value', 5, 2)->default(0);
            $table->timestamps();
        });

        // Inserisci un valore iniziale
        DB::table('ids')->insert([
            'value' => 1.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gdps');
    }
};
