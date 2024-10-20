<?php

namespace Database\Seeders\Anthaleja;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Anthaleja\City\MapSquare;
use App\Models\Anthaleja\City\Transport\BusLine;
use App\Models\Anthaleja\City\Transport\TramLine;
use App\Models\Anthaleja\City\Transport\MetroLine;
use App\Services\Anthaleja\City\CityPlanningService;

class PublicTransportSeeder extends Seeder
{
    protected $cityPlanningService;

    public function __construct(CityPlanningService $cityPlanningService)
    {
        $this->cityPlanningService = $cityPlanningService;
    }

    public function run()
    {
        DB::table('bus_lines')->truncate();
        DB::table('tram_lines')->truncate();
        DB::table('metro_lines')->truncate();

        $currentTime = now();

        // Genera le linee degli autobus elettrici
        $this->generateBusLines($currentTime);

        // Genera le linee dei tram elettrici
        $this->generateTramLines($currentTime);

        // Genera le linee della metropolitana
        $this->generateMetroLines($currentTime);
    }

    protected function generateBusLines($currentTime)
    {
        for ($i = 1; $i <= 12; $i++) {
            $lineName = 'Linea Bus ' . $i;
            $stops = $this->cityPlanningService->generateTransportStops(6, []);
            $path = $this->cityPlanningService->generatePathForLine($stops);

            DB::table('bus_lines')->insert([
                'line_name' => $lineName,
                'stops' => json_encode($stops),
                'path' => json_encode($path),
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);
        }
    }

    protected function generateTramLines($currentTime)
    {
        for ($i = 1; $i <= 12; $i++) {
            $lineName = 'Linea Tram ' . $i;
            $stops = $this->cityPlanningService->generateTransportStops(6, []);
            $path = $this->cityPlanningService->generatePathForLine($stops);

            DB::table('tram_lines')->insert([
                'line_name' => $lineName,
                'stops' => json_encode($stops),
                'path' => json_encode($path),
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);
        }
    }

    protected function generateMetroLines($currentTime)
    {
        $metroLines = ['Metro A', 'Metro B', 'Metro K'];

        foreach ($metroLines as $lineName) {
            $stops = $this->cityPlanningService->generateTransportStops(6, []);
            $path = $this->cityPlanningService->generatePathForLine($stops);

            DB::table('metro_lines')->insert([
                'line_name' => $lineName,
                'stops' => json_encode($stops),
                'path' => json_encode($path),
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);
        }
    }
}
