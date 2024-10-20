<?php

namespace App\Services\Anthaleja\Character;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\Character\Character\Mission;

class MissionService
{
    public function assignMission(Character $character)
    {
        $missionData = $this->getRandomMission();
        $mission = Mission::create([
            'character_id' => $character->id,
            'title' => $missionData['title'],
            'description' => $missionData['description'],
            'assigned_at' => now(),
        ]);

        // Log dell'assegnazione della missione
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'mission_assigned',
            'details' => json_encode([
                'mission_title' => $missionData['title'],
                'description' => $missionData['description'],
            ]),
            'created_at' => now(),
        ]);

        return "Missione assegnata al personaggio {$character->username}: {$missionData['title']}";
    }

    public function completeMission(Mission $mission)
    {
        $mission->status = 'completed';
        $mission->completed_at = now();
        $mission->save();

        // Premi per la missione completata
        $reward = rand(100, 500);  // Premio casuale in denaro
        $mission->character->cash += $reward;
        $mission->character->save();

        // Log del completamento della missione
        EventLog::create([
            'character_id' => $mission->character_id,
            'event_type' => 'mission_completed',
            'details' => json_encode([
                'mission_title' => $mission->title,
                'reward' => $reward,
            ]),
            'created_at' => now(),
        ]);

        return "Missione completata: {$mission->title}. Premio: {$reward} AA.";
    }

    protected function getRandomMission()
    {
        $missions = [
            ['title' => 'Trova una risorsa rara', 'description' => 'Cerca e trova una risorsa rara in uno dei quartieri.'],
            ['title' => 'Concludi un affare commerciale', 'description' => 'Concludi un affare con un altro personaggio per vendere risorse.'],
            ['title' => 'Partecipa a un evento sociale', 'description' => 'Partecipa a un evento sociale in uno dei quartieri per aumentare la tua reputazione.'],
        ];

        return $missions[array_rand($missions)];
    }
}
