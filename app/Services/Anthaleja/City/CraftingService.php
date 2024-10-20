<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\City\Resource;

class CraftingService
{
    public function craftItem(Character $character, array $resourcesRequired, $itemName)
    {
        // Verifica se il personaggio ha tutte le risorse necessarie
        foreach ($resourcesRequired as $resourceType => $amountRequired) {
            $resource = Resource::where('character_id', $character->id)
                ->where('type', $resourceType)
                ->first();

            if (!$resource || $resource->amount < $amountRequired) {
                return "{$character->username} non ha abbastanza {$resourceType} per creare {$itemName}.";
            }
        }

        // Consuma le risorse necessarie
        foreach ($resourcesRequired as $resourceType => $amountRequired) {
            $resource = Resource::where('character_id', $character->id)
                ->where('type', $resourceType)
                ->first();
            $resource->amount -= $amountRequired;
            $resource->save();
        }

        // Log dell'oggetto creato
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'item_crafted',
            'details' => json_encode([
                'item_name' => $itemName,
                'resources_used' => $resourcesRequired,
            ]),
            'created_at' => now(),
        ]);

        return "{$character->username} ha creato {$itemName}.";
    }
}
