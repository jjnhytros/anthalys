<?php

namespace App\Http\Controllers\Anthaleja\Bank;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Anthaleja\Character\Character;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\Bank\Transaction;

class BankController extends Controller
{
    public function index()
    {
        return view('anthaleja.bank.index');
    }

    public function withdrawForm()
    {
        return view('anthaleja.bank.withdraw');
    }

    // Processa il prelievo
    public function withdraw(Request $request)
    {
        $request->validate([
            'withdraw_amount' => 'required|numeric|min:1|max:' . Auth::user()->character->bank,
        ]);

        $character = Auth::user()->character;

        // Verifica se l'utente ha abbastanza soldi in banca per il prelievo
        if ($character->bank < $request->withdraw_amount) {
            return back()->withErrors('Non hai abbastanza soldi in banca per il prelievo.');
        }

        // Trasferisce denaro dalla banca al contante
        $character->decrement('bank', $request->withdraw_amount);
        $character->increment('cash', $request->withdraw_amount);

        // Registra la transazione
        Transaction::create([
            'recipient_id' => $character->id,
            'type' => 'expense',
            'amount' => $request->withdraw_amount,
            'description' => 'Prelievo contanti'
        ]);

        return back()->with('success', 'Prelievo completato con successo!');
    }

    public function depositForm()
    {
        return view('anthaleja.bank.deposit');
    }

    // Processa il deposito
    public function deposit(Request $request)
    {
        $request->validate([
            'deposit_amount' => 'required|numeric|min:1|max:' . Auth::user()->character->cash,
        ]);

        $character = Auth::user()->character;

        // Verifica se l'utente ha abbastanza contanti per il deposito
        if ($character->cash < $request->deposit_amount) {
            return back()->withErrors('Non hai abbastanza contanti per depositare.');
        }

        // Verifica se il personaggio ha un saldo di emergenza da rimborsare
        if ($character->emergency_balance > 0) {
            // L'importo da rimborsare sarà il minore tra l'importo del deposito e il saldo di emergenza
            $amountToRepay = min($request->input('deposit_amount'), $character->emergency_balance);

            // Rimborso del saldo di emergenza
            $character->decrement('emergency_balance', $amountToRepay);

            // Riduci l'importo del deposito effettivo
            $remainingDeposit = $request->input('deposit_amount') - $amountToRepay;

            // Messaggio di rimborso completo
            if ($character->emergency_balance == 0) {
                session()->flash('success', 'Il tuo saldo di emergenza è stato completamente rimborsato.');
            }
        } else {
            // Se non c'è nulla da rimborsare, l'intero importo viene depositato
            $remainingDeposit = $request->input('deposit_amount');
        }

        // Trasferisce i contanti al conto in banca
        $character->decrement('cash', $request->deposit_amount);
        $character->increment('bank', $request->deposit_amount);

        // Registra la transazione
        Transaction::create([
            'recipient_id' => $character->id,
            'type' => 'income',
            'amount' => $request->deposit_amount,
            'description' => 'Deposito contanti',
        ]);

        return back()->with('success', 'Deposito completato con successo!');
    }

    public function transfer()
    {
        return view('anthaleja.bank.transfer');
    }

    public function processTransfer(Request $request)
    {
        // Validazione dei dati
        $request->validate([
            'recipientAccount' => 'required|exists:characters,bank_account',
            'amount' => 'required|numeric|min:1',
        ]);

        // Ottieni il mittente autenticato
        $sender = Auth::user()->character;

        if (!$sender) {
            return redirect()->back()->withErrors('Mittente non trovato.');
        }

        // Ottieni il destinatario
        $recipient = Character::where('bank_account', $request->recipientAccount)->first();

        if (!$recipient) {
            return redirect()->back()->withErrors('Destinatario non trovato.');
        }

        // Verifica che il mittente non stia trasferendo a sé stesso
        if ($sender->bank_account === $recipient->bank_account) {
            return redirect()->back()->withErrors('Il destinatario non può essere lo stesso del mittente.');
        }

        // Definisci il limite massimo per la transazione
        $transactionLimit = config('ath.bank.transfer_limit');

        // Verifica che l'importo non superi il limite
        if ($request->amount > $transactionLimit) {
            return redirect()->back()->withErrors("L'importo della transazione supera il limite massimo di {$transactionLimit}.");
        }

        // Verifica che il mittente abbia sufficiente denaro, incluso il costo della commissione
        $commission = config('ath.bank.commission_fee'); // Commissione per la transazione
        $totalAmount = $request->amount + $commission;

        // Verifica che il mittente abbia sufficiente denaro
        if ($sender->bank < $totalAmount) {
            return redirect()->back()->withErrors('Saldo insufficiente per completare la transazione.');
        }

        // Ottieni il character 2 per la commissione
        $commissionReceiver = Character::find(2);

        if (!$commissionReceiver) {
            return redirect()->back()->withErrors('Impossibile elaborare la commissione. Personaggio non trovato.');
        }

        // Esegui la transazione all'interno di una transazione di database per garantire la consistenza
        DB::transaction(function () use ($sender, $recipient, $commissionReceiver, $request, $commission) {
            // Deduce l'importo dal mittente (inclusa la commissione)
            $sender->decrement('bank', $request->amount + $commission);

            // Aggiunge l'importo al destinatario
            $recipient->increment('bank', $request->amount);

            // Aggiunge la commissione al Character 2
            $commissionReceiver->increment('bank', $commission);

            // Registra il log della transazione per il mittente (expense)
            Transaction::create([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'amount' => $request->amount,
                'status' => 'success',
                'description' => 'Transazione completata con successo.',
                'type' => 'expense',
            ]);

            // Registra il log della transazione per il destinatario (income)
            Transaction::create([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'amount' => $request->amount,
                'status' => 'success',
                'description' => 'Transazione ricevuta con successo.',
                'type' => 'income',
            ]);

            // Registra il log per la commissione (income per Character 2)
            Transaction::create([
                'sender_id' => $sender->id,
                'recipient_id' => $commissionReceiver->id,
                'amount' => $commission,
                'status' => 'success',
                'description' => 'Commissione di transazione.',
                'type' => 'income',
            ]);
        });

        return redirect()->back()->with('success', 'Transazione completata con successo!');
    }


    public function statement()
    {
        // Ottieni il personaggio autenticato
        $character = Auth::user()->character;

        // Recupera tutte le transazioni dove il personaggio è mittente o destinatario
        $transactions = Transaction::where(function ($query) use ($character) {
            $query->where('sender_id', $character->id)
                ->orWhere('recipient_id', $character->id);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        // Filtra per evitare che lo stesso personaggio sia sia mittente che destinatario nella stessa transazione
        $filteredTransactions = $transactions->reject(function ($transaction) use ($character) {
            return $transaction->sender_id == $character->id && $transaction->recipient_id == $character->id;
        });

        // Mostra la vista con l'estratto conto
        $currentBalance = $character->bank;

        // Calcola il saldo medio e la giacenza media
        $monthlyAverageBalance = $this->calculateMonthlyAverageBalance($character);
        $yearlyAverageBalance = $this->calculateYearlyAverageBalance($character);
        $annualAverageBalance = $this->calculateAnnualAverageBalance($character);

        return view('anthaleja.bank.statement', compact(
            'filteredTransactions',
            'currentBalance',
            'monthlyAverageBalance',
            'yearlyAverageBalance',
            'annualAverageBalance'
        ));
    }





    public function otherOperations()
    {
        return view('anthaleja.bank.other-operations'); // Crea la vista per altre operazioni
    }


    /**
     * Calcola il saldo medio mensile per il personaggio
     */
    private function calculateMonthlyAverageBalance($character)
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $initialBalance = $this->getInitialBalanceForPeriod($character, $startOfMonth);

        return $this->calculateAverageBalanceForPeriod($character, $startOfMonth, $endOfMonth, $initialBalance);
    }

    /**
     * Calcola il saldo medio annuale per il personaggio
     */
    private function calculateYearlyAverageBalance($character)
    {
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();
        $initialBalance = $this->getInitialBalanceForPeriod($character, $startOfYear);

        return $this->calculateAverageBalanceForPeriod($character, $startOfYear, $endOfYear, $initialBalance);
    }

    /**
     * Calcola il saldo medio per un periodo di tempo
     */
    private function calculateAverageBalanceForPeriod($character, $start, $end, $initialBalance)
    {
        // Recupera tutte le transazioni nel periodo
        $transactions = Transaction::where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->where(function ($query) use ($character) {
                $query->where('sender_id', $character->id)
                    ->orWhere('recipient_id', $character->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Se non ci sono transazioni, il saldo medio corrisponde al saldo iniziale
        if ($transactions->isEmpty()) {
            return $initialBalance;
        }

        $dailyBalances = [];
        $balance = $initialBalance;
        $currentDay = $start->copy();
        $totalDays = $start->diffInDays($end) + 1;

        foreach ($transactions as $transaction) {
            $transactionDay = $transaction->created_at->startOfDay();

            while ($currentDay->lessThan($transactionDay)) {
                $dailyBalances[] = $balance;
                $currentDay->addDay();
            }

            if ($transaction->type === 'expense') {
                $balance -= $transaction->amount;
            } else {
                $balance += $transaction->amount;
            }

            $dailyBalances[] = $balance;
            $currentDay->addDay();
        }

        // Aggiungi il saldo per i giorni rimanenti fino alla fine del periodo
        while ($currentDay->lessThanOrEqualTo($end)) {
            $dailyBalances[] = $balance;
            $currentDay->addDay();
        }

        $totalBalance = array_sum($dailyBalances);
        $averageBalance = $totalBalance / $totalDays;

        return $averageBalance;
    }

    /**
     * Ottiene il saldo iniziale per un periodo
     */
    private function getInitialBalanceForPeriod($character, $start)
    {
        $lastTransactionBeforePeriod = Transaction::where(function ($query) use ($character) {
            $query->where('sender_id', $character->id)
                ->orWhere('recipient_id', $character->id);
        })
            ->where('created_at', '<', $start)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastTransactionBeforePeriod) {
            return $lastTransactionBeforePeriod->balance_after;
        }

        return $character->bank;
    }

    /**
     * Calcola la giacenza media annuale per il personaggio
     */
    private function calculateAnnualAverageBalance($character)
    {
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();
        $initialBalance = $this->getInitialBalanceForPeriod($character, $startOfYear);

        return $this->calculateAverageBalanceForPeriod($character, $startOfYear, now(), $initialBalance);
    }
}
