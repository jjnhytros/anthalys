<?php

namespace Database\Seeders\Anthaleja\Wiki;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\Anthaleja\Wiki\WikiArticle;
use App\Models\Anthaleja\Wiki\WikiCategory;
use App\Models\Anthaleja\Character\Character;

class WikiArticleSeeder extends Seeder
{
    public function run()
    {
        // Categorie esistenti a cui collegare gli articoli
        $categories = WikiCategory::pluck('id')->toArray();

        // Personaggi non NPC con character_id >= 5
        $characters = Character::where('is_npc', false)
            ->where('id', '>=', 5)
            ->pluck('id')
            ->toArray();

        // Carica le bad words dal file bad_words.txt
        $badWords = $this->getBadWords();

        // Articoli d'esempio ispirati a tematiche di Geologia, con contenuti simili per testare duplicati
        $articles = [
            [
                'title' => $title = 'La deriva dei continenti e le placche tettoniche',
                'slug' => Str::slug($title),
                'content' => 'La deriva dei continenti è un fenomeno che descrive il movimento delle placche tettoniche...',
                'html_content' => '<p>La deriva dei continenti è un fenomeno che descrive il movimento delle placche...</p>',
                'category_id' => $this->randomCategory($categories),
                'published_at' => now(),
                'character_id' => $this->randomCharacter($characters),
            ],
            [
                'title' => $title = 'Il movimento delle placche tettoniche e la deriva dei continenti',
                'slug' => Str::slug($title),
                'content' => 'Le placche tettoniche si muovono costantemente e questo movimento è ciò che conosciamo...',
                'html_content' => '<p>Le placche tettoniche si muovono costantemente e questo movimento è ciò che...</p>',
                'category_id' => $this->randomCategory($categories),
                'published_at' => now(),
                'character_id' => $this->randomCharacter($characters),
            ],
            [
                'title' => $title = 'Articolo contenente bad words',
                'slug' => Str::slug($title),
                'content' => 'Questo articolo contiene linguaggio offensivo. Parole come "idiota" e "stupido" non dovrebbero essere usate.',
                'html_content' => '<p>Questo articolo contiene linguaggio offensivo. Parole come "idiota" e "stupido" non dovrebbero essere usate.</p>',
                'category_id' => $this->randomCategory($categories),
                'published_at' => now(),
                'character_id' => $this->randomCharacter($characters),
            ],
            [
                'title' => $title = 'Questo articolo contiene bad words',
                'slug' => Str::slug($title),
                'content' => 'Questo articolo contiene linguaggio offensivo. Parole come "' . implode(", ", $this->getRandomBadWords($badWords)) . '" non dovrebbero essere usate.',
                'html_content' => '<p>Questo articolo contiene linguaggio offensivo. Parole come "' . implode(", ", $this->getRandomBadWords($badWords)) . '" non dovrebbero essere usate.</p>',
                'category_id' => $this->randomCategory($categories),
                'published_at' => now(),
                'character_id' => $this->randomCharacter($characters),
            ]
        ];

        // Inserimento degli articoli nel database
        foreach ($articles as $article) {
            WikiArticle::create($article);
        }
    }

    // Funzione per ottenere una categoria casuale
    private function randomCategory($categories)
    {
        return $categories[array_rand($categories)];
    }

    // Funzione per ottenere un character_id casuale (>= 5 e non NPC)
    private function randomCharacter($characters)
    {
        return $characters[array_rand($characters)];
    }

    // Funzione per caricare le bad words da un file di testo
    private function getBadWords()
    {
        // Percorso al file bad_words.txt
        $filePath = storage_path('app/bad_words.txt');

        // Leggi le bad words dal file e ritorna come array
        if (file_exists($filePath)) {
            return array_map('trim', file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
        }

        // Ritorna un array vuoto se il file non esiste
        return [];
    }

    // Funzione per ottenere un set casuale di bad words
    private function getRandomBadWords($badWords, $count = 3)
    {
        if (empty($badWords)) {
            return ['bad_word1', 'bad_word2', 'bad_word3']; // Parole predefinite se non ci sono bad words
        }

        // Seleziona $count parole casuali dall'elenco
        return array_rand(array_flip($badWords), $count);
    }

    // Funzione per generare uno slug unico
    private function generateUniqueSlug($title)
    {
        // Crea lo slug di base
        $slug = Str::slug($title);
        $originalSlug = $slug;

        // Controlla se lo slug esiste già nel database
        $counter = 1;
        while (WikiArticle::where('slug', $slug)->exists()) {
            // Se esiste, aggiungi un numero progressivo per renderlo unico
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
