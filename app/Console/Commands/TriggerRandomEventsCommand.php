<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anthaleja\City\MapSquare;
use App\Services\Anthaleja\City\EventGenerationService;

class TriggerRandomEventsCommand extends Command
{
    protected $signature = 'events:trigger-random';
    protected $description = 'Genera eventi casuali per i quartieri';

    protected $eventGenerationService;

    public function __construct(EventGenerationService $eventGenerationService)
    {
        parent::__construct();
        $this->eventGenerationService = $eventGenerationService;
    }

    public function handle()
    {
        $squares = MapSquare::all();

        foreach ($squares as $square) {
            // Genera un evento casuale per ogni quartiere
            $eventMessage = $this->eventGenerationService->generateRandomEvent($square);
            $this->info($eventMessage);
        }

        $this->info('Eventi casuali generati.');
    }
}
