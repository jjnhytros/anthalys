<?php

namespace App\Services\Anthaleja\Wiki;

use App\Models\Anthaleja\Wiki\WikiArticle;

class ResearchService
{
    protected $synonyms = [
        'fast' => ['quick', 'speedy', 'rapid'],
        'smart' => ['intelligent', 'clever', 'bright'],
        // Aggiungi altre parole e i loro sinonimi
    ];

    /**
     * Cerca articoli in base a parole chiave.
     *
     * @param string $keyword
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchArticles($keyword)
    {
        // Trova sinonimi della parola chiave, se presenti
        $synonyms = $this->getSynonyms($keyword);
        $searchTerms = array_merge([$keyword], $synonyms);

        // Costruisce una query full-text con le parole chiave e i sinonimi
        return WikiArticle::where(function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->orWhereRaw('SOUNDEX(content) = SOUNDEX(?)', [$term]);
            }
        })->get();
    }

    private function getSynonyms($keyword)
    {
        return $this->synonyms[$keyword] ?? [];
    }
}
