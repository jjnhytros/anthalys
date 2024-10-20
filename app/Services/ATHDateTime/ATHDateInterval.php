<?php

declare(strict_types=1);

namespace App\Services\ATHDateTime;

class ATHDateInterval implements ATHConstants
{
    /**
     * @var int $y Anni dell'intervallo di tempo.
     */
    public int $y;

    /**
     * @var int $m Mesi dell'intervallo di tempo.
     */
    public int $m;

    /**
     * @var int $d Giorni dell'intervallo di tempo.
     */
    public int $d;

    /**
     * @var int $h Ore dell'intervallo di tempo.
     */
    public int $h;

    /**
     * @var int $i Minuti dell'intervallo di tempo.
     */
    public int $i;

    /**
     * @var int $s Secondi dell'intervallo di tempo.
     */
    public int $s;

    /**
     * @var float $f Microsecondi come frazione di secondo.
     */
    public float $f;

    /**
     * @var int $invert Inversione dell'intervallo (0 per positivo, 1 per negativo).
     */
    public int $invert;

    /**
     * @var mixed $days Numero totale di giorni nell'intervallo.
     */
    public mixed $days;

    /**
     * @var bool $from_string Indica se l'intervallo è stato creato da una stringa.
     */
    public bool $from_string;

    /**
     * @var string $date_string Stringa originale utilizzata per creare l'intervallo.
     */
    public string $date_string;

    /**
     * Costruttore della classe.
     * Accetta una stringa in formato ATH personalizzato e la analizza per estrarre i valori dell'intervallo.
     *
     * @param string $duration Stringa in formato intervallo ATH (es. "P1Y2M3DT4H5M6S").
     */
    public function __construct(string $duration)
    {
        $this->date_string = $duration;
        $this->from_string = true;
        $this->parseDuration($duration);
    }

    // --------------------------------------------------------------------------------------------
    // SEZIONE PUBBLICA - Metodi pubblici
    // --------------------------------------------------------------------------------------------

    /**
     * Metodo statico per creare un intervallo da una stringa in formato data.
     * Esempio: "3 years, 2 months, 5 days"
     *
     * @param string $datetime Stringa di data da convertire.
     * @return ATHDateInterval|false Ritorna un oggetto ATHDateInterval o false in caso di errore.
     */
    public static function createFromDateString(string $datetime): ATHDateInterval|false
    {
        try {
            // Crea un'istanza dell'intervallo utilizzando una stringa di durata analizzata.
            $instance = new self(self::parseDateString($datetime));
            return $instance;
        } catch (\Exception $e) {
            return false; // Ritorna false in caso di errore
        }
    }

    /**
     * Formatta l'intervallo basandosi su una stringa di formato personalizzata.
     * Esempio: '%y years, %m months, %d days'
     *
     * @param string $format Stringa di formato da usare per la rappresentazione dell'intervallo.
     * @return string Stringa formattata dell'intervallo.
     */
    public function format(string $format): string
    {
        // Sostituzioni per il formato personalizzato
        $replacements = [
            '%y' => $this->y,
            '%m' => $this->m,
            '%d' => $this->d,
            '%h' => $this->h,
            '%i' => $this->i,
            '%s' => $this->s,
            '%f' => number_format($this->f, 6), // Formatta la frazione di secondi (microsecondi)
            '%r' => $this->invert ? '-' : '', // Indica se l'intervallo è negativo
        ];

        // Ritorna la stringa formattata sostituendo i segnaposto
        return strtr($format, $replacements);
    }

    // --------------------------------------------------------------------------------------------
    // SEZIONE PRIVATA - Metodi privati
    // --------------------------------------------------------------------------------------------

    /**
     * Analizza una stringa di intervallo nel formato ATH personalizzato (es. "P1Y2M3DT4H5M6S")
     * e imposta i valori per anni, mesi, giorni, ore, minuti, secondi e microsecondi.
     *
     * @param string $duration Stringa di durata da analizzare.
     */
    private function parseDuration(string $duration): void
    {
        // Regex per analizzare la stringa di intervallo nel formato ATH
        if (preg_match('/P(?:(\d+)Y)?(?:(\d+)M)?(?:(\d+)D)?T?(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $duration, $matches)) {
            $this->y = (int)($matches[1] ?? 0); // Anni
            $this->m = (int)($matches[2] ?? 0); // Mesi
            $this->d = (int)($matches[3] ?? 0); // Giorni
            $this->h = (int)($matches[4] ?? 0); // Ore
            $this->i = (int)($matches[5] ?? 0); // Minuti
            $this->s = (int)($matches[6] ?? 0); // Secondi
            $this->f = isset($matches[7]) ? (float)("0." . $matches[7]) : 0.0; // Microsecondi come frazione di secondo
        } else {
            // Lancia un'eccezione se la stringa di durata non è valida
            throw new \InvalidArgumentException('Formato di durata non valido: ' . $duration);
        }
    }

    /**
     * Metodo helper per convertire una stringa leggibile ("3 years, 2 months, 5 days") in una stringa di intervallo.
     *
     * @param string $datetime Stringa di data da convertire.
     * @return string Stringa di intervallo nel formato ATH (es. "P3Y2M5D").
     */
    private static function parseDateString(string $datetime): string
    {
        // Variabili di default per anni, mesi, giorni, ore, minuti e secondi
        $y = $m = $d = $h = $i = $s = 0;

        // Estrae gli anni dalla stringa
        if (preg_match('/(\d+)\s*years?/', $datetime, $matches)) {
            $y = (int)$matches[1];
        }
        // Estrae i mesi dalla stringa
        if (preg_match('/(\d+)\s*months?/', $datetime, $matches)) {
            $m = (int)$matches[1];
        }
        // Estrae i giorni dalla stringa
        if (preg_match('/(\d+)\s*days?/', $datetime, $matches)) {
            $d = (int)$matches[1];
        }
        // Estrae le ore dalla stringa
        if (preg_match('/(\d+)\s*hours?/', $datetime, $matches)) {
            $h = (int)$matches[1];
        }
        // Estrae i minuti dalla stringa
        if (preg_match('/(\d+)\s*minutes?/', $datetime, $matches)) {
            $i = (int)$matches[1];
        }
        // Estrae i secondi dalla stringa
        if (preg_match('/(\d+)\s*seconds?/', $datetime, $matches)) {
            $s = (int)$matches[1];
        }

        // Ritorna la stringa di intervallo nel formato ATH
        return "P{$y}Y{$m}M{$d}DT{$h}H{$i}M{$s}S";
    }
}
