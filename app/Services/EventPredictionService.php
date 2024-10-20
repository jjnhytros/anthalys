<?php

namespace App\Services;

use App\Models\Anthaleja\Character\Character;
use App\Services\Anthaleja\City\EventGenerationService;

class EventPredictionService extends EventGenerationService
{
    protected $mlService;

    public function __construct(MachineLearningService $mlService, EventLoggingService $eventLogger)
    {
        // Inietta il servizio di machine learning e il logger degli eventi
        // parent::__construct($eventLogger);
        $this->mlService = $mlService;
    }

    public function generateEventWithAI(Character $character)
    {
        // Addestra o carica il modello
        $classifier = $this->mlService->trainModel();

        // Estrai gli attributi del personaggio utilizzando i metodi personalizzati
        $attributes = [
            $character->getAttributesField('health') ?? 100,
            $character->getAttributesField('cash') ?? 0,
        ];

        // Usa il modello per predire l'evento
        $predictedEvent = $classifier->predict([$attributes]);

        // Genera l'evento predetto
        return $this->generateEventFromPrediction($character, $predictedEvent);
    }

    protected function generateEventFromPrediction(Character $character, $predictedEvent)
    {
        // Riutilizza le funzioni definite nel servizio EventGenerationService
        // switch ($predictedEvent) {
        //     case 'health_crisis':
        //         return $this->handleHealthCrisis($character);
        //     case 'investment_opportunity':
        //         return $this->handleInvestmentOpportunity($character);
        //     default:
        //         return $this->handleRoutineEvent($character);
        // }
    }
}
