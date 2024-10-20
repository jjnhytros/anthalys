<?php

namespace App\Http\Controllers\Anthaleja\Marketplace;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anthaleja\Marketplace\Region;
use App\Models\Anthaleja\Marketplace\Resource;

class ResourceController extends Controller
{
    public function distributeResources(Region $region)
    {
        $resources = Resource::where('region_id', $region->id)->get();
        foreach ($resources as $resource) {
            // Distribuire le risorse in base alla regione e gestire la disponibilità
        }
    }
}
