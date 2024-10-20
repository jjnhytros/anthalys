<?php

namespace App\Http\Controllers\Anthaleja;

use Illuminate\Http\Request;
use App\Models\Anthaleja\Message;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    /**
     * Mostra il form per la gestione delle preferenze.
     */
    public function index()
    {
        // Ottieni il profilo associato al personaggio dell'utente corrente
        $profile = Auth::user()->character->profile;

        return view('anthaleja.preferences.index', compact('profile'));
    }

    public function edit()
    {
        // Recupera i tipi di notifiche unici dalla tabella messages
        $notificationTypes = Message::select('type')->distinct()->get()->pluck('type');

        // Passa le preferenze e i tipi di notifiche alla vista
        return view('preferences.edit', [
            'notificationTypes' => $notificationTypes,
            'preferences' => Auth::user()->character->profile->preferences,
        ]);
    }


    /**
     * Aggiorna le preferenze del profilo.
     */
    public function update(Request $request)
    {
        $profile = Auth::user()->character->profile;

        // Recupera i tipi di notifiche
        $notificationTypes = Message::select('type')->distinct()->get()->pluck('type');

        // Crea un array di preferenze da salvare
        $preferences = [];
        foreach ($notificationTypes as $type) {
            $preferences[$type . '_notification'] = $request->has($type . '_notification') ? true : false;
        }

        // Salva le preferenze nel campo JSON del profilo
        $profile->preferences = array_merge($profile->preferences, $preferences);
        $profile->save();

        return redirect()->back()->with('success', 'Preferences saved successfully.');
    }

    public function updateNightMode(Request $request)
    {
        // Ottieni il personaggio dell'utente autenticato
        $character = Auth::user()->character;

        // Verifica se il profilo esiste, altrimenti crealo
        if ($character->profile === null) {
            $character->profile()->create([
                'night_mode' => false, // Default a light mode
            ]);
        }

        // Se night_mode è nullo, impostalo su false (light)
        $nightMode = $request->night_mode !== null ? $request->night_mode : false;

        // Aggiorna il campo night_mode nel profilo del personaggio
        $character->profile->update([
            'night_mode' => $nightMode,
        ]);

        return response()->json(['success' => true]);
    }
}
