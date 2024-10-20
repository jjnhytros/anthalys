<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anthaleja\Character\Character;
use App\Services\Anthaleja\City\PersonalEventService;

class TriggerPersonalEventsCommand extends Command
{
    protected $signature = 'events:trigger-personal';
    protected $description = 'Genera eventi personali casuali per i personaggi';

    protected $personalEventService;

    public function __construct(PersonalEventService $personalEventService)
    {
        parent::__construct();
        $this->personalEventService = $personalEventService;
    }

    public function handle()
    {
        $characters = Character::inRandomOrder()->take(rand(1, 5))->get();  // Fino a 5 personaggi casuali

        foreach ($characters as $character) {
            $eventMessage = $this->personalEventService->triggerPersonalEvent($character);
            $this->info($eventMessage);
        }

        $this->info('Eventi personali generati.');
    }
}
