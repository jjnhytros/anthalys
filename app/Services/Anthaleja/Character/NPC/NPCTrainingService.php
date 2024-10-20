<?php

namespace App\Services\Anthaleja\Character\NPC;

use App\Models\Anthaleja\Character\NPC;

class NPCTrainingService
{
    public function conductTraining(NPC $npc)
    {
        // Logica per addestrare l'NPC
        $npc->train();

        // Logica aggiuntiva per specializzazioni
        if ($npc->skill_level >= 5) {
            echo "{$npc->name} is now an expert in inventory management.";
        } elseif ($npc->skill_level >= 10) {
            echo "{$npc->name} is now a master supervisor.";
        }
    }
}
