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
        Schema::create('professions', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Nome della professione
            $table->text('description')->nullable();  // Descrizione della professione
            $table->decimal('salary', 12, 2);  // Stipendio base per il lavoro
            $table->string('required_skill')->nullable(); // Abilità richiesta
            $table->timestamps();
        });

        Schema::table('characters', function (Blueprint $table) {
            $table->foreignId('profession_id')->nullable()->constrained('professions')->nullOnDelete()->after('region_id'); // Collegamento con la professione
            $table->integer('work_experience')->default(0)->after('trading_skill');  // Esperienza lavorativa accumulata
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professions');
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn('profession_id');
            $table->dropColumn('work_experience');
        });
    }
};
