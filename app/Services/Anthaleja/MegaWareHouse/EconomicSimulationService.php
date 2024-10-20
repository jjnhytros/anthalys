<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\Character\NPC;

class EconomicSimulationService
{
    public function getEconomicConditions()
    {
        // Condizioni economiche basate su fattori come risorse disponibili, domanda, ecc.
        $demand = rand(50, 100);  // Simuliamo la domanda
        $resourcesAvailable = rand(50, 100);  // Risorse attualmente disponibili

        if ($demand > 90 && $resourcesAvailable < 60) {
            return 'inflation';  // Se la domanda è alta e le risorse basse, si simula inflazione
        } elseif ($demand < 60) {
            return 'recession';  // Se la domanda è bassa, si simula una recessione
        } else {
            return 'political_change';  // Altrimenti, un cambiamento politico casuale
        }
    }

    public function reactToEconomicChanges()
    {
        // Determina il tipo di evento economico
        $event = $this->simulateEconomicEvent();

        // Logica di reazione degli NPC
        if ($event == "Inflation: Operating costs increased by 10%.") {
            // Azioni degli NPC durante l'inflazione
            $this->alertNPCs("Inflation is affecting costs, adjust stock levels.");
        } elseif ($event == "Recession: Demand decreased by 15%.") {
            // Azioni degli NPC durante una recessione
            $this->alertNPCs("Demand is low, consider discounts.");
        }
    }


    public function simulateEconomicEvent()
    {
        $economicEvent = $this->getEconomicConditions();

        switch ($economicEvent) {
            case 'inflation':
                return $this->simulateInflation();
            case 'recession':
                return $this->simulateRecession();
            case 'political_change':
                return $this->simulatePoliticalChange();
            default:
                return "No economic event.";
        }
    }

    // ----------------------------------------------------------------- //

    protected function alertNPCs($message)
    {
        // Logica per inviare notifiche agli NPC
        $npcs = NPC::all();
        foreach ($npcs as $npc) {
            echo "{$npc->name} received alert: {$message}";
        }
    }

    protected function simulateInflation()
    {
        // Simula inflazione, aumentando i costi operativi
        return "Inflation: Operating costs increased by 10%.";
    }

    protected function simulatePoliticalChange()
    {
        // Simula cambiamenti politici che influenzano il commercio
        return "Political change: Trade tariffs increased.";
    }

    protected function simulateRecession()
    {
        // Simula recessione, riducendo la domanda
        return "Recession: Demand decreased by 15%.";
    }
}
