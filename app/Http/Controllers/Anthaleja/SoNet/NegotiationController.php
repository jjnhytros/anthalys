<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\Negotiation;

class NegotiationController extends Controller
{
    public function store(Request $request, $jobOfferId)
    {
        $request->validate([
            'salary_offered' => 'required|numeric|min:0',
            'message' => 'nullable|string',
        ]);

        $character = Auth::user()->character;

        Negotiation::create([
            'job_offer_id' => $jobOfferId,
            'character_id' => $character->id,
            'salary_offered' => $request->salary_offered,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Offerta di negoziazione inviata con successo');
    }

    public function respond(Request $request, $negotiationId)
    {
        $negotiation = Negotiation::findOrFail($negotiationId);

        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $negotiation->update(['status' => $request->status]);

        return back()->with('success', 'Risposta inviata con successo');
    }
}
