<?php

namespace App\Services;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\Character\Character;

class EventLoggingService
{
    public function logEvent(Character $character, $eventType, $context = [])
    {
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => $eventType,
            'character_attributes' => $character->getAttributes(),  // Stato degli attributi del personaggio
            'event_context' => $context,  // Altre informazioni contestuali come la posizione
        ]);
    }
}
