<?php

namespace App\Services\Anthaleja\City;

class CityPlanningService
{
    // Public Functions

    /**
     * Calcola la distanza di una cella dal centro della griglia.
     *
     * @param int $x
     * @param int $y
     * @return float
     */
    public function calculateDistanceFromCenter($x, $y)
    {
        return sqrt(pow($x - 18, 2) + pow($y - 18, 2)); // Centro è (18,18)
    }

    /**
     * Calcola il tasso di criminalità in base al tipo di settore e alla sua posizione.
     *
     * @param string $sectorType
     * @param int $x
     * @param int $y
     * @return int
     */
    public function calculateCrimeRate($sectorType, $x, $y)
    {
        $populationDensity = $this->getPopulationDensity($x, $y, $sectorType);
        $proximityToPolice = $this->calculateProximityToServices($x, $y);
        $economicGrowth = $this->generateEconomicGrowthAI($sectorType);

        return match ($sectorType) {
            'residential' => ($populationDensity === 'alta' && $proximityToPolice === 'bassa') ? rand(50, 70) : rand(30, 50),
            'industrial' => rand(10, 30),
            'commercial' => ($economicGrowth > 80) ? rand(60, 80) : rand(40, 60),
            'police_station' => 10,
            'fire_station' => 15,
            default => rand(30, 50),
        };
    }

    /**
     * Calcola la domanda abitativa in base alla densità di popolazione.
     *
     * @param int $x
     * @param int $y
     * @return string
     */
    public function calculateHousingDemand($x, $y)
    {
        return $this->getPopulationDensity($x, $y, 'residential') === 'alta' ? 'alta' : 'bassa';
    }

    /**
     * Calcola il livello di inquinamento in base al tipo di settore.
     *
     * @param string $sectorType
     * @return string
     */
    public function calculatePollutionLevel($sectorType)
    {
        return match ($sectorType) {
            'industrial' => 'alta',
            'commercial' => 'media',
            default => 'bassa',
        };
    }

    /**
     * Calcola la vicinanza ai servizi essenziali.
     *
     * @param int $x
     * @param int $y
     * @return string
     */
    public function calculateProximityToServices($x, $y)
    {
        return $this->calculateDistanceFromCenter($x, $y) <= 8 ? 'alta' : 'bassa';
    }

    /**
     * Valuta l'accessibilità ai trasporti pubblici in base alla distanza dal centro.
     *
     * @param int $x
     * @param int $y
     * @return string
     */
    public function evaluatePublicTransportAccessibility($x, $y)
    {
        $distance = $this->calculateDistanceFromCenter($x, $y);
        return $distance <= 5 ? 'eccellente' : ($distance <= 10 ? 'buona' : 'scarsa');
    }

    /**
     * Valuta la qualità delle infrastrutture (strade, edifici, servizi) in base alla distanza dal centro.
     *
     * @param int $x
     * @param int $y
     * @return string
     */
    public function evaluateInfrastructureQuality($x, $y)
    {
        return $this->calculateDistanceFromCenter($x, $y) <= 10 ? 'alta' : 'media';
    }

    /**
     * Genera una zona commerciale di 6x6 in una posizione periferica.
     *
     * @return array
     */
    public function generateCommercialZone()
    {
        $xStart = rand(2, 30);
        $yStart = rand(2, 30);
        return [
            'x_start' => $xStart,
            'y_start' => $yStart,
            'x_end' => $xStart + 5,
            'y_end' => $yStart + 5
        ];
    }

    /**
     * Calcola la crescita economica basata sul tipo di settore.
     *
     * @param string $sectorType
     * @return int
     */
    public function generateEconomicGrowthAI($sectorType)
    {
        return match ($sectorType) {
            'commercial' => rand(70, 100),
            'residential' => rand(40, 70),
            'industrial' => rand(50, 90),
            default => rand(30, 50),
        };
    }

    /**
     * Genera un percorso per una linea di trasporto tra le fermate, utilizzando logiche AI.
     *
     * @param array $stops Un array di fermate (coordinate [x, y])
     * @return array Un array di coordinate che rappresentano il percorso tra le fermate
     */
    public function generatePathForLine(array $stops)
    {
        $path = [];

        // Ordina le fermate in base alla distanza per creare un percorso logico
        $sortedStops = $this->sortStopsByProximity($stops);

        // Genera il percorso che connette tutte le fermate ordinate
        for ($i = 0; $i < count($sortedStops) - 1; $i++) {
            $start = $sortedStops[$i];
            $end = $sortedStops[$i + 1];

            // Aggiungi il segmento del percorso
            $pathSegment = $this->generatePathSegment($start, $end);
            $path = array_merge($path, $pathSegment);
        }

        return $path;
    }

    /**
     * Genera un set di fermate di trasporto pubblico per una linea specifica.
     *
     * @param int $numberOfStops Il numero di fermate da generare (multiplo di 6)
     * @param array $excludedAreas Aree escluse per la generazione delle fermate (coordinate X, Y)
     * @return array Un array di fermate generate, rappresentate come coordinate [x, y]
     */
    public function generateTransportStops($numberOfStops, $excludedAreas = [])
    {
        $stops = [];

        // Assicurati che il numero di fermate sia un multiplo di 6
        if ($numberOfStops % 6 !== 0) {
            throw new \InvalidArgumentException("Il numero di fermate deve essere un multiplo di 6.");
        }

        // Genera fermate in modo casuale, escludendo le aree specificate
        while (count($stops) < $numberOfStops) {
            $x = rand(1, 36);
            $y = rand(1, 36);

            // Evita le aree escluse e non duplicare fermate
            if (!in_array([$x, $y], $excludedAreas) && !in_array([$x, $y], $stops)) {
                $stops[] = [$x, $y];
            }
        }

        return $stops;
    }

    /**
     * Ottiene la densità della popolazione in base alla distanza dal centro e al tipo di settore.
     *
     * @param int $x
     * @param int $y
     * @param string $sectorType
     * @return string
     */
    public function getPopulationDensity($x, $y, $sectorType)
    {
        $distance = $this->calculateDistanceFromCenter($x, $y);
        if ($distance <= 6) {
            return $sectorType === 'industrial' ? 'media' : 'alta';
        } elseif ($distance <= 12) {
            return 'media';
        } elseif ($distance <= 18) {
            return 'bassa';
        } else {
            return 'molto bassa';
        }
    }


    /**
     * Determina il tipo di settore (residenziale o industriale) basato su AI, escludendo le aree commerciali.
     *
     * @param int $x Coordinate X della cella
     * @param int $y Coordinate Y della cella
     * @return string Il tipo di settore determinato (residential o industrial)
     */
    public function getSectorTypeAIWithoutCommercial($x, $y)
    {
        $distance = $this->calculateDistanceFromCenter($x, $y);
        $transportAccess = $this->evaluatePublicTransportAccessibility($x, $y);

        if (
            $transportAccess === 'eccellente'
        ) {
            return 'residential';
        }

        if ($distance > 12) {
            return 'industrial';
        }

        return 'residential';
    }

    /**
     * Verifica se la cella corrente è parte della zona commerciale generata.
     *
     * @param int $x
     * @param int $y
     * @param array $commercialZone
     * @return bool
     */
    public function isPartOfCommercialZone($x, $y, $commercialZone)
    {
        return $x >= $commercialZone['x_start'] && $x <= $commercialZone['x_end']
            && $y >= $commercialZone['y_start'] && $y <= $commercialZone['y_end'];
    }

    /**
     * Verifica se è possibile piazzare una fermata della metropolitana.
     *
     * @param int $x Coordinata X della subcella
     * @param int $y Coordinata Y della subcella
     * @param string $subCellType Tipo della subcella
     * @param array $transportStops Lista delle fermate di trasporto pubblico esistenti
     * @return bool True se è possibile piazzare la fermata della metro
     */
    public function canPlaceMetroStop($x, $y, $subCellType, $transportStops)
    {
        if ($subCellType === 'park') {
            return false;
        }

        foreach ($transportStops as $stop) {
            if ($stop['x'] == $x && $stop['y'] == $y) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifica se una fermata di trasporto pubblico del tipo specificato è adiacente alla posizione corrente.
     * La distanza minima per essere considerato adiacente varia in base al tipo di trasporto:
     * - Bus e tram: massimo 3 cella di distanza.
     * - Metro: massimo 6 celle di distanza.
     *
     * @param int $x Coordinata X della posizione attuale.
     * @param int $y Coordinata Y della posizione attuale.
     * @param array $transportStops Un array contenente le fermate di trasporto pubblico esistenti, con le coordinate X, Y e il tipo di fermata.
     * @param string $type Il tipo di fermata da verificare (bus, tram, metro).
     * @return bool Restituisce true se una fermata del tipo specificato è adiacente, altrimenti false.
     */
    public function isAdjacentToTransportStop($x, $y, array $transportStops, string $type)
    {
        // Definisce la distanza minima in base al tipo di fermata
        $minDistance = ($type === 'metro') ? 6 : 3;

        foreach ($transportStops as $stop) {
            // Verifica se la fermata è del tipo corretto e se è entro la distanza minima
            if (
                $stop['type'] === $type
            ) {
                if (abs($stop['x'] - $x) <= $minDistance && abs($stop['y'] - $y) <= $minDistance) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determina se un'area è industriale in base alla posizione (periferia).
     *
     * @param int $x
     * @param int $y
     * @return bool
     */
    public function isIndustrialArea($x, $y)
    {
        return ($x < 5 && $y > 30) || ($x > 30 && $y < 5);
    }

    /**
     * Verifica se la cella corrente rappresenta una strada principale.
     * Le strade principali potrebbero essere determinate da diversi fattori, come:
     * - Tipologia di strada ("main_road")
     * - Posizione specifica sulla mappa (ad esempio, strade centrali)
     *
     * @param int $x Coordinata X della cella.
     * @param int $y Coordinata Y della cella.
     * @param array $subCells Un array di subcelle che rappresentano una griglia di subcelle.
     * @return bool Restituisce true se la cella è una strada principale, altrimenti false.
     */
    public function isMainRoad($x, $y, array $subCells)
    {
        // Ad esempio, si potrebbe considerare una "strada principale" se il tipo di subcella è 'road' o 'main_road'.
        if (isset($subCells[$x][$y]) && $subCells[$x][$y]['type'] === 'main_road') {
            return true;
        }

        // Altri criteri per determinare una strada principale, come la posizione
        // Ad esempio, le strade centrali possono essere considerate strade principali
        if ($x >= 15 && $x <= 21 && $y >= 15 && $y <= 21) {
            return true; // Considera le strade centrali come strade principali
        }

        return false;
    }

    /**
     * Determina se è necessaria una stazione dei vigili del fuoco in base alla copertura esistente e alla densità della popolazione.
     *
     * @param int $x
     * @param int $y
     * @param array $stationCoverage
     * @return bool
     */
    public function needsFireStation($x, $y, $stationCoverage)
    {
        foreach ($stationCoverage as $station) {
            $distance = sqrt(pow($station['x'] - $x, 2) + pow($station['y'] - $y, 2));
            if ($distance < 8) {
                return false;
            }
        }

        $density = $this->getPopulationDensity($x, $y, 'residential');
        return $density === 'alta' || $density === 'media' || $this->isIndustrialArea($x, $y);
    }

    /**
     * Determina se è necessaria una stazione di polizia in base alla copertura esistente e alla densità della popolazione.
     *
     * @param int $x
     * @param int $y
     * @param array $stationCoverage
     * @return bool
     */
    public function needsPoliceStation($x, $y, $stationCoverage)
    {
        foreach ($stationCoverage as $station) {
            $distance = sqrt(pow($station['x'] - $x, 2) + pow($station['y'] - $y, 2));
            if ($distance < 6) {
                return false;
            }
        }

        $density = $this->getPopulationDensity($x, $y, 'residential');
        return $density === 'alta' || $density === 'media';
    }

    /**
     * Ottimizza il posizionamento delle fermate di bus sulla mappa in base alla densità della popolazione.
     *
     * Questa funzione verifica ogni cella della mappa e, se la densità di popolazione è alta,
     * e non c'è una fermata di bus vicina (almeno 3 celle di distanza), aggiunge una nuova fermata di bus.
     *
     * @param array $map La mappa della città come array bidimensionale di celle.
     * @param array $populationDensity Un array bidimensionale che rappresenta la densità della popolazione per ogni cella.
     * @param array $transportStops Un array contenente le fermate di trasporto pubblico esistenti, con le coordinate X, Y e il tipo di fermata.
     * @return array Restituisce un array aggiornato delle fermate di trasporto pubblico, incluso il posizionamento ottimizzato delle fermate di bus.
     */
    public function optimizeBusStops($map, $populationDensity, $transportStops)
    {
        foreach ($map as $x => $row) {
            foreach ($row as $y => $cell) {
                // Verifica se la densità di popolazione è alta e se non ci sono fermate di bus troppo vicine
                if ($populationDensity[$x][$y] === 'alta' && !$this->isAdjacentToTransportStop($x, $y, $transportStops, 'bus')) {
                    // Aggiungi la fermata di bus
                    $transportStops[] = ['x' => $x, 'y' => $y, 'type' => 'bus'];
                }
            }
        }
        return $transportStops;
    }

    /**
     * Ottimizza il posizionamento delle fermate della metro in base alla vicinanza a aree ad alta densità e alla distribuzione esistente.
     *
     * Questa funzione verifica ogni cella della mappa e, se è possibile posizionare una fermata della metro
     * (secondo le regole definite: non essere in un parco, non sovrapporsi con altre fermate), aggiunge la fermata.
     *
     * @param array $map La mappa della città come array bidimensionale di celle.
     * @param array $populationDensity Un array bidimensionale che rappresenta la densità della popolazione per ogni cella.
     * @param array $transportStops Un array contenente le fermate di trasporto pubblico esistenti, con le coordinate X, Y e il tipo di fermata.
     * @return array Restituisce un array aggiornato delle fermate di trasporto pubblico, incluso il posizionamento ottimizzato delle fermate della metro.
     */
    public function optimizeMetroStops($map, $populationDensity, $transportStops)
    {
        foreach ($map as $x => $row) {
            foreach ($row as $y => $cell) {
                // Verifica se è possibile posizionare una fermata della metro in questa cella
                if ($this->canPlaceMetroStop($x, $y, $cell['type'], $transportStops) && !$this->isAdjacentToTransportStop($x, $y, $transportStops, 'metro')) {
                    // Aggiungi la fermata della metro
                    $transportStops[] = ['x' => $x, 'y' => $y, 'type' => 'metro'];
                }
            }
        }
        return $transportStops;
    }

    /**
     * Ottimizza il posizionamento delle fermate di tram lungo le strade principali.
     *
     * Questa funzione cerca le strade principali sulla mappa e, se non ci sono fermate di tram vicine
     * (almeno 3 celle di distanza), aggiunge una fermata. Le fermate di tram sono posizionate solo lungo le strade principali.
     *
     * @param array $map La mappa della città come array bidimensionale di celle.
     * @param array $populationDensity Un array bidimensionale che rappresenta la densità della popolazione per ogni cella.
     * @param array $transportStops Un array contenente le fermate di trasporto pubblico esistenti, con le coordinate X, Y e il tipo di fermata.
     * @return array Restituisce un array aggiornato delle fermate di trasporto pubblico, incluso il posizionamento ottimizzato delle fermate di tram.
     */
    public function optimizeTramStops($map, $populationDensity, $transportStops, $subCells)
    {
        foreach ($map as $x => $row) {
            foreach ($row as $y => $cell) {
                // Verifica se la cella è una strada principale e se non ci sono fermate di tram vicine
                if ($this->isMainRoad($x, $y, $subCells) && !$this->isAdjacentToTransportStop($x, $y, $transportStops, 'tram')) {
                    // Aggiungi la fermata di tram
                    $transportStops[] = ['x' => $x, 'y' => $y, 'type' => 'tram'];
                }
            }
        }
        return $transportStops;
    }

    /**
     * Restituisce un tipo di sottocella casuale in base al tipo di settore principale.
     *
     * @param string $sectorType
     * @return string
     */
    public function randomSubCellType($sectorType)
    {
        if ($sectorType === 'residential') {
            $types = ['residential', 'park', 'road'];
        } elseif ($sectorType === 'commercial') {
            $types = ['commercial', 'road', 'office'];
        } elseif ($sectorType === 'industrial') {
            $types = ['industrial', 'warehouse', 'road'];
        } else {
            $types = ['residential', 'road'];
        }

        return $types[array_rand($types)];
    }

    // Protected Functions

    /**
     * Ordina le fermate in base alla loro vicinanza reciproca.
     *
     * @param array $stops Un array di fermate (coordinate [x, y])
     * @return array Fermate ordinate per vicinanza
     */
    protected function sortStopsByProximity(array $stops)
    {
        usort($stops, function ($a, $b) {
            return sqrt(pow($a[0] - $b[0], 2) + pow($a[1] - $b[1], 2)) <=> sqrt(pow($b[0] - $a[0], 2) + pow($b[1] - $a[1], 2));
        });

        return $stops;
    }

    /**
     * Genera un segmento di percorso tra due fermate.
     *
     * @param array $start La fermata di partenza (coordinate [x, y])
     * @param array $end La fermata di arrivo (coordinate [x, y])
     * @return array Un array di coordinate che rappresenta il percorso tra le due fermate
     */
    protected function generatePathSegment(array $start, array $end)
    {
        $pathSegment = [];

        $xDirection = ($end[0] > $start[0]) ? 1 : -1;
        for ($x = $start[0]; $x != $end[0]; $x += $xDirection) {
            $pathSegment[] = [$x, $start[1]];
        }

        $yDirection = ($end[1] > $start[1]) ? 1 : -1;
        for ($y = $start[1]; $y != $end[1]; $y += $yDirection) {
            $pathSegment[] = [$end[0], $y];
        }

        return $pathSegment;
    }
}
