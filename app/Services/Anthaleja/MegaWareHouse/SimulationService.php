<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\MegaWareHouse\Warehouse;

class SimulationService
{
    protected $demandPrediction;
    protected $economicSimulation;
    protected $warehouseService;

    public function __construct(
        DemandPredictionService $demandPrediction,
        EconomicSimulationService $economicSimulation,
        WarehouseManagementService $warehouseService
    ) {
        $this->demandPrediction = $demandPrediction;
        $this->economicSimulation = $economicSimulation;
        $this->warehouseService = $warehouseService;
    }

    public function runRealisticSimulation()
    {
        $warehouse = Warehouse::findOrFail(1);

        // Simulazione della domanda di prodotti
        $predictedDemand = $this->demandPrediction->predictDemand($warehouse);

        // Simulazione delle dinamiche economiche
        $economicChanges = $this->economicSimulation->simulateEconomicEvent();

        // Aggiornamento della logistica e del magazzino
        $this->warehouseService->manageInventoryByCategory();

        return [
            'predicted_demand' => $predictedDemand,
            'economic_changes' => $economicChanges,
        ];
    }
}
