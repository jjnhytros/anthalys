<?php

namespace App\Services\Anthaleja\City;

use RulerZ\RulerZ;
use RulerZ\Compiler\Compiler;
use RulerZ\Target\Native\Native;
use App\Models\Anthaleja\City\MapSquare;;

class DecisionEngine
{
    protected $rulerz;
    protected $constructionService;
    protected $economicService;

    public function __construct(AIConstructionDecisionService $constructionService, EconomicSimulationService $economicService)
    {
        $this->constructionService = $constructionService;
        $this->economicService = $economicService;
    }

    public function process(MapSquare $square)
    {
        if ($this->needsConstruction($square)) {
            return $this->constructionService->decideBuildingType($square);
        }
        return null;
    }

    protected function needsConstruction(MapSquare $square)
    {
        return $square->current_buildings < $square->building_limit;
    }

    public function makeDecision($character)
    {
        $rule = 'cash > 100 AND reputation > 50';
        return $this->rulerz->satisfies($character, $rule);
    }
}
