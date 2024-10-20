<?php

namespace App\Models\Anthaleja\Marketplace;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Definisce i campi che possono essere assegnati in massa
    protected $fillable = [
        'name',          // Nome dell'oggetto
        'type',          // Tipo di oggetto (es. risorsa, strumento, ecc.)
        'price',         // Prezzo corrente dell'oggetto
        'owner_id',      // ID del proprietario dell'oggetto
        'demand',        // Domanda corrente per l'oggetto (da 0 a 100)
        'is_craftable'   // Se l'oggetto è artigianale (craftable)
    ];

    /**
     * Relazione tra l'oggetto e il suo proprietario.
     * Un oggetto appartiene a un personaggio (Character).
     */
    public function owner()
    {
        return $this->belongsTo(Character::class, 'owner_id');
    }

    /**
     * Relazione tra l'oggetto e la regione in cui si trova.
     * Un oggetto può appartenere a una regione specifica.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Calcola il prezzo dinamico in base alla domanda e alle transazioni recenti.
     */
    public function calculateDynamicPrice()
    {
        try {
            // Recupera il numero di transazioni recenti negli ultimi 7 giorni
            $recentTransactions = MarketplaceTransaction::where('item_id', $this->id)
                ->where('created_at', '>=', now()->subDays(7))
                ->count();

            // Calcola il fattore di aumento del prezzo basato sulle transazioni recenti
            $demandFactor = 1 + ($recentTransactions / 10); // Aumento del 10% per ogni transazione
            $newPrice = $this->base_price * $demandFactor;

            // Se non ci sono state vendite recenti, riduce il prezzo del 5%
            if ($recentTransactions === 0) {
                $newPrice = $this->price * 0.95;
            }

            // Aggiorna il prezzo assicurando che non sia negativo
            $this->update(['price' => max($newPrice, 0)]);
        } catch (\Exception $e) {
            dd('Error calculating dynamic price: ' . $e->getMessage());
        }
    }

    /**
     * Applica l'impatto di un evento globale a tutti gli oggetti.
     */
    public function applyGlobalEventImpact()
    {
        try {
            $items = Item::all();

            foreach ($items as $item) {
                // Applica un impatto casuale sui prezzi (aumento o diminuzione)
                $impactFactor = rand(-20, 20); // Impatto casuale in percentuale
                $newPrice = $item->price * (1 + $impactFactor / 100);

                // Aggiorna il prezzo dell'oggetto assicurando che non sia negativo
                $item->update(['price' => max($newPrice, 0)]);
            }
        } catch (\Exception $e) {
            dd('Error applying global event impact: ' . $e->getMessage());
        }
    }

    /**
     * Aggiorna la domanda di un oggetto.
     * La domanda deve essere compresa tra 0 e 100.
     */
    public function updateDemand($amount)
    {
        try {
            // Aggiorna la domanda in base all'importo specificato
            $newDemand = $this->demand + $amount;
            $this->demand = max(0, min(100, $newDemand)); // La domanda deve essere tra 0 e 100
            $this->save();
        } catch (\Exception $e) {
            dd('Error updating item demand: ' . $e->getMessage());
        }
    }
}
