<?php

namespace App\Http\Controllers\Anthaleja\Character;

use App\Models\Anthaleja\Character\Character;
use App\Services\Anthaleja\NPC\NPCInteractionService;
use App\Services\Anthaleja\MegaWareHouse\TaskAssignmentService;

class NPCController extends CharacterController
{
    protected $taskService;
    protected $interactionService;

    public function __construct(TaskAssignmentService $taskService, NPCInteractionService $interactionService)
    {
        $this->taskService = $taskService;
        $this->interactionService = $interactionService;
    }

    // Funzione per gestire le interazioni specifiche tra NPC
    public function interactWithNPC($npc1Id, $npc2Id, $interactionType)
    {
        return $this->interactionService->npcInteractsWithNPC($npc1Id, $npc2Id, $interactionType);
    }

    // Funzione per assegnare task specifici agli NPC
    public function assignTaskToNPC($npcId, $task, $priority = 1)
    {
        return $this->taskService->assignTaskToNPC($npcId, $task, $priority);
    }

    // Sovrascrivi funzioni specifiche se necessario
    public function assignTaskToCharacter($characterId, $task, $priority = 1)
    {
        $character = Character::find($characterId);
        if ($character->is_npc) {
            // Se il personaggio è un NPC, usa la logica specifica per NPC
            return $this->taskService->assignTaskToNPC($characterId, $task, $priority);
        } else {
            // Altrimenti, usa la logica standard di CharacterController
            return parent::assignTaskToCharacter($characterId, $task, $priority);
        }
    }
}
