<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Models\Anthaleja\Character\Character;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\Bank\Transaction;
use App\Models\Anthaleja\SoNet\Subscription;

class SubscriptionController extends Controller
{
    public function create(Request $request)
    {
        $character = Auth::user()->character;

        // Validazione dei dati in ingresso
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'duration' => 'required|in:1 month,3 months,6 months,9 months,18 months',
        ]);

        // Calcolo della durata in secondi
        $durationInSeconds = $this->getSeconds($validated['duration']);

        // Crea l'abbonamento
        $subscription = new Subscription([
            'character_id' => $character->id,
            'amount' => $validated['amount'],
            'duration' => $validated['duration'],
            'next_payment_date' => now()->addSeconds($durationInSeconds), // Imposta la data della prossima transazione
            'active' => true,
        ]);

        $subscription->save();

        return response()->json(['message' => 'Abbonamento creato con successo.', 'subscription' => $subscription]);
    }

    public function confirmRenewal(Subscription $subscription)
    {
        if (!$subscription->active) {
            return response()->json(['error' => 'Abbonamento non attivo.'], 400);
        }

        // Aggiorna la data del prossimo pagamento
        $subscription->next_payment_date = $subscription->calculateNextPaymentDate();
        $subscription->save();

        return response()->json(['message' => 'Abbonamento rinnovato con successo.', 'subscription' => $subscription]);
    }

    public function checkExpirations()
    {
        $subscriptions = Subscription::where('active', true)
            ->where('next_payment_date', '<', now())
            ->get();

        foreach ($subscriptions as $subscription) {
            $subscription->update(['active' => false]);
        }

        return response()->json(['message' => 'Scadenze verificate con successo.']);
    }


    public function completeSubscription(Request $request)
    {
        $recipient = Character::find($request->recipient_id);
        $sender = Auth::user()->character;
        $amount = $request->amount;

        // Calcola la microtassa dell'1%
        $microtax = round($amount * 0.01, 2); // Arrotondamento per precisione

        // Verifica se il destinatario ha fondi sufficienti per pagare la microtassa
        if ($recipient->bank >= $microtax) {
            try {
                // Deduce la microtassa dal destinatario e la trasferisce al governo
                $recipient->bank -= $microtax;
                $government = Character::find(2);
                $government->bank += $microtax;

                // Trasferisce l'importo al destinatario (dopo aver dedotto la microtassa)
                $recipient->bank += $amount;

                $recipient->save();
                $government->save();

                // Crea la transazione associata alla microtassa
                $taxTransaction = new Transaction([
                    'sender_id' => $recipient->id,
                    'recipient_id' => $government->id,
                    'amount' => $microtax,
                    'type' => 'tax',
                    'status' => 'completed',
                    'description' => 'Microtassa abbonamento',
                ]);
                $taxTransaction->save();

                // Crea la transazione associata all'abbonamento
                $transaction = new Transaction([
                    'sender_id' => $sender->id,
                    'recipient_id' => $recipient->id,
                    'amount' => $amount,
                    'type' => 'subscription',
                    'status' => 'completed',
                    'description' => $request->description ?? 'Abbonamento'
                ]);
                $transaction->save();

                return response()->json(['message' => 'Abbonamento completato con successo', 'transaction' => $transaction]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Errore durante la transazione: ' . $e->getMessage()], 500);
            }
        } else {
            return response()->json(['message' => 'Fondi insufficienti per pagare la microtassa'], 400);
        }
    }

    private function getSeconds($duration)
    {
        switch ($duration) {
            case '1 month':
                return 2419200; // 1 mese
            case '3 months':
                return 2419200 * 3; // 3 mesi
            case '6 months':
                return 2419200 * 6; // 6 mesi
            case '9 months':
                return 2419200 * 9; // 9 mesi
            case '18 months':
                return 2419200 * 18; // 18 mesi
        }
    }
}
