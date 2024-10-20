<?php

namespace App\Http\Controllers\Anthaleja\Marketplace;

use Illuminate\Http\Request;
use App\Models\Anthaleja\Message;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\Marketplace\Item;
use App\Models\Anthaleja\Marketplace\Offer;
use App\Models\Anthaleja\Marketplace\MarketplaceTransaction;

class OfferController extends Controller
{
    public function makeOffer(Request $request, Item $item)
    {
        $buyer = Auth::user()->character;

        Offer::create([
            'item_id' => $item->id,
            'buyer_id' => $buyer->id,
            'offer_price' => $request->offer_price,
        ]);

        return redirect()->back()->with('success', 'Offerta inviata con successo!');
    }

    public function acceptOffer(Offer $offer)
    {
        // Verifica che il venditore sia il proprietario dell'oggetto
        if (Auth::user()->character->id !== $offer->item->owner_id) {
            return redirect()->back()->with('error', 'Non hai i permessi per accettare questa offerta.');
        }

        // Accetta l'offerta
        $offer->accept();

        // Trasferisce la proprietà dell'oggetto al compratore
        $offer->item->owner_id = $offer->buyer_id;
        $offer->item->save();

        // Aggiorna il saldo del venditore e del compratore (transazioni)
        // ... codice per le transazioni ...

        return redirect()->route('marketplace.index')->with('success', 'Offerta accettata con successo!');
    }

    public function rejectOffer(Offer $offer)
    {
        // Verifica che il venditore sia il proprietario dell'oggetto
        if (Auth::user()->character->id !== $offer->item->owner_id) {
            return redirect()->back()->with('error', 'Non hai i permessi per rifiutare questa offerta.');
        }

        // Rifiuta l'offerta
        $offer->reject();

        return redirect()->route('marketplace.index')->with('success', 'Offerta rifiutata.');
    }
    public function completeTransaction($transactionId)
    {
        $transaction = MarketplaceTransaction::findOrFail($transactionId);

        // Esegui la logica di completamento della transazione

        // Invia notifiche
        $this->notifyTransaction($transaction);

        return redirect()->route('marketplace.index')->with('success', 'Transazione completata e notifiche inviate.');
    }

    public function notifyTransaction($transaction)
    {
        // Notifica per l'acquirente
        Message::create([
            'sender_id' => 1, // ID del sistema o dell'amministratore
            'recipient_id' => $transaction->buyer_id,
            'subject' => 'Conferma acquisto',
            'message' => "Hai acquistato l'oggetto '{$transaction->item->name}' per " . athel($transaction->amount),
            'is_notification' => true,
        ]);

        // Notifica per il venditore
        Message::create([
            'sender_id' => 1,
            'recipient_id' => $transaction->seller_id,
            'subject' => 'Conferma vendita',
            'message' => "Hai venduto l'oggetto '{$transaction->item->name}' per " . athel($transaction->amount),
            'is_notification' => true,
        ]);

        // Aggiorna lo stato della notifica
        $transaction->update(['notification_sent' => true]);
    }
}
