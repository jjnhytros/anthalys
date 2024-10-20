<?php

namespace App\Services\Anthaleja\Character;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\Character\Character;

class AIControllerService
{
    public function handleNPCDecisions()
    {
        // Recupera tutti gli NPC, escludendo quelli con ID 2 (Government) e 4 (Bank)
        $npcCharacters = Character::where('is_npc', true)
            ->whereNotIn('id', [2, 4])  // Esclude gli NPC Government e Bank
            ->get();

        foreach ($npcCharacters as $npc) {
            // Decisioni dell'NPC in base al suo stato economico e sociale
            $this->makeEconomicDecision($npc);
            $this->makeSocialInteraction($npc);
            $this->makeMovementDecision($npc);
        }

        return "Decisioni degli NPC gestite.";
    }

    protected function makeEconomicDecision(Character $npc)
    {
        // Logica per gestire le decisioni economiche
        if ($npc->cash < 500) {
            // Se ha poco denaro, cerca un modo per guadagnare
            $income = rand(100, 500);  // Guadagno casuale
            $npc->cash += $income;
            $npc->save();

            // Log dell'azione economica
            EventLog::create([
                'character_id' => $npc->id,
                'event_type' => 'npc_economic_decision',
                'details' => json_encode([
                    'action' => 'earned_income',
                    'amount' => $income
                ]),
                'created_at' => now(),
            ]);

            return "NPC {$npc->username} ha guadagnato {$income} AA.";
        }

        return "NPC {$npc->username} non ha preso decisioni economiche.";
    }

    protected function makeSocialInteraction(Character $npc)
    {
        // Logica per gestire le interazioni sociali degli NPC
        $targetCharacter = Character::inRandomOrder()->first();
        $interaction = rand(0, 1) ? 'friendly' : 'hostile';

        // Log dell'interazione sociale
        EventLog::create([
            'character_id' => $npc->id,
            'event_type' => 'npc_social_interaction',
            'details' => json_encode([
                'interaction_type' => $interaction,
                'target_character' => $targetCharacter->username
            ]),
            'created_at' => now(),
        ]);

        return "NPC {$npc->username} ha avuto un'interazione {$interaction} con {$targetCharacter->username}.";
    }

    protected function makeMovementDecision(Character $npc)
    {
        // Logica per il movimento dell'NPC tra i rioni
        $newLocation = rand(1, 36);  // Quartiere casuale
        $npc->map_square_id = $newLocation;  // Aggiorna la posizione
        $npc->save();

        // Log dell'azione di movimento
        EventLog::create([
            'character_id' => $npc->id,
            'event_type' => 'npc_movement',
            'details' => json_encode([
                'new_location' => $newLocation
            ]),
            'created_at' => now(),
        ]);

        return "NPC {$npc->username} si è spostato nel rione con ID {$newLocation}.";
    }
}
