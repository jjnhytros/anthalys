<?php

namespace App\Models\Anthaleja\SoNet;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'character_id',
        'amount',
        'duration',
        'next_payment_date',
        'active',
    ];

    // Relazione con il personaggio che ha l'abbonamento
    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    // Metodo per verificare se l'abbonamento è attivo
    public function isActive()
    {
        return $this->active && $this->next_payment_date > now();
    }

    // Metodo per calcolare la prossima data di pagamento in base alla durata
    public function calculateNextPaymentDate()
    {
        switch ($this->duration) {
            case '1 month':
                return now()->addSeconds(2419200); // 1 mese in secondi
            case '3 months':
                return now()->addSeconds(2419200 * 3);
            case '6 months':
                return now()->addSeconds(2419200 * 6);
            case '9 months':
                return now()->addSeconds(2419200 * 9);
            case '18 months':
                return now()->addSeconds(2419200 * 18);
            default:
                return now(); // Imposta una data predefinita nel caso qualcosa vada storto
        }
    }
}
