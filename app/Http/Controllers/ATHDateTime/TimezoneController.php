<?php

namespace App\Http\Controllers\ATHDateTime;

use Illuminate\Http\Request;
use App\Models\ATHDateTime\Timezone;
use App\Http\Controllers\Controller;

class TimezoneController extends Controller
{
    /**
     * Mostra una lista di tutti i timezones, inclusi quelli soft-deleted
     */
    public function index()
    {
        try {
            // Recupera tutte le timezones, incluse quelle soft deleted
            $timezones = Timezone::withTrashed()->get();
            return view('timezones.index', compact('timezones'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error loading timezones.']);
        }
    }

    /**
     * Mostra il form per creare una nuova timezone
     */
    public function create()
    {
        return view('timezones.create');
    }

    /**
     * Memorizza una nuova timezone nel database
     */
    public function store(Request $request)
    {
        try {
            // Valida i dati in arrivo
            $request->validate([
                'identifier' => 'required|unique:timezones,identifier',
                'latitude' => 'required|numeric|min:-90|max:90',
                'longitude' => 'required|numeric|min:-180|max:180',
                'comments' => 'required'
            ]);

            // Recupera tutte le abbreviazioni già usate
            $usedAbbreviations = Timezone::pluck('abbreviation')->toArray();

            $data = $request->all();

            // Istanza di Timezone per chiamare il metodo non statico
            $timezone = new Timezone();
            $data['abbreviation'] = $timezone->generateAbbreviation($request->identifier, $usedAbbreviations);
            $data['offset_hours'] = Timezone::calculateOffsetHours($request->longitude);

            // Crea la nuova timezone
            Timezone::create($data);

            return redirect()->route('timezones.index')->with('success', 'Timezone created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error creating timezone.']);
        }
    }

    /**
     * Mostra i dettagli di una specifica timezone
     */
    public function show(Timezone $timezone)
    {
        return view('timezones.show', compact('timezone'));
    }

    /**
     * Mostra il form per modificare una timezone esistente
     */
    public function edit(Timezone $timezone)
    {
        return view('timezones.edit', compact('timezone'));
    }

    /**
     * Aggiorna una timezone esistente
     */
    public function update(Request $request, Timezone $timezone)
    {
        try {
            // Valida i dati in arrivo
            $request->validate([
                'identifier' => 'required|unique:timezones,identifier,' . $timezone->id,
                'latitude' => 'required|numeric|min:-90|max:90',
                'longitude' => 'required|numeric|min:-180|max:180',
                'comments' => 'required'
            ]);

            // Recupera tutte le abbreviazioni, eccetto quella della timezone attuale
            $usedAbbreviations = Timezone::where('id', '!=', $timezone->id)->pluck('abbreviation')->toArray();

            $data = $request->all();

            // Istanza di Timezone per chiamare il metodo non statico
            $data['abbreviation'] = $timezone->generateAbbreviation($request->identifier, $usedAbbreviations);
            $data['offset_hours'] = Timezone::calculateOffsetHours($request->longitude);

            // Aggiorna la timezone
            $timezone->update($data);

            return redirect()->route('timezones.index')->with('success', 'Timezone updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error updating timezone.']);
        }
    }

    /**
     * Elimina (soft delete) una timezone
     */
    public function destroy(Timezone $timezone)
    {
        try {
            $timezone->delete(); // Soft delete
            return redirect()->route('timezones.index')->with('success', 'Timezone soft deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error deleting timezone.']);
        }
    }

    /**
     * Ripristina una timezone precedentemente eliminata (soft delete)
     */
    public function restore($id)
    {
        try {
            Timezone::withTrashed()->where('id', $id)->restore(); // Restore soft deleted timezone
            return redirect()->route('timezones.index')->with('success', 'Timezone restored successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error restoring timezone.']);
        }
    }

    /**
     * Elimina definitivamente una timezone
     */
    public function forceDelete($id)
    {
        try {
            Timezone::withTrashed()->where('id', $id)->forceDelete(); // Permanently delete timezone
            return redirect()->route('timezones.index')->with('success', 'Timezone permanently deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error permanently deleting timezone.']);
        }
    }
}
