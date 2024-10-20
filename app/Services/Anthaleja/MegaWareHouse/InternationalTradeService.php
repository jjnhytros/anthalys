<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\MegaWareHouse\Warehouse;

class InternationalTradeService
{
    public function handleExport(Warehouse $warehouse, $quantity)
    {
        if ($warehouse->current_stock >= $quantity) {
            $warehouse->current_stock -= $quantity;
            $warehouse->save();
            return "Exported $quantity units from warehouse {$warehouse->id}. Current stock: " . $warehouse->current_stock;
        }
        return "Not enough stock for export.";
    }

    public function handleImport(Warehouse $warehouse, $quantity)
    {
        $warehouse->current_stock += $quantity;
        $warehouse->save();
        return "Imported $quantity units to warehouse {$warehouse->id}. Current stock: " . $warehouse->current_stock;
    }
}
