<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\Bank\Transaction;
use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\SoNet\SoNetDonation;

class DonationController extends Controller
{
    public function send(Request $request)
    {
        $sender = Auth::user()->character;
        $recipient = Character::find($request->recipient_id);
        $amount = $request->amount;

        $validated = $request->validate([
            'sender_id' => 'required|exists:characters,id',
            'recipient_id' => 'required|exists:characters,id',
            'amount' => 'required|numeric|min:0',
            'message' => 'nullable|string',
        ]);

        // Crea la transazione associata alla donazione
        $transaction = new Transaction([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'amount' => $amount,
            'type' => 'donation',
            'status' => 'pending',
            'description' => $request->message // Messaggio personalizzato
        ]);

        $transaction->save();

        // Crea la donazione
        $donation = SoNetDonation::create([
            'transaction_id' => $transaction->id,
            'message' => $validated['message'] ?? null,
        ]);

        // Verifica i fondi e trasferisci l'importo
        if ($sender->bank >= $amount) {
            $sender->bank -= $amount;
            $recipient->bank += $amount;

            $sender->save();
            $recipient->save();

            // Aggiorna lo stato della transazione
            $transaction->status = 'completed';
            $transaction->save();
        } else {
            return response()->json(['message' => 'Fondi insufficienti per la donazione'], 400);
        }

        return response()->json(['message' => 'Donation sent successfully', 'donation' => $donation]);
    }
}
