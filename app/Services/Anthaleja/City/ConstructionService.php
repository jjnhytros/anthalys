<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\City\Building;
use App\Models\Anthaleja\City\Resource;

class ConstructionService
{
    public function constructBuilding(Character $character, $buildingType)
    {
        // Risorse necessarie per costruire il tipo di edificio
        $constructionCosts = [
            'factory' => ['acciaio' => 100, 'legno' => 50], // Fabbrica richiede acciaio e legno
            'house' => ['legno' => 30], // Casa richiede solo legno
        ];

        $requiredResources = $constructionCosts[$buildingType] ?? [];

        // Verifica se il personaggio ha tutte le risorse necessarie
        foreach ($requiredResources as $resourceType => $amountRequired) {
            $resource = Resource::where('character_id', $character->id)
                ->where('type', $resourceType)
                ->first();

            if (!$resource || $resource->amount < $amountRequired) {
                return "{$character->username} non ha abbastanza {$resourceType} per costruire un {$buildingType}.";
            }
        }

        // Consuma le risorse necessarie per la costruzione
        foreach ($requiredResources as $resourceType => $amountRequired) {
            $resource = Resource::where('character_id', $character->id)
                ->where('type', $resourceType)
                ->first();
            $resource->amount -= $amountRequired;
            $resource->save();
        }

        // Crea il nuovo edificio
        Building::create([
            'name' => ucfirst($buildingType),
            'type' => $buildingType,
            'owner_id' => $character->id,
            'map_square_id' => $character->map_square_id,
        ]);

        // Log della costruzione
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'building_constructed',
            'details' => json_encode([
                'building_type' => $buildingType,
                'resources_used' => $requiredResources,
            ]),
            'event_context' => json_encode(['map_square_id' => $character->map_square_id]),
            'created_at' => now(),
        ]);

        return "Costruzione di un {$buildingType} completata con successo.";
    }
}

    // public function build(MapSquare $square)
    // {
    //     // Verifica se è possibile costruire in questo quartiere
    //     if ($square->current_buildings >= $square->building_limit) {
    //         return "Non è possibile costruire altri edifici in questo quartiere.";
    //     }

    //     // L'AI decide quale tipo di edificio è necessario
    //     $aiService = new AIConstructionDecisionService();
    //     $buildingType = $aiService->decideNextConstruction($square);

    //     // Crea un nuovo edificio nella tabella 'buildings'
    //     Building::create([
    //         'map_square_id' => $square->id,
    //         'name' => $buildingType,
    //         'type' => $this->getBuildingType($buildingType),
    //         'description' => "Un nuovo $buildingType è stato costruito nel settore {$square->sector_name}.",
    //     ]);

    //     // Aggiungi un nuovo edificio al quartiere
    //     $square->current_buildings += 1;
    //     $square->save();

    //     return "Un nuovo edificio di tipo $buildingType è stato costruito nel settore {$square->sector_name}.";
    // }

    // public function investInBuilding(Character $character, MapSquare $square, $buildingType)
    // {
    //     $cost = $this->getBuildingCost($buildingType);

    //     if ($character->cash >= $cost) {
    //         $character->cash -= $cost;
    //         $character->save();

    //         // Aggiungi l'edificio nel quartiere
    //         Building::create([
    //             'map_square_id' => $square->id,
    //             'name' => ucfirst($buildingType),
    //             'type' => $buildingType,
    //         ]);

    //         $square->current_buildings += 1;
    //         $square->socio_economic_status += 5;  // Aumenta leggermente il valore socio-economico
    //         $square->save();

    //         return "{$character->username} ha costruito un nuovo edificio di tipo {$buildingType} nel quartiere {$square->sector_name}.";
    //     }

    //     return "Fondi insufficienti per costruire {$buildingType}.";
    // }


    // protected function getBuildingType($buildingName)
    // {
    //     // Logica per determinare il tipo di edificio basato sul nome
    //     if (str_contains($buildingName, 'appartamento')) {
    //         return 'residential';
    //     } elseif (str_contains($buildingName, 'negozio') || str_contains($buildingName, 'centro commerciale')) {
    //         return 'commercial';
    //     } elseif (str_contains($buildingName, 'fabbrica')) {
    //         return 'industrial';
    //     } elseif (str_contains($buildingName, 'ospedale') || str_contains($buildingName, 'polizia')) {
    //         return 'public_service';
    //     }

    //     return 'mixed_use';  // Edifici a uso misto
    // }

    // // Funzione per determinare il costo dell'edificio in base al tipo
    // protected function getBuildingCost($buildingType)
    // {
    //     $costs = [
    //         'residential' => 50000,
    //         'commercial' => 100000,
    //         'industrial' => 150000,
    //         'public_service' => 200000,
    //         'mixed_use' => 120000,
    //     ];

    //     // Ritorna il costo del tipo di edificio, o un costo di default
    //     return $costs[$buildingType] ?? 75000;
    // }

    // protected function aiDecideBuildingType(MapSquare $square)
    // {
    //     // L'AI decide quale tipo di edificio è necessario in base a diversi fattori
    //     if ($square->type === 'residential') {
    //         return $this->chooseResidentialBuilding($square);
    //     } elseif ($square->type === 'commercial') {
    //         return 'Centro commerciale';
    //     } elseif ($square->type === 'industrial') {
    //         return 'Fabbrica';
    //     } else {
    //         return 'Edificio misto';
    //     }
    // }

    // // Sottosistema per determinare il tipo di edificio residenziale
    // protected function chooseResidentialBuilding(MapSquare $square)
    // {
    //     if ($square->development_level < 3) {
    //         return 'Casa isolata';
    //     } elseif ($square->development_level >= 3 && $square->development_level < 5) {
    //         return 'Casa a schiera';
    //     } else {
    //         return 'Casa a torre';
    //     }
    // }
