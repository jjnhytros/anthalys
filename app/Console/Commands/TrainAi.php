<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Anthaleja\AI\LearningService;

class TrainAi extends Command
{
    protected $signature = 'ai:train';
    protected $description = 'Addestra il modello AI sui contenuti della Wiki';

    protected $learningService;

    public function __construct(LearningService $learningService)
    {
        parent::__construct();
        $this->learningService = $learningService;
    }

    public function handle()
    {
        // Avvia il training dell'AI
        $this->learningService->train();
        $this->info('AI trained successfully!');
    }
}
