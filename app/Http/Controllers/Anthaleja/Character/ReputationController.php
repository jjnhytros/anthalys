<?php

namespace App\Http\Controllers\Anthaleja\Character;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anthaleja\Reputation;
use Illuminate\Support\Facades\Auth;

class ReputationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rated_character_id' => 'required|integer|exists:characters,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:255',
        ]);

        $characterId = Auth::user()->character->id;
        $ratedCharacterId = $request->input('rated_character_id');

        if ($characterId === $ratedCharacterId) {
            return redirect()->back()->with('error', 'Non puoi valutare te stesso.');
        }

        Reputation::updateOrCreate(
            [
                'character_id' => $characterId,
                'rated_character_id' => $ratedCharacterId,
            ],
            [
                'rating' => $request->input('rating'),
                'feedback' => $request->input('feedback'),
            ]
        );

        return redirect()->back()->with('success', 'Valutazione aggiunta con successo.');
    }
}
