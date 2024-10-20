<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\MegaWareHouse\Warehouse;

class CompensationService
{
    public function compensateImpact(Warehouse $warehouse)
    {
        $emissions = $warehouse->current_stock * 0.02;
        $treesPlanted = round($emissions / 10); // Supponiamo che ogni 10 unità di emissioni venga piantato un albero

        return "To compensate for the environmental impact of warehouse {$warehouse->id}, $treesPlanted trees will be planted.";
    }
}
