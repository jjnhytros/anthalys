<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\MegaWareHouse\Drone;
use App\Models\Anthaleja\MegaWareHouse\Robot;

class PerformanceEvaluationService
{
    public function evaluateDronePerformance(Drone $drone)
    {
        $efficiency = $drone->battery_life - rand(0, 10); // Simula l'efficienza in base al consumo di batteria
        return "Drone {$drone->id} performance evaluated: Efficiency at $efficiency%.";
    }

    public function evaluateRobotPerformance(Robot $robot)
    {
        $efficiency = $robot->battery_life - rand(0, 5); // Simula l'efficienza per i robot
        return "Robot {$robot->id} performance evaluated: Efficiency at $efficiency%.";
    }
}
