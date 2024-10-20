<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anthaleja\Wiki\WikiArticle;
use App\Services\Anthaleja\Wiki\ModerationService;

class ModerateContentCommand extends Command
{
    protected $signature = 'ath:moderate-content';
    protected $description = 'Automatically moderate articles for inappropriate content';

    protected $moderationService;

    public function __construct(ModerationService $moderationService)
    {
        parent::__construct();
        $this->moderationService = $moderationService;
    }

    public function handle()
    {
        // Ottieni tutti gli articoli
        $articles = WikiArticle::all();

        foreach ($articles as $article) {
            if ($this->moderationService->containsOffensiveLanguage($article->content)) {
                $this->info('Offensive content detected in article: ' . $article->title);
                // Puoi aggiungere logica per segnalare l'articolo o notificare un amministratore
            }

            if ($this->moderationService->isSpam($article->content)) {
                $this->info('Spam detected in article: ' . $article->title);
            }
        }
    }
}
