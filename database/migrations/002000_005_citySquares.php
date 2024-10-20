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
        Schema::table('characters', function (Blueprint $table) {
            $table->foreignId('map_square_id')->nullable()->after('user_id')->constrained('map_squares')->nullOnDelete();
        });

        Schema::create('map_squares', function (Blueprint $table) {
            $table->id();
            $table->integer('x_coordinate');  // Coordinata X nella griglia 36x36
            $table->integer('y_coordinate');  // Coordinata Y nella griglia 36x36
            $table->string('sector_name');    // Nome del quartiere o settore
            $table->string('type')->default('residential');  // Tipo di area (residenziale, commerciale, ecc.)
            $table->string('description')->nullable(); // Descrizione del quadrato della mappa
            $table->integer('development_level')->default(1);  // Livello di sviluppo del quartiere (1-5)
            $table->integer('building_limit')->default(10);  // Limite di costruzioni nel quartiere
            $table->integer('current_buildings')->default(0);  // Numero di costruzioni attuali
            $table->integer('socio_economic_status')->default(50); // Valore tra 0 e 100 per la condizione economica del quartiere
            $table->integer('event_impact')->default(0); // Impatto degli eventi recenti (+ o -)
            $table->string('population_density')->nullable();
            $table->integer('crime_rate')->default(0);  // Valore tra 0 e 100 per indicare il tasso di criminalità
            $table->integer('unemployment_rate')->default(0);  // Percentuale di disoccupazione
            $table->integer('available_budget')->default(100000);  // Budget disponibile per nuove costruzioni
            $table->integer('economic_growth')->default(50); // Valore tra 0 e 100
            $table->string('public_transport')->default('media'); // Accessibilità ai trasporti pubblici
            $table->string('essential_services_proximity')->default('bassa'); // Prossimità ai servizi essenziali
            $table->string('pollution_level')->default('bassa'); // Livello di inquinamento
            $table->string('housing_demand')->default('media'); // Domanda abitativa
            $table->string('infrastructure_quality')->default('media'); // Qualità delle infrastrutture

            $table->timestamps();
        });

        Schema::create('sub_cells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('map_square_id')->constrained()->cascadeOnDelete();
            $table->integer('x');  // Coordinata X nella griglia NxN
            $table->integer('y');  // Coordinata Y nella griglia NxN
            $table->string('type');  // Tipo di struttura (residential, commercial, road, ecc.)
            $table->text('description')->nullable();  // Descrizione della sottocella
            $table->boolean('has_bus_stop')->nullable()->default(false); // Determina se la cella ha una fermata bus
            $table->boolean('has_tram_stop')->nullable()->default(false); // Determina se la cella ha una fermata tram
            $table->boolean('has_metro_stop')->nullable()->default(false); // Determina se la cella ha una fermata metro
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn('map_square_id');
        });
        Schema::dropIfExists('map_squares');
    }
};
