<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\City\Resource;
use App\Models\Anthaleja\City\MapSquare;

class ResourceManagementService
{
    public function addResourceToSquare(MapSquare $square, $resourceType, $quantity)
    {
        // Aggiunge risorse al rione
        $resource = Resource::firstOrNew([
            'map_square_id' => $square->id,
            'type' => $resourceType
        ]);

        $resource->amount += $quantity;
        $resource->save();

        // Log dell'aggiunta di risorse
        EventLog::create([
            'event_type' => 'resource_added',
            'details' => json_encode([
                'square' => $square->sector_name,
                'resource_type' => $resourceType,
                'quantity' => $quantity,
            ]),
            'created_at' => now(),
        ]);

        return "{$quantity} unità di {$resourceType} aggiunte al rione {$square->sector_name}.";
    }

    public function removeResourceFromSquare(MapSquare $square, $resourceType, $quantity)
    {
        // Rimuove risorse dal rione
        $resource = Resource::where('map_square_id', $square->id)
            ->where('type', $resourceType)
            ->first();

        if ($resource && $resource->amount >= $quantity) {
            $resource->amount -= $quantity;
            $resource->save();

            // Log della rimozione di risorse
            EventLog::create([
                'event_type' => 'resource_removed',
                'details' => json_encode([
                    'square' => $square->sector_name,
                    'resource_type' => $resourceType,
                    'quantity' => $quantity,
                ]),
                'created_at' => now(),
            ]);

            return "{$quantity} unità di {$resourceType} rimosse dal rione {$square->sector_name}.";
        }

        return "Il rione {$square->sector_name} non ha abbastanza {$resourceType} da rimuovere.";
    }
}
