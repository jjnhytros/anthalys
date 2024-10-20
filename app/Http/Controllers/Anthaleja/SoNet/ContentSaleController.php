<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Models\Anthaleja\Message;
use App\Models\Anthaleja\Character\Character;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\Bank\Transaction;

class ContentSaleController extends Controller
{
    public function processSale(Request $request)
    {
        $sender = Auth::user()->character;
        $recipient = Character::find($request->recipient_id);
        $amount = $request->amount;

        // Calcola la tassa del 0.1%
        $tax = $amount * 0.001;

        // Verifica se il mittente ha fondi sufficienti
        if ($sender->bank >= $amount) {
            // Deduce l'importo totale dal mittente
            $sender->bank -= $amount;

            // Trasferisce la tassa al governo
            $government = Character::find(2); // ID del governo
            $government->bank += $tax;

            // Trasferisce il resto al destinatario (venditore)
            $recipient->bank += ($amount - $tax);

            $sender->save();
            $government->save();
            $recipient->save();

            // Crea la transazione associata alla vendita
            $transaction = new Transaction([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'amount' => $amount,
                'type' => 'sale',
                'status' => 'completed',
                'description' => $request->description ?? 'Vendita di contenuti'
            ]);

            $transaction->save();

            // Invia messaggio di notifica al mittente e al destinatario
            Message::create([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'subject' => 'Vendita Completata',
                'message' => 'Hai completato una vendita di contenuti per un importo di ' . athel($amount),
                'type' => 'notification',
                'is_notification' => true,
            ]);

            return response()->json(['message' => 'Vendita completata con successo', 'transaction' => $transaction]);
        } else {
            return response()->json(['message' => 'Fondi insufficienti per completare la vendita'], 400);
        }
    }
}
