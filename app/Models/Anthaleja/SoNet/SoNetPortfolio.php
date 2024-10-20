<?php

namespace App\Models\Anthaleja\SoNet;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Profile;

class SoNetPortfolio extends Model
{
    // Definisce i campi che possono essere riempiti tramite inserimento massivo
    protected $fillable = [
        'profile_id',    // ID del profilo associato al portfolio
        'title',         // Titolo del portfolio
        'description',   // Descrizione del portfolio
        'link'           // Link associato al portfolio (es. un URL al progetto)
    ];

    /**
     * Relazione con il modello Profile (Profilo).
     * Definisce che il portfolio appartiene a un profilo specifico.
     */
    public function profile()
    {
        try {
            // Definisce la relazione con Profile
            return $this->belongsTo(Profile::class);
        } catch (\Exception $e) {
            dd('Error retrieving profile: ' . $e->getMessage());
        }
    }
}
