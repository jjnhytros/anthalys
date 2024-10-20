<?php

namespace App\Models\Anthaleja\City;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\City\Transport\BusLine;
use App\Models\Anthaleja\City\Transport\MetroLine;

class MapSquare extends Model
{
    protected $fillable = ['x_coordinate', 'y_coordinate', 'sector_name', 'type'];

    // Relazione con i personaggi
    public function characters()
    {
        return $this->hasMany(Character::class);
    }

    public function buildings()
    {
        return $this->hasMany(Building::class, 'map_square_id');
    }

    public function subCells()
    {
        return $this->hasMany(SubCell::class);
    }


    // Verifica se la cella fa parte di una fermata del bus
    public function hasBusStop()
    {
        $busLines = BusLine::all();  // Recupera tutte le linee del bus
        foreach ($busLines as $line) {
            $stops = json_decode($line->stops, true);  // Decodifica il JSON delle fermate
            foreach ($stops as $stop) {
                if ($stop['x'] == $this->x_coordinate && $stop['y'] == $this->y_coordinate) {
                    return true;
                }
            }
        }
        return false;
    }

    // Verifica se la cella fa parte di una fermata della metro
    public function hasMetroStop()
    {
        $metroLines = MetroLine::all();  // Recupera tutte le linee della metro
        foreach ($metroLines as $line) {
            $stops = json_decode($line->stops, true);  // Decodifica il JSON delle fermate
            foreach ($stops as $stop) {
                if ($stop['x'] == $this->x_coordinate && $stop['y'] == $this->y_coordinate) {
                    return true;
                }
            }
        }
        return false;
    }
}
