<?php

declare(strict_types=1);

namespace App\Services\ATHDateTime;

use ArrayIterator;

class ATHDatePeriod implements \IteratorAggregate
{
    // Constants
    public const EXCLUDE_START_DATE = 1;
    public const INCLUDE_END_DATE = 2;

    // Properties
    public readonly ?ATHDateTime $current;
    public readonly ?ATHDateInterval $interval;
    public readonly bool $include_end_date;
    public readonly bool $include_start_date;
    public readonly int $recurrences;
    public readonly ?ATHDateTime $start;
    public readonly ?ATHDateTime $end;

    /**
     * Costruttore che accetta una data di inizio, un intervallo, e una data di fine o un numero di ricorrenze.
     *
     * @param ATHDateTime $start Data di inizio del periodo.
     * @param ATHDateInterval $interval Intervallo da applicare tra le date.
     * @param ATHDateTime|int|null $endOrRecurrences Data di fine o numero di ricorrenze.
     * @param int $options Opzioni per includere o escludere date di inizio o fine.
     */
    public function __construct(
        ATHDateTime $start,
        ATHDateInterval $interval,
        ATHDateTime|int|null $endOrRecurrences = null,
        int $options = 0
    ) {
        $this->start = $start;
        $this->interval = $interval;

        try {
            if (is_int($endOrRecurrences)) {
                $this->recurrences = $endOrRecurrences;
                $this->end = null;
            } elseif ($endOrRecurrences instanceof ATHDateTime) {
                $this->recurrences = 0;
                $this->end = $endOrRecurrences;
            } else {
                throw new \InvalidArgumentException('Invalid argument: must be an ATHDateTime or an integer');
            }
        } catch (\InvalidArgumentException $e) {
            // Gestione eccezione: invalid argument
            throw new \InvalidArgumentException($e->getMessage());
        }

        // Gestione delle opzioni
        $this->include_start_date = ($options & self::EXCLUDE_START_DATE) === 0;
        $this->include_end_date = ($options & self::INCLUDE_END_DATE) !== 0;
        $this->current = $this->include_start_date ? $start : null;
    }

    // --------------------------------------------------------------------------------------------
    // SEZIONE PUBBLICA - Metodi pubblici
    // --------------------------------------------------------------------------------------------

    /**
     * Metodo statico per creare un periodo da una stringa ISO8601.
     *
     * @param string $specification Stringa in formato ISO8601.
     * @param int $options Opzioni per includere o escludere date di inizio o fine.
     * @return static Un nuovo oggetto ATHDatePeriod.
     * @throws \InvalidArgumentException Se la stringa non è valida.
     */
    public static function createFromISO8601String(string $specification, int $options = 0): static
    {
        $parts = explode('/', $specification);

        try {
            if (count($parts) !== 3) {
                throw new \InvalidArgumentException('Invalid ISO 8601 string');
            }

            $start = new ATHDateTime($parts[0]);
            $interval = new ATHDateInterval($parts[1]);

            // Controlla il formato dell'ultimo elemento per determinare se è un intervallo o una data di fine
            if (str_contains($parts[2], 'P')) {
                // Se è un intervallo, crea un'istanza di ATHDateInterval
                $end = new ATHDateInterval($parts[2]);
                // Supponendo che il costruttore accetti un ATHDateTime o null come terzo parametro
                return new static($start, $interval, null, $options);
            } else {
                // Altrimenti, crea un'istanza di ATHDateTime
                $end = new ATHDateTime($parts[2]);
                return new static($start, $interval, $end, $options);
            }
        } catch (\InvalidArgumentException $e) {
            // Gestione eccezione per stringa ISO8601 non valida
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Ottiene l'intervallo tra le date del periodo.
     *
     * @return ATHDateInterval Intervallo applicato alle date del periodo.
     */
    public function getDateInterval(): ATHDateInterval
    {
        return $this->interval;
    }

    /**
     * Ottiene la data di fine del periodo, se esiste.
     *
     * @return ATHDateTime|null La data di fine del periodo o null se non è impostata.
     */
    public function getEndDate(): ?ATHDateTime
    {
        return $this->end;
    }

    /**
     * Ottiene il numero di ricorrenze per il periodo, se applicabile.
     *
     * @return int|null Il numero di ricorrenze o null se non è impostato.
     */
    public function getRecurrences(): ?int
    {
        return $this->recurrences > 0 ? $this->recurrences : null;
    }

    /**
     * Ottiene la data di inizio del periodo.
     *
     * @return ATHDateTime La data di inizio del periodo.
     */
    public function getStartDate(): ATHDateTime
    {
        return $this->start;
    }

    /**
     * Implementa la funzionalità di iteratore per scorrere le date nel periodo.
     *
     * @return ArrayIterator Un iteratore per scorrere le date nel periodo.
     */
    public function getIterator(): ArrayIterator
    {
        $dates = [];
        $currentDate = $this->start;
        $count = 0;

        try {
            while ($this->shouldContinue($currentDate, $count)) {
                if ($this->include_start_date || $count > 0) {
                    $dates[] = clone $currentDate;
                }

                $currentDate->add($this->interval);
                $count++;
            }

            if ($this->include_end_date && $this->end && $currentDate == $this->end) {
                $dates[] = clone $currentDate;
            }
        } catch (\Exception $e) {
            // Gestione eccezioni generiche durante l'iterazione
            throw new \RuntimeException('Errore durante l\'iterazione delle date: ' . $e->getMessage());
        }

        return new ArrayIterator($dates);
    }

    // --------------------------------------------------------------------------------------------
    // SEZIONE PRIVATA - Metodi privati
    // --------------------------------------------------------------------------------------------

    /**
     * Determina se l'iterazione delle date dovrebbe continuare in base alla fine del periodo o al numero di ricorrenze.
     *
     * @param ATHDateTime $currentDate La data corrente nel ciclo.
     * @param int $count Numero di ricorrenze finora.
     * @return bool Ritorna true se l'iterazione dovrebbe continuare, altrimenti false.
     */
    private function shouldContinue(ATHDateTime $currentDate, int $count): bool
    {
        if ($this->recurrences > 0) {
            return $count < $this->recurrences;
        }

        if ($this->end) {
            return $currentDate < $this->end;
        }

        return false;
    }
}
