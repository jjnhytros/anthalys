<?php

namespace App\Services;

use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\City\MapSquare;;

class MovementService
{
    public function moveCharacter(Character $character, $newX, $newY)
    {
        // Trova la nuova posizione sulla mappa
        $newSquare = MapSquare::where('x_coordinate', $newX)
            ->where('y_coordinate', $newY)
            ->first();

        if ($newSquare) {
            // Aggiorna la posizione del personaggio
            $character->map_square_id = $newSquare->id;
            $character->save();

            return "Il personaggio si è spostato in {$newSquare->sector_name}.";
        } else {
            return "Posizione non valida.";
        }
    }
}
