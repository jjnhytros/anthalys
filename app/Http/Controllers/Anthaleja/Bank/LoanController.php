<?php

namespace App\Http\Controllers\Anthaleja\Bank;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Anthaleja\Bank\Loan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function showLoans(Request $request)
    {
        $character = Auth::user()->character;

        // Filtri sui prestiti (attivi, pagati, in ritardo)
        $filter = $request->input('filter', 'all');
        $loansQuery = Loan::where('character_id', $character->id);

        if ($filter === 'active') {
            $loansQuery->where('loan_amount', '>', 0);
        } elseif ($filter === 'paid') {
            $loansQuery->where('loan_amount', '=', 0);
        } elseif ($filter === 'overdue') {
            $loansQuery->where('next_payment_due_date', '<', now())->where('loan_amount', '>', 0);
        }

        $loans = $loansQuery->get();

        // Recupera il layout selezionato (default 'card')
        $layout = $request->input('layout', 'card');

        // Mostra la vista con i prestiti e il layout selezionato
        return view('anthaleja.bank.loans', compact('loans', 'layout'));
    }

    public function loanRequest()
    {
        $character = Auth::user()->character;

        // Calcola gli interessi e aggiorna i valori del prestito se ci sono prestiti attivi
        if ($character->loan_amount > 0) {
            $this->calculateInterest($character);
        }

        return view('anthaleja.bank.loan_request', compact('character'));
    }

    public function applyLoan(Request $request)
    {
        $request->validate([
            'loan_amount' => 'required|numeric|min:1',
            'loan_duration' => 'required|integer|min:1', // Durata in mesi
        ]);

        $character = Auth::user()->character;

        if ($character->loan_amount > 0) {
            return back()->withErrors('Hai già un prestito in corso.');
        }

        $loanAmount = $request->loan_amount;
        $loanDuration = $request->loan_duration;
        $interestRate = 5; // Tasso di interesse fisso annuo

        // Calcola la rata mensile
        $monthlyInstallment = $this->calculateInstallment($loanAmount, $interestRate, $loanDuration);

        // Salva i dettagli del prestito
        $character->loan_amount = $loanAmount;
        $character->loan_interest = ($monthlyInstallment * $loanDuration) - $loanAmount; // Interessi totali
        $character->loan_installment = $monthlyInstallment; // Salva la rata mensile
        $character->loan_due_date = Carbon::now()->addMonths($loanDuration); // Scadenza prestito
        $character->bank += $loanAmount; // Aggiungi l'importo del prestito al saldo del personaggio
        $character->save();

        return back()->with('success', 'Prestito approvato con successo! La tua rata mensile è di ' . $monthlyInstallment . ' unità.');
    }

    // Visualizza la pagina per ripagare il prestito
    public function repayLoanForm()
    {
        $character = Auth::user()->character;

        if ($character->loan_amount > 0) {
            $this->calculateInterest($character);
        } else {
            return back()->withErrors('Non hai prestiti attivi.');
        }

        return view('anthaleja.bank.repay_loan', compact('character'));
    }

    public function repayLoan(Request $request)
    {
        $request->validate([
            'repay_amount' => 'required|numeric|min:1',
            'interest_type' => 'required|string|in:annual,monthly,weekly,daily,hourly'
        ]);

        $character = Auth::user()->character;

        // Calcola gli interessi passivi se ci sono ritardi
        $this->calculatePassiveInterest($character, $request->interest_type);

        // Verifica se il saldo bancario è sufficiente per il pagamento
        if ($character->bank < $request->repay_amount) {
            return back()->withErrors('Saldo bancario insufficiente.');
        }

        // Detrarre l'importo rimborsato dal saldo bancario
        $character->decrement('bank', $request->repay_amount);

        // Ridurre l'importo del prestito
        $character->loan_amount -= $request->repay_amount;

        // Se il prestito è completamente ripagato
        if ($character->loan_amount <= 0) {
            $character->loan_amount = 0;
            $character->loan_interest = 0;
            $character->loan_due_date = null;
            $character->loan_installment = 0;
            $character->next_payment_due_date = null;
        } else {
            // Aggiorna la prossima scadenza della rata
            $character->next_payment_due_date = Carbon::now()->addMonth();
        }

        $character->save();

        return back()->with('success', 'Rimborso effettuato con successo!');
    }

    public function extendLoan(Request $request)
    {
        $request->validate([
            'extension_months' => 'required|integer|min:1',  // Numero di mesi di estensione
        ]);

        $character = Auth::user()->character;

        // Verifica se il personaggio ha un prestito attivo
        if ($character->loan_amount <= 0) {
            return back()->withErrors('Non hai prestiti attivi da estendere.');
        }

        // Estendi la durata del prestito e aggiorna la data di scadenza
        $extensionMonths = $request->extension_months;
        $interestRate = 0.0001; // Tasso di interesse annuale o mensile, può essere dinamico

        // Calcola i nuovi interessi per il periodo esteso
        $newInterest = $character->loan_amount * $interestRate * $extensionMonths;

        // Aggiungi il nuovo interesse all'importo degli interessi
        $character->loan_interest += $newInterest;

        // Aggiorna la data di scadenza del prestito
        $character->loan_due_date = Carbon::parse($character->loan_due_date)->addMonths($extensionMonths);

        // Aggiungi l'estensione alla durata del prestito
        $character->loan_duration += $extensionMonths;

        // Salva le modifiche al personaggio
        $character->save();

        return back()->with('success', 'Il prestito è stato esteso di ' . $extensionMonths . ' mesi. Gli interessi sono stati aggiornati.');
    }

    public function checkEmergencyBalance()
    {
        // Ottieni il personaggio autenticato
        $character = Auth::user()->character;

        // Verifica se il saldo in contanti e in banca è inferiore a una soglia (ad esempio 50)
        if ($character->cash <= 50 && $character->bank <= 50 && $character->emergency_balance == 0) {
            // Concedi un saldo di emergenza
            $character->increment('cash', 100);  // Concedi 100 unità di denaro
            $character->update(['emergency_balance' => 100]);

            return back()->with('success', 'Hai ricevuto un saldo di emergenza di 100 unità.');
        }

        return back()->with('info', 'Il tuo saldo non richiede un saldo di emergenza.');
    }

    public function calculateTotalAmount($amount, $interest_rate, $term)
    {
        $interest = $amount * ($interest_rate / 100) * ($term / 18);
        return $amount + $interest;
    }

    public function calculateInstallment($loanAmount, $annualInterestRate, $loanTerm)
    {
        // Converti il tasso di interesse annuo in un tasso mensile
        $monthlyInterestRate = $annualInterestRate / 18 / 100;

        // Numero totale di mesi (rate)
        $totalPayments = $loanTerm;

        // Calcola la rata mensile usando la formula dell'ammortamento
        $installment = ($loanAmount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $totalPayments)) /
            (pow(1 + $monthlyInterestRate, $totalPayments) - 1);

        return round($installment, 2); // Restituisce l'importo della rata mensile, arrotondato a 2 decimali
    }

    private function calculateInterest($character)
    {
        $dailyInterestRate = 0.0001; // 0.01% giornaliero

        // Calcola i giorni trascorsi dall'ultima data di aggiornamento
        $daysElapsed = Carbon::parse($character->loan_due_date)->diffInDays(now());

        if ($daysElapsed > 0) {
            $interestAccrued = $character->loan_amount * $dailyInterestRate * $daysElapsed;
            $character->loan_interest += $interestAccrued;

            // Se la scadenza della prossima rata è stata superata, applica una penalità
            if (now()->greaterThan($character->next_payment_due_date)) {
                $penaltyRate = 0.01; // Penalità dell'1% del saldo residuo
                $penalty = $character->loan_amount * $penaltyRate;
                $character->loan_amount += $penalty;

                // Aggiorna il prossimo pagamento in base al ritardo
                $character->next_payment_due_date = now()->addMonth();
            }

            $character->loan_due_date = now();
            $character->save();
        }
    }

    private function calculateDynamicInterest($character, $interestType = 'hourly')
    {
        // Definire i periodi per il calcolo degli interessi
        $interestDurations = [
            'annual' => 432,  // 432 giorni in un anno
            'monthly' => 24,  // 24 giorni in un mese
            'weekly' => 7,    // 7 giorni in una settimana
            'daily' => 1,     // 1 giorno
            'hourly' => 28    // 28 ore in un giorno
        ];

        // Tasso di interesse di base (puoi cambiarlo o farlo dinamico)
        $baseInterestRate = 0.0001;

        // Verifica se il tipo di interesse selezionato esiste, altrimenti usa "hourly"
        $periodDuration = $interestDurations[$interestType] ?? $interestDurations['hourly'];

        // Calcola il tasso di interesse per il periodo selezionato
        $interestRate = $baseInterestRate / $periodDuration;

        // Calcolare le ore/giorni trascorsi
        $timeElapsed = 0;

        if ($interestType === 'hourly') {
            // Calcola le ore trascorse
            $timeElapsed = Carbon::parse($character->loan_due_date)->diffInHours(now());
        } else {
            // Calcola i giorni trascorsi
            $timeElapsed = Carbon::parse($character->loan_due_date)->diffInDays(now());
        }

        // Calcolare l'interesse accumulato in base al tempo trascorso
        if ($timeElapsed > 0) {
            $interestAccrued = $character->loan_amount * $interestRate * $timeElapsed;
            $character->loan_interest += $interestAccrued;

            // Verifica eventuali ritardi e applica penalità
            if (now()->greaterThan($character->next_payment_due_date)) {
                $penaltyRate = 0.01; // Penalità dell'1% del saldo residuo
                $penalty = $character->loan_amount * $penaltyRate;
                $character->loan_amount += $penalty;

                // Aggiorna la prossima scadenza della rata in base al ritardo
                $character->next_payment_due_date = now()->addMonth();
            }

            // Aggiorna la data di scadenza dell'interesse
            $character->loan_due_date = now();
            $character->save();
        }
    }

    private function calculatePassiveInterest($character, $interestType = 'hourly')
    {
        // Definisci i periodi di calcolo degli interessi
        $interestDurations = [
            'annual' => 432,  // 432 giorni in un anno
            'monthly' => 24,  // 24 giorni in un mese
            'weekly' => 7,    // 7 giorni in una settimana
            'daily' => 1,     // 1 giorno
            'hourly' => 28    // 28 ore in un giorno
        ];

        // Tasso di interesse base
        $baseInterestRate = 0.0001;

        // Ottieni il periodo di calcolo selezionato
        $periodDuration = $interestDurations[$interestType] ?? $interestDurations['hourly'];

        // Calcola il tasso di interesse per il tipo selezionato
        $interestRate = $baseInterestRate / $periodDuration;

        // Calcola il tempo trascorso (ore o giorni)
        $timeElapsed = 0;
        if ($interestType === 'hourly') {
            $timeElapsed = Carbon::parse($character->loan_due_date)->diffInHours(now());
        } else {
            $timeElapsed = Carbon::parse($character->loan_due_date)->diffInDays(now());
        }

        // Calcolare l'interesse passivo accumulato
        if ($timeElapsed > 0) {
            // Applica solo se il tempo trascorso supera la scadenza
            if (now()->greaterThan($character->next_payment_due_date)) {
                $passiveInterestAccrued = $character->loan_amount * $interestRate * $timeElapsed;

                // Aggiungi l'interesse passivo al saldo del prestito
                $character->loan_amount += $passiveInterestAccrued;
                $character->loan_interest += $passiveInterestAccrued;

                // Aggiorna la prossima data di pagamento
                $character->next_payment_due_date = now()->addMonth();

                // Salva le modifiche
                $character->save();
            }
        }
    }
}
