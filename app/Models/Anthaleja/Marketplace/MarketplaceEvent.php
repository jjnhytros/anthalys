<?php

namespace App\Models\Anthaleja\Marketplace;

use Illuminate\Database\Eloquent\Model;

class MarketplaceEvent extends Model
{
    // Definisce i campi che possono essere assegnati in massa
    protected $fillable = [
        'name',         // Nome dell'evento nel marketplace
        'start_time',   // Data e ora di inizio dell'evento
        'end_time'      // Data e ora di fine dell'evento
    ];

    /**
     * Crea un evento del marketplace
     *
     * @param array $data
     * @return static
     */
    public static function createEvent(array $data)
    {
        try {
            // Crea un nuovo evento con i dati forniti
            return self::create($data);
        } catch (\Exception $e) {
            // Gestione dell'errore
            dd('Error creating marketplace event: ' . $e->getMessage());
        }
    }

    /**
     * Aggiorna le informazioni di un evento del marketplace.
     *
     * @param array $data
     * @return bool
     */
    public function updateEvent(array $data)
    {
        try {
            // Aggiorna l'evento con i dati forniti
            return $this->update($data);
        } catch (\Exception $e) {
            // Gestione dell'errore
            dd('Error updating marketplace event: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un evento del marketplace.
     *
     * @return bool|null
     */
    public function deleteEvent()
    {
        try {
            // Elimina l'evento
            return $this->delete();
        } catch (\Exception $e) {
            // Gestione dell'errore
            dd('Error deleting marketplace event: ' . $e->getMessage());
        }
    }
}
