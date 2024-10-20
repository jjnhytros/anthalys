<?php

namespace App\Models\Anthaleja\SoNet;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class SoNetReview extends Model
{
    // Campi che possono essere riempiti tramite inserimento massivo
    protected $fillable = [
        'character_id',  // ID del personaggio a cui è rivolta la recensione
        'reviewer_id',   // ID del recensore
        'rating',        // Valutazione numerica (es. 1-5 stelle)
        'review'         // Testo della recensione
    ];

    /**
     * Relazione con il modello Character (Personaggio recensito).
     * Definisce che la recensione appartiene a un personaggio.
     */
    public function character()
    {
        try {
            // Relazione con il personaggio recensito
            return $this->belongsTo(Character::class);
        } catch (\Exception $e) {
            dd('Error retrieving character: ' . $e->getMessage());
        }
    }

    /**
     * Relazione con il modello Character (Recensore).
     * Definisce che la recensione è stata scritta da un personaggio recensore.
     */
    public function reviewer()
    {
        try {
            // Relazione con il recensore
            return $this->belongsTo(Character::class, 'reviewer_id');
        } catch (\Exception $e) {
            dd('Error retrieving reviewer: ' . $e->getMessage());
        }
    }
}
