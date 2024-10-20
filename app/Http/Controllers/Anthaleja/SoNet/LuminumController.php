<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Models\Anthaleja\Message;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\Luminum;
use App\Models\Anthaleja\SoNet\SonetPost;
use App\Models\Anthaleja\SoNet\SonetComment;

class LuminumController extends Controller
{
    /**
     * Aggiungi una "Lumina" a un Sonet, Commento o Profilo.
     */
    public function addLuminum(Request $request)
    {
        $request->validate([
            'luminable_id' => 'required|integer',
            'luminable_type' => 'required|string',
        ]);

        $luminableId = $request->input('luminable_id');
        $luminableType = $request->input('luminable_type');
        $characterId = Auth::user()->character->id;

        // Controlla se la Lumina esiste già
        $exists = Luminum::where([
            'character_id' => $characterId,
            'luminable_id' => $luminableId,
            'luminable_type' => $luminableType,
        ])->exists();

        if (!$exists) {
            // Aggiungi la Lumina
            Luminum::create([
                'character_id' => $characterId,
                'luminable_id' => $luminableId,
                'luminable_type' => $luminableType,
            ]);

            // Recupera l'ID del destinatario in base al tipo di contenuto
            $recipientId = match ($luminableType) {
                SonetPost::class => SonetPost::find($luminableId)?->character_id,
                SonetComment::class => SonetComment::find($luminableId)?->character_id,
                default => null,
            };

            // Invia la notifica solo se il destinatario è valido e non è l'autore stesso della Lumina
            if ($recipientId && $recipientId !== $characterId) {
                Message::create([
                    'recipient_id' => $recipientId,
                    'subject' => 'Nuova Lumina ricevuta',
                    'message' => 'Hai ricevuto una nuova Lumina sul tuo contenuto!',
                    'is_message' => false,
                    'is_notification' => true,
                    'status' => 'unread',
                ]);
            }

            return response()->json(['message' => 'Lumina aggiunta con successo.']);
        }

        return response()->json(['message' => 'Lumina già esistente.'], 400);
    }

    public function removeLuminum(Request $request)
    {
        $request->validate([
            'luminable_id' => 'required|integer',
            'luminable_type' => 'required|string',
        ]);

        $luminableId = $request->input('luminable_id');
        $luminableType = $request->input('luminable_type');
        $characterId = Auth::user()->character->id;

        // Elimina la Lumina esistente
        $deleted = Luminum::where([
            'character_id' => $characterId,
            'luminable_id' => $luminableId,
            'luminable_type' => $luminableType,
        ])->delete();

        if ($deleted) {
            return response()->json(['message' => 'Lumina rimossa con successo.']);
        }

        return response()->json(['message' => 'Lumina non trovata.'], 404);
    }
}
