<?php

namespace App\Services;

use App\Models\Anthaleja\Region;
use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\WeatherForecast;
use App\Models\Anthaleja\WeatherCondition;

class WeatherForecastService
{
    protected $timeService;
    protected $notificationService;

    /**
     * Costruttore della classe WeatherForecastService.
     * Inizializza il servizio temporale e il servizio di notifiche.
     */
    public function __construct(TimeService $timeService, NotificationService $notificationService)
    {
        $this->timeService = $timeService;
        $this->notificationService = $notificationService;
    }

    // --------------------------------------------------------------------------------------------
    // Metodi pubblici
    // --------------------------------------------------------------------------------------------

    /**
     * Genera le previsioni meteo per un certo numero di giorni futuri.
     *
     * @param int $daysAhead Numero di giorni futuri per cui generare le previsioni (default: 7 giorni).
     */
    public function generateForecasts($daysAhead = 7)
    {
        try {
            $currentDay = $this->timeService->getCurrentTime()['day'];
            $currentMonth = $this->timeService->getCurrentTime()['month'];

            for ($i = 1; $i <= $daysAhead; $i++) {
                $futureDay = $currentDay + $i;
                $futureMonth = $currentMonth;

                if ($futureDay > 24) {
                    $futureDay = $futureDay % 24;
                    $futureMonth++;
                    if ($futureMonth > 18) {
                        $futureMonth = 1;
                    }
                }

                $season = $this->timeService->getSeasonForMonth($futureMonth);
                $weatherConditions = WeatherCondition::where('season', $season)->get();

                $selectedCondition = $weatherConditions->filter(function ($condition) {
                    return rand(1, 100) <= $condition->probability;
                })->first();

                $accuracy = $selectedCondition->type == __('messages.storm') || $selectedCondition->type == __('messages.hurricane') ? rand(60, 80) : rand(85, 100);

                WeatherForecast::create([
                    'day' => $futureDay,
                    'month' => $futureMonth,
                    'weather_type' => $selectedCondition->type,
                    'accuracy' => $accuracy,
                ]);
            }
        } catch (\Exception $e) {
            throw new \RuntimeException("Error generating forecasts: " . $e->getMessage());
        }
    }

    /**
     * Restituisce le previsioni meteo per i prossimi giorni.
     *
     * @param int $days Numero di giorni per cui ottenere le previsioni (default: 7 giorni).
     * @return \Illuminate\Database\Eloquent\Collection Le previsioni per i giorni successivi.
     */
    public function getForecastsForNextDays($days = 7)
    {
        try {
            $currentDay = $this->timeService->getCurrentTime()['day'];
            $currentMonth = $this->timeService->getCurrentTime()['month'];

            return WeatherForecast::where('day', '>=', $currentDay)
                ->where('month', '>=', $currentMonth)
                ->orderBy('month')
                ->orderBy('day')
                ->take($days)
                ->get();
        } catch (\Exception $e) {
            throw new \RuntimeException("Error retrieving forecasts: " . $e->getMessage());
        }
    }

    /**
     * Genera un evento meteo casuale basato sulla stagione corrente.
     *
     * @return string L'evento meteo generato.
     */
    public function generateRandomWeatherEvent()
    {
        try {
            $currentDay = $this->timeService->getCurrentDay();
            $currentMonth = $this->timeService->getCurrentMonth();
            $season = $this->timeService->getSeasonForMonth($currentMonth);

            if ($season === __('messages.summer') && rand(1, 100) <= 20) {
                return __('messages.summer_storm');
            } elseif ($season === __('messages.winter') && rand(1, 100) <= 30) {
                return __('messages.snowstorm');
            } elseif ($season === __('messages.spring') && rand(1, 100) <= 15) {
                return __('messages.heavy_rain');
            } elseif ($season === __('messages.autumn') && rand(1, 100) <= 25) {
                return __('messages.high_winds');
            }

            return __('messages.normal_conditions');
        } catch (\Exception $e) {
            throw new \RuntimeException("Error generating weather event: " . $e->getMessage());
        }
    }

    /**
     * Applica gli effetti del meteo a tutti i personaggi.
     *
     * @param string $weatherCondition La condizione meteo attuale.
     */
    public function applyWeatherEffectsToCharacters($weatherCondition)
    {
        try {
            $characters = Character::all();

            foreach ($characters as $character) {
                switch ($weatherCondition) {
                    case __('messages.summer_storm'):
                        $character->energy -= 10;
                        $character->happiness -= 5;
                        break;
                    case __('messages.snowstorm'):
                        $character->energy -= 15;
                        $character->productivity -= 10;
                        break;
                    case __('messages.heavy_rain'):
                        $character->hydration += 5;
                        $character->happiness -= 3;
                        break;
                    case __('messages.high_winds'):
                        $character->energy -= 5;
                        $character->happiness -= 2;
                        break;
                    case __('messages.normal_conditions'):
                        $character->happiness += 5;
                        $character->energy += 5;
                        break;
                }

                $character->energy = max(0, $character->energy);
                $character->happiness = max(0, min(100, $character->happiness));
                $character->hydration = max(0, $character->hydration);
                $character->productivity = max(0, $character->productivity);

                $character->save();
                $this->notificationService->sendWeatherEffectNotification($character, $weatherCondition);
            }
        } catch (\Exception $e) {
            throw new \RuntimeException("Error applying weather effects to characters: " . $e->getMessage());
        }
    }

    /**
     * Genera le condizioni meteo per una regione specifica in base al personaggio.
     *
     * @param Character $character Il personaggio per cui generare le condizioni meteo.
     * @return array Condizioni meteo regionali e impatto ambientale.
     */
    public function generateRegionalWeather(Character $character)
    {
        $region = $character->region;

        try {
            switch ($region->name) {
                case __('messages.frozen_mountains'):
                    return $this->generateMountainWeather();
                case __('messages.barren_desert'):
                    return $this->generateDesertWeather();
                case __('messages.tropical_coast'):
                    return $this->generateCoastalWeather();
                default:
                    return $this->generateDefaultWeather();
            }
        } catch (\Exception $e) {
            throw new \RuntimeException("Error generating regional weather: " . $e->getMessage());
        }
    }

    // --------------------------------------------------------------------------------------------
    // Metodi protetti
    // --------------------------------------------------------------------------------------------

    /**
     * Genera condizioni meteo per una regione montana.
     *
     * @return array Condizioni meteo e impatto ambientale.
     */
    protected function generateMountainWeather()
    {
        $weather = rand(1, 100) <= 40 ? __('messages.snowstorm') : __('messages.cold_sun');
        $environmentalEffect = __('messages.low_vegetation');

        return [
            'weather' => $weather,
            'environment' => $environmentalEffect,
        ];
    }

    /**
     * Genera condizioni meteo per una regione desertica.
     *
     * @return array Condizioni meteo e impatto ambientale.
     */
    protected function generateDesertWeather()
    {
        $weather = rand(1, 100) <= 50 ? __('messages.drought') : __('messages.dry_heat');
        $environmentalEffect = __('messages.low_water');

        return [
            'weather' => $weather,
            'environment' => $environmentalEffect,
        ];
    }

    /**
     * Genera condizioni meteo per una regione costiera.
     *
     * @return array Condizioni meteo e impatto ambientale.
     */
    protected function generateCoastalWeather()
    {
        $weather = rand(1, 100) <= 30 ? __('messages.heavy_rain') : __('messages.sun_humidity');
        $environmentalEffect = __('messages.rich_aquatic_resources');

        return [
            'weather' => $weather,
            'environment' => $environmentalEffect,
        ];
    }

    /**
     * Genera condizioni meteo standard per regioni non specifiche.
     *
     * @return array Condizioni meteo e impatto ambientale.
     */
    protected function generateDefaultWeather()
    {
        $weather = rand(1, 100) <= 20 ? __('messages.light_rain') : __('messages.sun');
        $environmentalEffect = __('messages.normal_resources');

        return [
            'weather' => $weather,
            'environment' => $environmentalEffect,
        ];
    }

    /**
     * Restituisce le condizioni meteo per una determinata regione.
     *
     * @param Region $region La regione per cui ottenere il meteo.
     * @return string Condizione meteo.
     */
    public function getWeatherForRegion(Region $region)
    {
        try {
            $weatherConditions = ['sunny', 'rainy', 'stormy', 'cloudy'];
            return $weatherConditions[array_rand($weatherConditions)];
        } catch (\Exception $e) {
            throw new \RuntimeException("Error retrieving weather for region: " . $e->getMessage());
        }
    }
}
