<?php

namespace App\Services\Anthaleja\AI;

use App\Models\Anthaleja\AI\KnowledgeBase;
use App\Models\Anthaleja\Wiki\WikiArticle;

class KnowledgeBaseSyncService
{
    public function syncArticles()
    {
        // Recupera tutti gli articoli della Wiki
        $articles = WikiArticle::all();

        foreach ($articles as $article) {
            // Verifica se l'articolo esiste già nella knowledge base
            $existingEntry = KnowledgeBase::where('wiki_article_id', $article->id)->first();

            if (!$existingEntry) {
                // Crea una nuova entry nella knowledge base senza duplicare il contenuto
                KnowledgeBase::create([
                    'wiki_article_id' => $article->id,
                    'category_id' => $article->category_id,
                    'tags' => json_encode($article->tags()->pluck('name')->toArray()),
                    'type' => 'article'
                ]);
            }
        }
    }
}
