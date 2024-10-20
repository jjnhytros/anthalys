<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\Marketplace\Product;
use App\Models\Anthaleja\MegaWareHouse\Drone;
use App\Models\Anthaleja\MegaWareHouse\Robot;
use App\Models\Anthaleja\MegaWareHouse\Warehouse;
use App\Models\Anthaleja\MegaWareHouse\WarehouseLevel;
use App\Models\Anthaleja\MegaWareHouse\ProductCategory;

class WarehouseManagementService
{
    protected $economicSimulation;

    public function __construct(EconomicSimulationService $economicSimulation)
    {
        $this->economicSimulation = $economicSimulation;
    }

    public function applyStorePromotions(Warehouse $warehouse)
    {
        $totalStock = $warehouse->current_stock;
        $discount = 0;

        if ($totalStock > 5000) {
            $discount = 10; // Applica uno sconto del 10% per abbondanza di stock
        }

        return "Promotion applied: $discount% discount on all products from Warehouse " . $warehouse->id;
    }

    public function chargeDrone(Drone $drone)
    {
        if ($drone->battery_life < 100) {
            $drone->battery_life = min($drone->battery_life + 10, 100); // Incrementa senza superare 100
            $drone->status = 'charging';
            $drone->save();
            return "Drone is charging. Current battery life: " . $drone->battery_life;
        }
        return "Drone battery is full";
    }

    public function checkDroneBattery(Drone $drone)
    {
        if ($drone->battery_life <= 20) {
            return "Drone battery is low. Need to charge.";
        }
        return "Drone battery life is sufficient.";
    }

    public function checkEconomicEventImpact()
    {
        // Gestione di eventi casuali o crisi economiche che influiscono sui prezzi
        $randomEvent = rand(1, 10);
        if ($randomEvent < 3) {
            return 1.2; // Crisi economica, aumento dei prezzi
        } elseif ($randomEvent > 8) {
            return 0.8; // Evento positivo, riduzione dei prezzi
        }

        return 1.0; // Nessun impatto
    }

    public function checkResourceLevels()
    {
        $products = Product::all();
        foreach ($products as $product) {
            if ($product->quantity < 50) {
                // Se le risorse sono basse, ordina automaticamente una rifornitura
                $this->orderProductRefill($product);
            }
        }
    }

    public function dynamicRouteOptimization(Drone $drone, $destination)
    {
        $initialTime = 1; // Supponiamo che la consegna normale impieghi 1 ora
        $obstacle = rand(1, 10);

        if ($obstacle <= 3) {
            // Ostacoli imprevisti, come condizioni atmosferiche o traffico
            $adjustedTime = $initialTime + 1;
            return "Delivery delayed due to obstacles. Time taken: $adjustedTime hours.";
        }

        // Nessun ostacolo
        return "Delivery successful to $destination. Time taken: $initialTime hours.";
    }

    public function manageInventoryByCategory()
    {
        $levels = WarehouseLevel::all();

        foreach ($levels as $level) {
            $products = Product::where('level_id', $level->id)->get();

            foreach ($products as $product) {
                $category = ProductCategory::find($product->category_id);

                // Logica basata sulla categoria
                if ($category->macro_category == 'Alimentari') {
                    // Se alimentari, assicurarsi che i prodotti siano nei primi livelli
                    if ($level->depth > 6) {
                        $this->moveProductToHigherLevel($product);
                    }
                } elseif ($category->macro_category == 'Arredamento') {
                    // Se arredamento, posizionare i prodotti in livelli più profondi
                    if ($level->depth <= 6) {
                        $this->moveProductToLowerLevel($product);
                    }
                }
            }
        }

        return 'Gestione dell’inventario completata';
    }

    public function manageEnergy(Warehouse $warehouse)
    {
        $renewableEnergyAvailable = rand(0, 1); // Simula la disponibilità di energia rinnovabile (es. solare)
        $energyConsumption = $warehouse->current_stock * 0.05; // Consumo energetico in base allo stock
        $energyCost = 0;

        if ($renewableEnergyAvailable) {
            // Usa energia rinnovabile, riduce il costo
            $energyCost = $energyConsumption * 0.5; // 50% di risparmio energetico
            return "Using renewable energy. Energy cost: $energyCost units.";
        } else {
            // Usa energia tradizionale
            $energyCost = $energyConsumption;
            return "Using traditional energy. Energy cost: $energyCost units.";
        }
    }

    public function manageResources(Warehouse $warehouse)
    {
        // Supponiamo che ogni magazzino abbia un livello di consumo di risorse basato sulla capacità e stock
        $resourceConsumptionRate = $warehouse->capacity * 0.01; // Consumo di risorse per ogni unità di capacità
        $availableResources = $warehouse->current_stock;

        if ($availableResources < $resourceConsumptionRate) {
            return "Warning: Warehouse resources are running low!";
        }

        $warehouse->current_stock -= $resourceConsumptionRate;
        $warehouse->save();

        return "Resources consumed: $resourceConsumptionRate. Current stock: " . $warehouse->current_stock;
    }

    public function moveDrone(Drone $drone, $from, $to)
    {
        if ($drone->status === 'active') {
            // Logica per il movimento del drone
            // Aggiorna variabili di stato, come l'energia consumata
            return "Drone moved from $from to $to";
        }
        return "Drone is not active";
    }

    public function moveRobot(Robot $robot, $from, $to)
    {
        // Logica per spostare il robot per stoccare o recuperare merci
    }


    public function optimizeWarehouse(Warehouse $warehouse)
    {
        // Ottimizza la disposizione delle merci nel magazzino
        return "Warehouse optimized for stock capacity: " . $warehouse->capacity;
    }

    public function operateDrone(Drone $drone, $operationTime)
    {
        $energyConsumed = $operationTime * 5; // Supponiamo che consumi 5 unità di batteria per ogni ora di operazione
        $drone->battery_life = max($drone->battery_life - $energyConsumed, 0); // Riduce la batteria senza scendere sotto 0
        $drone->save();

        if ($drone->battery_life <= 20) {
            return "Warning: Drone battery is low. Please charge.";
        }

        return "Drone operated for $operationTime hours. Current battery life: " . $drone->battery_life;
    }

    public function operateRobot(Robot $robot, $operationTime)
    {
        $energyConsumed = $operationTime * 3; // Supponiamo che i robot consumino 3 unità di batteria per ora
        $robot->battery_life = max($robot->battery_life - $energyConsumed, 0);
        $robot->save();

        if ($robot->battery_life <= 20) {
            return "Warning: Robot battery is low. Please charge.";
        }

        return "Robot operated for $operationTime hours. Current battery life: " . $robot->battery_life;
    }

    public function optimizeDeliveryRoute(Drone $drone, $destination)
    {
        // Supponiamo che ci siano 3 livelli di ottimizzazione: facile, media e difficile
        $routeDifficulty = rand(1, 3);

        if ($routeDifficulty === 1) {
            $timeTaken = 1; // Percorso facile
        } elseif ($routeDifficulty === 2) {
            $timeTaken = 2; // Percorso medio
        } else {
            $timeTaken = 3; // Percorso difficile
        }

        $this->operateDrone($drone, $timeTaken); // Consuma batteria basata sul tempo della consegna

        return "Drone took $timeTaken hours to deliver to $destination. Current battery life: " . $drone->battery_life;
    }

    public function repairDrone(Robot $robot, Drone $drone)
    {
        if ($drone->status === 'repairing' && $robot->battery_life >= 10) {
            // Il robot ripara il drone e consuma energia
            $drone->status = 'active';
            $drone->battery_life = 100; // Drone riparato con batteria piena
            $robot->battery_life -= 10; // Consumo di energia del robot
            $drone->save();
            $robot->save();

            return "Drone repaired successfully by Robot {$robot->id}.";
        }
        return "Not enough battery life for the repair.";
    }

    public function updateMarketPrices(Warehouse $warehouse)
    {
        $totalStock = $warehouse->current_stock;
        $eventImpact = $this->checkEconomicEventImpact(); // Funzione che gestisce eventi casuali o crisi

        if ($totalStock < 1000) {
            $priceMultiplier = 1.5 * $eventImpact; // Prezzi aumentano per scarsità
        } elseif ($totalStock > 5000) {
            $priceMultiplier = 0.8 * $eventImpact; // Prezzi ridotti per abbondanza
        } else {
            $priceMultiplier = 1.0 * $eventImpact; // Prezzi stabili
        }

        return "Market prices updated with multiplier: $priceMultiplier";
    }

    public function updateProductPrices()
    {
        $products = Product::all();
        foreach ($products as $product) {
            // Se c'è inflazione, aumentare i prezzi
            if ($this->economicSimulation->getEconomicConditions() == 'inflation') {
                $product->price += $product->price * 0.10;  // Aumento del 10% per l'inflazione
            }

            // Se c'è recessione, ridurre i prezzi per stimolare la domanda
            if ($this->economicSimulation->getEconomicConditions() == 'recession') {
                $product->price -= $product->price * 0.15;  // Riduzione del 15% per la recessione
            }

            $product->save();  // Salva i nuovi prezzi
        }
    }


    // --------------------------------------------------------------------- //

    protected function moveProductToHigherLevel($product)
    {
        // Logica per spostare il prodotto in un livello più alto
        $higherLevel = WarehouseLevel::where('depth', '<=', 6)->first();
        $product->level_id = $higherLevel->id;
        $product->save();
    }

    protected function moveProductToLowerLevel($product)
    {
        // Logica per spostare il prodotto in un livello più basso
        $lowerLevel = WarehouseLevel::where('depth', '>', 6)->first();
        $product->level_id = $lowerLevel->id;
        $product->save();
    }

    protected function orderProductRefill($product)
    {
        // Logica per ordinare un rifornimento del prodotto
        $product->quantity += 100;  // Rifornimento simulato di 100 unità
        $product->save();
    }
}
