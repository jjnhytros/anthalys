<?php

namespace App\Http\Controllers\Anthaleja\City;

use App\Http\Controllers\Controller;
use App\Models\Anthaleja\City\MapSquare;
use App\Models\Anthaleja\City\Transport\BusLine;
use App\Models\Anthaleja\City\Transport\MetroLine;

class MapController extends Controller
{
    public function showMap()
    {
        // Carica solo le celle e gli edifici
        $mapSquares = MapSquare::with('buildings')->orderBy('x_coordinate')->orderBy('y_coordinate')->get();

        $squares = $mapSquares->map(function ($square) {
            // Determina la classe CSS per ogni tipo di cella
            $class = match ($square->type) {
                'residential' => 'bg-success',
                'commercial' => 'bg-info',
                'industrial' => 'bg-warning',
                'police_station' => 'bg-primary text-light',
                'fire_station' => 'bg-danger text-light',
                'hospital' => 'bg-light text-danger',
                default => 'bg-secondary',
            };

            // Mappa i dettagli degli edifici nella cella
            $buildings = $square->buildings->map(function ($building) {
                $adjacent = $building->adjacentSquare ? " - Esteso a {$building->adjacentSquare->x_coordinate},{$building->adjacentSquare->y_coordinate}" : '';
                return [
                    'name' => $building->name,
                    'type' => $building->type,
                    'description' => $building->description . $adjacent,
                    'is_main' => $building->is_main_structure,
                ];
            });

            // Controlla se ci sono fermate di bus o metro
            $hasBusStop = $square->hasBusStop();
            $hasMetroStop = $square->hasMetroStop();

            // Restituisce i dettagli della cella senza caricare le subCells
            return [
                'id' => $square->id,
                'x_coordinate' => $square->x_coordinate,
                'y_coordinate' => $square->y_coordinate,
                'sector_name' => $square->sector_name,
                'type' => $square->type,
                'description' => $square->description,
                'class' => $class,
                'buildings' => $buildings->toArray(),  // Converte la collection in array
                'bus_stop' => $hasBusStop,
                'metro_stop' => $hasMetroStop,
            ];
        });

        // Passa i dati alla vista della mappa
        return view('anthaleja.city.map', ['squares' => $squares]);
    }

    public function getSquareDetails($x, $y)
    {
        // Recupera i dettagli del quadrato specifico
        $mapSquare = MapSquare::where('x_coordinate', $x)->where('y_coordinate', $y)->first();

        if ($mapSquare) {
            return response()->json($mapSquare);
        } else {
            return response()->json(['error' => 'Quadrato non trovato'], 404);
        }
    }

    public function loadSubCells($mapSquareId)
    {
        // Usa il parametro corretto $mapSquareId per cercare il MapSquare
        $mapSquare = MapSquare::with('subCells')->findOrFail($mapSquareId);

        // Restituisci la lista delle subcells come JSON
        return response()->json([
            'sub_cells' => $mapSquare->subCells->map(function ($subCell) {
                return [
                    'x' => $subCell->x,
                    'y' => $subCell->y,
                    'type' => $subCell->type,
                    'description' => $subCell->description,
                ];
            }),
        ]);
    }


    public function showTransportMap()
    {
        $mapSquares = MapSquare::orderBy('x_coordinate')->orderBy('y_coordinate')->get();

        // Recupera tutte le linee di trasporto (bus e metro)
        $busLines = BusLine::all();
        $metroLines = MetroLine::all();

        // Genera colori casuali per ogni linea di bus e metro e determina il colore del testo
        $busLineColors = [];
        $metroLineColors = [];

        foreach ($busLines as $line) {
            $bgColor = $this->generateRandomColor();
            $busLineColors[$line->id] = [
                'background' => $bgColor,
                'text' => $this->getTextColorForBackground($bgColor),
            ];
        }

        foreach ($metroLines as $line) {
            $bgColor = $this->generateRandomColor();
            $metroLineColors[$line->id] = [
                'background' => $bgColor,
                'text' => $this->getTextColorForBackground($bgColor),
            ];
        }

        // Traccia le fermate e i percorsi di ciascuna linea
        $stops = [];
        $paths = [];

        foreach ($busLines as $line) {
            $stopsData = json_decode($line->stops, true);
            $pathData = json_decode($line->path, true);

            foreach ($stopsData as $stop) {
                $coordinateKey = $stop['x'] . ',' . $stop['y'];
                if (!isset($stops[$coordinateKey])) {
                    $stops[$coordinateKey] = [];
                }
                $stops[$coordinateKey][] = ['type' => 'bus', 'line' => 'Bus ' . $line->id, 'color' => $busLineColors[$line->id]];
            }

            foreach ($pathData as $point) {
                $coordinateKey = $point['x'] . ',' . $point['y'];
                if (!isset($paths[$coordinateKey])) {
                    $paths[$coordinateKey] = [];
                }
                $paths[$coordinateKey][] = ['type' => 'bus', 'line' => 'Bus ' . $line->id, 'color' => $busLineColors[$line->id]];
            }
        }

        foreach ($metroLines as $line) {
            $stopsData = json_decode($line->stops, true);
            $pathData = json_decode($line->path, true);

            foreach ($stopsData as $stop) {
                $coordinateKey = $stop['x'] . ',' . $stop['y'];
                if (!isset($stops[$coordinateKey])) {
                    $stops[$coordinateKey] = [];
                }
                $stops[$coordinateKey][] = ['type' => 'metro', 'line' => 'Metro ' . $line->id, 'color' => $metroLineColors[$line->id]];
            }

            foreach ($pathData as $point) {
                $coordinateKey = $point['x'] . ',' . $point['y'];
                if (!isset($paths[$coordinateKey])) {
                    $paths[$coordinateKey] = [];
                }
                $paths[$coordinateKey][] = ['type' => 'metro', 'line' => 'Metro ' . $line->id, 'color' => $metroLineColors[$line->id]];
            }
        }

        // Recupera tutte le celle della mappa ordinate per coordinate
        $squares = $mapSquares->map(function ($square) use ($stops, $paths) {
            $coordinateKey = $square->x_coordinate . ',' . $square->y_coordinate;

            // Se la cella è attraversata da più percorsi, creiamo un gradient
            $pathColors = $paths[$coordinateKey] ?? [];
            $backgroundStyle = null;

            if (count($pathColors) === 1) {
                // Solo un percorso, usa un singolo colore
                $backgroundStyle = $pathColors[0]['color']['background'];
            } elseif (count($pathColors) > 1) {
                // Più percorsi, crea un gradient
                $colorStops = array_map(function ($path) {
                    return $path['color']['background'];
                }, $pathColors);
                $backgroundStyle = 'linear-gradient(45deg, ' . implode(', ', $colorStops) . ')';
            }

            // Verifica se la cella è una fermata
            $stopLines = $stops[$coordinateKey] ?? [];

            return [
                'id' => $square->id,
                'x_coordinate' => $square->x_coordinate,
                'y_coordinate' => $square->y_coordinate,
                'sector_name' => $square->sector_name,
                'type' => $square->type,
                'description' => $square->description,
                'background_style' => $backgroundStyle,
                'stop_lines' => $stopLines,  // Le linee presenti su questa fermata
            ];
        });

        // Ritorna la vista con i dati mappati
        return view('anthaleja.city.transport_map', ['squares' => $squares]);
    }



    public function showTransportGridMap()
    {
        // Recupera tutte le linee di bus e metro
        $busLines = BusLine::all();
        $metroLines = MetroLine::all();
        $mapSquares = MapSquare::orderBy('x_coordinate')->orderBy('y_coordinate')->get();

        // Colori per le linee
        $busLineColors = [];
        $metroLineColors = [];

        foreach ($busLines as $line) {
            $bgColor = $this->generateRandomColor();
            $busLineColors[$line->id] = $bgColor;
        }

        foreach ($metroLines as $line) {
            $bgColor = $this->generateRandomColor();
            $metroLineColors[$line->id] = $bgColor;
        }

        // Prepara i dati delle fermate e percorsi
        $stops = [];
        $paths = [];

        // Traccia fermate e percorsi per le linee di bus
        foreach ($busLines as $line) {
            $stopsData = json_decode($line->stops, true);
            $pathData = json_decode($line->path, true);

            foreach ($stopsData as $stop) {
                $coordinateKey = $stop['x'] . ',' . $stop['y'];
                $stops[$coordinateKey][] = ['type' => 'bus', 'color' => $busLineColors[$line->id]];
            }

            foreach ($pathData as $path) {
                $coordinateKey = $path['x'] . ',' . $path['y'];
                $paths[$coordinateKey][] = ['type' => 'bus', 'color' => $busLineColors[$line->id]];
            }
        }

        // Traccia fermate e percorsi per le linee di metro
        foreach ($metroLines as $line) {
            $stopsData = json_decode($line->stops, true);
            $pathData = json_decode($line->path, true);

            foreach ($stopsData as $stop) {
                $coordinateKey = $stop['x'] . ',' . $stop['y'];
                $stops[$coordinateKey][] = ['type' => 'metro', 'color' => $metroLineColors[$line->id]];
            }

            foreach ($pathData as $path) {
                $coordinateKey = $path['x'] . ',' . $path['y'];
                $paths[$coordinateKey][] = ['type' => 'metro', 'color' => $metroLineColors[$line->id]];
            }
        }

        // Associa le fermate e i percorsi alle celle della griglia
        $squares = $mapSquares->map(function ($square) use ($stops, $paths) {
            $coordinateKey = $square->x_coordinate . ',' . $square->y_coordinate;

            // Colore del background per la cella
            $backgroundStyle = null;

            // Controlla se la cella è una fermata o fa parte di un percorso
            $stopColors = $stops[$coordinateKey] ?? [];
            $pathColors = $paths[$coordinateKey] ?? [];

            if (count($stopColors) > 0) {
                // Se è una fermata, usa il colore della fermata
                $backgroundStyle = $stopColors[0]['color'];
            } elseif (count($pathColors) > 0) {
                // Se è parte di un percorso, usa il colore del percorso
                $backgroundStyle = $pathColors[0]['color'];
            }

            return [
                'id' => $square->id,
                'x_coordinate' => $square->x_coordinate,
                'y_coordinate' => $square->y_coordinate,
                'sector_name' => $square->sector_name,
                'type' => $square->type,
                'description' => $square->description,
                'background_style' => $backgroundStyle,  // Imposta il colore dello sfondo della cella
            ];
        });

        return view('anthaleja.city.transport_map', compact('squares'));
    }


    public function showTransportNetwork()
    {
        // Recupera tutte le linee di bus e metro
        $busLines = BusLine::all();
        $metroLines = MetroLine::all();

        // Prepara i dati per il rendering
        $busData = $busLines->map(function ($line) {
            return [
                'name' => 'Bus ' . $line->id,
                'stops' => json_decode($line->stops, true),
                'color' => $this->generateRandomColor(),
            ];
        });

        $metroData = $metroLines->map(function ($line) {
            return [
                'name' => 'Metro ' . $line->id,
                'stops' => json_decode($line->stops, true),
                'color' => $this->generateRandomColor(),
            ];
        });
        return view('anthaleja.city.network', compact('busData', 'metroData'));
    }

    private function generateRandomColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    private function getTextColorForBackground($hexColor)
    {
        // Rimuovi il simbolo "#" se presente
        $hexColor = str_replace('#', '', $hexColor);

        // Converti l'esadecimale in valori RGB
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        // Calcola la luminosità in base alla formula standard
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        // Se la luminosità è inferiore a 0.5, ritorna il bianco, altrimenti ritorna il nero
        return ($luminance > 0.5) ? '#000000' : '#FFFFFF';
    }
}
