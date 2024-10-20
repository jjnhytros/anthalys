<?php

namespace App\Models\ATHDateTime;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timezone extends Model
{
    use SoftDeletes; // Abilita l'uso di SoftDeletes e le Factory

    // Definizione della tabella associata al modello
    public $table = 'anthal_timezones';

    // I campi che possono essere riempiti in modo massivo
    protected $fillable = [
        'identifier',      // Identificatore del fuso orario
        'abbreviation',    // Abbreviazione del fuso orario
        'offset_hours',    // Offset in ore
        'country_code',    // Codice del paese
        'latitude',        // Latitudine
        'longitude',       // Longitudine
        'comments'         // Commenti aggiuntivi
    ];

    /**
     * Ottieni il codice del paese in base allo stato.
     */
    public function getCountryCode($state)
    {
        switch ($state) {
            case 'Anthal':
                return 'ATH';
            case 'Jit Anthal': // Sud Anthal
                return 'ATJ';
            case 'Exentya':
                return 'EXN';
            case 'Mokisya':
                return 'MKS';
            case 'Pixina':
                return 'PIX';
            case 'Xonys':
                return 'XNS';
            default:
                return 'NLH'; // Nelha (sconosciuto)
        }
    }

    /**
     * Calcola automaticamente l'offset_hours in base alla longitudine.
     */
    public static function calculateOffsetHours($longitude)
    {
        return (int)($longitude / (360 / 28)); // Offset in base alla longitudine
    }

    /**
     * Genera un'abbreviazione unica per il fuso orario.
     */
    public function generateAbbreviation($province, &$usedAbbreviations)
    {
        // Divide la provincia in base agli spazi (per province con due parole)
        $words = explode(' ', $province);

        if ($province === 'Anthalys') {
            return 'ATH'; // Caso speciale per Anthalys
        }

        if (count($words) === 1) {
            // Provincia con una sola parola
            return $this->getConsonantOrVowelAbbreviation($words[0], $usedAbbreviations);
        } else {
            // Provincia con due parole
            $firstAbbreviation = strtoupper(substr($this->extractConsonants($words[0]), 0, 1)); // Prima parola, una consonante

            // Salta la prima consonante e prendi le due consonanti successive dalla seconda parola
            $secondAbbreviation = $this->getSecondWordAbbreviation($words[1]);

            return $this->makeUnique($firstAbbreviation . $secondAbbreviation, $usedAbbreviations);
        }
    }

    /**
     * Ottieni l'abbreviazione della seconda parola (saltando la prima consonante).
     */
    private function getSecondWordAbbreviation($word)
    {
        // Estrai consonanti e vocali
        $consonants = $this->extractConsonants($word);
        $vowels = $this->extractVowels($word);

        // Salta la prima consonante e prendi le prossime due consonanti
        $abbreviationChars = substr($consonants, 1, 2);

        // Se non ci sono abbastanza consonanti, riempi con vocali
        if (strlen($abbreviationChars) < 2) {
            $remainingLength = 2 - strlen($abbreviationChars);
            $abbreviationChars .= substr(implode('', $vowels), 0, $remainingLength);
        }

        return strtoupper($abbreviationChars);
    }

    /**
     * Estrai le consonanti da una parola
     * Sostituisci ĉ/Ĉ con c/C e ĝ/Ĝ con g/G
     * Tratta h/H come vuota, q/Q come k/K
     */
    private function extractConsonants($word)
    {
        $word = str_replace(['ĉ', 'Ĉ', 'ĝ', 'Ĝ', 'h', 'H', 'q', 'Q'], ['c', 'C', 'g', 'G', '', '', 'k', 'K'], $word);

        // Rimuovi le vocali, inclusa y e w trattate come vocali
        return preg_replace('/[aeiouyAEIOUWw]/', '', $word);
    }

    /**
     * Estrai le vocali dalla parola.
     */
    private function extractVowels($word)
    {
        preg_match_all('/[aeiouyAEIOUWw]/', $word, $matches);
        return $matches[0];
    }

    /**
     * Rendi l'abbreviazione unica modificandola se necessario.
     */
    private function makeUnique($abbreviation, &$usedAbbreviations)
    {
        // Assicurati che l'abbreviazione sia unica
        $originalAbbreviation = $abbreviation;
        $index = 1;

        while (in_array($abbreviation, $usedAbbreviations)) {
            $abbreviation = substr($originalAbbreviation, 0, 2) . chr(65 + $index); // Sposta al carattere successivo
            $index++;
        }

        // Marca l'abbreviazione come utilizzata
        $usedAbbreviations[] = $abbreviation;
        return $abbreviation;
    }
}
