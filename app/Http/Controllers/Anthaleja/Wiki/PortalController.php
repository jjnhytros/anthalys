<?php

namespace App\Http\Controllers\Anthaleja\Wiki;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anthaleja\Wiki\WikiPortal;
use App\Services\Anthaleja\Wiki\AnalyticsService;

class PortalController extends Controller
{
    protected $analyticsService;
    public function __construct()
    {
        $this->analyticsService = new AnalyticsService();
    }

    public function index()
    {
        try {
            $portals = WikiPortal::all();
            // Statistiche sui portali
            $portalStats = $this->analyticsService->calculatePortalStats();
            return view('anthaleja.wiki.portals.index', compact('portals', 'portalStats'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error loading portals.']);
        }
    }

    public function show($id)
    {
        $portal = WikiPortal::with('articles')->findOrFail($id);
        return view('anthaleja.wiki.portals.show', compact('portal'));
    }

    public function create()
    {
        return view('anthaleja.wiki.portals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:wiki_portals',
            'description' => 'required',
            'cover_image' => 'nullable|string'
        ]);

        WikiPortal::create($request->all());

        return redirect()->route('portals.index')->with('success', 'Portal successfully created!');
    }

    public function edit($id)
    {
        $portal = WikiPortal::findOrFail($id);
        return view('anthaleja.wiki.portals.edit', compact('portal'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:wiki_portals,name,' . $id,
            'description' => 'required',
            'cover_image' => 'nullable|string'
        ]);

        $portal = WikiPortal::findOrFail($id);
        $portal->update($request->all());

        return redirect()->route('portals.index')->with('success', 'Portal successfully updated!');
    }
}
