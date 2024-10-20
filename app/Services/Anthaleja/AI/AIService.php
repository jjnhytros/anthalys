<?php

namespace App\Services\Anthaleja\AI;

use GuzzleHttp\Client;
use Phpml\Dataset\ArrayDataset;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Anthaleja\Wiki\WikiArticle;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TokenCountVectorizer;

class AIService
{
    protected $httpClient;
    protected $markovChain = [];

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    public function generateArticle($seedWord, $length = 100)
    {
        if (empty($this->markovChain)) {
            $this->trainMarkovModel(); // Allena il modello se non è già stato allenato
        }

        $article = [];
        $currentWord = $seedWord;

        for ($i = 0; $i < $length; $i++) {
            $article[] = $currentWord;

            if (!isset($this->markovChain[$currentWord])) {
                break; // Interrompi se non ci sono più parole nella catena
            }

            // Scegli una parola successiva in base alla probabilità
            $nextWords = $this->markovChain[$currentWord];
            $currentWord = $this->chooseNextWord($nextWords);
        }

        return implode(' ', $article); // Restituisce l'articolo come stringa
    }

    public function suggestRelatedArticles(WikiArticle $article)
    {
        // Estrai tutti gli articoli dalla base di dati
        $articles = WikiArticle::all();

        $samples = [];
        $labels = [];

        foreach ($articles as $art) {
            $samples[] = $art->content;
            $labels[] = $art->title; // Usa i titoli come etichette
        }

        // Vettorizziamo i campioni usando il Bag of Words (TokenCountVectorizer)
        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        $vectorizer->fit($samples);
        $vectorizer->transform($samples);

        // Crea il classificatore KNN
        $classifier = new KNearestNeighbors();

        // Addestra il classificatore con i campioni e le etichette
        $classifier->train($samples, $labels);

        // Trasforma l'articolo corrente in un vettore numerico
        $currentSample = [$article->content];
        $vectorizer->transform($currentSample);

        // Prevedi gli articoli correlati
        $relatedTitles = $classifier->predict($currentSample[0]);

        // Verifica se la previsione è una singola stringa e la converte in array
        if (!is_array($relatedTitles)) {
            $relatedTitles = [$relatedTitles];
        }

        // Recupera gli articoli correlati basati sui titoli previsti
        $relatedArticles = WikiArticle::whereIn('title', $relatedTitles)->get();

        return $relatedArticles;
    }

    public function trainMarkovModel()
    {
        $articles = WikiArticle::all(); // Recupera tutti gli articoli dalla base di dati
        foreach ($articles as $article) {
            $this->addTextToMarkovChain($article->content); // Aggiunge il contenuto dell'articolo al modello Markov
        }
    }

    public function translateArticle($content, $targetLanguage)
    {
        // La tua chiave API di DeepL
        $apiKey = env('DEEPL_API_KEY');

        // Effettua la richiesta a DeepL
        $response = Http::post('https://api.deepl.com/v2/translate', [
            'auth_key' => $apiKey,
            'text' => $content,
            'target_lang' => strtoupper($targetLanguage), // Deepl richiede il codice lingua in maiuscolo
        ]);

        // Se la richiesta ha successo, restituiamo la traduzione
        if ($response->successful()) {
            return $response->json()['translations'][0]['text'];
        }

        // Logga l'errore per il debug in caso di fallimento
        Log::error('DeepL Translation failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return null; // In caso di errore
    }

    private function addTextToMarkovChain($text)
    {
        $words = explode(' ', $text); // Dividi il testo in parole
        $wordCount = count($words);

        for ($i = 0; $i < $wordCount - 1; $i++) {
            $currentWord = $words[$i];
            $nextWord = $words[$i + 1];

            // Aggiungi la parola successiva alla catena
            if (!isset($this->markovChain[$currentWord])) {
                $this->markovChain[$currentWord] = [];
            }

            if (isset($this->markovChain[$currentWord][$nextWord])) {
                $this->markovChain[$currentWord][$nextWord]++;
            } else {
                $this->markovChain[$currentWord][$nextWord] = 1;
            }
        }
    }

    private function chooseNextWord($nextWords)
    {
        $total = array_sum($nextWords);
        $random = rand(1, $total);

        foreach ($nextWords as $word => $count) {
            $random -= $count;
            if ($random <= 0) {
                return $word;
            }
        }

        return array_key_first($nextWords); // Restituisce la prima parola come fallback
    }
}
