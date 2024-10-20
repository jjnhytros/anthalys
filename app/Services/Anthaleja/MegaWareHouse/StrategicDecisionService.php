<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\MegaWareHouse\Drone;
use App\Models\Anthaleja\MegaWareHouse\Warehouse;

class StrategicDecisionService
{
    public function expandWarehouse(Warehouse $warehouse)
    {
        // Espansione del magazzino in base a stock e domanda
        if ($warehouse->current_stock > 5000) {
            $warehouse->capacity += 1000; // Espandi la capacità
            $warehouse->save();
            return "Warehouse {$warehouse->id} expanded by 1000 units.";
        }
        return "Expansion not needed for warehouse {$warehouse->id}.";
    }

    public function hireMoreDrones(Warehouse $warehouse)
    {
        // Assunzione di droni in base alla capacità del magazzino
        if ($warehouse->capacity > 7000) {
            Drone::create([
                'type' => 'delivery',
                'battery_life' => 100,
                'capacity' => 50,
                'assigned_warehouse_id' => $warehouse->id,
                'status' => 'active'
            ]);
            return "New drone hired for warehouse {$warehouse->id}.";
        }
        return "No need for additional drones.";
    }
}
