<?php

namespace Database\Seeders\Anthaleja;

use Database\Seeders\SharedFunctions;
use App\Models\Anthaleja\City\Transport\MetroLine;

class MetroLineSeeder extends SharedFunctions
{
    public function run()
    {
        // Cancella tutte le linee di metropolitana esistenti
        MetroLine::truncate();

        // Genera 2 linee di metropolitana con fermate e percorsi realistici
        for ($i = 1; $i <= 2; $i++) {
            // Usa le funzioni condivise dalla classe base per generare fermate e percorsi
            $stops = $this->generateRealisticStops(10, 20);  // Genera tra 10 e 20 fermate
            $path = $this->generatePathFromStops($stops);  // Genera il percorso basato sulle fermate

            MetroLine::create([
                'line_name' => 'Metro ' . $i,
                'stops' => json_encode($stops),
                'path' => json_encode($path),
            ]);
        }
    }
}
