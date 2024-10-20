<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Anthaleja\City\RotateOldEventLogsService;

class RotateLogsCommand extends Command
{
    protected $signature = 'logs:rotate';
    protected $description = 'Ruota i log più vecchi di un certo numero di giorni';

    protected $rotateOldEventLogsService;

    public function __construct(RotateOldEventLogsService $rotateOldEventLogsService)
    {
        parent::__construct();
        $this->rotateOldEventLogsService = $rotateOldEventLogsService;
    }

    public function handle()
    {
        $result = $this->rotateOldEventLogsService->rotateLogs(90);  // Default: log più vecchi di 90 giorni
        $this->info($result);
    }
}
