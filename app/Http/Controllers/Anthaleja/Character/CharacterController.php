<?php

namespace App\Http\Controllers\Anthaleja\Character;

use App\Http\Controllers\Controller;
use App\Models\Anthaleja\Character\Character;

class CharacterController extends Controller
{
    // Funzione per l'interazione tra due Character (NPC o personaggi umani)
    public function interactWithCharacter($character1Id, $character2Id, $interactionType)
    {
        $character1 = Character::find($character1Id);
        $character2 = Character::find($character2Id);

        if ($character1 && $character2) {
            return "$character1->name interagisce con $character2->name.";
        }
        return "Character non trovato.";
    }

    // Funzione per assegnare task a un Character (NPC o umano)
    public function assignTaskToCharacter($characterId, $task, $priority = 1)
    {
        $character = Character::find($characterId);
        // Logica per assegnare task generico (potrebbe essere usata per NPC e Character normali)
        $character->current_task = $task;
        $character->task_priority = $priority;
        $character->save();
        return "Task assegnato a $character->name.";
    }
}
