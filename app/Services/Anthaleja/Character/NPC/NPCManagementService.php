<?php

namespace App\Services\Anthaleja\Character\NPC;

use App\Models\Anthaleja\Character\NPC;
use App\Models\Anthaleja\Marketplace\Product;
use App\Models\Anthaleja\Character\NPCReputation;

class NPCManagementService
{

    public function assignDailyMissions()
    {
        $npcs = NPC::where('status', 'active')->get();

        foreach ($npcs as $npc) {
            // Assegna una missione casuale
            $mission = $this->getRandomMission();
            echo "{$npc->name} has been assigned the mission: {$mission}.";
        }
    }

    public function assignTasks()
    {
        $npcs = NPC::where('status', 'active')->get();

        foreach ($npcs as $npc) {
            switch ($npc->role) {
                case 'Magazziniere':
                    $this->manageInventory($npc);
                    break;
                case 'Supervisore':
                    $this->monitorOperations($npc);
                    break;
                    // Aggiungi altri ruoli e logiche se necessario
            }
        }
    }

    protected function manageInventory(NPC $npc)
    {
        // Controlla le scorte
        $lowStockItems = Product::where('quantity', '<', 50)->get();

        foreach ($lowStockItems as $item) {
            // Assegna un compito all'NPC per rifornire l'item
            $this->assignRestockTask($npc, $item);
        }
    }

    protected function monitorOperations(NPC $npc)
    {
        // Logica per monitorare le operazioni nel magazzino
        // Esempio: verifica che le operazioni siano eseguite correttamente
    }

    public function reactToNewEvents()
    {
        // Logica per reagire a eventi non solo economici
        // Puoi integrare anche eventi di emergenza, come un incendio o un guasto
        $newEvent = $this->checkForNewEvents();

        if ($newEvent) {
            $this->alertNPCs("An event has occurred: {$newEvent}");
        }
    }

    public function updateReputation(NPC $npc, $action)
    {
        $reputation = NPCReputation::where('npc_id', $npc->id)->first();

        switch ($action) {
            case 'completed_mission':
                $reputation->increaseReputation(10);  // Aumenta di 10 per missione completata
                break;
            case 'failed_mission':
                $reputation->decreaseReputation(5);  // Diminuisci di 5 per missione fallita
                break;
                // Aggiungi altri casi per le azioni degli NPC
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

    protected function assignRestockTask(NPC $npc, $item)
    {
        // Logica per assegnare il compito di rifornire
        echo "{$npc->name} is assigned to restock {$item->name}.";
        // Aggiungi ulteriori dettagli per il compito, se necessario
    }

    protected function checkForNewEvents()
    {
        // Controlla se ci sono eventi nel gioco che richiedono attenzione
        // Esempio: incendio nel magazzino, guasto tecnico, ecc.
        return null; // Sostituisci con la logica per verificare eventi
    }

    protected function getRandomMission()
    {
        $missions = [
            "Restock supplies in section A.",
            "Check the temperature of refrigerated items.",
            "Prepare a report on stock levels."
        ];

        return $missions[array_rand($missions)];
    }
}
