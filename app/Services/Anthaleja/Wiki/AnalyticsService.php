<?php

namespace App\Services\Anthaleja\Wiki;

use App\Models\Anthaleja\Wiki\WikiPortal;
use App\Models\Anthaleja\Wiki\WikiArticle;
use App\Models\Anthaleja\Wiki\WikiCategory;

class AnalyticsService
{
    public function calculateArticleStats()
    {
        $articles = WikiArticle::all();

        $totalArticles = $articles->count();
        $averageLength = $articles->avg(function ($article) {
            return str_word_count($article->content);
        });

        // Aggiungi ulteriori statistiche se necessario

        return [
            'total_articles' => $totalArticles,
            'average_length' => $averageLength,
            // Aggiungi altre statistiche qui
        ];
    }
    public function calculatePortalStats()
    {
        return [
            'totalPortals' => WikiPortal::count(),
            // Puoi aggiungere ulteriori statistiche qui, ad esempio, il numero di articoli associati
            'articlesPerPortal' => WikiPortal::withCount('articles')->get()->pluck('articles_count'),
        ];
    }
    public function calculateCategoryStats()
    {
        return [
            'totalCategories' => WikiCategory::count(),
            // Puoi aggiungere ulteriori statistiche qui, ad esempio, il numero di articoli per categoria
            'articlesPerCategory' => WikiCategory::withCount('articles')->get()->pluck('articles_count'),
        ];
    }
}
