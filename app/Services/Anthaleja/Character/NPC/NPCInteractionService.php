<?php

namespace App\Services\Anthaleja\Character\NPC;

use App\Models\Anthaleja\Character\NPC;
use App\Models\Anthaleja\Character\Character;

class NPCInteractionService
{
    public function npcInteractsWithCharacter($npcId, $characterId, $interactionType)
    {
        $npc = NPC::find($npcId);
        $character = Character::find($characterId);

        if ($npc && $npc->isNPC() && $character) {
            // Gestire diversi tipi di interazione tra NPC e Character
            switch ($interactionType) {
                case 'richiesta aiuto':
                    return "$npc->name chiede aiuto a $character->name.";
                case 'consegna task':
                    return "$npc->name ha assegnato un task a $character->name.";
                case 'dialogo':
                    return "$npc->name dialoga con $character->name.";
                default:
                    return "Tipo di interazione non definito.";
            }
        }
        return "NPC o Character non valido.";
    }

    public function npcInteractsWithNPC($npc1Id, $npc2Id, $interactionType)
    {
        $npc1 = NPC::find($npc1Id);
        $npc2 = NPC::find($npc2Id);

        if ($npc1 && $npc2 && $npc1->isNPC() && $npc2->isNPC()) {
            switch ($interactionType) {
                case 'scambio task':
                    return "$npc1->name ha scambiato compiti con $npc2->name.";
                case 'collaborazione':
                    return "$npc1->name e $npc2->name stanno collaborando.";
                default:
                    return "Tipo di interazione tra NPC non definito.";
            }
        }
        return "Uno o entrambi gli NPC non sono validi.";
    }
}
