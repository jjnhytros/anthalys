<?php

namespace App\Http\Controllers\ATHDateTime;

use App\Models\ATHDateTime\{Day, Month};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DayMonthController extends Controller
{
    /**
     * Mostra l'indice con tutti i giorni e i mesi.
     * Recupera tutti i giorni e i mesi dal database.
     */
    public function index()
    {
        try {
            // Recupera tutti i giorni e i mesi
            $days = Day::all();
            $months = Month::all();

            // Passa i dati alla vista
            return view('days_months.index', compact('days', 'months'));
        } catch (\Exception $e) {
            // Gestione degli errori e visualizzazione del messaggio di errore
            return redirect()->back()->withErrors(['error' => 'Error loading days and months.']);
        }
    }

    /**
     * Aggiorna i nomi dei giorni.
     * Riceve un array di dati e aggiorna i giorni corrispondenti.
     */
    public function updateDays(Request $request)
    {
        try {
            // Recupera i dati dei giorni dal form
            $days = $request->input('days');

            // Aggiorna ogni giorno se esiste
            foreach ($days as $id => $name) {
                $day = Day::find($id);
                if ($day) {
                    $day->name = $name;
                    $day->save();
                }
            }

            // Reindirizza con un messaggio di successo
            return redirect()->back()->with('success', 'Days updated successfully');
        } catch (\Exception $e) {
            // Gestione degli errori
            return redirect()->back()->withErrors(['error' => 'Error updating days.']);
        }
    }

    /**
     * Aggiorna i nomi dei mesi.
     * Riceve un array di dati e aggiorna i mesi corrispondenti.
     */
    public function updateMonths(Request $request)
    {
        try {
            // Recupera i dati dei mesi dal form
            $months = $request->input('months');

            // Aggiorna ogni mese se esiste
            foreach ($months as $id => $name) {
                $month = Month::find($id);
                if ($month) {
                    $month->name = $name;
                    $month->save();
                }
            }

            // Reindirizza con un messaggio di successo
            return redirect()->back()->with('success', 'Months updated successfully');
        } catch (\Exception $e) {
            // Gestione degli errori
            return redirect()->back()->withErrors(['error' => 'Error updating months.']);
        }
    }
}
