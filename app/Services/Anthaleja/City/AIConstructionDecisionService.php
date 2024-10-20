<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\City\MapSquare;

class AIConstructionDecisionService
{
    protected $economicService;
    protected $machineLearningService;

    public function __construct(
        EconomicSimulationService $economicService,
        MachineLearningService $machineLearningService
    ) {
        $this->economicService = $economicService;
        $this->machineLearningService = $machineLearningService;
    }

    public function decideBuildingType(MapSquare $square)
    {
        // Consultare l'eventLog per fare previsioni economiche
        $predictedGrowth = $this->machineLearningService->predictEconomicTrends($square);

        // Logica di costruzione basata sulle previsioni
        if ($predictedGrowth > 10) {
            // Se è prevista una crescita economica, costruisci edifici commerciali
            $this->logEvent('commercial', $square);
            return 'commercial';
        }

        $unemploymentRate = $square->unemployment_rate;
        $availableBudget = $square->available_budget;

        // Se la criminalità è alta, costruisci stazioni di polizia o vigili del fuoco
        $crimeRate = $square->crime_rate;
        if ($crimeRate > 50 && $availableBudget >= 50000) {
            $this->logEvent('police_station', $square);
            return 'police_station';
        }

        // Se il tasso di disoccupazione è alto, costruisci fabbriche
        if ($unemploymentRate > 20 && $availableBudget >= 75000) {
            $this->logEvent('factory', $square);
            return 'factory';
        }

        // Logica di costruzione standard basata su popolazione ed economia
        $population = $square->population_density;
        $socioEconomicStatus = $square->socio_economic_status;
        $currentBuildings = $square->current_buildings;

        if ($population == 'alta' && $socioEconomicStatus > 60 && $currentBuildings < $square->building_limit) {
            $this->logEvent('residential', $square);
            return 'residential';
        } elseif ($socioEconomicStatus <= 60 && $currentBuildings < $square->building_limit) {
            $this->logEvent('industrial', $square);
            return 'industrial';
        }
    }

    public function decideNextConstruction(MapSquare $square)
    {
        $population = $this->getPopulation($square);
        $needs = $this->getCommunityNeeds($square);
        $economy = $this->getEconomicStatus($square);

        if ($population > 1000 && $needs['residential']) {
            return $this->buildResidential($square);
        } elseif ($economy > 500000 && $needs['commercial']) {
            return $this->buildCommercial($square);
        } elseif ($needs['public_services']) {
            return $this->buildPublicService($square);
        } else {
            return $this->buildMixedUse($square);
        }
    }

    protected function getRecentEvents(MapSquare $square)
    {
        // Recuperare gli eventi recenti dal log degli eventi
        return EventLog::where('event_context->map_square_id', $square->id)
            ->where('created_at', '>', now()->subMonths(6))  // Esempio: eventi negli ultimi 6 mesi
            ->get();
    }


    protected function getPopulation(MapSquare $square)
    {
        // Logica per ottenere la popolazione reale
        return rand(500, 2000);
    }

    protected function getCommunityNeeds(MapSquare $square)
    {
        return [
            'residential' => true,
            'commercial' => false,
            'public_services' => false,
        ];
    }

    protected function getEconomicStatus(MapSquare $square)
    {
        return rand(100000, 1000000);
    }

    protected function buildResidential(MapSquare $square)
    {
        return 'Costruzione di un edificio residenziale.';
    }

    protected function buildCommercial(MapSquare $square)
    {
        return 'Costruzione di un centro commerciale.';
    }

    protected function buildPublicService(MapSquare $square)
    {
        return 'Costruzione di una stazione di polizia o vigili del fuoco.';
    }

    protected function buildMixedUse(MapSquare $square)
    {
        return 'Costruzione di un edificio a uso misto (residenziale e commerciale).';
    }

    protected function logEvent($buildingType, MapSquare $square)
    {
        $details = [
            'building_type' => $buildingType,
            'location' => $square->x_coordinate . ',' . $square->y_coordinate,
            'initiator' => 'AI'
        ];

        EventLog::create([
            'character_id' => null,  // Non legato a un personaggio specifico
            'event_type' => 'construction',
            'category' => 'urban_development',
            'details' => json_encode($details),
            'event_context' => json_encode([
                'map_square_id' => $square->id,
                'city_state' => 'expansion',
            ]),
            'created_at' => now(),
        ]);
    }
}
