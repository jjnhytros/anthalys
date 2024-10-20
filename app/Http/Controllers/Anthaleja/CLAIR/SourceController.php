<?php

namespace App\Http\Controllers\Anthaleja\CLAIR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anthaleja\CLAIR\Source;

class SourceController extends Controller
{
    // Visualizza l'elenco delle fonti
    public function index()
    {
        $sources = Source::all();
        return view('anthaleja.sonet.sources.index', compact('sources'));
    }

    // Mostra il form per creare una nuova fonte
    public function create()
    {
        return view('anthaleja.sonet.sources.create');
    }

    // Salva una nuova fonte nel database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'type' => 'required|string',
            'publication_date' => 'nullable|date',
            'url' => 'nullable|url',
        ]);

        Source::create($request->all());

        return redirect()->route('sources.index')->with('success', 'Fonte creata con successo.');
    }

    // Mostra i dettagli di una fonte
    public function show(Source $source)
    {
        return view('anthaleja.sonet.sources.show', compact('source'));
    }

    // Mostra il form per modificare una fonte esistente
    public function edit(Source $source)
    {
        return view('anthaleja.sonet.sources.edit', compact('source'));
    }

    // Aggiorna una fonte esistente
    public function update(Request $request, Source $source)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'type' => 'required|string',
            'publication_date' => 'nullable|date',
            'url' => 'nullable|url',
        ]);

        $source->update($request->all());

        return redirect()->route('sources.index')->with('success', 'Fonte aggiornata con successo.');
    }

    // Elimina una fonte
    public function destroy(Source $source)
    {
        $source->delete();
        return redirect()->route('sources.index')->with('success', 'Fonte eliminata con successo.');
    }
}
