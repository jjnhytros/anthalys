<?php

namespace App\Services\Anthaleja\Character\NPC;

use App\Models\Anthaleja\Character\NPC;

class NPCRoutineService
{
    public function updateNPCActionsBasedOnTime()
    {
        $currentTime = now(); // Ottieni il tempo attuale del gioco

        $npcs = NPC::where('is_npc', true)->get();
        foreach ($npcs as $npc) {
            if ($currentTime->hour >= 8 && $currentTime->hour <= 17) {
                $npc->assignTask('Gestione logistica', 2); // Esempio: compiti di lavoro durante il giorno
            } else {
                $npc->assignTask('Sorveglianza notturna', 1); // Esempio: compiti notturni
            }
        }
    }
}
