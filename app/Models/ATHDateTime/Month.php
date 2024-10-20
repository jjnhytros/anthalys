<?php

namespace App\Models\ATHDateTime;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    // Disabilita i timestamp automatici
    public $timestamps = false;

    // Definizione della tabella associata al modello
    public $table = 'months';

    // I campi che possono essere riempiti in modo massivo
    protected $fillable = ['name'];

    // Metodo per aggiornare il nome del mese (può essere implementato)
    public function updateName($newName)
    {
        try {
            $this->update(['name' => $newName]);
        } catch (\Exception $e) {
            // Notifica di errore durante l'aggiornamento
            dd('Error updating month name: ' . $e->getMessage());
        }
    }
}
