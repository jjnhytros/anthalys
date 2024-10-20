<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\City\Building;
use App\Models\Anthaleja\City\Resource;

class BuildingProductionService
{
    public function produceResources(Building $building)
    {
        // Definizione della produzione per tipo di edificio
        $productionRates = [
            'farm' => ['cibo' => 50], // Una fattoria produce 50 unità di cibo
            'refinery' => ['carburante' => 30], // Una raffineria produce 30 unità di carburante
            'sawmill' => ['legno' => 40], // Una segheria produce 40 unità di legno
        ];

        $buildingType = $building->type;
        $producedResources = $productionRates[$buildingType] ?? [];

        foreach ($producedResources as $resourceType => $amount) {
            // Aggiungi le risorse prodotte al proprietario dell'edificio
            $resource = Resource::firstOrNew([
                'character_id' => $building->owner_id,
                'type' => $resourceType
            ]);

            $resource->amount += $amount;
            $resource->save();

            // Log della produzione
            EventLog::create([
                'character_id' => $building->owner_id,
                'event_type' => 'resource_produced',
                'details' => json_encode([
                    'resource_type' => $resourceType,
                    'amount_produced' => $amount,
                    'building_id' => $building->id,
                ]),
                'event_context' => json_encode(['map_square_id' => $building->map_square_id]),
                'created_at' => now(),
            ]);
        }
    }
}
