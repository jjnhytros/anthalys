<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Anthaleja\AI\KnowledgeBaseSyncService;

class SyncKnowledgeBase extends Command
{
    protected $signature = 'knowledge:sync';
    protected $description = 'Sincronizza i dati della Wiki con la Knowledge Base';

    protected $syncService;

    public function __construct(KnowledgeBaseSyncService $syncService)
    {
        parent::__construct();
        $this->syncService = $syncService;
    }

    public function handle()
    {
        $this->syncService->syncArticles();
        $this->info('Knowledge Base sincronizzata con gli articoli della Wiki.');
    }
}
