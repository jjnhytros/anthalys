<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\City\MapSquare;
use App\Models\Anthaleja\City\LongTermEffect;

class EventGenerationService
{
    public function generateRandomEvent(MapSquare $square)
    {
        $eventType = $this->getRandomEventType();
        switch ($eventType) {
            case 'economic_crisis':
                return $this->handleEconomicCrisis($square);
            case 'natural_disaster':
                return $this->handleNaturalDisaster($square);
            case 'social_event':
                return $this->handleSocialEvent($square);
        }
    }

    protected function shouldTriggerEvent()
    {
        // Riduciamo ulteriormente la probabilità complessiva di generare un evento
        return rand(1, max: 10000) <= 5;  // Solo il 5% di probabilità di generare un evento in ogni ciclo
    }

    protected function getRandomEventType()
    {
        // Definisce una probabilità per ogni tipo di evento
        $eventTypes = [
            'economic_crisis' => 3,
            'natural_disaster' => 2,
            'social_event' => 5,
        ];

        if ($this->shouldTriggerEvent()) {

            $random = rand(1, 10000);
            $cumulative = 0;

            foreach ($eventTypes as $event => $probability) {
                $cumulative += $probability;
                if ($random <= $cumulative) {
                    return $event;
                }
            }
        }

        return 'social_event'; // Evento di fallback
    }

    protected function handleEconomicCrisis(MapSquare $square)
    {
        $impact = rand(10, 30);  // Impatto immediato
        $square->socio_economic_status = max(0, $square->socio_economic_status - $impact);
        $square->save();

        // Crea un effetto a lungo termine per ridurre l'economia nei giorni successivi
        LongTermEffect::create([
            'map_square_id' => $square->id,
            'effect_type' => 'economic_decline',
            'duration' => 5,  // Effetto della durata di 5 giorni
            'remaining_days' => 5,
            'impact' => json_encode(['economic_decline' => rand(5, 10)]),  // Riduzione giornaliera
        ]);

        // Log dell'evento
        EventLog::create([
            'event_type' => 'economic_crisis',
            'details' => json_encode([
                'square' => $square->sector_name,
                'immediate_impact' => $impact,
            ]),
            'created_at' => now(),
        ]);

        return "Crisi economica nel rione {$square->sector_name}: stato socio-economico ridotto di {$impact} punti.";
    }

    protected function handleNaturalDisaster(MapSquare $square)
    {
        $destroyedBuildings = rand(1, 5);
        $square->current_buildings = max(0, $square->current_buildings - $destroyedBuildings);
        $square->save();

        // Log dell'evento
        EventLog::create([
            'event_type' => 'natural_disaster',
            'details' => json_encode([
                'square' => $square->sector_name,
                'destroyed_buildings' => $destroyedBuildings,
            ]),
            'created_at' => now(),
        ]);

        return "Disastro naturale nel rione {$square->sector_name}: {$destroyedBuildings} edifici distrutti.";
    }

    protected function handleSocialEvent(MapSquare $square)
    {
        $populationChange = rand(-50, 50);  // Aumento o diminuzione della popolazione
        $square->population_density = max(0, $square->population_density + $populationChange);
        $square->save();

        // Log dell'evento
        EventLog::create([
            'event_type' => 'social_event',
            'details' => json_encode([
                'square' => $square->sector_name,
                'population_change' => $populationChange,
            ]),
            'created_at' => now(),
        ]);

        return "Evento sociale nel rione {$square->sector_name}: popolazione cambiata di {$populationChange}.";
    }

    protected function handleFestivalEvent(MapSquare $square)
    {
        $economicBoost = rand(5, 15);  // Aumento economico durante il festival
        $square->socio_economic_status = min(100, $square->socio_economic_status + $economicBoost);
        $square->save();

        EventLog::create([
            'event_type' => 'festival',
            'details' => json_encode([
                'square' => $square->sector_name,
                'economic_boost' => $economicBoost,
            ]),
            'created_at' => now(),
        ]);

        return "Festival nel rione {$square->sector_name}: economia migliorata di {$economicBoost} punti.";
    }

    protected function handleRiotEvent(MapSquare $square)
    {
        $affectedCharacters = $square->characters->take(rand(1, 3));  // Colpisce fino a 3 personaggi
        foreach ($affectedCharacters as $character) {
            $character->reputation = max(0, $character->reputation - rand(10, 20));  // Riduce la reputazione
            $character->save();

            EventLog::create([
                'character_id' => $character->id,
                'event_type' => 'riot',
                'details' => json_encode([
                    'square' => $square->sector_name,
                    'reputation_loss' => $character->reputation,
                ]),
                'created_at' => now(),
            ]);
        }

        return "Rivolta nel rione {$square->sector_name}: la reputazione dei personaggi è diminuita.";
    }

    protected function handleEconomicBoom(MapSquare $square)
    {
        $resourceBoost = rand(50, 100);  // Aumento delle risorse durante il boom economico
        $square->resources->each(function ($resource) use ($resourceBoost) {
            $resource->amount += $resourceBoost;
            $resource->save();
        });

        EventLog::create([
            'event_type' => 'economic_boom',
            'details' => json_encode([
                'square' => $square->sector_name,
                'resource_boost' => $resourceBoost,
            ]),
            'created_at' => now(),
        ]);

        return "Boom economico nel rione {$square->sector_name}: le risorse sono aumentate.";
    }




    protected function applyLongTermEffect($square, $effectType, $duration)
    {
        for ($i = 0; $i < $duration; $i++) {
            // Log dell'effetto a lungo termine per i giorni successivi
            EventLog::create([
                'event_type' => 'long_term_effect',
                'details' => json_encode([
                    'square' => $square->sector_name,
                    'effect_type' => $effectType,
                    'day' => $i + 1,
                ]),
                'created_at' => now()->addDays($i),
            ]);
        }

        return "Effetto a lungo termine applicato al rione {$square->sector_name}.";
    }
}
