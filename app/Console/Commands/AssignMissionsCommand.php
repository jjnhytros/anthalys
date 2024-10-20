<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anthaleja\Character\Character;
use App\Services\Anthaleja\Character\MissionService;

class AssignMissionsCommand extends Command
{
    protected $signature = 'missions:assign';
    protected $description = 'Assegna missioni casuali ai personaggi';

    protected $missionService;

    public function __construct(MissionService $missionService)
    {
        parent::__construct();
        $this->missionService = $missionService;
    }

    public function handle()
    {
        $characters = Character::inRandomOrder()->take(rand(1, 5))->get();  // Fino a 5 personaggi

        foreach ($characters as $character) {
            $missionMessage = $this->missionService->assignMission($character);
            $this->info($missionMessage);
        }

        $this->info('Missioni assegnate.');
    }
}
