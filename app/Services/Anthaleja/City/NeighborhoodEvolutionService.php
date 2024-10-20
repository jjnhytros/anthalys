<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\City\Building;
use App\Models\Anthaleja\City\Resource;
use App\Models\Anthaleja\City\MapSquare;;

class NeighborhoodEvolutionService
{
    public function evolveNeighborhood(MapSquare $square)
    {
        // Definisce le soglie di risorse necessarie per stimolare la crescita
        $growthThresholds = [
            'cibo' => 200,
            'acqua' => 150,
            'carburante' => 100
        ];

        $canGrow = true;

        // Verifica se ci sono abbastanza risorse per permettere la crescita del quartiere
        foreach ($growthThresholds as $resourceType => $threshold) {
            $resource = Resource::where('map_square_id', $square->id)
                ->where('type', $resourceType)
                ->first();

            if (!$resource || $resource->amount < $threshold) {
                $canGrow = false;
                break;
            }
        }

        if ($canGrow) {
            // Incrementa la popolazione e lo status socio-economico del quartiere
            $square->population_density += 10;
            $square->socio_economic_status += 5;
            $square->save();

            // Log dell'evoluzione del quartiere
            EventLog::create([
                'event_type' => 'neighborhood_evolved',
                'details' => json_encode([
                    'square' => $square->sector_name,
                    'new_population_density' => $square->population_density,
                    'new_socio_economic_status' => $square->socio_economic_status,
                ]),
                'created_at' => now(),
            ]);

            return $this->triggerBuildingExpansion($square);
        }

        return "Il rione {$square->sector_name} non ha risorse sufficienti per crescere.";
    }

    public function checkNeighborhoodGrowth(MapSquare $square)
    {
        $resourceCheck = $this->evolveNeighborhood($square);
        return $resourceCheck;
    }

    public function triggerBuildingExpansion(MapSquare $square)
    {
        if ($square->socio_economic_status > 60) {
            $buildingOptions = [];

            if ($square->population_density > 100) {
                $buildingOptions[] = 'residential';
            }

            if ($square->socio_economic_status > 70) {
                $buildingOptions[] = 'commercial';
            }

            if ($square->population_density > 150) {
                $buildingOptions[] = 'industrial';
            }

            if (!empty($buildingOptions)) {
                $newBuildingType = $buildingOptions[array_rand($buildingOptions)];

                Building::create([
                    'name' => "Nuovo Edificio {$newBuildingType}",
                    'type' => $newBuildingType,
                    'map_square_id' => $square->id,
                    'owner_id' => null,
                ]);

                EventLog::create([
                    'event_type' => 'new_building_constructed',
                    'details' => json_encode([
                        'building_type' => $newBuildingType,
                        'square' => $square->sector_name,
                    ]),
                    'created_at' => now(),
                ]);

                return "Nel rione {$square->sector_name} è stato costruito un nuovo edificio {$newBuildingType}.";
            }

            return "Il rione {$square->sector_name} ha bisogno di più popolazione o status per espandere gli edifici.";
        }

        return "Il rione {$square->sector_name} non ha lo status socio-economico sufficiente per espandere gli edifici.";
    }

    public function monitorNeighborhoodDecline(MapSquare $square)
    {
        // Definisce soglie critiche per risorse che possono portare al declino
        $declineThresholds = [
            'cibo' => 30,
            'acqua' => 20,
            'carburante' => 15,
        ];

        $isInDecline = false;

        // Verifica se le risorse sono sotto le soglie critiche
        foreach ($declineThresholds as $resourceType => $threshold) {
            $resource = Resource::where('map_square_id', $square->id)
                ->where('type', $resourceType)
                ->first();

            // Introduci una probabilità che il quartiere non declini subito
            if (!$resource || $resource->amount < $threshold) {
                if (
                    rand(1, 100) < 30
                ) {  // Solo il 30% di probabilità che il declino inizi subito
                    $isInDecline = true;
                    break;
                }
            }
        }

        if ($isInDecline) {
            // Riduci la popolazione e lo status socio-economico del quartiere
            if (!is_numeric($square->population_density)) {
                $square->population_density = $this->convertPopulationDensityToNumber($square->population_density);
            }
            $square->socio_economic_status = max(0, $square->socio_economic_status - 10);
            $square->save();

            // Log del declino
            EventLog::create([
                'event_type' => 'neighborhood_declined',
                'details' => json_encode([
                    'square' => $square->sector_name,
                    'new_population_density' => $square->population_density,
                    'new_socio_economic_status' => $square->socio_economic_status,
                ]),
                'created_at' => now(),
            ]);

            return "Il rione {$square->sector_name} è in declino a causa della scarsità di risorse.";
        }

        return "Il rione {$square->sector_name} non è in declino.";
    }

    public function triggerRecoveryEvent(MapSquare $square)
    {
        // Evento di recupero con una probabilità del 20% che avvenga
        if (rand(1, 100) <= 20) {
            $recoveryType = ['economic_incentive', 'resource_boost'][array_rand(['economic_incentive', 'resource_boost'])];

            if ($recoveryType == 'economic_incentive') {
                $square->socio_economic_status = min(100, $square->socio_economic_status + 10);  // Incentivo economico
            } elseif ($recoveryType == 'resource_boost') {
                $resource = Resource::firstOrNew(['map_square_id' => $square->id, 'type' => 'cibo']);
                $resource->amount += 50;  // Aggiungi risorse
                $resource->save();
            }

            $square->save();

            EventLog::create([
                'event_type' => 'recovery_event',
                'details' => json_encode([
                    'square' => $square->sector_name,
                    'recovery_type' => $recoveryType,
                    'new_socio_economic_status' => $square->socio_economic_status,
                ]),
                'created_at' => now(),
            ]);

            return "Il rione {$square->sector_name} ha ricevuto un evento di recupero ({$recoveryType}).";
        }

        return "Nessun evento di recupero per il rione {$square->sector_name}.";
    }


    protected function convertPopulationDensityToNumber($density)
    {
        // Definisci una mappa di valori per convertire la densità della popolazione in numeri
        $densityMap = [
            'molto bassa' => 10,
            'bassa' => 30,
            'media' => 60,
            'alta' => 90,
        ];

        // Ritorna il valore numerico associato alla stringa di densità
        return $densityMap[$density] ?? 0; // 0 come fallback
    }
}
