<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\City\Resource;
use App\Models\Anthaleja\City\MapSquare;

class ResourceDemandService
{
    public function getResourceDemand(MapSquare $square, $resourceType)
    {
        // Calcola la domanda in base alla popolazione e alla disponibilità di risorse
        $population = $square->population_density;
        $availableResource = Resource::where('map_square_id', $square->id)
            ->where('type', $resourceType)
            ->first();

        $demand = 0;

        if ($availableResource) {
            $demand = max(0, ($population * 10) - $availableResource->amount);
        } else {
            $demand = $population * 10;  // Se non ci sono risorse, la domanda è alta
        }

        return $demand;
    }
}
