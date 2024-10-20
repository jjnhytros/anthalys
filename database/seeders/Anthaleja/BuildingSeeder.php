<?php

namespace Database\Seeders\Anthaleja;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Anthaleja\City\Building;
use App\Models\Anthaleja\City\MapSquare;

class BuildingSeeder extends Seeder
{
    public function run()
    {
        DB::table('buildings')->truncate();

        // Recupera tutti i quadrati della mappa
        $mapSquares = MapSquare::all();

        // Definisci i tipi di edifici disponibili
        $buildingTypes = [
            'shop' => 'Negozio',
            'bank' => 'Banca',
            'residential' => 'Edificio Residenziale',
            'commercial' => 'Edificio Commerciale',
            'industrial' => 'Edificio Industriale'
        ];

        // Array per accumulare gli edifici da inserire
        $buildings = [];

        // Cicla su tutti i quadrati della mappa
        foreach ($mapSquares as $square) {
            // Numero casuale di edifici per quadrato (puoi regolare il range a seconda delle esigenze)
            $numBuildings = rand(1, 3);

            for ($i = 0; $i < $numBuildings; $i++) {
                // Seleziona casualmente un tipo di edificio
                $buildingType = array_rand($buildingTypes);
                $buildingName = $buildingTypes[$buildingType];

                // Accumula i dati dell'edificio nell'array
                $buildings[] = [
                    'map_square_id' => $square->id,
                    'name' => $buildingName . " " . ($i + 1),  // Aggiungi un numero per differenziare più edifici
                    'type' => $buildingType,
                    'description' => "Questo è un {$buildingName} situato nel settore {$square->sector_name}.",
                    'is_main_structure' => ($i === 0),  // Il primo edificio creato è la struttura principale
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Inserisci tutti gli edifici in un'unica query
        Building::insert($buildings);

        $this->command->info('Edifici creati in modo casuale per ogni quadrato della mappa.');
    }
}


// class BuildingSeeder extends Seeder
// {
//     public function run()
//     {
//         DB::table('buildings')->truncate();

//         // Trova i settori residenziale, commerciale e industriale generati dinamicamente
//         $mapSquareCentro = MapSquare::where('type', 'commercial')->inRandomOrder()->first();
//         $mapSquareCommerciale = MapSquare::where('type', 'commercial')->inRandomOrder()->skip(1)->first();
//         $mapSquareResidenziale = MapSquare::where('type', 'residential')->inRandomOrder()->first();

//         if (!$mapSquareCentro || !$mapSquareCommerciale || !$mapSquareResidenziale) {
//             $this->command->error('Uno dei settori chiave (Centro, Distretto Commerciale, Zona Residenziale) non esiste.');
//             return;
//         }

//         // Creazione di edifici nel settore "Centro"
//         Building::create([
//             'map_square_id' => $mapSquareCentro->id,
//             'name' => 'Grande Banca di Anthalys',
//             'type' => 'bank',
//             'description' => 'La banca più grande e prestigiosa del centro città.',
//             'is_main_structure' => true,
//         ]);

//         // Altri edifici nel Centro
//         Building::create([
//             'map_square_id' => $mapSquareCentro->id,
//             'name' => 'Distretto di Polizia Centrale',
//             'type' => 'police_station',
//             'description' => 'Il principale distretto di polizia del centro città.',
//         ]);

//         // Edifici nel Distretto Commerciale
//         Building::create([
//             'map_square_id' => $mapSquareCommerciale->id,
//             'name' => 'Centro Commerciale di Anthalys',
//             'type' => 'shop',
//             'description' => 'Un enorme centro commerciale con negozi di ogni tipo.',
//             'is_main_structure' => true,
//         ]);

//         // Edifici nella Zona Residenziale
//         Building::create([
//             'map_square_id' => $mapSquareResidenziale->id,
//             'name' => 'Ospedale Generale di Anthalys',
//             'type' => 'hospital',
//             'description' => 'L’ospedale principale per le emergenze sanitarie.',
//             'is_main_structure' => true,
//         ]);
//     }
// }
