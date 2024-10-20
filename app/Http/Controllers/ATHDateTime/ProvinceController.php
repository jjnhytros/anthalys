<?php

namespace App\Http\Controllers\ATHDateTime;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ATHDateTime\Province;
use Illuminate\Database\Eloquent\Collection;

class ProvinceController extends Controller
{
    // Alfabeto personalizzato per l'ordinamento
    private $customAlphabet = ['a', 'b', 'k', 'ĉ', 'd', 'e', 'f', 'g', 'ĝ', 'h', 'i', 'y', 'j', 'l', 'm', 'n', 'o', 'p', 'r', 's', 'x', 't', 'u', 'w', 'v', 'z'];

    /**
     * Mostra la lista delle province, con ordinamento personalizzato
     */
    public function index(Request $request)
    {
        try {
            // Recupera la colonna e la direzione per l'ordinamento, con valori di default
            $sortColumn = $request->get('sort_by', 'province'); // Default 'province'
            $sortDirection = $request->get('order', 'asc'); // Default 'asc'

            // Recupera le province ordinate in base alla colonna e direzione
            $provinces = Province::orderBy($sortColumn, $sortDirection)->get();

            return view('provinces.index', compact('provinces', 'sortColumn', 'sortDirection'));
        } catch (\Exception $e) {
            // Gestione dell'errore
            return redirect()->back()->withErrors(['error' => 'Error loading provinces list.']);
        }
    }

    /**
     * Mostra il form per la creazione di una nuova provincia
     */
    public function create()
    {
        return view('provinces.create');
    }

    /**
     * Salva una nuova provincia nel database
     */
    public function store(Request $request)
    {
        try {
            // Valida i dati in arrivo
            $request->validate([
                'province' => 'required|unique:provinces',
                'full_name' => 'required',
                'form' => 'required',
                'state' => 'required',
                'color' => 'required',
                'area_km2' => 'required|numeric',
                'population_total' => 'required|numeric',
                'population_rural' => 'required|numeric',
                'population_urban' => 'required|numeric',
                'burgs' => 'required|integer'
            ]);

            // Crea la nuova provincia
            Province::create($request->all());

            // Reindirizza con un messaggio di successo
            return redirect()->route('provinces.index')->with('success', 'Province created successfully.');
        } catch (\Exception $e) {
            // Gestione dell'errore
            return redirect()->back()->withErrors(['error' => 'Error creating province.']);
        }
    }

    /**
     * Mostra il form per modificare una provincia esistente
     */
    public function edit(Province $province)
    {
        return view('provinces.edit', compact('province'));
    }

    /**
     * Aggiorna una provincia esistente
     */
    public function update(Request $request, Province $province)
    {
        try {
            // Valida i dati in arrivo
            $request->validate([
                'province' => 'required|unique:provinces,province,' . $province->id,
                'full_name' => 'required',
                'form' => 'required',
                'state' => 'required',
                'color' => 'required',
                'area_km2' => 'required|numeric',
                'population_total' => 'required|numeric',
                'population_rural' => 'required|numeric',
                'population_urban' => 'required|numeric',
                'burgs' => 'required|integer'
            ]);

            // Aggiorna la provincia
            $province->update($request->all());

            // Reindirizza con un messaggio di successo
            return redirect()->route('provinces.index')->with('success', 'Province updated successfully.');
        } catch (\Exception $e) {
            // Gestione dell'errore
            return redirect()->back()->withErrors(['error' => 'Error updating province.']);
        }
    }

    /**
     * Elimina una provincia (soft delete)
     */
    public function destroy(Province $province)
    {
        try {
            // Esegui la soft delete della provincia
            $province->delete();

            // Reindirizza con un messaggio di successo
            return redirect()->route('provinces.index')->with('success', 'Province deleted successfully.');
        } catch (\Exception $e) {
            // Gestione dell'errore
            return redirect()->back()->withErrors(['error' => 'Error deleting province.']);
        }
    }

    /**
     * Ripristina una provincia eliminata
     */
    public function restore($id)
    {
        try {
            // Recupera la provincia eliminata e la ripristina
            $province = Province::withTrashed()->find($id);
            $province->restore();

            // Reindirizza con un messaggio di successo
            return redirect()->route('provinces.index')->with('success', 'Province restored successfully.');
        } catch (\Exception $e) {
            // Gestione dell'errore
            return redirect()->back()->withErrors(['error' => 'Error restoring province.']);
        }
    }

    /**
     * Elimina definitivamente una provincia
     */
    public function forceDelete($id)
    {
        try {
            // Recupera la provincia eliminata e la elimina definitivamente
            $province = Province::withTrashed()->find($id);
            $province->forceDelete();

            // Reindirizza con un messaggio di successo
            return redirect()->route('provinces.index')->with('success', 'Province permanently deleted.');
        } catch (\Exception $e) {
            // Gestione dell'errore
            return redirect()->back()->withErrors(['error' => 'Error permanently deleting province.']);
        }
    }

    /**
     * Mappa una stringa nell'ordine personalizzato
     * @param string $string La stringa da mappare
     * @return array L'array delle posizioni nell'alfabeto personalizzato
     */
    private function mapStringToCustomOrder($string)
    {
        $string = strtolower($string); // Converte la stringa in minuscolo
        $order = [];

        // Mappa ogni carattere della stringa
        for ($i = 0; $i < strlen($string); $i++) {
            $letter = $string[$i];
            $position = array_search($letter, $this->customAlphabet);

            // Se la lettera non è nell'alfabeto personalizzato, la mette alla fine
            if ($position === false) {
                $position = count($this->customAlphabet);
            }

            $order[] = $position;
        }

        return $order;
    }

    /**
     * Ordina le province in base all'alfabeto personalizzato
     * @param Collection $provinces Le province da ordinare
     * @param string $sortColumn La colonna su cui basare l'ordinamento
     * @param string $sortDirection La direzione dell'ordinamento ('asc' o 'desc')
     * @return array L'array delle province ordinate
     */
    public function sortByCustomAlphabet(Collection $provinces, $sortColumn, $sortDirection)
    {
        // Converti la collection in un array
        $provincesArray = $provinces->toArray();

        // Funzione di ordinamento personalizzato con usort()
        usort($provincesArray, function ($a, $b) use ($sortColumn, $sortDirection) {
            // Mappa le stringhe in base all'alfabeto personalizzato
            $aOrder = $this->mapStringToCustomOrder($a[$sortColumn]);
            $bOrder = $this->mapStringToCustomOrder($b[$sortColumn]);

            // Ordinamento in base alla direzione scelta (asc o desc)
            if ($sortDirection === 'asc') {
                return $aOrder <=> $bOrder;
            } else {
                return $bOrder <=> $aOrder;
            }
        });

        return $provincesArray;
    }
}
