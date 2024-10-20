<?php

namespace App\Services\Anthaleja\Character\NPC;

use App\Models\Anthaleja\Character\NPCReputation;

class NPCReputationService
{
    public function updateReputationOnTaskCompletion($npcId)
    {
        $reputation = NPCReputation::where('npc_id', $npcId)->first();
        if ($reputation) {
            $reputation->tasks_completed += 1;
            $reputation->calculateReputation();
            return "Reputazione aggiornata per NPC $npcId.";
        }
        return "Reputazione non trovata per NPC $npcId.";
    }

    public function updateReputationOnInteraction($npcId, $interactionType)
    {
        $reputation = NPCReputation::where('npc_id', $npcId)->first();
        if ($reputation) {
            $reputation->interactions += 1;
            // Puoi aggiungere diverse logiche per modificare il punteggio in base al tipo di interazione
            $reputation->calculateReputation();
            return "Reputazione aggiornata per NPC $npcId dopo un'interazione.";
        }
        return "Reputazione non trovata per NPC $npcId.";
    }

    public function giveFeedbackToNPC($npcId, $feedbackScore)
    {
        $reputation = NPCReputation::where('npc_id', $npcId)->first();
        if ($reputation) {
            $reputation->feedback_received += $feedbackScore;
            $reputation->calculateReputation();
            return "Feedback ricevuto per NPC $npcId.";
        }
        return "Reputazione non trovata per NPC $npcId.";
    }
}
