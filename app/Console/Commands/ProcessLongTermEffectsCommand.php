<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Anthaleja\City\LongTermEffectService;

class ProcessLongTermEffectsCommand extends Command
{
    protected $signature = 'effects:process-long-term';
    protected $description = 'Processa gli effetti a lungo termine';

    protected $longTermEffectService;

    public function __construct(LongTermEffectService $longTermEffectService)
    {
        parent::__construct();
        $this->longTermEffectService = $longTermEffectService;
    }

    public function handle()
    {
        $this->longTermEffectService->processLongTermEffects();
        $this->info('Effetti a lungo termine processati.');
    }
}
