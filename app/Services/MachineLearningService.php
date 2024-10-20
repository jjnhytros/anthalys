<?php

namespace App\Services;

use App\Models\Anthaleja\EventLog;
use Phpml\Classification\KNearestNeighbors;

class MachineLearningService
{
    public function trainModel()
    {
        $samples = [];
        $labels = [];

        // Raccogli i dati dai log degli eventi
        $eventLogs = EventLog::all();
        foreach ($eventLogs as $log) {
            $attributes = array_values($log->character_attributes);  // Usa gli attributi come input
            $samples[] = $attributes;
            $labels[] = $log->event_type;  // Usa il tipo di evento come etichetta
        }

        // Addestra il modello K-Nearest Neighbors
        $classifier = new KNearestNeighbors();
        $classifier->train($samples, $labels);

        return $classifier;  // Ritorna il modello addestrato
    }
}
