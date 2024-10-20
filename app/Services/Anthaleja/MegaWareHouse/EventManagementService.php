<?php

namespace App\Services\Anthaleja\MegaWareHouse;

class EventManagementService
{
    protected $taskService;

    public function __construct(TaskAssignmentService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function handleEvent($eventType, $characterId = null)
    {
        switch ($eventType) {
            case 'guasto tecnico':
                $this->taskService->assignTasksToAllNPCs('Riparazione guasto', 1);
                return "Task di riparazione assegnato a tutti gli NPC.";
            case 'aumento domanda':
                $this->taskService->assignTasksToAllNPCs('Gestione aumento domanda', 2);
                return "Task di gestione aumento domanda assegnato.";
            case 'aiuto richiesto':
                if ($characterId) {
                    return $this->taskService->npcInteractsWithCharacter('richiesta aiuto', $characterId);
                }
                return "Character non specificato.";
            default:
                return "Evento non gestito.";
        }
    }
}
