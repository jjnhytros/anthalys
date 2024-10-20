<?php

namespace App\Http\Controllers\Anthaleja\Admin;

use Illuminate\Http\Request;
use App\Models\Anthaleja\Character\Character;
use App\Http\Controllers\Controller;

class ResourceController extends Controller
{
    public function index()
    {
        // Recupera tutti i personaggi
        $characters = Character::all();

        return route('anthaleja.admin.resources.index', compact('characters'));
    }

    public function update(Request $request)
    {
        // Validazione dell'input
        $request->validate([
            'character_id' => 'required|exists:characters,id',
            'resources' => 'required|array',
            'resources.*' => 'numeric|min:0|max:100', // Assicurarsi che le risorse siano tra 0 e 100
        ]);

        // Trova il personaggio
        $character = Character::find($request->character_id);

        // Aggiorna le risorse
        $character->resources = $request->resources;
        $character->save();

        return redirect()->route('anthaleja.admin.resources.index')->with('success', 'Risorse aggiornate con successo!');
    }
}
