<?php

namespace App\Http\Controllers\Anthaleja\CLAIR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anthaleja\CLAIR\Source;
use App\Models\Anthaleja\CLAIR\SuggestedSource;

class SuggestedSourceController extends Controller
{
    // Visualizza le fonti suggerite in attesa di approvazione (solo per amministratori)
    public function index()
    {
        $suggestedSources = SuggestedSource::where('status', 'pending')->get();
        return view('anthaleja.clair.suggested.index', compact('suggestedSources'));
    }

    // Salva una nuova fonte suggerita dagli utenti
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'type' => 'required|string',
            'url' => 'nullable|url',
        ]);

        SuggestedSource::create($request->all());

        return redirect()->back()->with('success', 'Fonte suggerita con successo. In attesa di approvazione.');
    }

    // Approvazione di una fonte suggerita (solo per amministratori)
    public function approve($id)
    {
        $suggestedSource = SuggestedSource::findOrFail($id);
        $suggestedSource->update(['status' => 'approved']);

        // Converti la fonte suggerita in una fonte effettiva
        Source::create([
            'title' => $suggestedSource->title,
            'description' => $suggestedSource->description,
            'author' => $suggestedSource->author,
            'type' => $suggestedSource->type,
            'url' => $suggestedSource->url,
        ]);

        return redirect()->back()->with('success', 'Fonte approvata e aggiunta.');
    }

    // Rifiuto di una fonte suggerita (solo per amministratori)
    public function reject($id)
    {
        $suggestedSource = SuggestedSource::findOrFail($id);
        $suggestedSource->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Fonte rifiutata.');
    }
}
