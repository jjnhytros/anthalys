<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\Review;

class ReviewController extends Controller
{
    public function store(Request $request, $characterId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:255',
        ]);

        $reviewer = Auth::user()->character;

        // Evitare di recensire se stesso
        if ($reviewer->id === (int) $characterId) {
            return back()->with('error', 'Non puoi recensire te stesso.');
        }

        // Verifica se è già stata lasciata una recensione per lo stesso personaggio
        if (Review::where('character_id', $characterId)->where('reviewer_id', $reviewer->id)->exists()) {
            return back()->with('error', 'Hai già recensito questo personaggio.');
        }

        Review::create([
            'character_id' => $characterId,
            'reviewer_id' => $reviewer->id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Recensione aggiunta con successo.');
    }
}
