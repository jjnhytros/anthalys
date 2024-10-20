<?php

namespace App\Services\Anthaleja\Character\NPC;

use App\Models\Anthaleja\Character\NPC;

class NPCMissionService
{
    public function assignDailyMissions()
    {
        $npcs = NPC::where('status', 'active')->get();

        foreach ($npcs as $npc) {
            $mission = $this->getRandomMission();
            echo "{$npc->name} has been assigned the mission: {$mission}.";

            // Reagisci all'evento economico corrente
            // $this->reactToEconomicChanges();
        }
    }

    // ----------------------------------------------------------------- //

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
