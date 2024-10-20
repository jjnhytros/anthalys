<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\City\MapSquare;
use App\Models\Anthaleja\City\LongTermEffect;

class LongTermEffectService
{
    public function processLongTermEffects()
    {
        $effects = LongTermEffect::where('remaining_days', '>', 0)->get();

        foreach ($effects as $effect) {
            // Applica l'effetto al quartiere o al personaggio
            if ($effect->map_square_id) {
                $this->applyEffectToMapSquare($effect);
            } elseif ($effect->character_id) {
                $this->applyEffectToCharacter($effect);
            }

            // Riduce la durata rimanente
            $effect->remaining_days -= 1;
            $effect->save();

            // Log dell'effetto applicato
            EventLog::create([
                'event_type' => 'long_term_effect_applied',
                'details' => json_encode([
                    'effect_type' => $effect->effect_type,
                    'remaining_days' => $effect->remaining_days,
                    'target' => $effect->map_square_id ? 'MapSquare: ' . $effect->map_square_id : 'Character: ' . $effect->character_id,
                ]),
                'created_at' => now(),
            ]);

            // Se la durata è scaduta, termina l'effetto
            if ($effect->remaining_days <= 0) {
                $effect->delete();

                // Log dell'effetto terminato
                EventLog::create([
                    'event_type' => 'long_term_effect_ended',
                    'details' => json_encode([
                        'effect_type' => $effect->effect_type,
                        'target' => $effect->map_square_id ? 'MapSquare: ' . $effect->map_square_id : 'Character: ' . $effect->character_id,
                    ]),
                    'created_at' => now(),
                ]);
            }
        }
    }

    protected function applyEffectToMapSquare(LongTermEffect $effect)
    {
        $mapSquare = MapSquare::find($effect->map_square_id);
        $impact = json_decode($effect->impact, true);

        // Applica l'impatto all'economia o alla popolazione del quartiere
        if (isset($impact['economic_decline'])) {
            $mapSquare->socio_economic_status = max(0, $mapSquare->socio_economic_status - $impact['economic_decline']);
        }
        if (isset($impact['population_loss'])) {
            $mapSquare->population_density = max(0, $mapSquare->population_density - $impact['population_loss']);
        }
        $mapSquare->save();
    }

    protected function applyEffectToCharacter(LongTermEffect $effect)
    {
        $character = Character::find($effect->character_id);
        $impact = json_decode($effect->impact, true);

        // Applica l'impatto alla reputazione o alle risorse del personaggio
        if (isset($impact['reputation_loss'])) {
            $character->reputation = max(0, $character->reputation - $impact['reputation_loss']);
        }
        if (isset($impact['resource_loss'])) {
            $character->cash = max(0, $character->cash - $impact['resource_loss']);
        }
        $character->save();
    }
}
