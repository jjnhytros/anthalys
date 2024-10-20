<?php

namespace App\Services\Anthaleja\Wiki;

use App\Models\Anthaleja\Wiki\CharacterActivity;

class RecommendationDataService
{
    public function getTrainingData()
    {
        $activities = CharacterActivity::select('character_id', 'article_id', 'action', 'created_at', 'category_id')
            ->whereIn('action', ['view', 'like', 'comment'])
            ->get();

        $data = [];

        foreach ($activities as $activity) {
            $userId = $activity->character_id;
            $articleId = $activity->article_id;
            $categoryId = $activity->category_id;

            if (!isset($data[$userId])) {
                $data[$userId] = [
                    'views' => 0,
                    'likes' => 0,
                    'comments' => 0,
                    'categories' => [],
                    'total_time_spent' => 0
                ];
            }

            // Conta visualizzazioni, like, commenti e categorie visualizzate
            if ($activity->action == 'view') {
                $data[$userId]['views'] += 1;
                $data[$userId]['total_time_spent'] += rand(20, 500); // Simula il tempo di permanenza in secondi
            } elseif ($activity->action == 'like') {
                $data[$userId]['likes'] += 1;
            } elseif ($activity->action == 'comment') {
                $data[$userId]['comments'] += 1;
            }

            // Raccoglie categorie con cui l'utente interagisce
            if (!in_array($categoryId, $data[$userId]['categories'])) {
                $data[$userId]['categories'][] = $categoryId;
            }
        }

        return $data;
    }

    public function getTestData()
    {
        // Ottieni dati diversi da quelli di allenamento (ad esempio, attività degli ultimi 30 giorni)
        $activities = CharacterActivity::select('character_id', 'article_id', 'action', 'created_at', 'category_id')
            ->whereIn('action', ['view', 'like', 'comment'])
            ->where('created_at', '>=', now()->subDays(30)) // Limita a un periodo recente
            ->get();

        $data = $this->processActivityData($activities);

        return $data;
    }

    protected function processActivityData($activities)
    {
        $data = [];

        foreach ($activities as $activity) {
            $userId = $activity->character_id;
            $articleId = $activity->article_id;
            $categoryId = $activity->category_id;

            if (!isset($data[$userId])) {
                $data[$userId] = [
                    'views' => 0,
                    'likes' => 0,
                    'comments' => 0,
                    'categories' => [],
                    'total_time_spent' => 0
                ];
            }

            // Conta visualizzazioni, like, commenti e categorie visualizzate
            if ($activity->action == 'view') {
                $data[$userId]['views'] += 1;
                $data[$userId]['total_time_spent'] += rand(20, 500); // Simula il tempo di permanenza
            } elseif ($activity->action == 'like') {
                $data[$userId]['likes'] += 1;
            } elseif ($activity->action == 'comment') {
                $data[$userId]['comments'] += 1;
            }

            // Raccoglie categorie con cui l'utente interagisce
            if (!in_array($categoryId, $data[$userId]['categories'])) {
                $data[$userId]['categories'][] = $categoryId;
            }
        }

        return $data;
    }
}
