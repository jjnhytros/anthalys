<?php

namespace App\Models\Anthaleja\Marketplace;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class MarketplaceTransaction extends Model
{
    // Definisce i campi che possono essere assegnati in massa
    protected $fillable = [
        'item_id',   // ID dell'oggetto transato
        'buyer_id',  // ID del compratore
        'seller_id', // ID del venditore
        'price'      // Prezzo dell'oggetto nella transazione
    ];

    /**
     * Relazione con il modello Item (oggetto venduto/acquistato).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relazione con il modello Character per l'acquirente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buyer()
    {
        return $this->belongsTo(Character::class, 'buyer_id');
    }

    /**
     * Relazione con il modello Character per il venditore.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(Character::class, 'seller_id');
    }

    /**
     * Regola i prezzi degli oggetti nel marketplace in base a eventi globali.
     *
     * @return void
     */
    public function adjustPricesForGlobalEvents()
    {
        try {
            // Verifica se esiste un evento attivo nel marketplace
            $event = MarketplaceEvent::where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->first();

            // Se esiste un evento, regola i prezzi degli oggetti
            if ($event) {
                Item::all()->each(function ($item) use ($event) {
                    // Aumento casuale tra il 5% e il 15%
                    $priceAdjustment = 1 + (rand(5, 15) / 100);
                    $item->price *= $priceAdjustment;
                    // Salva il nuovo prezzo dell'oggetto
                    $item->save();
                });
            }
        } catch (\Exception $e) {
            dd('Error adjusting prices for global events: ' . $e->getMessage()); // Gestione dell'errore
        }
    }
}
