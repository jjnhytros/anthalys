<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anthaleja\City\MapSquare;
use App\Services\Anthaleja\City\NeighborhoodEvolutionService;

class MonitorNeighborhoodsCommand extends Command
{
    protected $signature = 'monitor:neighborhoods';
    protected $description = 'Monitora i quartieri per verificare il declino delle risorse e registrare eventi di declino';

    protected $neighborhoodEvolutionService;

    public function __construct(NeighborhoodEvolutionService $neighborhoodEvolutionService)
    {
        parent::__construct();
        $this->neighborhoodEvolutionService = $neighborhoodEvolutionService;
    }

    public function handle()
    {
        $neighborhoods = MapSquare::all();  // Recupera tutti i quartieri (map squares)

        foreach ($neighborhoods as $neighborhood) {
            // Usa il servizio di evoluzione dei quartieri per monitorare il declino
            $declineMessage = $this->neighborhoodEvolutionService->monitorNeighborhoodDecline($neighborhood);

            // Output a console per verificare lo stato
            $this->info($declineMessage);
        }

        $this->info('Monitoraggio dei quartieri completato.');
    }
}
