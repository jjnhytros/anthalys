<?php

namespace App\Services\Anthaleja\MegaWareHouse;

use App\Models\Anthaleja\Character\NPC;

class TaskAssignmentService
{
    public function assignTaskToNPC($npcId, $task, $priority = 1)
    {
        $npc = NPC::find($npcId);
        if ($npc && $npc->isNPC()) {
            $npc->assignTask($task, $priority);
            return "Task assegnato a NPC: $npc->name";
        }
        return "NPC non trovato o non valido.";
    }

    public function assignTasksToAllNPCs($task, $priority = 1)
    {
        $npcs = NPC::where('is_npc', true)->get();
        foreach ($npcs as $npc) {
            $npc->assignTask($task, $priority);
        }
        return "Task assegnato a tutti gli NPC.";
    }
}
