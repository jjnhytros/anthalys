<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\MegaWareHouse\Warehouse;

class EmergencyManagementService
{
    public function handleEmergency(Warehouse $warehouse)
    {
        $emergencyType = rand(1, 3);

        switch ($emergencyType) {
            case 1:
                return $this->handleFire($warehouse);
            case 2:
                return $this->handleRobotMalfunction($warehouse);
            case 3:
                return $this->handleCyberAttack($warehouse);
            default:
                return "No emergency.";
        }
    }

    // ----------------------------------------------------------------- //

    protected function handleCyberAttack(Warehouse $warehouse)
    {
        // Simula un attacco informatico
        return "Cyber attack detected in warehouse {$warehouse->id}. Security protocols activated.";
    }

    protected function handleFire(Warehouse $warehouse)
    {
        $warehouse->current_stock = max($warehouse->current_stock - 500, 0); // Simula perdita di stock
        $warehouse->save();
        return "Fire in warehouse {$warehouse->id}! Stock lost: 500 units.";
    }

    protected function handleRobotMalfunction(Warehouse $warehouse)
    {
        // Simula un malfunzionamento di un robot
        return "Robot malfunction in warehouse {$warehouse->id}. Maintenance required.";
    }
}
