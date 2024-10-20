<?php

namespace App\Models\Anthaleja\Bank;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    // Definisce i campi che possono essere riempiti tramite mass assignment
    protected $fillable = [
        'character_id',  // ID del personaggio che ha preso il prestito
        'amount',  // Importo originale del prestito
        'interest_rate',  // Tasso d'interesse del prestito
        'total_amount',  // Importo totale da restituire (prestito + interessi)
        'balance',  // Saldo residuo del prestito
        'term',  // Durata del prestito (es. 12 mesi)
        'status'  // Stato del prestito (es. "in corso", "completato", "in ritardo")
    ];

    /**
     * Relazione tra il prestito e il personaggio (character).
     * Un prestito appartiene a un solo personaggio.
     */
    public function character()
    {
        // Un prestito è associato a un personaggio
        return $this->belongsTo(Character::class);
    }

    /**
     * Relazione tra il prestito e i pagamenti del prestito.
     * Un prestito può avere molti pagamenti associati.
     */
    public function payments()
    {
        // Un prestito ha molti pagamenti
        return $this->hasMany(LoanPayment::class);
    }
}
