<?php

namespace App\Models\Anthaleja\Marketplace;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    // Definisce i campi che possono essere assegnati in massa
    protected $fillable = [
        'item_id',      // ID dell'oggetto per cui è stata fatta l'offerta
        'buyer_id',     // ID del compratore che fa l'offerta
        'offer_price',  // Prezzo offerto per l'oggetto
        'status'        // Stato dell'offerta (es. 'accettata', 'rifiutata', 'in attesa')
    ];

    /**
     * Relazione con il modello Item (oggetto per cui è stata fatta l'offerta).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relazione con il modello Character per il compratore.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buyer()
    {
        return $this->belongsTo(Character::class, 'buyer_id');
    }

    /**
     * Metodo per accettare un'offerta.
     *
     * @return void
     */
    public function accept()
    {
        try {
            // Aggiorna lo stato dell'offerta a 'accettata'
            $this->update(['status' => 'accepted']);
        } catch (\Exception $e) {
            // Gestione dell'errore
            dd('Error accepting offer: ' . $e->getMessage());
        }
    }

    /**
     * Metodo per rifiutare un'offerta.
     *
     * @return void
     */
    public function reject()
    {
        try {
            // Aggiorna lo stato dell'offerta a 'rifiutata'
            $this->update(['status' => 'rejected']);
        } catch (\Exception $e) {
            // Gestione dell'errore
            dd('Error rejecting offer: ' . $e->getMessage());
        }
    }
}
