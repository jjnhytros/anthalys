<?php

namespace App\Models\ATHDateTime;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use SoftDeletes; // Abilita il soft delete

    // Definizione della tabella associata al modello
    public $table = 'anthal_provinces';

    // I campi che possono essere riempiti in modo massivo
    protected $fillable = [
        'province',         // Nome della provincia
        'full_name',        // Nome completo della provincia
        'form',             // Forma giuridica della provincia
        'state',            // Stato di appartenenza
        'color',            // Colore rappresentativo
        'capital',          // Capitale della provincia
        'area_km2',         // Area in chilometri quadrati
        'population_total', // Popolazione totale
        'population_rural', // Popolazione rurale
        'population_urban', // Popolazione urbana
        'burgs'             // Numero di comuni o borghi
    ];

    // Metodo per aggiornare il nome della provincia
    public function updateProvinceName($newName)
    {
        try {
            $this->update(['province' => $newName]); // Aggiorna il nome della provincia
        } catch (\Exception $e) {
            // Notifica di errore durante l'aggiornamento
            dd('Error updating province name: ' . $e->getMessage());
        }
    }
}
