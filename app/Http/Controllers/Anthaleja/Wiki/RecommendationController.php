<?php

namespace App\Http\Controllers\Anthaleja\Wiki;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Anthaleja\Wiki\RecommendationService;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function getDynamicRecommendations()
    {
        $character = Auth::user()->character;
        $recommendations = $this->recommendationService->recommendArticlesForCharacter($character->id);

        return response()->json($recommendations);
    }
}
