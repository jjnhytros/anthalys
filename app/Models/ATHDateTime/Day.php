<?php

namespace App\Models\ATHDateTime;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    // Disabilita i timestamp automatici
    public $timestamps = false;

    // Definizione della tabella associata al modello
    public $table = 'days';

    // I campi che possono essere riempiti in modo massivo
    protected $fillable = ['name'];

    // Metodo per aggiornare il nome del giorno (può essere implementato)
    public function updateName($newName)
    {
        try {
            $this->update(['name' => $newName]);
        } catch (\Exception $e) {
            // Notifica di errore durante l'aggiornamento
            dd('Error updating day name: ' . $e->getMessage());
        }
    }
}
