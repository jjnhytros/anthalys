<?php

namespace App\Jobs\Anthaleja\Bank;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CalculateLoanInterestJob implements ShouldQueue
{
    use Queueable;

    /**
     * Costruttore del job.
     * Inizialmente non sono necessarie azioni specifiche nel costruttore.
     */
    public function __construct()
    {
        // Può essere utilizzato per iniezione di dipendenze se necessario in futuro
    }

    /**
     * Funzione che viene eseguita quando il job viene processato.
     * Calcola gli interessi o le penalità per i personaggi con prestiti.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Recupera tutti i personaggi con prestiti attivi
            $charactersWithLoans = Character::where('loan_amount', '>', 0)->get();

            // Itera sui personaggi per calcolare eventuali penalità
            foreach ($charactersWithLoans as $character) {
                // Se la data di scadenza del prestito è passata, aggiungi una penalità
                if (now()->greaterThan($character->loan_due_date)) {
                    $penalty = $character->loan_amount * 0.02; // Penalità del 2%
                    $character->increment('loan_amount', $penalty); // Aggiorna l'importo del prestito
                }
            }
        } catch (\Exception $e) {
            // Gestione di eventuali errori durante l'esecuzione del job
            dd('Error calculating loan interest: ' . $e->getMessage());
        }
    }
}
