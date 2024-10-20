<?php

namespace App\Services\Anthaleja\Character;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\City\Property;

class RealEstateService
{
    public function purchaseProperty(Character $character, $mapSquareId, $propertyType)
    {
        $price = $this->calculatePropertyPrice($mapSquareId, $propertyType);

        // Verifica se il personaggio ha abbastanza denaro
        if ($character->cash < $price) {
            return "Transazione fallita: il personaggio {$character->username} non ha abbastanza denaro.";
        }

        // Se il tipo di proprietà è residenziale, impostiamo un numero casuale di famiglie
        $familiesCount = $propertyType === 'residenziale' ? rand(1, 5) : null;

        // Crea la proprietà e addebita il pagamento
        Property::create([
            'character_id' => $character->id,
            'map_square_id' => $mapSquareId,
            'type' => $propertyType,
            'price' => $price,
            'families_count' => $familiesCount,
            'status' => 'owned',
        ]);

        $character->cash -= $price;
        $character->save();

        // Log dell'acquisto
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'property_purchase',
            'details' => json_encode([
                'property_type' => $propertyType,
                'price' => $price,
                'map_square_id' => $mapSquareId,
                'families_count' => $familiesCount
            ]),
            'created_at' => now(),
        ]);

        return "Il personaggio {$character->username} ha acquistato una proprietà di tipo {$propertyType} nel rione con ID {$mapSquareId} per {$price} AA.";
    }

    public function rentProperty(Character $character, $mapSquareId, $propertyType)
    {
        $price = $this->calculateRentPrice($mapSquareId, $propertyType);

        // Verifica se il personaggio ha abbastanza denaro
        if ($character->cash < $price) {
            return "Transazione fallita: il personaggio {$character->username} non ha abbastanza denaro per affittare.";
        }

        // Se il tipo di proprietà è residenziale, impostiamo un numero casuale di famiglie
        $familiesCount = $propertyType === 'residenziale' ? rand(1, 5) : null;

        // Crea la proprietà in affitto e addebita il pagamento
        Property::create([
            'character_id' => $character->id,
            'map_square_id' => $mapSquareId,
            'type' => $propertyType,
            'price' => $price,
            'families_count' => $familiesCount,
            'status' => 'rented',
        ]);

        $character->cash -= $price;
        $character->save();

        // Log dell'affitto
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'property_rent',
            'details' => json_encode([
                'property_type' => $propertyType,
                'price' => $price,
                'map_square_id' => $mapSquareId,
                'families_count' => $familiesCount
            ]),
            'created_at' => now(),
        ]);

        return "Il personaggio {$character->username} ha affittato una proprietà di tipo {$propertyType} nel rione con ID {$mapSquareId} per {$price} AA.";
    }

    protected function calculatePropertyPrice($mapSquareId, $propertyType)
    {
        // Logica per calcolare il prezzo di acquisto basato sul tipo di proprietà e sul rione
        return rand(50000, 150000);  // Prezzo casuale per ora
    }

    protected function calculateRentPrice($mapSquareId, $propertyType)
    {
        // Logica per calcolare il prezzo di affitto basato sul tipo di proprietà e sul rione
        return rand(5000, 15000);  // Prezzo casuale per ora
    }
}
