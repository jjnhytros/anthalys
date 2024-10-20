<?php

namespace App\Models\Anthaleja\Marketplace;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    // Definisce i campi che possono essere assegnati in massa
    protected $fillable = [
        'name',             // Nome della regione
        'price_multiplier', // Moltiplicatore del prezzo per la regione (può influire sul prezzo degli oggetti)
        'description'       // Descrizione della regione
    ];

    /**
     * Relazione con il modello Item (gli oggetti presenti in questa regione).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
