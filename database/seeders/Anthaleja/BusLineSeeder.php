<?php

namespace Database\Seeders\Anthaleja;

use Illuminate\Database\Seeder;
use Database\Seeders\SharedFunctions;
use App\Models\Anthaleja\City\Transport\BusLine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BusLineSeeder extends SharedFunctions
{
    public function run()
    {
        // Cancella tutte le linee di bus esistenti
        BusLine::truncate();

        // Genera 6 linee di bus con fermate e percorsi realistici
        for ($i = 1; $i <= 6; $i++) {
            // Usa le funzioni condivise dalla classe base per generare fermate e percorsi
            $stops = $this->generateRealisticStops(12, 24);  // Genera tra 12 e 24 fermate
            $path = $this->generatePathFromStops($stops);  // Genera il percorso basato sulle fermate

            BusLine::create([
                'line_name' => 'Linea ' . $i,
                'stops' => json_encode($stops),
                'path' => json_encode($path),
            ]);
        }
    }
}
