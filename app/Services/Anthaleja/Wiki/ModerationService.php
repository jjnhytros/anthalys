<?php

namespace App\Services\Anthaleja\Wiki;

use Illuminate\Support\Facades\Storage;

class ModerationService
{
    protected $offensiveWords;
    protected $spamIndicators;

    public function __construct()
    {
        // Carica la lista di parole offensive da un file
        $this->offensiveWords = explode("\n", Storage::get('bad_words.txt'));

        // Lista di indicatori di spam
        $this->spamIndicators = [
            '/http/',
            '/buy now/',
            '/!!!+/', // Esempi di pattern di spam
        ];
    }

    public function containsOffensiveLanguage($content)
    {
        foreach ($this->offensiveWords as $word) {
            if (stripos($content, $word) !== false) {
                return true;
            }
        }
        return false;
    }

    public function isSpam($content)
    {
        foreach ($this->spamIndicators as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        return false;
    }
}
