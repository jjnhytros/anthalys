<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use Carbon\Carbon;
use App\Models\Anthaleja\MegaWareHouse\Warehouse;

class DemandPredictionService
{
    public function predictDemand(Warehouse $warehouse)
    {
        $currentStock = $warehouse->current_stock;

        // Prevedere la domanda basata su eventi passati
        // Supponiamo che ogni mese vi sia un incremento di domanda del 5%
        $monthlyDemandIncreaseRate = 0.05;

        $predictedDemand = $currentStock * (1 + $monthlyDemandIncreaseRate);
        $nextMonth = Carbon::now()->addMonth()->format('F');

        return "Predicted demand for warehouse {$warehouse->id} in $nextMonth: $predictedDemand units.";
    }
}
