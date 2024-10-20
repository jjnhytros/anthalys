<?php

namespace App\Http\Controllers\Anthaleja;

use App\Jobs\RandomEventJob;
use Illuminate\Http\Request;
use App\Services\DecisionEngine;
use App\Models\Anthaleja\Character\Character;
use App\Http\Controllers\Controller;

class AIController extends Controller
{
    public function triggerEvent($characterId)
    {
        $character = Character::findOrFail($characterId);
        RandomEventJob::dispatch($character);

        return response()->json(['status' => 'Event triggered']);
    }

    public function makeDecision($characterId)
    {
        $character = Character::findOrFail($characterId);
        $decisionEngine = new DecisionEngine();

        $decision = $decisionEngine->makeDecision($character);

        return response()->json(['decision' => $decision]);
    }
}
