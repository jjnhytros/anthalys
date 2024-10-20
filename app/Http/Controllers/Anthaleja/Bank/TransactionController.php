<?php

namespace App\Http\Controllers\Anthaleja\Bank;

use Illuminate\Http\Request;
use App\Models\Anthaleja\Message;
use App\Models\Anthaleja\Character\Character;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\Bank\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['sender', 'recipient'])->get();  // Eager loading
        return view('transactions.index', compact('transactions'));
    }

    // Conferma la transazione da parte del mittente
    public function confirm(Transaction $transaction)
    {
        if ($transaction->status !== Transaction::STATUS_PENDING) {
            return response()->json(['error' => 'Transazione non in stato pendente'], 400);
        }

        $transaction->update(['status' => Transaction::STATUS_CONFIRMED]);

        // Crea il messaggio di notifica per il destinatario
        $this->createNotification(
            $transaction->sender_id,
            $transaction->recipient_id,
            'Transazione in attesa di approvazione',
            'La transazione di ' . athel($transaction->amount) . ' richiede la tua approvazione.'
        );

        return response()->json(['message' => 'Transazione confermata dal mittente.']);
    }

    // Approvazione della transazione da parte del destinatario
    public function approve(Transaction $transaction)
    {
        if ($transaction->status !== Transaction::STATUS_CONFIRMED) {
            return response()->json(['error' => 'Transazione non in stato confermato'], 400);
        }

        $transaction->update(['status' => Transaction::STATUS_APPROVED]);

        // Crea il messaggio di notifica per il mittente
        $this->createNotification(
            $transaction->recipient_id,
            $transaction->sender_id,
            'Transazione approvata',
            'La transazione di ' . athel($transaction->amount) . ' è stata approvata.'
        );

        return response()->json(['message' => 'Transazione approvata dal destinatario.']);
    }

    public function complete(Transaction $transaction)
    {
        if ($transaction->status !== Transaction::STATUS_APPROVED) {
            return response()->json(['error' => 'Transazione non in stato approvato'], 400);
        }

        $transaction->update(['status' => Transaction::STATUS_COMPLETED]);

        return response()->json(['message' => 'Transazione completata con successo.']);
    }

    public function store(Request $request)
    {
        $sender = Auth::user()->character;
        $recipient = Character::find($request->recipient_id);
        $amount = $request->amount;

        // Crea la transazione
        $transaction = new Transaction([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'amount' => $amount,
            'type' => $request->type,
            'status' => 'pending'
        ]);

        // Calcola la commissione
        $commission = $transaction->calculateCommission();
        $transaction->commission_amount = $commission;

        // Salva la transazione
        $transaction->save();

        return response()->json(['message' => 'Transazione creata', 'transaction' => $transaction]);
    }

    protected function createNotification($senderId, $recipientId, $subject, $message)
    {
        Message::create([
            'sender_id' => $senderId,
            'recipient_id' => $recipientId,
            'subject' => $subject,
            'message' => $message,
            'type' => 'notification',
            'is_message' => false,
            'is_notification' => true,
            'status' => 'unread',
        ]);
    }
}
