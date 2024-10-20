<?php

namespace App\Services\Anthaleja\AI;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Anthaleja\AI\KnowledgeBase;
use App\Models\Anthaleja\Wiki\WikiArticle;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TokenCountVectorizer;

class LearningService
{
    protected $classifier;

    public function __construct()
    {
        // Inizializza il classificatore K-Nearest Neighbors
        $this->classifier = new KNearestNeighbors();
    }

    public function getPopularArticles()
    {
        // Supponiamo di avere un campo 'views' negli articoli
        return WikiArticle::orderBy('views', 'desc')->take(5)->get();
    }

    public function suggestInfobox(WikiArticle $article)
    {
        // Verifica se l'articolo appartiene a una categoria che supporta infobox
        $category = $article->category;
        if (!$category) {
            return null;
        }

        // Estrarre informazioni chiave dal contenuto
        $keyInfo = $this->extractKeyInformation($article->content, $category->name);

        // Mappa le informazioni ai campi di un template di infobox
        $infoboxTemplate = WikiInfoboxTemplate::getByType($category->name);
        if (!$infoboxTemplate) {
            return null; // Nessun template per questa categoria
        }

        // Genera l'infobox riempiendo i campi del template con le informazioni estratte
        return TemplateHelpers::renderInfobox($category->name, $keyInfo);
    }


    public function suggestRelatedArticles($content)
    {
        // Tokenizza e vettorizza il contenuto corrente
        $sample = [$content];
        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        $vectorizer->fit($sample);
        $vectorizer->transform($sample);

        // Prevedi articoli correlati
        $relatedArticles = $this->classifier->predict($sample[0]);

        // Verifica se la previsione è una singola stringa, quindi la convertiamo in array
        if (!is_array($relatedArticles)) {
            $relatedArticles = [$relatedArticles];
        }

        return $relatedArticles;
    }

    public function train()
    {
        // Recupera tutti gli articoli dalla Knowledge Base
        $entries = WikiArticle::all(); // Assumiamo che la tabella contenga articoli

        $samples = [];
        $labels = [];

        foreach ($entries as $entry) {
            $samples[] = $entry->content; // Usa il contenuto dell'articolo come sample
            $labels[] = $entry->title;    // Usa il titolo come label
        }

        // Tokenizza i contenuti e vettorizza
        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        $vectorizer->fit($samples);
        $vectorizer->transform($samples);

        // Addestra il modello KNN con i contenuti vettorizzati
        $this->classifier->train($samples, $labels);
    }

    // ------------------------------------------------------------------------- //

    protected function extractKeyInformation($content, $categoryName)
    {
        // Logica di estrazione specifica per ciascuna categoria
        $info = [];

        switch (strtolower($categoryName)) {
            case 'person':
                // Estrazione di informazioni come nome, data di nascita, ecc.
                $info['name'] = $this->extractName($content);
                $info['birth_date'] = $this->extractDate($content);
                $info['occupation'] = $this->extractOccupation($content);
                break;

            case 'city':
                // Estrazione di informazioni come popolazione, regione, ecc.
                $info['city_name'] = $this->extractCityName($content);
                $info['population'] = $this->extractPopulation($content);
                $info['region'] = $this->extractRegion($content);
                break;

                // Aggiungi altre categorie qui
            default:
                // Default extraction logic if needed
                break;
        }

        return $info;
    }

    protected function extractCityName($content)
    {
        // Implementa logica di estrazione del nome della città
        return 'Città Estratta';
    }


    protected function extractDate($content)
    {
        // Implementa logica di estrazione delle date
        return '01-01-1900';
    }

    protected function extractName($content)
    {
        // Implementa logica di estrazione del nome
        return 'Nome Estratto';
    }

    protected function extractOccupation($content)
    {
        // Implementa logica di estrazione della professione
        return 'Professione Estratta';
    }

    protected function extractPopulation($content)
    {
        // Implementa logica di estrazione della popolazione
        return '100000';
    }

    protected function extractRegion($content)
    {
        // Implementa logica di estrazione della regione
        return 'Regione Estratta';
    }
}
