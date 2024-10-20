<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anthaleja\Character\Character;
use App\Services\Anthaleja\Character\RealEstateService;

class ManageRealEstateCommand extends Command
{
    protected $signature = 'realestate:manage';
    protected $description = 'Gestisci le transazioni immobiliari per i personaggi';

    protected $realEstateService;

    public function __construct(RealEstateService $realEstateService)
    {
        parent::__construct();
        $this->realEstateService = $realEstateService;
    }

    public function handle()
    {
        $characters = Character::inRandomOrder()->take(rand(1, 5))->get();

        foreach ($characters as $character) {
            $action = rand(0, 1) ? 'purchase' : 'rent';
            $mapSquareId = rand(1, 36);  // Quartiere casuale
            $propertyType = rand(0, 1) ? 'residenziale' : 'commerciale';

            if ($action === 'purchase') {
                $message = $this->realEstateService->purchaseProperty($character, $mapSquareId, $propertyType);
            } else {
                $message = $this->realEstateService->rentProperty($character, $mapSquareId, $propertyType);
            }

            $this->info($message);
        }

        $this->info('Transazioni immobiliari completate.');
    }
}
