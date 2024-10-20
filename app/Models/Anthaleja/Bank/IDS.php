<?php

namespace App\Models\Anthaleja\Bank;

use App\Models\ATHDateTime\Month;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Services\ATHDateTime\ATHDateTime;

class IDS extends Model
{
    // Nome della tabella associata
    protected $table = 'ids';

    // Campi fillabili
    protected $fillable = ['value'];

    /**
     * Aggiorna l'Indice di Sviluppo (IDS) con una variazione casuale.
     * La variazione è simulata tra -2% e +2%.
     *
     * @return mixed Nuovo valore dell'IDS.
     */
    public function updateRandomIDS(): mixed
    {
        try {
            // Recupera l'ultimo valore dell'IDS
            $ids = IDS::latest()->first();

            // Variazione casuale tra -2% e +2%
            $idsChange = mt_rand(-2, 2) / 100;

            // Calcola il nuovo valore dell'IDS
            $newIdsValue = $ids->value + $idsChange;

            // Assicurati che l'IDS non scenda sotto 0
            if ($newIdsValue < 0) {
                $newIdsValue = 0;
            }

            // Salva il nuovo valore
            $this->updateIDS($newIdsValue);

            return $newIdsValue;
        } catch (\Exception $e) {
            // Gestione degli errori
            dd('Error updating random IDS: ' . $e->getMessage());
            return null; // Gestione personalizzata dell'errore
        }
    }

    /**
     * Calcola il nuovo IDS basato sul moltiplicatore del mese corrente.
     */
    public function calculateNewIDS()
    {
        try {
            // Recupera l'IDS attuale
            $currentIDS = $this->getCurrentIDS();

            // Recupera il nome del mese corrente
            $monthName = $this->getCurrentMonthName();

            // Ottieni il moltiplicatore del mese corrente
            $multiplier = DB::table('months')->where('name', $monthName)->value('multiplier');

            // Calcola il nuovo IDS moltiplicato per il moltiplicatore
            $newIDS = $currentIDS * $multiplier;

            // Aggiorna il nuovo valore dell'IDS
            $this->updateIDS($newIDS);
        } catch (\Exception $e) {
            // Gestione degli errori
            dd('Error calculating new IDS: ' . $e->getMessage());
        }
    }

    /**
     * Aggiorna o crea il valore dell'IDS nel database.
     *
     * @param float $newIdsValue Nuovo valore dell'IDS da salvare.
     */
    public function updateIDS($newIdsValue)
    {
        try {
            // Aggiorna o crea il record con il nuovo valore
            IDS::updateOrCreate([], ['value' => $newIdsValue]);
        } catch (\Exception $e) {
            // Gestione degli errori
            dd('Error updating IDS value: ' . $e->getMessage());
        }
    }

    /**
     * Recupera il valore corrente dell'IDS.
     *
     * @return mixed Valore corrente dell'IDS.
     */
    public function getCurrentIDS()
    {
        return IDS::latest()->value('value');
    }

    /**
     * Recupera il nome del mese corrente in base al numero del mese corrente.
     *
     * @return string Nome del mese corrente.
     */
    public function getCurrentMonthName()
    {
        // Ottiene il numero del mese corrente
        $currentMonthNumber = $this->getCurrentMonthNumber();

        // Recupera il nome del mese corrispondente
        return Month::where('id', $currentMonthNumber)->value('name');
    }

    /**
     * Ottiene il numero del mese corrente in base al sistema personalizzato (ATHDateTime).
     *
     * @return int Numero del mese corrente.
     */
    public function getCurrentMonthNumber()
    {
        // Inizializza l'oggetto ATHDateTime e ottiene il mese corrente
        $athDateTime = new ATHDateTime();
        return $athDateTime->getAMonth();
    }
}
