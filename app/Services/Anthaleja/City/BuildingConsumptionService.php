<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\City\Building;
use App\Models\Anthaleja\City\Resource;

class BuildingConsumptionService
{
    public function consumeResources(Building $building)
    {
        // Definisci le risorse richieste in base al tipo di costruzione
        $consumptionRates = [
            'factory' => ['carburante' => 10, 'acciaio' => 5], // Una fabbrica richiede carburante e acciaio
        ];

        $buildingType = $building->type;
        $requiredResources = $consumptionRates[$buildingType] ?? [];

        foreach ($requiredResources as $resourceType => $requiredAmount) {
            $resource = Resource::where('character_id', $building->owner_id)
                ->where('type', $resourceType)
                ->first();

            if ($resource && $resource->amount >= $requiredAmount) {
                // Consuma le risorse
                $resource->amount -= $requiredAmount;
                $resource->save();

                // Log del consumo delle risorse
                EventLog::create([
                    'character_id' => $building->owner_id,
                    'event_type' => 'resource_consumed',
                    'details' => json_encode([
                        'resource_type' => $resourceType,
                        'amount_consumed' => $requiredAmount,
                        'building_id' => $building->id,
                    ]),
                    'event_context' => json_encode(['map_square_id' => $building->map_square_id]),
                    'created_at' => now(),
                ]);
            } else {
                // Se le risorse non sono disponibili, logga l'evento
                EventLog::create([
                    'character_id' => $building->owner_id,
                    'event_type' => 'resource_shortage',
                    'details' => json_encode([
                        'resource_type' => $resourceType,
                        'building_id' => $building->id,
                        'required_amount' => $requiredAmount,
                    ]),
                    'event_context' => json_encode(['map_square_id' => $building->map_square_id]),
                    'created_at' => now(),
                ]);

                // Interrompi la produzione
                return "{$building->name} ha bisogno di più {$resourceType} per operare.";
            }
        }

        return "{$building->name} ha consumato le risorse necessarie per funzionare.";
    }
}
