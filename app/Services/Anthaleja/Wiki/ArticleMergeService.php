<?php

namespace App\Services\Anthaleja\Wiki;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\Anthaleja\Wiki\WikiArticle;

class ArticleMergeService
{
    /**
     * Fonde due articoli in uno.
     *
     * @param WikiArticle $article1
     * @param WikiArticle $article2
     * @param array $mergeOptions
     * @return WikiArticle
     */
    public function mergeArticles(WikiArticle $article1, WikiArticle $article2, array $mergeOptions)
    {
        // Esegui la fusione basata sulle opzioni fornite
        $mergedArticle = new WikiArticle();

        // Titolo - Mantieni il titolo selezionato
        $mergedArticle->title = $mergeOptions['title'] ?? $article1->title;

        // Contenuto - Unisci il contenuto o usa uno dei due
        if (isset($mergeOptions['content'])) {
            $mergedArticle->content = $mergeOptions['content'];
        } else {
            $mergedArticle->content = $article1->content . "\n\n" . $article2->content;
        }

        // Categorie - Unisci le categorie
        $mergedArticle->categories()->syncWithoutDetaching($article1->categories->pluck('id')->toArray());
        $mergedArticle->categories()->syncWithoutDetaching($article2->categories->pluck('id')->toArray());

        // Assegna altri campi comuni
        $mergedArticle->character_id = $article1->character_id;
        $mergedArticle->slug = Str::slug($mergedArticle->title);

        // Salva l'articolo fuso
        $mergedArticle->save();

        // Elimina i vecchi articoli
        $article1->delete();
        $article2->delete();

        return $mergedArticle;
    }

    public function splitIntoSections($content)
    {
        $sections = preg_split("/(\n\s*\n)|(\n#)/", $content);
        return array_filter($sections); // Rimuove eventuali sezioni vuote
    }

    public function mergeArticlesWithSections(WikiArticle $article1, WikiArticle $article2, array $selectedSections)
    {
        $mergedArticle = new WikiArticle();

        // Titolo - Usa il titolo dell'articolo 1 o qualsiasi altro titolo impostato
        $mergedArticle->title = $selectedSections['title'] ?? $article1->title;

        // Unisci le sezioni selezionate
        $mergedContent = implode("\n\n", $selectedSections['content']);
        $mergedArticle->content = $mergedContent;

        // Categorie - Unisci le categorie dei due articoli
        $mergedArticle->categories()->syncWithoutDetaching($article1->categories->pluck('id')->toArray());
        $mergedArticle->categories()->syncWithoutDetaching($article2->categories->pluck('id')->toArray());

        $mergedArticle->character_id = $article1->character_id;
        $mergedArticle->slug = Str::slug($mergedArticle->title);

        // Salva l'articolo fuso
        $mergedArticle->save();

        // Elimina i vecchi articoli
        $article1->delete();
        $article2->delete();

        return $mergedArticle;
    }

    public function cosineSimilarity($section1, $section2)
    {
        // Converti le sezioni in vettori di parole
        $vector1 = $this->textToVector($section1);
        $vector2 = $this->textToVector($section2);

        // Calcola il coseno di similitudine
        $dotProduct = $this->dotProduct($vector1, $vector2);
        $magnitude1 = $this->magnitude($vector1);
        $magnitude2 = $this->magnitude($vector2);

        if ($magnitude1 * $magnitude2 == 0) {
            return 0;
        }

        return $dotProduct / ($magnitude1 * $magnitude2);
    }

    protected function textToVector($text)
    {
        $words = Str::lower(preg_replace("/[^a-zA-Z0-9\s]/", "", $text));
        $wordsArray = explode(' ', $words);
        return array_count_values($wordsArray);
    }
    protected function dotProduct($vector1, $vector2)
    {
        $dotProduct = 0;
        foreach ($vector1 as $word => $count1) {
            $count2 = $vector2[$word] ?? 0;
            $dotProduct += $count1 * $count2;
        }
        return $dotProduct;
    }
    protected function magnitude($vector)
    {
        $sum = 0;
        foreach ($vector as $value) {
            $sum += $value * $value;
        }
        return sqrt($sum);
    }


    public function calculateSimilarity($section1, $section2)
    {
        // Utilizza la distanza di Levenshtein per calcolare la differenza tra le sezioni
        $distance = levenshtein($section1, $section2);
        $maxLength = max(strlen($section1), strlen($section2));

        // Restituisci la somiglianza come percentuale inversa della distanza
        if ($maxLength == 0) {
            return 1.0; // Se entrambe le sezioni sono vuote
        }

        return 1.0 - ($distance / $maxLength); // Similitudine come percentuale
    }

    public function findSimilarSections(array $sections1, array $sections2, $threshold = 0.7)
    {
        $suggestedMerges = [];

        foreach ($sections1 as $index1 => $section1) {
            foreach ($sections2 as $index2 => $section2) {
                // Calcola la somiglianza tra la sezione 1 e la sezione 2
                $similarity = $this->cosineSimilarity($section1, $section2);

                // Se la somiglianza supera il valore di soglia, suggerisci la fusione
                if ($similarity >= $threshold) {
                    $suggestedMerges[] = [
                        'section1' => $section1,
                        'section2' => $section2,
                        'similarity' => round($similarity * 100, 2), // Percentuale di somiglianza
                        'index1' => $index1,
                        'index2' => $index2
                    ];
                }
            }
        }

        return $suggestedMerges;
    }

    public function findSimilarSectionsWithCache(array $sections1, array $sections2, $threshold = 0.7)
    {
        // Genera una chiave cache unica basata sui contenuti delle sezioni e la soglia
        $cacheKey = 'similar_sections_' . md5(json_encode($sections1) . json_encode($sections2) . $threshold);

        // Controlla se il risultato è già memorizzato nella cache
        return Cache::remember($cacheKey, 3600, function () use ($sections1, $sections2, $threshold) {
            return $this->findSimilarSections($sections1, $sections2, $threshold);
        });
    }
}
