<?php

namespace App\Models\Anthaleja\Marketplace;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;
use Exception;

class MarketResource extends Model
{
    // Definisce i campi che possono essere assegnati in massa
    protected $fillable = ['region_id', 'character_id', 'type', 'amount', 'max_amount'];

    /**
     * Relazione con il modello Character (indica il personaggio proprietario delle risorse).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Consuma una quantità specifica di una risorsa del personaggio.
     *
     * @param Character $character - Il personaggio che possiede la risorsa.
     * @param string $type - Il tipo di risorsa da consumare.
     * @param int $amount - La quantità di risorsa da consumare.
     * @throws Exception - Se il personaggio non ha abbastanza risorse.
     */
    public function consumeResource($character, $type, $amount)
    {
        // Recupera la risorsa specifica per il personaggio e il tipo
        $resource = $character->resources()->where('type', $type)->first();

        // Verifica se il personaggio ha abbastanza risorse
        if ($resource->amount >= $amount) {
            // Se c'è abbastanza risorsa, la consuma
            $resource->update(['amount' => $resource->amount - $amount]);
        } else {
            // Se non c'è abbastanza risorsa, lancia un'eccezione
            throw new Exception('Not enough ' . $type);
        }
    }

    /**
     * Genera risorse iniziali per una regione specifica.
     *
     * @param Region $region - La regione per cui generare le risorse.
     */
    public static function generateResourcesForRegion(Region $region)
    {
        // Tipi di risorse disponibili per la generazione
        $resourceTypes = ['Wood', 'Stone', 'Iron']; // Esempi di risorse

        // Cicla attraverso i tipi di risorse e le crea
        foreach ($resourceTypes as $type) {
            self::create([
                'region_id' => $region->id,
                'type' => $type,
                'amount' => rand(50, 100), // Quantità casuale
                'max_amount' => 100, // Quantità massima
            ]);
        }
    }

    /**
     * Distribuisce le risorse per una regione, eliminando quelle esistenti e generando nuove.
     *
     * @param Region $region - La regione per cui distribuire le risorse.
     * @return \Illuminate\Http\RedirectResponse - Ritorna un redirect alla pagina delle regioni con un messaggio di successo.
     */
    public function distributeResources(Region $region)
    {
        // Elimina le risorse esistenti per la regione
        Resource::where('region_id', $region->id)->delete();

        // Genera nuove risorse per la regione
        Resource::generateResourcesForRegion($region);

        // Ritorna un messaggio di successo
        return redirect()->route('regions.index')->with('success', 'Resources successfully distributed for the region ' . $region->name);
    }

    /**
     * Rigenera una quantità specifica di risorsa per un personaggio, fino alla quantità massima consentita.
     *
     * @param Character $character - Il personaggio che possiede la risorsa.
     * @param string $type - Il tipo di risorsa da rigenerare.
     * @param int $amount - La quantità di risorsa da rigenerare.
     */
    public function regenerateResource($character, $type, $amount)
    {
        // Recupera la risorsa specifica per il personaggio e il tipo
        $resource = $character->resources()->where('type', $type)->first();

        // Calcola la nuova quantità senza superare il limite massimo
        $newAmount = min($resource->amount + $amount, $resource->max_amount);

        // Aggiorna la quantità di risorsa
        $resource->update(['amount' => $newAmount]);
    }
}
