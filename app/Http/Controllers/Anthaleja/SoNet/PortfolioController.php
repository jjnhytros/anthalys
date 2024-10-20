<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\Portfolio;

class PortfolioController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->character->profile;
        $portfolios = $profile->portfolios;

        return view('portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        return view('portfolios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|url',
        ]);

        $profile = Auth::user()->character->profile;

        Portfolio::create([
            'profile_id' => $profile->id,
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
        ]);

        return redirect()->route('portfolios.index')->with('success', 'Progetto aggiunto al portafoglio con successo');
    }
}
