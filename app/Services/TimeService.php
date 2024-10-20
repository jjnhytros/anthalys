<?php

namespace App\Services;

use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\CalendarEvent;
use App\Models\Anthaleja\WeatherCondition;
use App\Services\ATHDateTime\ATHConstants;

class TimeService implements ATHConstants
{
    // Proprietà che definiscono il tempo corrente e le unità temporali personalizzate
    protected $currentHour = 0;
    protected $currentDay;
    protected $currentMonth;
    protected $currentYear = 1;
    protected $hoursPerDay = self::HXD;  // Ore per giorno (28 ore)
    protected $daysPerMonth = self::DXM; // Giorni per mese (24 giorni)
    protected $monthsPerYear = self::MXY; // Mesi per anno (18 mesi)

    /**
     * Costruttore che imposta il giorno e il mese corrente.
     */
    public function __construct()
    {
        try {
            $this->currentDay = now()->day;
            $this->currentMonth = now()->month;
        } catch (\Exception $e) {
            throw new \RuntimeException("Errore nell'inizializzazione del servizio temporale: " . $e->getMessage());
        }
    }

    // --------------------------------------------------------------------------------------------
    // Metodi pubblici
    // --------------------------------------------------------------------------------------------

    /**
     * Avanza il tempo di un certo numero di ore. Se le ore superano il numero di ore in un giorno,
     * avanza al giorno successivo.
     *
     * @param int $hours Numero di ore da avanzare.
     */
    public function advanceTime($hours = 1)
    {
        try {
            $this->currentHour += $hours;
            if ($this->currentHour >= $this->hoursPerDay) {
                $this->currentHour = $this->currentHour % $this->hoursPerDay;
                $this->advanceDay();
            }
        } catch (\Exception $e) {
            throw new \RuntimeException("Errore nell'avanzamento del tempo: " . $e->getMessage());
        }
    }

    /**
     * Restituisce la stagione corrente basata sul mese fornito.
     *
     * @param int $month Mese corrente.
     * @return string La stagione corrispondente al mese.
     */
    public function getSeasonForMonth($month): string
    {
        $dayOfYear = ($this->currentMonth - 1) * $this->daysPerMonth + $this->currentDay;
        $springStartDay = (2 * $this->daysPerMonth) + 4;
        $seasonLength = 108;

        try {
            if ($dayOfYear >= $springStartDay && $dayOfYear < $springStartDay + $seasonLength) {
                return __('messages.spring');
            } elseif ($dayOfYear >= $springStartDay + $seasonLength && $dayOfYear < $springStartDay + 2 * $seasonLength) {
                return __('messages.summer');
            } elseif ($dayOfYear >= $springStartDay + 2 * $seasonLength && $dayOfYear < $springStartDay + 3 * $seasonLength) {
                return __('messages.autumn');
            } else {
                return __('messages.winter');
            }
        } catch (\Exception $e) {
            throw new \RuntimeException("Errore nella determinazione della stagione: " . $e->getMessage());
        }
    }

    /**
     * Genera una condizione meteorologica casuale basata sulla stagione corrente e applica
     * gli effetti metereologici ai personaggi.
     *
     * @return WeatherCondition|null Condizione meteorologica selezionata.
     */
    public function generateWeather()
    {
        // try {
        //     $currentMonth = $this->getCurrentMonth();
        //     $season = $this->getSeasonForMonth($currentMonth);
        //     $weatherConditions = WeatherCondition::where('season', $season)->get();
        //     $selectedCondition = $weatherConditions->filter(function ($condition) {
        //         return rand(1, 100) <= $condition->probability;
        //     })->first();

        //     if ($selectedCondition) {
        //         $this->applyWeatherEffects($selectedCondition);
        //     }

        //     return $selectedCondition;
        // } catch (\Exception $e) {
        //     throw new \RuntimeException("Errore nella generazione del meteo: " . $e->getMessage());
        // }
    }

    /**
     * Restituisce la stagione corrente.
     *
     * @return string La stagione corrente.
     */
    public function getCurrentSeason()
    {
        $currentMonth = $this->getCurrentMonth();
        return $this->getSeasonForMonth($currentMonth);
    }

    /**
     * Applica effetti stagionali a un personaggio in base alla stagione corrente.
     *
     * @param Character $character Il personaggio a cui applicare gli effetti.
     */
    public function applySeasonalEffects(Character $character)
    {
        $currentMonth = $this->getCurrentMonth();
        $season = $this->getSeasonForMonth($currentMonth);

        try {
            switch ($season) {
                case __('messages.winter'):
                    $character->energy -= 10;
                    $character->happiness -= 5;
                    break;
                case __('messages.summer'):
                    $character->happiness += 10;
                    $character->hydration -= 10;
                    break;
                case __('messages.autumn'):
                    $character->energy -= 3;
                    $character->happiness -= 2;
                    break;
                case __('messages.spring'):
                    $character->energy += 5;
                    $character->happiness += 5;
                    break;
            }

            $character->energy = max(0, $character->energy);
            $character->happiness = max(0, min(100, $character->happiness));
            $character->hydration = max(0, $character->hydration);
            $character->save();
        } catch (\Exception $e) {
            throw new \RuntimeException("Errore nell'applicazione degli effetti stagionali: " . $e->getMessage());
        }
    }

    /**
     * Ottiene il giorno corrente.
     *
     * @return int Giorno corrente.
     */
    public function getCurrentDay()
    {
        return $this->currentDay;
    }

    /**
     * Ottiene il mese corrente.
     *
     * @return int Mese corrente.
     */
    public function getCurrentMonth()
    {
        return $this->currentMonth;
    }

    /**
     * Restituisce il tempo corrente formattato in una stringa leggibile.
     *
     * @return string Il tempo corrente formattato.
     */
    public function getFormattedTime()
    {
        return sprintf(
            __('messages.time_format'),
            $this->currentHour,
            $this->currentDay,
            $this->currentMonth,
            $this->currentYear
        );
    }

    /**
     * Determina se è giorno (tra le 7:00 e le 21:00).
     *
     * @return bool True se è giorno, false altrimenti.
     */
    public function isDaytime()
    {
        return $this->currentHour >= 7 && $this->currentHour < 21;
    }

    /**
     * Determina se è notte (dalle 21:00 alle 7:00).
     *
     * @return bool True se è notte, false altrimenti.
     */
    public function isNighttime()
    {
        return !$this->isDaytime();
    }

    /**
     * Ottiene l'ora corrente, giorno, mese e anno in un array.
     *
     * @return array Il tempo corrente.
     */
    public function getCurrentTime()
    {
        return [
            'hour' => $this->currentHour,
            'day' => $this->currentDay,
            'month' => $this->currentMonth,
            'year' => $this->currentYear,
        ];
    }

    // --------------------------------------------------------------------------------------------
    // Metodi protetti
    // --------------------------------------------------------------------------------------------

    /**
     * Avanza al giorno successivo. Se i giorni superano quelli del mese, avanza al mese successivo.
     */
    protected function advanceDay()
    {
        $this->currentDay++;

        if ($this->currentDay > $this->daysPerMonth) {
            $this->currentDay = 1;
            $this->advanceMonth();
        }
    }

    /**
     * Avanza al mese successivo. Se i mesi superano quelli dell'anno, avanza all'anno successivo.
     */
    protected function advanceMonth()
    {
        $this->currentMonth++;

        if ($this->currentMonth > $this->monthsPerYear) {
            $this->currentMonth = 1;
            $this->advanceYear();
        }
    }

    /**
     * Avanza all'anno successivo.
     */
    protected function advanceYear()
    {
        $this->currentYear++;
    }

    /**
     * Applica gli effetti di un evento del calendario ai personaggi.
     */
    protected function applyCalendarEventEffects()
    {
        // try {
        //     $event = CalendarEvent::where('day', $this->currentDay)
        //         ->where('month', $this->currentMonth)
        //         ->first();

        //     if ($event) {
        //         $characters = Character::all();
        //         foreach ($characters as $character) {
        //             $this->applyEffectsToCharacter($character, $event->effects);
        //         }
        //     }
        // } catch (\Exception $e) {
        //     throw new \RuntimeException("Errore nell'applicazione degli effetti dell'evento del calendario: " . $e->getMessage());
        // }
    }

    // --------------------------------------------------------------------------------------------
    // Metodi privati
    // --------------------------------------------------------------------------------------------

    /**
     * Applica gli effetti di una condizione meteorologica a un personaggio.
     *
     * @param WeatherCondition $weatherCondition La condizione meteo selezionata.
     */
    private function applyWeatherEffects($weatherCondition)
    {
        try {
            $characters = Character::all();
            foreach ($characters as $character) {
                $this->applyEffectsToCharacter($character, $weatherCondition->effects);
            }
        } catch (\Exception $e) {
            throw new \RuntimeException("Errore nell'applicazione degli effetti meteo: " . $e->getMessage());
        }
    }

    /**
     * Applica un array di effetti a un personaggio specifico.
     *
     * @param Character $character Il personaggio a cui applicare gli effetti.
     * @param array $effects Gli effetti da applicare.
     */
    private function applyEffectsToCharacter(Character $character, $effects)
    {
        try {
            if (isset($effects['energy_change'])) {
                $character->energy += $effects['energy_change'];
            }
            if (isset($effects['happiness_change'])) {
                $character->happiness += $effects['happiness_change'];
            }
            $character->save();
        } catch (\Exception $e) {
            throw new \RuntimeException("Errore nell'applicazione degli effetti al personaggio: " . $e->getMessage());
        }
    }
}
