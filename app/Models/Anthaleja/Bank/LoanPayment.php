<?php

namespace App\Models\Anthaleja\Bank;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    // Definisce i campi che possono essere riempiti tramite mass assignment
    protected $fillable = [
        'loan_id',  // ID del prestito associato a questo pagamento
        'payment_amount',  // Importo del pagamento effettuato
        'payment_date'  // Data in cui è stato effettuato il pagamento
    ];

    /**
     * Relazione tra il pagamento e il prestito.
     * Un pagamento appartiene a un solo prestito.
     */
    public function loan()
    {
        // Un pagamento è associato a un prestito
        return $this->belongsTo(Loan::class);
    }
}
