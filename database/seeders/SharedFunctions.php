<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SharedFunctions extends Seeder
{
    /**
     * Funzione per generare fermate con un percorso realistico.
     */
    protected function generateRealisticStops($minStops, $maxStops)
    {
        $stops = [];
        $x = rand(1, 36);  // Coordinate iniziali random
        $y = rand(1, 36);
        $numStops = rand($minStops, $maxStops);  // Numero di fermate tra i limiti forniti

        for ($i = 0; $i < $numStops; $i++) {
            if (!in_array(['x' => $x, 'y' => $y], $stops)) {
                $stops[] = ['x' => $x, 'y' => $y];
            }

            // Muovi realisticamente con una distanza casuale tra le fermate
            $direction = $this->getRealisticDirection($x, $y);
            $coordinates = $this->moveInRealisticDirection($x, $y, $direction);

            $x = $coordinates['x'];
            $y = $coordinates['y'];
        }

        return $stops;
    }

    /**
     * Funzione per ottenere una direzione realistica per il percorso.
     */
    protected function getRealisticDirection($x, $y)
    {
        $directions = ['north', 'south', 'east', 'west', 'northeast', 'northwest', 'southeast', 'southwest'];

        // Filtra le direzioni che portano fuori dai limiti della mappa
        if ($x <= 5) {
            $directions = array_diff($directions, ['west', 'northwest', 'southwest']);
        }
        if ($x >= 30) {
            $directions = array_diff($directions, ['east', 'northeast', 'southeast']);
        }
        if ($y <= 5) {
            $directions = array_diff($directions, ['north', 'northeast', 'northwest']);
        }
        if ($y >= 30) {
            $directions = array_diff($directions, ['south', 'southeast', 'southwest']);
        }

        return $directions[array_rand($directions)];
    }

    /**
     * Muovi in una direzione realistica con una distanza casuale tra le fermate.
     */
    protected function moveInRealisticDirection($x, $y, $direction)
    {
        // Muovi con una distanza realistica casuale tra 3 e 6 unità
        $distance = rand(3, 6);

        switch ($direction) {
            case 'north':
                $y = max(1, $y - $distance);
                break;
            case 'south':
                $y = min(36, $y + $distance);
                break;
            case 'east':
                $x = min(36, $x + $distance);
                break;
            case 'west':
                $x = max(1, $x - $distance);
                break;
            case 'northeast':
                $x = min(36, $x + $distance);
                $y = max(1, $y - $distance);
                break;
            case 'northwest':
                $x = max(1, $x - $distance);
                $y = max(1, $y - $distance);
                break;
            case 'southeast':
                $x = min(36, $x + $distance);
                $y = min(36, $y + $distance);
                break;
            case 'southwest':
                $x = max(1, $x - $distance);
                $y = min(36, $y + $distance);
                break;
        }

        return ['x' => $x, 'y' => $y];
    }

    /**
     * Funzione per generare un percorso basato sulle fermate.
     */
    protected function generatePathFromStops($stops)
    {
        $path = [];
        foreach ($stops as $stop) {
            $path[] = $stop;
        }
        return $path;
    }
}
