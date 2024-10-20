<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Models\Anthaleja\SoNet\Ad;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\SoNetAd;
use App\Models\Anthaleja\Character\Character;

class AdController extends Controller
{
    // Creazione di un annuncio pubblicitario
    public function create(Request $request)
    {
        $character = Auth::user()->character;

        // Verifica che il tipo di annuncio sia valido
        $adType = $request->type;

        // Per ads a pagamento deduciamo il costo dalla banca
        if ($adType == 'paid') {
            if ($character->bank < $request->cost) {
                return response()->json(['message' => 'Saldo bancario insufficiente per creare l’annuncio'], 400);
            }
            $character->bank -= $request->cost;
            $character->save();
        }

        // Crea l'annuncio pubblicitario
        $ad = new SoNetAd([
            'character_id' => $character->id,
            'content' => $request->content,
            'cost' => $request->cost, // Solo per paid
            'type' => $adType,
            'start_date' => now(),
            'end_date' => now()->addSeconds(100800 * 7), // 7 giorni
        ]);

        $ad->save();

        return response()->json(['message' => 'Annuncio creato con successo', 'ad' => $ad]);
    }

    // Lista degli annunci
    public function index()
    {
        $ads = SoNetAd::where('active', true)->get();
        return view('anthaleja.sonet.ads.index', compact('ads'));
    }

    // Cancellazione di un annuncio
    public function destroy($id)
    {
        $ad = SoNetAd::find($id);
        if ($ad && $ad->character_id == Auth::user()->character->id) {
            $ad->delete();
            return response()->json(['message' => 'Annuncio cancellato con successo']);
        }

        return response()->json(['message' => 'Annuncio non trovato o non autorizzato'], 404);
    }

    public function interact($id, $interactionType)
    {
        $ad = SoNetAd::find($id);

        if ($interactionType == 'view') {
            $ad->views++;
        } elseif ($interactionType == 'click') {
            $ad->clicks++;

            // Ricalcola il costo totale basato su PPC
            $ad->cost = $ad->calculateCosts();

            $government = Character::find(2); // ID 2 rappresenta il governo
            $sender = $ad->character; // Chi ha creato l'annuncio
            $ppcTax = $ad->clicks * 0.01; // Tassa PPC totale

            // Verifica se il mittente ha abbastanza fondi in banca
            if ($sender->bank >= $ppcTax) {
                // Deduce il costo dal mittente
                $sender->bank -= $ppcTax;
                $sender->save();

                // Aggiunge il costo al governo
                $government->bank += $ppcTax;
                $government->save();
            } else {
                return response()->json(['message' => 'Fondi insufficienti per pagare la tassa PPC'], 400);
            }
        }

        $ad->save();

        return response()->json(['message' => 'Interazione aggiornata', 'ad' => $ad]);
    }
}
