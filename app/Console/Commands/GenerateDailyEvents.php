<?php

namespace App\Console\Commands;

use App\Services\EventService;
use Illuminate\Console\Command;
use App\Models\Anthaleja\Character\Character;

class GenerateDailyEvents extends Command
{
    protected $signature = 'game:generate-daily-events';
    protected $description = 'Genera eventi casuali giornalieri per i personaggi';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(EventService $eventService)
    {
        $characters = Character::all();

        foreach ($characters as $character) {
            $eventService->generateRandomEvent($character);
        }

        $this->info('Eventi casuali generati con successo.');
    }
}
