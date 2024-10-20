<?php

namespace App\Http\Controllers\ATHDateTime;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ATHDateTime\{Day, Month, Province, Timezone};

class HomeController extends Controller
{
    public function index()
    {
        // Visualizza la homepage per i visitatori
        return view('anthaleja.home.index');
    }

    public function dashboard()
    {
        // Recupera il character associato all'utente autenticato
        $character = Auth::user()->character;

        // Verifica se esiste il character e restituisce la dashboard con i dati del character
        if ($character) {
            return view('anthaleja.home.dashboard', [
                'character' => $character
            ]);
        }

        // Se non c'è un character associato, reindirizza o gestisci l'errore
        return redirect()->route('home')->with('error', 'Character non trovato.');
    }

    /**
     * Mostra la pagina principale con informazioni sulle province, fusi orari, mesi e giorni.
     * Calcola il numero totale di province, mesi, giorni e fusi orari.
     * Recupera anche le province e i fusi orari eliminati.
     */
    // public function index()
    // {
    //     try {
    //         // Conta il numero totale di province e di province eliminate
    //         $totalProvinces = Province::count();
    //         $deletedProvinces = Province::onlyTrashed()->count();

    //         // Conta il numero totale di fusi orari e di fusi orari eliminati
    //         $totalTimezones = Timezone::count();
    //         $deletedTimezones = Timezone::onlyTrashed()->count();

    //         // Conta il numero totale di mesi e recupera i nomi dei mesi
    //         $totalMonths = Month::count();
    //         $months = Month::pluck('name')->implode(', ', ' and ');
    //         $aMonths = explode(', ', $months);  // Array di mesi

    //         // Conta il numero totale di giorni e recupera i nomi dei giorni
    //         $totalDays = Day::count();
    //         $days = Day::pluck('name')->implode(', ', ' and ');
    //         $aDays = explode(', ', $days);  // Array di giorni

    //         // Passa le variabili alla vista
    //         return view('home', compact(
    //             'totalProvinces',
    //             'deletedProvinces',
    //             'totalTimezones',
    //             'deletedTimezones',
    //             'totalMonths',
    //             'months',
    //             'aMonths',
    //             'totalDays',
    //             'days',
    //             'aDays'
    //         ));
    //     } catch (\Exception $e) {
    //         // Gestione dell'errore in caso di problemi durante il recupero dei dati
    //         return redirect()->back()->withErrors(['error' => 'Error loading home page data.']);
    //     }
    // }
}
