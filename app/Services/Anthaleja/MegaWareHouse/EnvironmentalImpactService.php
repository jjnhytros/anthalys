<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\MegaWareHouse\Warehouse;

class EnvironmentalImpactService
{
    public function calculateEnvironmentalImpact(Warehouse $warehouse)
    {
        $emissions = $warehouse->current_stock * 0.02; // Simula emissioni in base allo stock
        return "Environmental impact for warehouse {$warehouse->id}: $emissions units of emissions.";
    }
}
