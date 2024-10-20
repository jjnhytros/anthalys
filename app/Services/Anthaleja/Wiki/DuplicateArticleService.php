<?php

namespace App\Services\Anthaleja\Wiki;

use Illuminate\Support\Facades\DB;
use App\Models\Anthaleja\Wiki\WikiArticle;

class DuplicateArticleService
{
    /**
     * Trova articoli con titoli simili.
     *
     * @param string $title
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findSimilarTitles($title)
    {
        // Confronta i titoli in modo fuzzy usando somiglianza con Levenshtein o Metaphone
        return WikiArticle::where('title', 'like', '%' . $title . '%')
            ->where('title', '!=', $title)
            ->get();
    }

    /**
     * Trova articoli con contenuti simili.
     *
     * @param string $content
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findSimilarContent($content)
    {
        // Ottieni il nome dell'engine del database
        $dbConnection = DB::connection();
        $databaseDriver = $dbConnection->getDriverName();

        // Base della query
        $query = WikiArticle::select(DB::raw('*, (LENGTH(content) - LENGTH(REPLACE(content, "0.5", ""))) / LENGTH(content) AS similarity'));

        // Applica la clausola HAVING se è MySQL, WHERE se è SQLite
        if ($databaseDriver === 'mysql') {
            $query->having('similarity', '>', 0.5); // MySQL può usare HAVING
        } elseif ($databaseDriver === 'sqlite') {
            $query->whereRaw('(LENGTH(content) - LENGTH(REPLACE(content, "0.5", ""))) / LENGTH(content) > 0.5'); // SQLite usa WHERE
        }

        // Esegui la query
        return $query->get();
    }

    /**
     * Trova articoli che condividono le stesse categorie.
     *
     * @param WikiArticle $article
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByCategory(WikiArticle $article)
    {
        return WikiArticle::whereHas('categories', function ($query) use ($article) {
            $query->whereIn('id', $article->categories->pluck('id'));
        })
            ->where('id', '!=', $article->id) // Esclude l'articolo stesso
            ->get();
    }
}
