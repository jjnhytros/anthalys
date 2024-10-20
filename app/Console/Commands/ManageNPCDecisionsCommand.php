<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Anthaleja\Character\AIControllerService;

class ManageNPCDecisionsCommand extends Command
{
    protected $signature = 'npc:manage-decisions';
    protected $description = 'Gestisce le decisioni quotidiane degli NPC';

    protected $aiControllerService;

    public function __construct(AIControllerService $aiControllerService)
    {
        parent::__construct();
        $this->aiControllerService = $aiControllerService;
    }

    public function handle()
    {
        $result = $this->aiControllerService->handleNPCDecisions();
        $this->info($result);
    }
}
