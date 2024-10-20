<?php

namespace App\Services\Anthaleja\Wiki;

use App\Models\Anthaleja\Wiki\CharacterActivity;

class RecommendationMetricsService
{
    public function calculateRecommendationAccuracy()
    {
        // Ottieni le raccomandazioni che sono state presentate
        $totalRecommendations = CharacterActivity::where('action', 'recommendation_shown')->count();

        // Ottieni il numero di raccomandazioni che sono state effettivamente visualizzate
        $clickedRecommendations = CharacterActivity::where('action', 'view')->where('source', 'recommendation')->count();

        if ($totalRecommendations === 0) {
            return 0; // Evita la divisione per zero
        }

        // Calcola la precisione delle raccomandazioni
        return ($clickedRecommendations / $totalRecommendations) * 100; // Percentuale di precisione
    }

    public function calculateRecommendationConversionRate()
    {
        // Raccomandazioni visualizzate
        $viewsFromRecommendations = CharacterActivity::where('action', 'view')->where('source', 'recommendation')->count();

        // Interazioni sulle raccomandazioni (like, commenti)
        $interactionsFromRecommendations = CharacterActivity::whereIn('action', ['like', 'comment'])
            ->where('source', 'recommendation')
            ->count();

        if ($viewsFromRecommendations === 0) {
            return 0; // Evita la divisione per zero
        }

        return ($interactionsFromRecommendations / $viewsFromRecommendations) * 100; // Tasso di conversione
    }
}
