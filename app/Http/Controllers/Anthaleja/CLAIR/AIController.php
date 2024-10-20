<?php

namespace App\Http\Controllers\Anthaleja\CLAIR;

use Phpml\ModelManager;
use Illuminate\Http\Request;
use Phpml\Classification\SVC;
use Phpml\Dataset\CsvDataset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Phpml\Classification\DecisionTree;
use Phpml\SupportVectorMachine\Kernel;
use App\Models\Anthaleja\CLAIR\ApiData;
use App\Models\Anthaleja\CLAIR\Dataset;
use Illuminate\Support\Facades\Storage;
use App\Models\Anthaleja\CLAIR\ApiSource;
use Phpml\Classification\KNearestNeighbors;


class AIController extends Controller
{
    // Metodo per ottenere dati da un'API salvata nel database
    public function fetchDataFromApiSource($apiSourceId)
    {
        // Recupera l'API Source dal database (in questo caso TMDB)
        $apiSource = ApiSource::findOrFail($apiSourceId);

        if ($apiSource->name === 'TMDB API') {
            $client = new \GuzzleHttp\Client();

            // dd(vars: $client);
            try {
                // Esegui la richiesta API a TMDB
                $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/popular', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiSource->api_key,
                        'accept' => 'application/json',
                    ],
                ]);
                $responseData = json_decode(
                    $response->getBody(),
                    true
                );

                // Verifica che $responseData sia un array e contenga il campo 'results'
                if (is_array($responseData) && isset($responseData['results'])) {
                    $results = $responseData['results'];

                    // Assicurati che $results sia un array prima di accedervi
                    if (is_array($results)) {
                        foreach ($results as $movie) {
                            // Accedi ai campi del film se sono disponibili
                            if (isset($movie['title'])) {
                                echo $movie['title'] . '<br>';
                            } else {
                                echo "Titolo non disponibile<br>";
                            }
                        }
                    } else {
                        return redirect()->back()->with('error', 'Il formato dei dati restituiti non è corretto.');
                    }
                } else {
                    return redirect()->back()->with('error', 'La risposta API non contiene il campo "results".');
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Errore durante la richiesta TMDB API: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'API non supportata.');
    }

    private function generateIdentifier($data)
    {
        // Supponiamo di utilizzare un campo specifico come identificatore (ad esempio un ID)
        return isset($data['id']) ? $data['id'] : md5(json_encode($data));
    }

    // Metodo per addestrare il modello utilizzando dati da un'API online
    public function trainModelFromAPI(Request $request)
    {
        // Recupera tutte le fonti API disponibili nel database
        $apiSources = ApiSource::all();

        if ($apiSources->isEmpty()) {
            return redirect()->back()->with('error', __('Nessuna fonte API trovata.'));
        }

        // Seleziona automaticamente l'algoritmo (ad esempio, SVM)
        $algorithm = 'svm';  // Puoi anche cambiarlo con 'knn' o 'decision_tree'

        // Inizializza le variabili per le caratteristiche (samples), le etichette (labels) e il vocabolario
        $samples = [];
        $labels = [];
        $vocabulary = $this->loadVocabulary(); // Carica il vocabolario esistente o crea uno nuovo

        // Cicla su tutte le fonti API
        foreach ($apiSources as $apiSource) {
            // Recupera i dati dall'API corrente
            $data = $this->fetchDataFromApiSource($apiSource->id);

            // Itera sui dati recuperati dall'API
            foreach ($data as $entry) {
                // Supponiamo che i dati siano divisi in 'features' e 'label'
                $features = explode(' ', $entry['features']);  // Esempio di split del testo in parole
                $target = $entry['label'];

                // Aggiungi le nuove parole al vocabolario solo se mancano
                $vocabulary = $this->updateVocabulary($features, $vocabulary);

                // Trasforma le caratteristiche in numeri usando Bag of Words
                $numeric_features = $this->transformToBagOfWords($features, $vocabulary);

                $samples[] = $numeric_features;
                $labels[] = $target;
            }
        }

        // Salva il vocabolario aggiornato
        $this->saveVocabulary($vocabulary);

        // Addestra il modello con i dati recuperati
        $classifier = $this->chooseAlgorithm($algorithm);
        $classifier->train($samples, $labels);

        // Salva il modello addestrato
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, storage_path('app/models/clair_model.model'));

        return redirect()->back()->with('success', __('Modello addestrato con successo utilizzando tutte le fonti API.'));
    }

    // Metodo per selezionare l'algoritmo scelto dall'utente
    private function chooseAlgorithm($algorithm)
    {
        switch ($algorithm) {
            case 'svm':
                return new SVC(Kernel::LINEAR, $cost = 1000);
            case 'knn':
                return new KNearestNeighbors();
            case 'decision_tree':
                return new DecisionTree();
            default:
                return new SVC(
                    Kernel::LINEAR,
                    $cost = 1000
                ); // Predefinito: SVM
        }
    }

    // Metodo per caricare il vocabolario esistente o crearne uno nuovo
    private function loadVocabulary()
    {
        if (Storage::exists('models/vocabulary.json')) {
            return json_decode(Storage::get('models/vocabulary.json'), true);
        }
        return []; // Se il file non esiste, restituisce un vocabolario vuoto
    }

    // Metodo per salvare il vocabolario aggiornato
    private function saveVocabulary($vocabulary)
    {
        Storage::put('models/vocabulary.json', json_encode($vocabulary));
    }

    // Metodo per aggiornare il vocabolario solo con parole mancanti
    private function updateVocabulary($features, $vocabulary)
    {
        foreach ($features as $word) {
            if (!in_array($word, $vocabulary)) {
                $vocabulary[] = $word; // Aggiungi la parola solo se non è già presente
            }
        }
        return $vocabulary;
    }

    // Metodo per trasformare le caratteristiche in numeri (Bag of Words)
    private function transformToBagOfWords($features, $vocabulary)
    {
        $numeric_features = [];

        // Per ogni parola nel vocabolario, assegna 1 se la parola è presente, altrimenti 0
        foreach ($vocabulary as $word) {
            $numeric_features[] = in_array($word, $features) ? 1 : 0;
        }

        return $numeric_features;
    }

    // Metodo per mostrare il form di predizione
    public function predictForm()
    {
        return view('anthaleja.clair.predict');
    }

    // Metodo per gestire la predizione
    public function predict(Request $request)
    {
        // Carica il vocabolario salvato
        $vocabulary = $this->loadVocabulary();

        // Validazione del campo di input
        $request->validate(['data' => 'required|string']);

        // Recupera il testo inserito dall'utente (o da una fonte online)
        $text = $request->input('data');
        $features = preg_split('/\s+/', trim($text));

        // Aggiungi nuove parole al vocabolario dinamicamente
        foreach ($features as $word) {
            if (!in_array($word, $vocabulary)) {
                $vocabulary[] = $word;  // Aggiungi la parola se non è presente nel vocabolario
            }
        }

        // Trasforma il testo in numeri usando il vocabolario aggiornato
        $numeric_features = $this->transformToBagOfWords($features, $vocabulary);

        // Carica il modello addestrato
        $modelManager = new ModelManager();
        $classifier = $modelManager->restoreFromFile(storage_path('app/models/clair_model.model'));

        // Esegui la predizione
        $prediction = $classifier->predict([$numeric_features]);

        // Mostra il risultato
        return view('anthaleja.clair.result', ['prediction' => $prediction[0]]);
    }
}
