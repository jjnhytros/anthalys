<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\City\MapSquare;

class ResourcePriceService
{
    public function calculateResourcePrice($resourceType, MapSquare $square)
    {
        // Prezzo di base per ciascuna risorsa
        $basePrices = [
            'cibo' => 10,
            'acqua' => 5,
            'carburante' => 20
        ];

        // Influenza del mercato del rione sul prezzo
        $marketInfluence = $square->commercial_buildings * 0.1;  // Maggiori i negozi, minore il prezzo
        $economicInfluence = rand(-5, 5);  // Fluttuazione casuale per crisi o boom economici

        // Calcola il prezzo finale
        $finalPrice = $basePrices[$resourceType] + $marketInfluence + $economicInfluence;

        return max(1, $finalPrice); // Il prezzo non può scendere sotto 1
    }
}
