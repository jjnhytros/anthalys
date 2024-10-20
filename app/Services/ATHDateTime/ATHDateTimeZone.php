<?php

declare(strict_types=1);

namespace App\Services\ATHDateTime;

use App\Models\ATHDateTime\Timezone;

class ATHDateTimeZone implements ATHConstants
{

    // Costanti per diverse regioni
    public const ANTHAL = 1;
    public const ANTHAL_JUT = 2;
    public const PIXINA = 4;
    public const EXENTYA = 8;
    public const MOKISYA = 16;
    public const XONYS = 32;
    public const BIG_LAKE = 64;
    public const AST = 128;
    public const ALL = 255;
    public const ALL_WITH_BC = 511;
    public const PER_COUNTRY = 512;

    // Proprietà
    private string $timezone;
    private array $locations;

    /**
     * Costruttore per l'impostazione di una timezone
     */
    public function __construct(string $timezone)
    {
        $this->timezone = $timezone;

        // Placeholder: Carica i dati della posizione per il fuso orario (coordinate, codice del paese, ecc.)
        $this->locations = $this->loadTimezoneData($timezone);
    }

    /**
     * Recupera informazioni sulla posizione del fuso orario
     *
     * @return array|false
     */
    public function getLocation(): array|false
    {
        return $this->locations ?: false;
    }

    /**
     * Ottiene il nome del fuso orario
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->timezone;
    }

    /**
     * Ottiene lo scostamento in secondi rispetto all'UTC per una determinata data e ora
     *
     * @param ATHDateTimeInterface $datetime
     * @return int
     */
    public function getOffset(ATHDateTimeInterface $datetime): int
    {
        // Recupera lo scostamento in ore dal database
        $timezone = Timezone::where('identifier', $this->getName())->first();

        // Verifica che il fuso orario esista
        if (!$timezone) {
            throw new \Exception("Timezone not found");
        }

        // Ottiene lo scostamento in ore e lo converte in secondi
        $offsetInSeconds = $timezone->offset_hours * 3600;

        // Ritorna lo scostamento in secondi
        return $offsetInSeconds;
    }

    /**
     * Ottiene i dettagli delle transizioni per l'ora legale o i cambiamenti del fuso orario
     *
     * @param int $timestampBegin
     * @param int $timestampEnd
     * @return array|false
     */
    public function getTransitions(int $timestampBegin = PHP_INT_MIN, int $timestampEnd = PHP_INT_MAX): array|false
    {
        // Questa implementazione presuppone che non ci siano transizioni per l'ora legale
        return [
            'start' => $timestampBegin,
            'end' => $timestampEnd,
            'transitions' => [],
        ];
    }

    /**
     * Metodo statico per elencare tutte le abbreviazioni dei fusi orari
     *
     * @return array
     */
    public static function listAbbreviations(): array
    {
        // Recupera abbreviazioni, identificatori e offset_hours dal database
        $abbreviations = Timezone::select('abbreviation', 'identifier', 'offset_hours')
            ->get();

        // Prepara un array associativo dove l'abbreviazione è la chiave e contiene identifier e offset_hours
        $result = [];

        foreach ($abbreviations as $abbreviation) {
            $result[$abbreviation->abbreviation] = [
                'offset_hours' => $abbreviation->offset_hours * self::SXH,
                'identifier' => $abbreviation->identifier,
            ];
        }

        return $result;
    }

    /**
     * Metodo statico per elencare tutti gli identificatori dei fusi orari
     *
     * @param int $timezoneGroup
     * @param string|null $countryCode
     * @return array
     */
    public static function listIdentifiers(int $timezoneGroup = self::ALL, ?string $countryCode = null): array
    {
        // Step 1: Definisci la mappatura delle costanti al loro ordine e prefisso identificatore
        $constants = [
            self::ANTHAL => 'ANTHAL',
            self::ANTHAL_JUT => 'ANTHAL_JUT',
            self::PIXINA => 'PIXINA',
            self::EXENTYA => 'EXENTYA',
            self::MOKISYA => 'MOKISYA',
            self::XONYS => 'XONYS',
            self::BIG_LAKE => 'BIG_LAKE',
            self::AST => 'AST',
        ];

        // Fusi orari retrocompatibili
        $backwardsCompatibleTimezones = [
            'OLD_ANTHAL' => 'ANTHAL_OLD',
            'OLD_PIXINA' => 'PIXINA_OLD',
            // Aggiungi altri fusi orari retrocompatibili qui...
        ];

        // Step 2: Gestisci la costante PER_COUNTRY per filtrare i fusi orari per codice del paese
        if ($timezoneGroup === self::PER_COUNTRY && $countryCode !== null) {
            return Timezone::where('country_code', '=', $countryCode)
                ->orderBy('identifier')
                ->pluck('identifier')
                ->toArray();
        }

        // Step 3: Se viene passato ALL_WITH_BC, includi tutti i fusi orari normali e quelli retrocompatibili
        if ($timezoneGroup === self::ALL_WITH_BC) {
            $result = [];

            // Gestisci i fusi orari regolari (stessa logica di ALL)
            foreach ($constants as $constant => $prefix) {
                $identifiers = Timezone::where('identifier', 'LIKE', "{$prefix}/%")
                    ->orderBy('identifier')
                    ->pluck('identifier')
                    ->toArray();
                $result = array_merge($result, $identifiers);
            }

            // Gestisci i fusi orari retrocompatibili
            foreach ($backwardsCompatibleTimezones as $prefix) {
                $identifiers = Timezone::where('identifier', 'LIKE', "{$prefix}/%")
                    ->orderBy('identifier')
                    ->pluck('identifier')
                    ->toArray();
                $result = array_merge($result, $identifiers);
            }

            return $result;
        }

        // Step 4: Se viene passato ALL, ottieni tutti i fusi orari normali
        if ($timezoneGroup === self::ALL) {
            $result = [];
            foreach ($constants as $constant => $prefix) {
                $identifiers = Timezone::where('identifier', 'LIKE', "{$prefix}/%")
                    ->orderBy('identifier')
                    ->pluck('identifier')
                    ->toArray();
                $result = array_merge($result, $identifiers);
            }
            return $result;
        }

        // Step 5: Gestisci le corrispondenze esatte dalle costanti
        if (array_key_exists($timezoneGroup, $constants)) {
            return Timezone::where('identifier', 'LIKE', "{$constants[$timezoneGroup]}/%")
                ->orderBy('identifier')
                ->pluck('identifier')
                ->toArray();
        }

        // Step 6: Gestisci somme di valori multipli (logica in stile bitmask)
        $result = [];
        $remainingValue = $timezoneGroup;

        // Ordina le costanti per valore in ordine decrescente per gestire prima il valore maggiore
        foreach (array_reverse(array_keys($constants)) as $constantValue) {
            if ($remainingValue >= $constantValue) {
                $remainingValue -= $constantValue;
                $constantName = $constants[$constantValue];

                // Aggiungi fusi orari per la costante corrispondente
                $identifiers = Timezone::where('identifier', 'LIKE', "{$constantName}/%")
                    ->orderBy('identifier')
                    ->pluck('identifier')
                    ->toArray();

                $result = array_merge($result, $identifiers);
            }

            if ($remainingValue == 0) {
                break;
            }
        }

        // Step 7: Logica di fallback se remainingValue non è 0
        if ($remainingValue !== 0) {
            return []; // Nessuna corrispondenza valida trovata
        }

        return $result;
    }

    /**
     * Carica i dati del fuso orario per il dato identificatore
     *
     * @param string $timezone
     * @return array
     */
    private function loadTimezoneData(string $timezone): array
    {
        // Placeholder: Recupera i dati della posizione, come latitudine, longitudine, paese, ecc.
        // Questo potrebbe essere recuperato da un database o file di configurazione
        return [
            'country_code' => 'ATH', // Esempio di placeholder
            'latitude' => 12.1968,
            'longitude' => 0.0000,
            'comments' => 'Anthalys Standard Time',
        ];
    }

    /**
     * Calcola lo scostamento per una data specifica in base alle regole del fuso orario
     *
     * @param ATHDateTime $datetime
     * @return int Scostamento in secondi
     */
    private function calculateOffset(ATHDateTime $datetime): int
    {
        // Placeholder: Restituisce scostamento fisso (es. UTC+0 per Anthalys)
        return 0;
    }
}
