<?php

namespace Database\Seeders\Anthaleja;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Services\Anthaleja\City\CityPlanningService;

class MapSquareSeeder extends Seeder
{
    protected $cityPlanningService;

    public function __construct(CityPlanningService $cityPlanningService)
    {
        $this->cityPlanningService = $cityPlanningService;
    }

    public function run()
    {
        DB::table('map_squares')->truncate();
        DB::table('sub_cells')->truncate();

        $subCells = [];
        $stationCoverage = [];
        $currentTime = now();
        $batchSize = 144; // Dimensione del blocco per gli inserimenti
        $maxIndustrialCells = 40; // Limite massimo di celle industriali
        $industrialCount = 0;
        $transportStops = []; // Inizializza l'array delle fermate di trasporto

        // Generazione di una zona commerciale 6x6 in periferia
        $commercialZone = $this->cityPlanningService->generateCommercialZone();

        for ($x = 1; $x <= 36; $x++) {
            for ($y = 1; $y <= 36; $y++) {
                $sectorType = null;

                if ($this->cityPlanningService->isPartOfCommercialZone($x, $y, $commercialZone)) {
                    $sectorType = 'commercial';
                    $mapSquare = $this->generateMapSquareData($x, $y, $sectorType, 'Zona Commerciale Periferica', $currentTime);
                } elseif ($this->cityPlanningService->needsPoliceStation($x, $y, $stationCoverage)) {
                    $stationCoverage[] = ['x' => $x, 'y' => $y];
                    $sectorType = 'police_station';
                    $mapSquare = $this->generateMapSquareData($x, $y, $sectorType, 'Stazione di Polizia', $currentTime);
                } elseif ($this->cityPlanningService->needsFireStation($x, $y, $stationCoverage)) {
                    $stationCoverage[] = ['x' => $x, 'y' => $y];
                    $sectorType = 'fire_station';
                    $mapSquare = $this->generateMapSquareData($x, $y, $sectorType, 'Stazione Vigili del Fuoco', $currentTime);
                } else {
                    // Aggiungi la condizione per limitare le celle industriali
                    if ($sectorType === 'industrial') {
                        if ($industrialCount >= $maxIndustrialCells) {
                            $sectorType = 'residential'; // Sostituisci con residenziale se il limite è raggiunto
                        } else {
                            $industrialCount++; // Incrementa il contatore delle celle industriali
                        }
                    }
                    $sectorName = $sectorType === 'residential' ? 'Zona Residenziale' : 'Zona Industriale';
                    $mapSquare = $this->generateMapSquareData($x, $y, $sectorType, $sectorName, $currentTime);
                }

                $mapSquareId = DB::table('map_squares')->insertGetId($mapSquare);

                // Passa l'array $transportStops per riferimento alla funzione generateSubCells
                $subCells = array_merge($subCells, $this->generateSubCells($mapSquareId, $sectorType, $currentTime, $transportStops));

                if (count($subCells) >= $batchSize) {
                    DB::table('sub_cells')->insert($subCells);
                    $subCells = [];
                }
                info("Sector Type: $sectorType for coordinates X: $x, Y: $y");
            }
        }

        if (!empty($subCells)) {
            DB::table('sub_cells')->insert($subCells);
        }
    }

    protected function generateMapSquareData($x, $y, $sectorType, $sectorName, $currentTime)
    {
        if (empty($sectorType)) {
            $sectorType = 'residential';  // Tipo di default
        }

        return [
            'x_coordinate' => $x,
            'y_coordinate' => $y,
            'sector_name' => $sectorName,
            'type' => $sectorType,
            'description' => "Area destinata a $sectorName",
            'population_density' => $this->cityPlanningService->getPopulationDensity($x, $y, $sectorType),
            'economic_growth' => $this->cityPlanningService->generateEconomicGrowthAI($sectorType),
            'public_transport' => $this->cityPlanningService->evaluatePublicTransportAccessibility($x, $y),
            'essential_services_proximity' => $this->cityPlanningService->calculateProximityToServices($x, $y),
            'pollution_level' => $this->cityPlanningService->calculatePollutionLevel($sectorType),
            'housing_demand' => $this->cityPlanningService->calculateHousingDemand($x, $y),
            'infrastructure_quality' => $this->cityPlanningService->evaluateInfrastructureQuality($x, $y),
            'crime_rate' => $this->cityPlanningService->calculateCrimeRate($sectorType, $x, $y),
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ];
    }

    protected function generateSubCells($mapSquareId, $sectorType, $currentTime, &$transportStops)
    {
        $subCells = [];

        $gridSizeX = rand(3, 6);
        $gridSizeY = rand(3, 6);

        // Se la zona è commerciale, chiama regenerateCommercialZone
        // if ($sectorType === 'commercial') {
        //     return $this->regenerateCommercialZone($mapSquareId, $currentTime);
        // }

        for ($i = 1; $i <= $gridSizeX; $i++) {
            for ($j = 1; $j <= $gridSizeY; $j++) {
                // Genera il tipo di subcella
                $subCellType = $this->cityPlanningService->randomSubCellType($sectorType);
                // Verifica se c'è una fermata di bus, tram o metro vicina
                $hasBusStop = $this->cityPlanningService->isAdjacentToTransportStop($i, $j, $transportStops, 'bus');
                $hasTramStop = $this->cityPlanningService->isAdjacentToTransportStop($i, $j, $transportStops, 'tram');
                $hasMetroStop = $this->cityPlanningService->canPlaceMetroStop($i, $j, $subCellType, $transportStops);

                // Aggiungi fermate bus/tram/metro a transportStops
                if ($hasBusStop) {
                    $transportStops[] = ['x' => $i, 'y' => $j, 'type' => 'bus'];
                }

                if ($hasTramStop) {
                    $transportStops[] = ['x' => $i, 'y' => $j, 'type' => 'tram'];
                }

                if ($hasMetroStop) {
                    $transportStops[] = ['x' => $i, 'y' => $j, 'type' => 'metro'];
                }

                // Assegna fermate solo a subcelle di tipo strada o altre per metro (no park)
                $subCells[] = [
                    'map_square_id' => $mapSquareId,
                    'x' => $i,
                    'y' => $j,
                    'type' => $subCellType,
                    'description' => 'Subcella ' . $i . ',' . $j,
                    'has_bus_stop' => $subCellType === 'road' && $hasBusStop ? 1 : 0,
                    'has_tram_stop' => $subCellType === 'road' && $hasTramStop ? 1 : 0,
                    'has_metro_stop' => $hasMetroStop ? 1 : 0, // Fermata metro
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
            }
        }

        return $subCells;
    }

    protected function regenerateCommercialZone($mapSquareId, $currentTime)
    {
        $subCells = [];
        $gridSizeX = 3; // Dimensione fissa 3x3 per le subcelle
        $gridSizeY = 3;

        // Genera tutte le subcelle come parcheggi ('P'), tranne l'entrata in (2, 2)
        for ($i = 1; $i <= $gridSizeX; $i++) {
            for ($j = 1; $j <= $gridSizeY; $j++) {
                $subCellType = 'P'; // Imposta tutte le celle come parcheggi (P)

                // Se la posizione è (2, 2), imposta come entrata (E)
                if ($i == 2 && $j == 2) {
                    $subCellType = 'E';
                }

                // Aggiungi la subcella all'array
                $subCells[] = [
                    'map_square_id' => $mapSquareId,
                    'x' => $i,
                    'y' => $j,
                    'type' => $subCellType, // 'P' per parcheggi, 'E' per entrata
                    'description' => 'Subcella commerciale ' . $i . ',' . $j,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
            }
        }

        return $subCells;
    }
}
