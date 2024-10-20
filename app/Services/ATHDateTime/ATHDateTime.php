<?php

declare(strict_types=1);

namespace App\Services\ATHDateTime;

use App\Models\ATHDateTime\Day;
use App\Models\ATHDateTime\Month;

class ATHDateTime implements ATHConstants
{
    // Proprietà protette
    protected $timezone;
    protected $eNow; // Epoch time now
    protected $eSecs; // Epoch time in seconds
    protected $tDays; // Total days since reference
    protected $aYear; // Anthal year
    protected $aMonth; // Anthal month
    protected $aDay; // Anthal day
    protected $aHour; // Anthal hour
    protected $aMinute; // Anthal minute
    protected $aSecond; // Anthal second
    protected $aHMS; // Anthal combined time (HMS)

    /**
     * Costruttore che accetta una stringa datetime e una timezone opzionale.
     *
     * @param string $datetime Stringa di data e ora.
     * @param \DateTimeZone|null $timezone Oggetto timezone opzionale.
     */
    public function __construct($datetime = 'now', $timezone = null)
    {
        try {
            $rdn = self::RDN; // Usa RDN (Reference Day Number)
            $dt = new \DateTime($datetime); // Usa la data corrente
            $this->timezone = $timezone ? $timezone : $dt->setTimezone(new \DateTimeZone('UTC'));
            $this->eNow = $dt->format('U'); // Timestamp Unix
            $this->eSecs = $this->eNow - $rdn; // Secondi dall'epoca di riferimento
            $this->make($this->eSecs); // Calcola la data Anthal
        } catch (\Exception $e) {
            throw new \RuntimeException('Errore nella creazione dell\'oggetto ATHDateTime: ' . $e->getMessage());
        }
    }

    // --------------------------------------------------------------------------------------------
    // SEZIONE PUBBLICA - Metodi pubblici
    // --------------------------------------------------------------------------------------------

    /**
     * Aggiunge un intervallo alla data corrente di ATHDateTime.
     *
     * @param ATHDateInterval $interval
     * @return ATHDateTime
     */
    public function add(ATHDateInterval $interval): ATHDateTime
    {
        $totalSeconds =
            ($interval->y * self::SXY) +
            ($interval->m * self::DXM * self::SXD) +
            ($interval->d * self::SXD) +
            ($interval->h * self::SXH) +
            ($interval->i * self::SXI) +
            $interval->s;

        // Aggiungi o sottrai in base alla proprietà 'invert'
        if ($interval->invert === 1) {
            $this->eSecs -= $totalSeconds;
        } else {
            $this->eSecs += $totalSeconds;
        }

        $this->make($this->eSecs);

        return $this;
    }

    /**
     * Crea un ATHDateTime da un formato specifico.
     *
     * @param string $format Formato di data.
     * @param string $datetime Stringa di data.
     * @param ATHDateTimeZone|null $timezone Timezone opzionale.
     * @return ATHDateTime|false
     */
    public static function createFromFormat(string $format, string $datetime, ?ATHDateTimeZone $timezone = null): ATHDateTime|false
    {
        try {
            $formatMap = [
                'Y' => '(?<Y>\d{4})',
                'm' => '(?<m>\d{2})',
                'd' => '(?<d>\d{2})',
                'H' => '(?<H>\d{2})',
                'i' => '(?<i>\d{2})',
                's' => '(?<s>\d{2})',
                'G' => '(?<G>\d{1,2})',
            ];

            $regex = $format;
            foreach ($formatMap as $key => $pattern) {
                $regex = str_replace($key, $pattern, $regex);
            }

            if (!preg_match("/^$regex$/", $datetime, $matches)) {
                return false;
            }

            $year = isset($matches['Y']) ? intval($matches['Y']) : self::RY;
            $month = isset($matches['m']) ? intval($matches['m']) : 1;
            $day = isset($matches['d']) ? intval($matches['d']) : 1;
            $hour = isset($matches['H']) || isset($matches['G']) ? intval($matches['H'] ?? $matches['G']) : 0;
            $minute = isset($matches['i']) ? intval($matches['i']) : 0;
            $second = isset($matches['s']) ? intval($matches['s']) : 0;

            $eSecs = self::calculateEpochSeconds($year, $month, $day, $hour, $minute, $second);

            $athDateTime = new self();
            $athDateTime->eNow = $eSecs + self::RDN;
            $athDateTime->eSecs = $eSecs;

            if ($timezone !== null) {
                $athDateTime->timezone = $timezone;
            }

            $athDateTime->make($eSecs);

            return $athDateTime;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Ottiene il nome del mese corrente.
     *
     * @return string
     */
    public function getCurrentMonthName(): string
    {
        $currentMonthNumber = $this->aMonth;
        $month = Month::where('id', $currentMonthNumber)->first();
        return $month ? $month->name : 'Unknown Month';
    }

    /**
     * Ottiene la stagione corrente in base alla data.
     *
     * @return string
     */
    public function getCurrentSeason(): string
    {
        $currentDayOfYear = ($this->getAMonth() - 1) * self::DXM + $this->getADay();
        $springStart = 12 + (self::DXM * 3);
        $summerStart = $springStart + 108;
        $autumnStart = $summerStart + 108;
        $winterStart = $autumnStart + 108;

        if ($currentDayOfYear >= $springStart && $currentDayOfYear < $summerStart) {
            return 'Spring';
        } elseif ($currentDayOfYear >= $summerStart && $currentDayOfYear < $autumnStart) {
            return 'Summer';
        } elseif ($currentDayOfYear >= $autumnStart && $currentDayOfYear < $winterStart) {
            return 'Autumn';
        } else {
            return 'Winter';
        }
    }

    /**
     * Restituisce true se è giorno, false se è notte.
     *
     * @return bool
     */
    public function isDayTime(): bool
    {
        return $this->aHour >= 7 && $this->aHour < 21;
    }

    /**
     * Restituisce true se è notte, false se è giorno.
     *
     * @return bool
     */
    public function isNightTime(): bool
    {
        return $this->aHour >= 21 || $this->aHour < 7;
    }

    // --------------------------------------------------------------------------------------------
    // SEZIONE PROTETTA - Metodi protetti
    // --------------------------------------------------------------------------------------------

    /**
     * Calcola il numero di secondi dall'epoca di riferimento.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @return int
     */
    private static function calculateEpochSeconds($year, $month, $day, $hour, $minute, $second): int
    {
        $yearSecs = ($year - self::RY) * self::SXY;
        $monthSecs = ($month - 1) * self::DXM * self::SXD;
        $daySecs = ($day - 1) * self::SXD;
        $timeSecs = $hour * self::SXH + $minute * self::SXI + $second;

        return (int) ($yearSecs + $monthSecs + $daySecs + $timeSecs);
    }

    /**
     * Calcola e imposta i componenti della data Anthal in base ai secondi.
     *
     * @param int $secs
     */
    protected function make($secs)
    {
        $this->tDays = ($this->eSecs / self::SXD);
        $tYears = 0;
        $this->aMonth = 1;

        while ($this->tDays >= self::DXY) {
            $this->tDays -= self::DXY;
            $tYears++;
        }

        while ($this->tDays >= self::DXM) {
            $this->tDays -= self::DXM;
            $this->aMonth++;
            if ($this->aMonth > self::MXY) {
                $tYears++;
                $this->aMonth = 1;
            }
        }

        $this->aYear = self::RY + $tYears;
        $this->aDay = intval($this->tDays) + 1;

        $remainingSecs = intval($this->eSecs % self::SXD);
        $this->aHour = intval($remainingSecs / self::SXH);
        $remainingSecs = $remainingSecs % self::SXH;
        $this->aMinute = intval($remainingSecs / self::SXI);
        $this->aSecond = $remainingSecs % self::SXI;

        $this->aHMS = $this->aHour + ($this->aMinute / self::IXH) + ($this->aSecond / self::SXH);
    }

    /**
     * Get the value of aSecond
     */
    public function getASecond()
    {
        return $this->aSecond;
    }

    /**
     * Get the value of aMinute
     */
    public function getAMinute()
    {
        return $this->aMinute;
    }

    /**
     * Get the value of aHour
     */
    public function getAHour()
    {
        return $this->aHour;
    }

    /**
     * Get the value of aDay
     */
    public function getADay()
    {
        return $this->aDay;
    }

    /**
     * Get the value of aMonth
     */
    public function getAMonth()
    {
        return $this->aMonth;
    }

    /**
     * Get the value of aYear
     */
    public function getAYear()
    {
        return $this->aYear;
    }
}
