<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\SonetReport;

class SonetReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reportable_id' => 'required|integer',
            'reportable_type' => 'required|string',
            'reason' => 'required|string|max:255',
        ]);

        $characterId = Auth::user()->character->id;

        SonetReport::create([
            'character_id' => $characterId,
            'reportable_id' => $request->input('reportable_id'),
            'reportable_type' => $request->input('reportable_type'),
            'reason' => $request->input('reason'),
        ]);

        return redirect()->back()->with('success', 'Segnalazione inviata con successo.');
    }

    // Aggiungi altri metodi per visualizzare e gestire le segnalazioni
}
