<?php

namespace App\Console\Commands;

use App\Services\TimeService;
use Illuminate\Console\Command;
use App\Services\WeatherForecastService;

class AdvanceTime extends Command
{
    protected $signature = 'game:advance-time {hours=1}';
    protected $description = 'Avanza il tempo nel gioco di un certo numero di ore';

    protected $timeService;
    protected $forecastService;

    public function __construct(TimeService $timeService, WeatherForecastService $forecastService)
    {
        parent::__construct();
        $this->timeService = $timeService;
        $this->forecastService = $forecastService;
    }

    public function handle()
    {
        $hours = $this->argument('hours');
        $this->timeService->advanceTime($hours);

        // Genera il meteo per il nuovo giorno
        $weather = $this->timeService->generateWeather();
        // Genera nuove previsioni meteo per i prossimi giorni
        $this->forecastService->generateForecasts(7);

        $time = $this->timeService->getFormattedTime();
        $this->info("Tempo avanzato di $hours ore. Tempo corrente: $time. Meteo: " . ($weather->type ?? 'Nessun cambiamento'));
    }
}
