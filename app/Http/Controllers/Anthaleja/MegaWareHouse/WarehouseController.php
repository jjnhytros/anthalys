<?php

namespace App\Http\Controllers\Anthaleja\MegaWareHouse;

use App\Http\Controllers\Controller;
use App\Models\Anthaleja\MegaWareHouse\Drone;
use App\Models\Anthaleja\MegaWareHouse\Robot;
use App\Models\Anthaleja\MegaWareHouse\Warehouse;
use App\Models\Anthaleja\MegaWareHouse\WarehouseLevel;
use App\Models\Anthaleja\MegaWareHouse\WarehouseMatrix;
use App\Services\Anthaleja\MegaWareHouse\CompensationService;
use App\Services\Anthaleja\MegaWareHouse\DemandPredictionService;
use App\Services\Anthaleja\MegaWareHouse\EnvironmentalImpactService;
use App\Services\Anthaleja\MegaWareHouse\WarehouseManagementService;


class WarehouseController extends Controller
{
    protected $compensationService;
    protected $environmentalImpactService;
    protected $warehouseService;

    public function __construct(WarehouseManagementService $warehouseService, EnvironmentalImpactService $environmentalImpactService, CompensationService $compensationService)
    {
        $this->compensationService = $compensationService;
        $this->environmentalImpactService = $environmentalImpactService;
        $this->warehouseService = $warehouseService;
    }

    public function dashboard()
    {
        $drones = Drone::all();
        $robots = Robot::all();
        $warehouses = Warehouse::all();

        return view('anthaleja.warehouse.dashboard', compact('drones', 'robots', 'warehouses'));
    }

    public function loadGrid(WarehouseLevel $level)
    {
        return view('anthaleja.warehouse.partials.grid', compact('level'));
    }

    public function manage()
    {
        $warehouse = Warehouse::find(1); // Recupera il primo magazzino
        $drone = Drone::find(1); // Recupera il primo drone

        // Test del movimento del drone
        echo $this->warehouseService->moveDrone($drone, 'Section A', 'Section B');

        // Ottimizzazione del magazzino
        echo $this->warehouseService->optimizeWarehouse($warehouse);

        // Test della ricarica del drone
        echo $this->warehouseService->chargeDrone($drone);

        // Aggiornamento dei prezzi di mercato basato sul magazzino
        echo $this->warehouseService->updateMarketPrices($warehouse);
    }

    public function manageEnergy(Warehouse $warehouse)
    {
        // Gestione dell'energia
        $energyStatus = $this->warehouseService->manageEnergy($warehouse);

        // Monitoraggio dell'impatto ambientale
        $environmentalImpact = $this->environmentalImpactService->calculateEnvironmentalImpact($warehouse);

        // Sistema di compensazione
        $compensationStatus = $this->compensationService->compensateImpact($warehouse);

        return view('anthaleja.warehouse.energy', compact('energyStatus', 'environmentalImpact', 'compensationStatus'));
    }

    public function predictDemand(Warehouse $warehouse, DemandPredictionService $demandPredictionService)
    {
        $prediction = $demandPredictionService->predictDemand($warehouse);

        return $prediction;
    }

    public function showCellData($levelId, $x, $y)
    {
        $cell = WarehouseMatrix::where('level_id', $levelId)
            ->where('x', $x)
            ->where('y', $y)
            ->first();

        if ($cell) {
            return response()->json([
                'item' => $cell->value['item'],
                'quantity' => $cell->value['quantity'],
            ]);
        } else {
            return response()->json(['error' => 'Cella non trovata'], 404);
        }
    }


    public function showLevels(Warehouse $warehouse)
    {
        $warehouse = Warehouse::find(1);
        $levels = $warehouse->levels()->get();
        return view('anthaleja.warehouse.levels', compact('levels'));
    }
}
