<?php

namespace App\Http\Controllers\Anthaleja\Wiki;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiRecommendationController extends Controller
{
    public function index()
    {
        $suggestions = AiRecommendation::all();  // Recupera tutte le raccomandazioni
        return view('admin.ai_recommendations.index', compact('suggestions'));
    }

    public function approve($id)
    {
        $suggestion = AiRecommendation::findOrFail($id);
        $suggestion->approved = true;
        $suggestion->save();

        return redirect()->route('ai_recommendations.index')->with('success', 'Recommendation approved.');
    }
}
