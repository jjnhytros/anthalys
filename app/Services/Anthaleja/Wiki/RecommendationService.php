<?php

namespace App\Services\Anthaleja\Wiki;

use Phpml\Clustering\KMeans;
use Illuminate\Support\Facades\DB;
use Phpml\Regression\LeastSquares;
use Illuminate\Support\Facades\Cache;
use App\Models\Anthaleja\Wiki\WikiArticle;
use App\Services\Anthaleja\AI\LearningService;
use App\Models\Anthaleja\Wiki\CharacterActivity;

class RecommendationService
{
    protected $dataService;
    protected $learningService;

    public function __construct(RecommendationDataService $dataService, LearningService $learningService)
    {
        $this->dataService = $dataService;
        $this->learningService = $learningService;
    }

    public function generateClusteredRecommendations($userData)
    {
        // Prepara i dati da usare nel clustering (es. visualizzazioni, like, tempo di permanenza)
        $dataset = $this->prepareDataset($userData);

        // Inizializza il clustering K-Means con 3 cluster (ad esempio)
        $kmeans = new KMeans(3);
        $clusters = $kmeans->cluster($dataset);

        // Suggerisci articoli in base al cluster dell'utente
        return $this->recommendForCluster($clusters);
    }

    public function predictInterest($userData)
    {
        // Prepara i dati di allenamento (es. visualizzazioni, like, interazioni)
        $samples = $this->prepareTrainingData();
        $targets = $this->prepareTargets();

        // Inizializza il modello di regressione
        $regression = new LeastSquares();
        $regression->train($samples, $targets);

        // Prevedi l'interesse di ogni utente per gli articoli
        $predictions = [];
        foreach ($userData as $user) {
            $prediction = $regression->predict([$user['views'], $user['likes'], $user['interactions']]);
            $predictions[$user['id']] = $prediction;
        }

        return $predictions;
    }

    public function recommendArticlesForCharacter($characterId)
    {
        // Recupera i pesi dalla cache o dal database se non presenti
        $weights = Cache::remember('recommendation_weights', 3600, function () {
            return DB::table('weights')->pluck('weight', 'action')->toArray();
        });

        // Recupera tutte le attività del Character
        $activities = CharacterActivity::where('character_id', $characterId)
            ->whereIn('action', ['view', 'like', 'comment', 'time_spent'])
            ->get();

        // Crea un array per tenere traccia del punteggio di ciascun articolo
        $articleScores = [];

        foreach ($activities as $activity) {
            $articleId = $activity->article_id;
            $action = $activity->action;
            $value = $activity->value ?? 1; // Per il tempo di permanenza, "value" sarà il numero di secondi

            // Calcola il punteggio basato sul peso e sul valore
            $articleScores[$articleId] = ($articleScores[$articleId] ?? 0) + $weights[$action] * $value;
        }

        // Ordina gli articoli in base al punteggio decrescente
        arsort($articleScores);

        // Ottieni gli ID degli articoli ordinati in base al punteggio
        $sortedArticleIds = array_keys($articleScores);

        // Recupera gli articoli suggeriti, limitati a 5
        return WikiArticle::whereIn('id', $sortedArticleIds)
            ->limit(5)
            ->get();
    }

    public function suggestRelatedArticles(WikiArticle $article)
    {
        // Usa l'AI per suggerire infobox
        $suggestedInfobox = $this->learningService->suggestInfobox($article);

        // Usa l'AI per suggerire articoli correlati
        $relatedArticles = $this->learningService->suggestRelatedArticles($article->content);

        // Validazione dei risultati
        $validatedArticles = array_filter($relatedArticles, function ($article) {
            return $this->isValidArticle($article);
        });

        return [
            'infobox' => $suggestedInfobox,
            'related_articles' => $validatedArticles,
        ];
    }

    public function testModelAccuracy()
    {
        // Ottieni i dati di test reali
        $testData = $this->dataService->getTestData();

        $samples = [];
        $realValues = [];

        foreach ($testData as $userId => $data) {
            $samples[] = [
                $data['views'],
                $data['likes'],
                $data['comments'],
                $data['total_time_spent'],
                count($data['categories'])
            ];
            $realValues[$userId] = $this->calculateUserEngagement($data);
        }

        // Prevedi l'engagement utilizzando il modello addestrato
        $regression = new LeastSquares();
        $regression->train($samples, $realValues);

        $predictions = [];
        foreach ($testData as $userId => $data) {
            $predictions[$userId] = $regression->predict([
                $data['views'],
                $data['likes'],
                $data['comments'],
                $data['total_time_spent'],
                count($data['categories'])
            ]);
        }

        // Calcola l'accuratezza confrontando le previsioni con i valori reali
        return $this->calculateAccuracy($predictions, $realValues);
    }

    public function trainRegressionModel()
    {
        // Ottieni i dati reali per l'allenamento
        $userData = $this->dataService->getTrainingData();

        // Prepara i dati di allenamento
        $samples = [];
        $targets = [];

        foreach ($userData as $userId => $data) {
            // Includi tutte le nuove variabili
            $samples[] = [
                $data['views'],
                $data['likes'],
                $data['comments'],
                $data['total_time_spent'],      // Tempo totale trascorso sugli articoli
                count($data['categories'])      // Numero di categorie interagite
            ];
            // Target: calcolo dell'engagement basato su queste variabili
            $targets[] = $this->calculateUserEngagement($data);
        }

        // Inizializza il modello di regressione
        $regression = new LeastSquares();
        $regression->train($samples, $targets);
    }









    // ------------------------------------------------------------------------- //


    protected function calculateAccuracy($predictions, $realValues)
    {
        $totalError = 0;
        $n = count($predictions);

        foreach ($predictions as $userId => $predictedValue) {
            $realValue = $realValues[$userId];
            $totalError += abs($predictedValue - $realValue);
        }

        return 100 - ($totalError / $n); // Restituisce una percentuale di accuratezza
    }

    protected function calculateUserEngagement($data)
    {
        // Esempio semplice di calcolo di engagement
        return ($data['views'] * 0.5) + ($data['likes'] * 1.0) + ($data['comments'] * 1.5);
    }

    protected function getArticlesForCluster($clusterId)
    {
        // Logica di selezione articoli per il cluster specifico
        // Esempio: selezioniamo articoli popolari tra gli utenti del cluster
        return WikiArticle::where('cluster_id', $clusterId)->get();
    }

    protected function isValidArticle($article)
    {
        // Controlla la lunghezza del titolo o altre proprietà per validare l'articolo
        return strlen($article->title) > 5;  // Esempio di validazione
    }

    protected function prepareDataset($userData)
    {
        // Esempio: trasforma i dati degli utenti in vettori numerici per K-Means
        $dataset = [];
        foreach ($userData as $user) {
            $dataset[] = [
                $user['views'],         // Numero di visualizzazioni
                $user['likes'],         // Numero di like
                $user['time_spent'],    // Tempo di permanenza medio
                $user['category_views'] // Interazioni con categorie specifiche
            ];
        }
        return $dataset;
    }

    protected function prepareTargets()
    {
        // Esempio di interesse target (es. punteggio di interesse)
        return [4.5, 5.0, 3.0];
    }

    protected function prepareTrainingData()
    {
        // Esempio di dati di allenamento: [visualizzazioni, like, interazioni]
        return [
            [10, 2, 5],
            [15, 3, 7],
            [8, 1, 4],
            // Aggiungi più campioni
        ];
    }

    protected function recommendForCluster($clusters)
    {
        $recommendations = [];

        // Itera attraverso i cluster per fornire raccomandazioni
        foreach ($clusters as $clusterId => $usersInCluster) {
            // Qui potremmo selezionare articoli in base al comportamento del cluster
            foreach ($usersInCluster as $user) {
                // Aggiungi raccomandazioni specifiche per ogni utente nel cluster
                $recommendations[$user['id']] = $this->getArticlesForCluster($clusterId);
            }
        }

        return $recommendations;
    }
}
