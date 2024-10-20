<?php

declare(strict_types=1);

namespace App\Services\ATHDateTime;

interface ATHDateTimeInterface
{
    /**
     * Calcola la differenza tra due oggetti ATHDateTimeInterface e restituisce un intervallo.
     *
     * @param ATHDateTimeInterface $targetObject L'oggetto di confronto.
     * @param bool $absolute Indica se la differenza deve essere assoluta (positiva).
     * @return ATHDateInterval L'intervallo risultante.
     */
    public function diff(ATHDateTimeInterface $targetObject, bool $absolute = false): ATHDateInterval;

    /**
     * Formatta la data e l'ora secondo il formato specificato.
     *
     * @param string $format Formato desiderato.
     * @return string Data e ora formattate.
     */
    public function format(string $format): string;

    /**
     * Ottiene lo scostamento orario dalla timezone UTC.
     *
     * @return int Scostamento in secondi.
     */
    public function getOffset(): int;

    /**
     * Ottiene il timestamp Unix corrente.
     *
     * @return int Timestamp Unix.
     */
    public function getTimeStamp(): int;

    /**
     * Ottiene l'oggetto della timezone corrente.
     *
     * @return ATHDateTimeZone|false La timezone corrente o false se non è impostata.
     */
    public function getTimezone(): ATHDateTimeZone|false;

    /**
     * Metodo chiamato durante il processo di unserializzazione.
     */
    public function __wakeup(): void;

    /**
     * Ottiene l'anno Anthal corrente.
     *
     * @return mixed Anno Anthal.
     */
    public function getAYear();

    /**
     * Ottiene il mese Anthal corrente.
     *
     * @return mixed Mese Anthal.
     */
    public function getAMonth();

    /**
     * Ottiene il giorno Anthal corrente.
     *
     * @return mixed Giorno Anthal.
     */
    public function getADay();

    /**
     * Ottiene l'ora Anthal corrente.
     *
     * @return mixed Ora Anthal.
     */
    public function getAHour();

    /**
     * Ottiene il minuto Anthal corrente.
     *
     * @return mixed Minuto Anthal.
     */
    public function getaMinute();

    /**
     * Ottiene il secondo Anthal corrente.
     *
     * @return mixed Secondo Anthal.
     */
    public function getASecond();

    /**
     * Converte l'oggetto ATHDateTime in un totale di secondi dall'epoca di riferimento.
     *
     * @return int Totale secondi.
     */
    public function toTotalSeconds(): int;
}
