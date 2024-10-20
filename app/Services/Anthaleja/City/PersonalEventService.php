<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\Character\Character;

class PersonalEventService
{
    public function triggerPersonalEvent(Character $character)
    {
        $eventType = $this->getRandomPersonalEvent();
        switch ($eventType) {
            case 'promotion':
                return $this->handlePromotion($character);
            case 'accident':
                return $this->handleAccident($character);
            case 'illness':
                return $this->handleIllness($character);
        }
    }

    protected function getRandomPersonalEvent()
    {
        $eventTypes = [
            'promotion' => 4,  // 4% di probabilità
            'accident' => 3,   // 3% di probabilità
            'illness' => 3,    // 3% di probabilità
        ];

        $random = rand(1, 100);
        $cumulative = 0;

        foreach ($eventTypes as $event => $probability) {
            $cumulative += $probability;
            if ($random <= $cumulative) {
                return $event;
            }
        }

        return 'promotion';  // Evento di fallback
    }

    protected function handlePromotion(Character $character)
    {
        $salaryIncrease = rand(12, 60);  // Aumento di stipendio casuale
        $character->cash += $salaryIncrease;
        $character->save();

        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'promotion',
            'details' => json_encode(['salary_increase' => $salaryIncrease]),
            'created_at' => now(),
        ]);

        return "Il personaggio {$character->username} ha ricevuto una promozione con aumento di {$salaryIncrease} AA.";
    }

    protected function handleAccident(Character $character)
    {
        $healthDecrease = rand(6, 24);  // Riduzione della salute
        $character->health = max(0, $character->health - $healthDecrease);
        $character->save();

        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'accident',
            'details' => json_encode(['health_decrease' => $healthDecrease]),
            'created_at' => now(),
        ]);

        return "Il personaggio {$character->username} ha subito un incidente, la sua salute è diminuita di {$healthDecrease} punti.";
    }

    protected function handleIllness(Character $character)
    {
        $reputationDecrease = rand(3, 12);  // Riduzione della reputazione
        $character->reputation = max(0, $character->reputation - $reputationDecrease);
        $character->save();

        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'illness',
            'details' => json_encode(['reputation_decrease' => $reputationDecrease]),
            'created_at' => now(),
        ]);

        return "Il personaggio {$character->username} è malato, la sua reputazione è diminuita di {$reputationDecrease} punti.";
    }
}
