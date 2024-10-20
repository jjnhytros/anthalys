<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\MegaWareHouse\Drone;
use App\Models\Anthaleja\MegaWareHouse\Robot;

class CostAnalysisService
{
    public function calculateDroneCosts(Drone $drone)
    {
        // Calcolo del consumo energetico per il drone
        $energyCost = $drone->battery_life * 0.1; // Supponiamo che ogni unità di batteria costi 0.1
        $maintenanceCost = 5; // Costo fisso di manutenzione per ogni operazione

        return $energyCost + $maintenanceCost;
    }

    public function calculateRobotCosts(Robot $robot)
    {
        // Calcolo del consumo energetico per il robot
        $energyCost = $robot->battery_life * 0.08; // Supponiamo un costo diverso per i robot
        $maintenanceCost = 7; // Costo di manutenzione leggermente superiore

        return $energyCost + $maintenanceCost;
    }
}
