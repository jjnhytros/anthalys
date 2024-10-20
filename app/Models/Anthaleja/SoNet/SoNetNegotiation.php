<?php

namespace App\Models\Anthaleja\SoNet;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class SoNetNegotiation extends Model
{
    // Definisce i campi che possono essere riempiti tramite inserimento massivo
    protected $fillable = [
        'job_offer_id',     // ID dell'offerta di lavoro a cui è associata la negoziazione
        'character_id',      // ID del personaggio coinvolto nella negoziazione
        'salary_offered',    // Salario offerto nella negoziazione
        'message',           // Messaggio inviato durante la negoziazione
        'status'             // Stato della negoziazione ('pending', 'accepted', 'rejected', ecc.)
    ];

    /**
     * Relazione con JobOffer (Offerta di Lavoro).
     * Definisce che la negoziazione appartiene a una specifica offerta di lavoro.
     */
    public function jobOffer()
    {
        try {
            return $this->belongsTo(SoNetJobOffer::class);
        } catch (\Exception $e) {
            dd('Error retrieving job offer: ' . $e->getMessage());
        }
    }

    /**
     * Relazione con Character (Personaggio).
     * Definisce che la negoziazione appartiene a un personaggio specifico.
     */
    public function character()
    {
        try {
            return $this->belongsTo(Character::class);
        } catch (\Exception $e) {
            dd('Error retrieving character: ' . $e->getMessage());
        }
    }
}
